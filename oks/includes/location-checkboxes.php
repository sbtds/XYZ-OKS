<?php
/**
 * Location Checkboxes Functions
 *
 * @package OKS
 */

// Include area mapping system
require_once get_template_directory() . '/includes/area-mapping.php';

/**
 * Generate location checkboxes with dynamic data
 *
 * @param array $args {
 *     Arguments for generating the location checkboxes.
 *
 *     @type array  $unique_prefectures Array of unique prefectures
 *     @type array  $unique_cities      Array of cities by prefecture
 *     @type int    $total_job_count    Total number of jobs
 *     @type array  $search_params      Current search parameters
 *     @type string $id_prefix          Prefix for checkbox IDs (default: 'search_select__area')
 *     @type string $class_prefix       Prefix for CSS classes (default: 'search_select')
 *     @type bool   $show_side_button   Whether to show the side button (default: false)
 * }
 * @return void
 */
function oks_render_location_checkboxes( $args = array() ) {
    global $wpdb;
    
    // Default arguments
    $defaults = array(
        'unique_prefectures' => array(),
        'unique_cities' => array(),
        'total_job_count' => 0,
        'search_params' => array(),
        'id_prefix' => 'search_select__area',
        'class_prefix' => 'search_select',
        'show_side_button' => false,
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    // Extract variables
    $unique_prefectures = $args['unique_prefectures'];
    $unique_cities = $args['unique_cities'];
    $total_job_count = $args['total_job_count'];
    $search_params = $args['search_params'];
    $id_prefix = $args['id_prefix'];
    $class_prefix = $args['class_prefix'];
    $show_side_button = $args['show_side_button'];
    
    ?>
    <div class="<?php echo esc_attr( $class_prefix ); ?>__menu_list">
        <!-- 全国 checkbox -->
        <div class="<?php echo esc_attr( $class_prefix ); ?>__area">
            <input type="checkbox" 
                   class="<?php echo esc_attr( $class_prefix ); ?>__area_check js-select-all-areas" 
                   id="<?php echo esc_attr( $id_prefix ); ?>00" />
            <label class="<?php echo esc_attr( $class_prefix ); ?>__area_title" 
                   for="<?php echo esc_attr( $id_prefix ); ?>00">
                <span class="checkbox"></span>
                <span class="label">全国</span>
                <span class="count">(<?php echo number_format( $total_job_count ); ?>件)</span>
            </label>
        </div>

        <!-- Dynamic prefectures from job posts -->
        <?php
        if ( ! empty( $unique_prefectures ) ) :
            $area_index = 1;
            foreach ( $unique_prefectures as $prefecture ) :
                $area_id = sprintf( '%s%02d', $id_prefix, $area_index );
                $show_id = sprintf( '%s_show%02d', $id_prefix, $area_index );
                
                // Get cities for this prefecture
                $prefecture_cities = isset( $unique_cities[ $prefecture ] ) ? $unique_cities[ $prefecture ] : array();
                
                // Count jobs in this prefecture
                $prefecture_count = $wpdb->get_var( $wpdb->prepare( "
                    SELECT COUNT(DISTINCT p.ID)
                    FROM {$wpdb->posts} p
                    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                    WHERE p.post_type = 'job'
                    AND p.post_status = 'publish'
                    AND pm.meta_key = 'prefecture'
                    AND pm.meta_value = %s
                ", $prefecture ) );
                ?>
                <div class="<?php echo esc_attr( $class_prefix ); ?>__area">
                    <?php if ( ! empty( $prefecture_cities ) ) : ?>
                        <input type="checkbox" 
                               class="<?php echo esc_attr( $class_prefix ); ?>__area_show" 
                               id="<?php echo $show_id; ?>" />
                    <?php endif; ?>
                    <input type="checkbox" 
                           class="<?php echo esc_attr( $class_prefix ); ?>__area_check js-prefecture-checkbox" 
                           id="<?php echo $area_id; ?>"
                           name="prefecture[]" 
                           value="<?php echo esc_attr( $prefecture ); ?>"
                           data-prefecture="<?php echo esc_attr( $prefecture ); ?>"
                           <?php checked( isset( $search_params['prefecture'] ) && in_array( $prefecture, $search_params['prefecture'] ) ); ?> />
                    <label class="<?php echo esc_attr( $class_prefix ); ?>__area_title" 
                           for="<?php echo $area_id; ?>">
                        <span class="checkbox"></span>
                        <span class="label"><?php echo esc_html( $prefecture ); ?></span>
                        <span class="count">(<?php echo number_format( $prefecture_count ); ?>件)</span>
                        <?php if ( ! empty( $prefecture_cities ) ) : ?>
                            <label class="arrow" for="<?php echo $show_id; ?>">
                                <span class="plus"><i class="fa-solid fa-plus"></i></span>
                                <span class="minus"><i class="fa-solid fa-minus"></i></span>
                            </label>
                        <?php endif; ?>
                    </label>
                    <?php if ( ! empty( $prefecture_cities ) ) : ?>
                        <div class="<?php echo esc_attr( $class_prefix ); ?>__area_menu">
                            <div class="<?php echo esc_attr( $class_prefix ); ?>__area_list">
                                <?php foreach ( $prefecture_cities as $city ) : ?>
                                    <label class="<?php echo esc_attr( $class_prefix ); ?>__area_item">
                                        <input type="checkbox" 
                                               class="<?php echo esc_attr( $class_prefix ); ?>__area_item_check js-city-checkbox" 
                                               name="city[]"
                                               value="<?php echo esc_attr( $city ); ?>"
                                               data-prefecture="<?php echo esc_attr( $prefecture ); ?>"
                                               <?php checked( isset( $search_params['city'] ) && in_array( $city, $search_params['city'] ) ); ?> />
                                        <span class="checkbox"></span>
                                        <span class="label"><?php echo esc_html( $city ); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                $area_index++;
            endforeach;
        endif;
        ?>
    </div>
    <?php
}

/**
 * Enqueue location checkboxes JavaScript
 */
function oks_enqueue_location_checkboxes_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Prefecture to area mapping
        var prefectureAreaMapping = <?php echo json_encode( oks_get_prefecture_area_mapping() ); ?>;
        
        // Handle 全国 (Select All) checkbox
        $('.js-select-all-areas').on('change', function() {
            var isChecked = $(this).prop('checked');
            var container = $(this).closest('.search_select__menu_list, .search_select__menu_list, .search_side__menu_list');
            
            // Check/uncheck all prefecture checkboxes
            container.find('.js-prefecture-checkbox').prop('checked', isChecked);
            
            // Check/uncheck all city checkboxes
            container.find('.js-city-checkbox').prop('checked', isChecked);
        });
        
        // Handle prefecture checkbox change
        $('.js-prefecture-checkbox').on('change', function() {
            var isChecked = $(this).prop('checked');
            var prefecture = $(this).data('prefecture');
            var container = $(this).closest('.search_select__area, .search_side__area');
            
            // Check/uncheck all cities in this prefecture
            container.find('.js-city-checkbox[data-prefecture="' + prefecture + '"]').prop('checked', isChecked);
            
            // Update 全国 checkbox state
            updateSelectAllState($(this).closest('.search_select__menu_list, .search_side__menu_list'));
        });
        
        // Handle city checkbox change
        $('.js-city-checkbox').on('change', function() {
            var prefecture = $(this).data('prefecture');
            var container = $(this).closest('.search_select__area, .search_side__area');
            var prefectureCheckbox = container.find('.js-prefecture-checkbox[data-prefecture="' + prefecture + '"]');
            
            // Get all city checkboxes for this prefecture
            var allCityCheckboxes = container.find('.js-city-checkbox[data-prefecture="' + prefecture + '"]');
            var checkedCityCheckboxes = allCityCheckboxes.filter(':checked');
            
            // Update prefecture checkbox state
            if (checkedCityCheckboxes.length === allCityCheckboxes.length) {
                prefectureCheckbox.prop('checked', true);
                prefectureCheckbox.prop('indeterminate', false);
            } else if (checkedCityCheckboxes.length > 0) {
                prefectureCheckbox.prop('checked', false);
                prefectureCheckbox.prop('indeterminate', true);
            } else {
                prefectureCheckbox.prop('checked', false);
                prefectureCheckbox.prop('indeterminate', false);
            }
            
            // Update 全国 checkbox state
            updateSelectAllState($(this).closest('.search_select__menu_list, .search_side__menu_list'));
        });
        
        // Update 全国 checkbox state based on prefecture checkboxes
        function updateSelectAllState(container) {
            var allPrefectureCheckboxes = container.find('.js-prefecture-checkbox');
            var checkedPrefectureCheckboxes = allPrefectureCheckboxes.filter(':checked');
            var indeterminatePrefectureCheckboxes = allPrefectureCheckboxes.filter(function() {
                return $(this).prop('indeterminate');
            });
            var selectAllCheckbox = container.find('.js-select-all-areas');
            
            if (checkedPrefectureCheckboxes.length === allPrefectureCheckboxes.length && 
                indeterminatePrefectureCheckboxes.length === 0) {
                // All prefectures are fully checked
                selectAllCheckbox.prop('checked', true);
                selectAllCheckbox.prop('indeterminate', false);
            } else if (checkedPrefectureCheckboxes.length > 0 || indeterminatePrefectureCheckboxes.length > 0) {
                // Some prefectures are checked or partially checked
                selectAllCheckbox.prop('checked', false);
                selectAllCheckbox.prop('indeterminate', true);
            } else {
                // No prefectures are checked
                selectAllCheckbox.prop('checked', false);
                selectAllCheckbox.prop('indeterminate', false);
            }
        }
        
        // Initialize states on page load
        $('.search_select__menu_list, .search_side__menu_list').each(function() {
            updateSelectAllState($(this));
        });
        
        // Handle form submission to convert prefecture/city selections to area IDs
        $('form.search_select').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var selectedPrefectures = [];
            var selectedCities = [];
            var areaIds = [];
            var cityOnlySelections = []; // Cities selected without their prefecture
            
            // Get selected prefectures
            form.find('input[name="prefecture[]"]:checked').each(function() {
                selectedPrefectures.push($(this).val());
            });
            
            // Get selected cities
            form.find('input[name="city[]"]:checked').each(function() {
                var city = $(this).val();
                var prefecture = $(this).data('prefecture');
                
                // Only include city if its prefecture is not selected
                if (!selectedPrefectures.includes(prefecture)) {
                    cityOnlySelections.push(city);
                }
            });
            
            // Convert prefectures to area IDs
            selectedPrefectures.forEach(function(prefecture) {
                if (prefectureAreaMapping[prefecture]) {
                    areaIds.push(prefectureAreaMapping[prefecture]);
                }
            });
            
            // Remove duplicates
            areaIds = [...new Set(areaIds)];
            
            // Build new URL
            var baseUrl = form.attr('action');
            var params = new URLSearchParams();
            
            // Add area parameter if any areas are selected
            if (areaIds.length > 0) {
                if (areaIds.length === 1) {
                    params.append('area', areaIds[0]);
                } else {
                    areaIds.forEach(function(areaId) {
                        params.append('area[]', areaId);
                    });
                }
            }
            
            // Add cities only if they're selected without their prefecture
            if (cityOnlySelections.length > 0) {
                cityOnlySelections.forEach(function(city) {
                    params.append('city[]', city);
                });
            }
            
            // Add other form fields (excluding prefecture/city arrays)
            form.find('input[type="text"], input[type="hidden"], select').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val();
                if (name && value && name !== 'prefecture[]' && name !== 'city[]') {
                    params.append(name, value);
                }
            });
            
            // Add other checked inputs (except prefecture/city which we already handled)
            form.find('input[type="checkbox"]:checked, input[type="radio"]:checked').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val();
                if (name && value && name !== 'prefecture[]' && name !== 'city[]') {
                    params.append(name, value);
                }
            });
            
            // Redirect to new URL
            var newUrl = baseUrl + '?' + params.toString();
            window.location.href = newUrl;
        });
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'oks_enqueue_location_checkboxes_script' );