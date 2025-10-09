<?php
/**
 * Template Name: 求人検索ページ
 * Template for Job Search Page
 *
 * @package OKS
 */

get_header();

// Include search handler
require_once get_template_directory() . '/includes/job-search/job-search-loader.php';

// Handle search
$search_params = array();
if (!empty($_GET)) {
    $search_params = $_GET;
} elseif (!empty($_POST)) {
    $search_params = $_POST;
}

$search_handler = new OKS_Search_Handler();
$search_results = $search_handler->search($search_params);

// Get search summary
$search_summary = $search_handler->get_search_summary($search_params);

// Get unique prefectures and cities from job posts
$unique_prefectures = $search_handler->get_unique_prefectures();
$unique_cities = $search_handler->get_unique_cities_by_prefecture();

// Define custom prefecture order (from north to south)
$prefecture_order = array(
    '北海道',
    '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
    '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
    '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県',
    '三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
    '鳥取県', '島根県', '岡山県', '広島県', '山口県',
    '徳島県', '香川県', '愛媛県', '高知県',
    '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県',
    '沖縄県'
);

// Sort prefectures according to the custom order
usort($unique_prefectures, function($a, $b) use ($prefecture_order) {
    $pos_a = array_search($a, $prefecture_order);
    $pos_b = array_search($b, $prefecture_order);

    // If both prefectures are in the order array, sort by their position
    if ($pos_a !== false && $pos_b !== false) {
        return $pos_a - $pos_b;
    }

    // If only one is in the order array, it comes first
    if ($pos_a !== false) return -1;
    if ($pos_b !== false) return 1;

    // If neither is in the order array, sort alphabetically
    return strcmp($a, $b);
});

// Get unique industries and job types from job posts
$unique_industries = $search_handler->get_unique_industries();
$unique_job_types = $search_handler->get_unique_job_types_by_industry();

// Get unique salary types from job posts
$unique_salary_types = $search_handler->get_unique_salary_types();
?>
<main class="page_main">
  <div class="page_title bg-primary">
    <p class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/search_title02_sp.svg"
        class="sp-only" alt="求人を探す">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/search_title02.svg" class="pc-only"
        alt="求人を探す">
    </p>
  </div>

  <section class="search_index">
    <div class="search_container">
      <div class="search_main">
        <div class="search_main__container">
          <!-- top -->
          <div class="search_main__top">
            <p class="count">求人情報<span class="number"><?php echo $search_results['found_posts']; ?></span>件</p>
            <div class="order">
              <p class="label">表示件数</p>
              <p class="select">
                <select name="posts_per_page" id="">
                  <option value="10"
                    <?php selected(isset($search_params['posts_per_page']) ? $search_params['posts_per_page'] : 20, 10); ?>>
                    10件</option>
                  <option value="20"
                    <?php selected(isset($search_params['posts_per_page']) ? $search_params['posts_per_page'] : 20, 20); ?>>
                    20件</option>
                </select>
              </p>
            </div>
          </div>
          <!-- //top -->
          <!-- panel -->
          <div class="search_main__panel">
            <input type="checkbox" id="search_main__panel_check" <?php if (!empty($search_params)) echo 'checked'; ?> />
            <label class="search_main__panel_title" for="search_main__panel_check">
              <span class="label">現在の検索条件</span>
              <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </label>
            <form class="search_main__panel_body" method="GET" action="">
              <div class="search_main__panel_result">
                <p class="title">現在の検索条件</p>
                <div class="contents">
                  <?php if (!empty($search_summary)): ?>
                  <?php echo $search_summary; ?>
                  <?php else: ?>
                  <p>検索条件が設定されていません。</p>
                  <?php endif; ?>
                </div>
              </div>
              <div class="search_main__panel_list">
                <!-- item -->
                <div class="search_main__panel_item" id="search_main_area">
                  <input type="checkbox" id="search_main__panel_subject01" />
                  <label class="search_main__panel_subject" for="search_main__panel_subject01">
                    <span class="icon"><img src="./assets/images/page/common_icon_area.svg" alt="" /></span>
                    <span class="label">勤務地</span>
                    <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
                  </label>
                  <div class="search_main__panel_block">
                    <!-- select_area -->
                    <?php
                    // Get unique prefectures from job posts
                    if (!empty($unique_prefectures)) :
                        global $wpdb;
                        $area_index = 1;
                        foreach ($unique_prefectures as $prefecture) :
                            $area_id = sprintf('search_select__area_%02d', $area_index);
                            $show_id = sprintf('search_select__area_show%02d__', $area_index);

                            // Get cities for this prefecture
                            $prefecture_cities = isset($unique_cities[$prefecture]) ? $unique_cities[$prefecture] : array();

                            // Count jobs in this prefecture
                            $prefecture_count = $wpdb->get_var($wpdb->prepare("
                                SELECT COUNT(DISTINCT p.ID)
                                FROM {$wpdb->posts} p
                                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                                WHERE p.post_type = 'job'
                                AND p.post_status = 'publish'
                                AND pm.meta_key = 'prefecture'
                                AND pm.meta_value = %s
                            ", $prefecture));
                    ?>
                    <div class="search_select__area">
                      <input type="checkbox" class="search_select__area_show" id="<?php echo $show_id; ?>" />
                      <input type="checkbox" class="search_select__area_check" id="<?php echo $area_id; ?>"
                        name="prefecture[]" value="<?php echo esc_attr($prefecture); ?>"
                        <?php if (isset($search_params['prefecture']) && in_array($prefecture, $search_params['prefecture'])) echo 'checked'; ?> />
                      <label class="search_select__area_title" for="<?php echo $area_id; ?>">
                        <span class="checkbox"></span>
                        <span class="label"><?php echo esc_html($prefecture); ?></span>
                        <span class="count">(<?php echo number_format($prefecture_count); ?>件)</span>
                        <label class="arrow" for="<?php echo $show_id; ?>">
                          <span class="plus"><i class="fa-solid fa-plus"></i></span>
                          <span class="minus"><i class="fa-solid fa-minus"></i></span>
                        </label>
                      </label>
                      <?php if (!empty($prefecture_cities)) : ?>
                      <div class="search_select__area_menu">
                        <div class="search_select__area_list">
                          <?php foreach ($prefecture_cities as $city) : ?>
                          <label class="search_select__area_item">
                            <input type="checkbox" class="search_select__area_item_check" name="city[]"
                              value="<?php echo esc_attr($city); ?>"
                              <?php if (isset($search_params['city']) && in_array($city, $search_params['city'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label"><?php echo esc_html($city); ?></span>
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
                    <!-- //select_area -->
                  </div>
                </div>
                <!-- //item -->
                <!-- item -->
                <div class="search_main__panel_item" id="search_main_type">
                  <input type="checkbox" id="search_main__panel_subject02" />
                  <label class="search_main__panel_subject" for="search_main__panel_subject02">
                    <span class="icon"><img src="./assets/images/page/common_icon_type.svg" alt="" /></span>
                    <span class="label">職種</span>
                    <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
                  </label>
                  <div class="search_main__panel_block">
                    <!-- type -->
                    <?php
                    // Get unique industries and job types from job posts
                    if (!empty($unique_industries)) :
                        $type_index = 1;
                        foreach ($unique_industries as $industry) :
                            $type_id = sprintf('search_select__type_%02d', $type_index);
                            $show_id = sprintf('search_select__type_show%02d__', $type_index);

                            // Get job types for this industry
                            $industry_job_types = isset($unique_job_types[$industry]) ? $unique_job_types[$industry] : array();

                            // Count jobs in this industry
                            $industry_count = $wpdb->get_var($wpdb->prepare("
                                SELECT COUNT(DISTINCT p.ID)
                                FROM {$wpdb->posts} p
                                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                                WHERE p.post_type = 'job'
                                AND p.post_status = 'publish'
                                AND pm.meta_key = 'industry'
                                AND pm.meta_value = %s
                            ", $industry));
                    ?>
                    <div class="search_select__type">
                      <input type="checkbox" class="search_select__type_show" id="<?php echo $show_id; ?>" />
                      <input type="checkbox" class="search_select__type_check" id="<?php echo $type_id; ?>"
                        name="industry[]" value="<?php echo esc_attr($industry); ?>"
                        <?php if (isset($search_params['industry']) && in_array($industry, $search_params['industry'])) echo 'checked'; ?> />
                      <label class="search_select__type_title" for="<?php echo $type_id; ?>">
                        <span class="checkbox"></span>
                        <span class="label"><?php echo esc_html($industry); ?></span>
                        <span class="count">(<?php echo number_format($industry_count); ?>件)</span>
                        <label class="arrow" for="<?php echo $show_id; ?>">
                          <span class="plus"><i class="fa-solid fa-plus"></i></span>
                          <span class="minus"><i class="fa-solid fa-minus"></i></span>
                        </label>
                      </label>
                      <?php if (!empty($industry_job_types)) : ?>
                      <div class="search_select__type_menu">
                        <div class="search_select__type_list">
                          <?php foreach ($industry_job_types as $job_type) : ?>
                          <?php
                          // Create a unique value combining industry and job_type
                          $unique_job_type_value = $industry . '|' . $job_type;
                          ?>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check"
                              name="job_type_with_industry[]" value="<?php echo esc_attr($unique_job_type_value); ?>"
                              <?php if (isset($search_params['job_type_with_industry']) && in_array($unique_job_type_value, $search_params['job_type_with_industry'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label"><?php echo esc_html($job_type); ?></span>
                          </label>
                          <?php endforeach; ?>
                        </div>
                      </div>
                      <?php endif; ?>
                    </div>
                    <?php
                        $type_index++;
                        endforeach;
                    endif;
                    ?>
                    <!-- // type -->
                  </div>
                </div>
                <!-- //item -->
                <!-- item -->
                <div class="search_main__panel_item" id="search_main_income">
                  <input type="checkbox" id="search_main__panel_subject03" />
                  <label class="search_main__panel_subject" for="search_main__panel_subject03">
                    <span class="icon"><img src="./assets/images/page/common_icon_income.svg" alt="" /></span>
                    <span class="label">給与</span>
                    <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
                  </label>
                  <div class="search_main__panel_block">
                    <!-- type -->
                    <div class="search_select__type">
                      <input type="checkbox" class="search_select__type_show" id="search_select__type_show01__" />
                      <input type="checkbox" class="search_select__type_check" id="search_select__income01"
                        name="income[]" value="時給"
                        <?php if (isset($search_params['income']) && in_array('時給', $search_params['income'])) echo 'checked'; ?> />
                      <label class="search_select__type_title" for="search_select__income01">
                        <span class="checkbox"></span>
                        <span class="label">時給</span>
                        <!-- <span class="count">(123,456件)</span> -->
                        <label class="arrow" for="search_select__type_show01__">
                          <span class="plus"><i class="fa-solid fa-plus"></i></span>
                          <span class="minus"><i class="fa-solid fa-minus"></i></span>
                        </label>
                      </label>
                      <div class="search_select__type_menu">
                        <div class="search_select__type_list">
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]"
                              value="1,300円未満"
                              <?php if (isset($search_params['income']) && in_array('1,300円未満', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">1,300円未満</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]"
                              value="1,300円以上"
                              <?php if (isset($search_params['income']) && in_array('1,300円以上', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">1,300円以上</span>
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="search_select__type">
                      <input type="checkbox" class="search_select__type_show" id="search_select__type_show02__" />
                      <input type="checkbox" class="search_select__type_check" id="search_select__income02"
                        name="income[]" value="月給"
                        <?php if (isset($search_params['income']) && in_array('月給', $search_params['income'])) echo 'checked'; ?> />
                      <label class="search_select__type_title" for="search_select__income02">
                        <span class="checkbox"></span>
                        <span class="label">月給</span>
                        <!-- <span class="count">(456件)</span> -->
                        <label class="arrow" for="search_select__type_show02__">
                          <span class="plus"><i class="fa-solid fa-plus"></i></span>
                          <span class="minus"><i class="fa-solid fa-minus"></i></span>
                        </label>
                      </label>
                      <div class="search_select__type_menu">
                        <div class="search_select__type_list">
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]" value="18万円未満"
                              <?php if (isset($search_params['income']) && in_array('18万円未満', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">18万円未満</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]" value="19万円以上"
                              <?php if (isset($search_params['income']) && in_array('19万円以上', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">19万円以上</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]" value="20万円以上"
                              <?php if (isset($search_params['income']) && in_array('20万円以上', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">20万円以上</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]" value="21万円以上"
                              <?php if (isset($search_params['income']) && in_array('21万円以上', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">21万円以上</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]" value="22万円以上"
                              <?php if (isset($search_params['income']) && in_array('22万円以上', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">22万円以上</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]" value="23万円以上"
                              <?php if (isset($search_params['income']) && in_array('23万円以上', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">23万円以上</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]" value="24万円以上"
                              <?php if (isset($search_params['income']) && in_array('24万円以上', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">24万円以上</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="income[]" value="25万円以上"
                              <?php if (isset($search_params['income']) && in_array('25万円以上', $search_params['income'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label">25万円以上</span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <!-- // type -->
                  </div>
                </div>
                <!-- //item -->
                <!-- item -->
                <div class="search_main__panel_item" id="search_main_conditions">
                  <input type="checkbox" id="search_main__panel_subject04" />
                  <label class="search_main__panel_subject" for="search_main__panel_subject04">
                    <span class="icon"><img src="./assets/images/page/common_icon_feature.svg" alt="" /></span>
                    <span class="label">特徴</span>
                    <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
                  </label>
                  <div class="search_main__panel_block block-last">
                    <div class="search_select__conditions">
                      <p class="search_select__conditions_title">人気の特徴</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="未経験OK"
                            <?php if (isset($search_params['conditions']) && in_array('未経験OK', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">未経験OK</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="寮・社宅あり" />
                          <span class="checkbox"></span>
                          <span class="label">寮・社宅あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="マイカー通勤OK" />
                          <span class="checkbox"></span>
                          <span class="label">マイカー通勤OK</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="紹介予定派遣" />
                          <span class="checkbox"></span>
                          <span class="label">紹介予定派遣</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="直接雇用実績あり" />
                          <span class="checkbox"></span>
                          <span class="label">直接雇用実績あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="新着" />
                          <span class="checkbox"></span>
                          <span class="label">新着</span>
                        </label>
                      </div>

                      <p class="search_select__conditions_title">勤務形態</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="土日祝日休み" />
                          <span class="checkbox"></span>
                          <span class="label">土日祝日休み</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="早朝勤務" />
                          <span class="checkbox"></span>
                          <span class="label">早朝勤務</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="朝ゆっくり" />
                          <span class="checkbox"></span>
                          <span class="label">朝ゆっくり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="夕方開始" />
                          <span class="checkbox"></span>
                          <span class="label">夕方開始</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="深夜勤務" />
                          <span class="checkbox"></span>
                          <span class="label">深夜勤務</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="残業なし" />
                          <span class="checkbox"></span>
                          <span class="label">残業なし</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="残業少なめ" />
                          <span class="checkbox"></span>
                          <span class="label">残業少なめ</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="残業多め" />
                          <span class="checkbox"></span>
                          <span class="label">残業多め</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="転勤なし" />
                          <span class="checkbox"></span>
                          <span class="label">転勤なし</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="社員登用あり" />
                          <span class="checkbox"></span>
                          <span class="label">社員登用あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="長期" />
                          <span class="checkbox"></span>
                          <span class="label">長期</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="単発" />
                          <span class="checkbox"></span>
                          <span class="label">単発</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="期間限定" />
                          <span class="checkbox"></span>
                          <span class="label">期間限定</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="日勤固定" />
                          <span class="checkbox"></span>
                          <span class="label">日勤固定</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="夜勤固定" />
                          <span class="checkbox"></span>
                          <span class="label">夜勤固定</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="２交替・３交替" />
                          <span class="checkbox"></span>
                          <span class="label">２交替・３交替</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="平日休み" />
                          <span class="checkbox"></span>
                          <span class="label">平日休み</span>
                        </label>
                      </div>

                      <p class="search_select__conditions_title">福利厚生・待遇</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="高収入" />
                          <span class="checkbox"></span>
                          <span class="label">高収入</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="賞与あり" />
                          <span class="checkbox"></span>
                          <span class="label">賞与あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="給与前払い制度あり" />
                          <span class="checkbox"></span>
                          <span class="label">給与前払い制度あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="交通費支給" />
                          <span class="checkbox"></span>
                          <span class="label">交通費支給</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="育児・介護休暇あり" />
                          <span class="checkbox"></span>
                          <span class="label">育児・介護休暇あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="研修・教育制度充実" />
                          <span class="checkbox"></span>
                          <span class="label">研修・教育制度充実</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="資格取得支援あり" />
                          <span class="checkbox"></span>
                          <span class="label">資格取得支援あり</span>
                        </label>
                      </div>

                      <p class="search_select__conditions_title">職場環境</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="女性が活躍" />
                          <span class="checkbox"></span>
                          <span class="label">女性が活躍</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="男性が活躍" />
                          <span class="checkbox"></span>
                          <span class="label">男性が活躍</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="20代活躍中" />
                          <span class="checkbox"></span>
                          <span class="label">20代活躍中</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="30代活躍中" />
                          <span class="checkbox"></span>
                          <span class="label">30代活躍中</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="ミドル活躍中" />
                          <span class="checkbox"></span>
                          <span class="label">ミドル活躍中</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="シニア活躍中" />
                          <span class="checkbox"></span>
                          <span class="label">シニア活躍中</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="主婦・主夫活躍中" />
                          <span class="checkbox"></span>
                          <span class="label">主婦・主夫活躍中</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="フリーター歓迎" />
                          <span class="checkbox"></span>
                          <span class="label">フリーター歓迎</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="オフィスカジュアル" />
                          <span class="checkbox"></span>
                          <span class="label">オフィスカジュアル</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="制服あり" />
                          <span class="checkbox"></span>
                          <span class="label">制服あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="髪型・髪色自由" />
                          <span class="checkbox"></span>
                          <span class="label">髪型・髪色自由</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="髭OK" />
                          <span class="checkbox"></span>
                          <span class="label">髭OK</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="ネイル・ピアスOK" />
                          <span class="checkbox"></span>
                          <span class="label">ネイル・ピアスOK</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="長期休暇あり" />
                          <span class="checkbox"></span>
                          <span class="label">長期休暇あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="資格・スキルが活かせる" />
                          <span class="checkbox"></span>
                          <span class="label">資格・スキルが活かせる</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="座り仕事" />
                          <span class="checkbox"></span>
                          <span class="label">座り仕事</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="立ち仕事" />
                          <span class="checkbox"></span>
                          <span class="label">立ち仕事</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="食堂" />
                          <span class="checkbox"></span>
                          <span class="label">食堂</span>/仕出し弁当/売店が利用可
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="喫煙所あり" />
                          <span class="checkbox"></span>
                          <span class="label">喫煙所あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="場内全面禁煙" />
                          <span class="checkbox"></span>
                          <span class="label">場内全面禁煙</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="空調完備" />
                          <span class="checkbox"></span>
                          <span class="label">空調完備</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="アクティブワーク" />
                          <span class="checkbox"></span>
                          <span class="label">アクティブワーク</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="コツコツ・モクモク集中" />
                          <span class="checkbox"></span>
                          <span class="label">コツコツ・モクモク集中</span>
                        </label>
                      </div>

                      <p class="search_select__conditions_title">その他特徴</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="副業・ＷワークOK" />
                          <span class="checkbox"></span>
                          <span class="label">副業・ＷワークOK</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="急募" />
                          <span class="checkbox"></span>
                          <span class="label">急募</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="即日勤務OK" />
                          <span class="checkbox"></span>
                          <span class="label">即日勤務OK</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="職場見学可" />
                          <span class="checkbox"></span>
                          <span class="label">職場見学可</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="リモート面接OK" />
                          <span class="checkbox"></span>
                          <span class="label">リモート面接OK</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="面接時マスク着用" />
                          <span class="checkbox"></span>
                          <span class="label">面接時マスク着用</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="新卒採用" />
                          <span class="checkbox"></span>
                          <span class="label">新卒採用</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="第2新卒歓迎" />
                          <span class="checkbox"></span>
                          <span class="label">第2新卒歓迎</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- //item -->
                <!-- item -->
                <div class="search_main__panel_item">
                  <input type="checkbox" id="" checked />
                  <div class="search_main__panel_block">
                    <p class="search_select__conditions_title">キーワード</p>

                    <p class="search_select__keyword_input">
                      <input type="text" name="keyword"
                        value="<?php echo isset($search_params['keyword']) ? esc_attr($search_params['keyword']) : ''; ?>"
                        placeholder="入力してください" />
                    </p>
                  </div>
                </div>
                <!-- //item -->
                <div class="search_main__panel_button">
                  <button type="submit" class="button_more secondary">
                    <span class="label">再検索</span>
                    <span class="icon"><svg class="svg-inline--fa fa-angle-right" aria-hidden="true" focusable="false"
                        data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 320 512" data-fa-i2svg="">
                        <path fill="currentColor"
                          d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z">
                        </path>
                      </svg><!-- <i class="fa-solid fa-angle-right"></i> Font Awesome fontawesome.com --></span>
                  </button>
                </div>
              </div>
            </form>
          </div>
          <!-- //panel -->

          <!-- テスト表示: デバッグ情報 -->
          <div style="background: #f0f0f0; padding: 20px; margin: 20px 0; border: 2px solid #ccc;">
            <h3>デバッグ情報</h3>

            <h4>検索パラメータ:</h4>
            <pre style="background: white; padding: 10px; overflow: auto;">
<?php print_r($search_params); ?>
            </pre>

            <h4>カスタムフィールド「industry」の値一覧</h4>
            <?php
            global $wpdb;
            $industries = $wpdb->get_col("
                SELECT DISTINCT pm.meta_value
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = 'job'
                AND p.post_status = 'publish'
                AND pm.meta_key = 'industry'
                AND pm.meta_value != ''
                ORDER BY pm.meta_value
            ");

            if (!empty($industries)) {
                echo '<ul>';
                foreach ($industries as $industry) {
                    // 各industryの投稿IDを取得
                    $post_ids = $wpdb->get_col($wpdb->prepare("
                        SELECT DISTINCT p.ID
                        FROM {$wpdb->posts} p
                        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                        WHERE p.post_type = 'job'
                        AND p.post_status = 'publish'
                        AND pm.meta_key = 'industry'
                        AND pm.meta_value = %s
                        ORDER BY p.ID
                    ", $industry));

                    $count = count($post_ids);

                    echo '<li>';
                    echo '<strong>' . esc_html($industry) . '</strong> (' . $count . '件)';
                    if (!empty($post_ids)) {
                        echo '<br>記事ID: ' . implode(', ', $post_ids);
                    }
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>industryデータが見つかりません。</p>';
            }
            ?>

            <h4>job_typeフィールドの実際の値（サンプル）</h4>
            <?php
            // 建設・不動産のjob記事を確認
            $construction_jobs = $wpdb->get_results("
                SELECT p.ID, p.post_title,
                       pm1.meta_value as industry,
                       pm2.meta_value as job_type
                FROM {$wpdb->posts} p
                LEFT JOIN {$wpdb->postmeta} pm1 ON p.ID = pm1.post_id AND pm1.meta_key = 'industry'
                LEFT JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id AND pm2.meta_key = 'job_type'
                WHERE p.post_type = 'job'
                AND p.post_status = 'publish'
                AND pm1.meta_value LIKE '%建設%'
                LIMIT 5
            ");

            if (!empty($construction_jobs)) {
                echo '<table style="background: white; width: 100%; border-collapse: collapse;">';
                echo '<tr style="background: #ddd;"><th style="border: 1px solid #ccc; padding: 5px;">ID</th><th style="border: 1px solid #ccc; padding: 5px;">タイトル</th><th style="border: 1px solid #ccc; padding: 5px;">industry</th><th style="border: 1px solid #ccc; padding: 5px;">job_type</th></tr>';
                foreach ($construction_jobs as $job) {
                    echo '<tr>';
                    echo '<td style="border: 1px solid #ccc; padding: 5px;">' . $job->ID . '</td>';
                    echo '<td style="border: 1px solid #ccc; padding: 5px;">' . esc_html($job->post_title) . '</td>';
                    echo '<td style="border: 1px solid #ccc; padding: 5px;">' . esc_html($job->industry) . '</td>';
                    echo '<td style="border: 1px solid #ccc; padding: 5px;">' . esc_html($job->job_type) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo '<p>建設・不動産の記事が見つかりません。</p>';
            }
            ?>
          </div>
          <!-- //テスト表示 -->

          <!-- list -->
          <div class="search_main__list">
            <?php if (!empty($search_results['posts'])): ?>
            <?php foreach ($search_results['posts'] as $job): ?>
            <div class="search_main__item">
              <p class="caption">
                <?php echo esc_html($job['industry'] . '／' . $job['job_type'] . '／' . ($job['company'] ? '非公開' : '公開')); ?>
              </p>
              <h2 class="title"><?php echo esc_html($job['title']); ?></h2>
              <ul class="badges">
                <?php if (!empty($job['conditions'])): ?>
                <?php foreach ($job['conditions'] as $condition): ?>
                <li class="badges_item"><a href="#"><?php echo esc_html($condition); ?></a></li>
                <?php endforeach; ?>
                <?php endif; ?>
              </ul>
              <div class="contents">
                <dl>
                  <dt>仕事内容</dt>
                  <dd><?php echo esc_html($job['job_description']); ?></dd>
                </dl>
                <dl>
                  <dt>想定年収</dt>
                  <dd><?php echo $job['annual_income'] ? number_format($job['annual_income']) . '円' : '未設定'; ?></dd>
                </dl>
                <dl>
                  <dt>勤務地</dt>
                  <dd><?php echo esc_html($job['prefecture'] . ' ' . $job['city']); ?></dd>
                </dl>
              </div>
              <div class="buttons">
                <p class="button">
                  <a class="button_more" href="mailto:?subject=<?php echo urlencode('求人問い合わせ: ' . $job['title']); ?>">
                    <span class="label">この求人を問い合わせる</span>
                    <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                  </a>
                </p>
                <p class="button">
                  <a class="button_more secondary" href="<?php echo esc_url($job['permalink']); ?>">
                    <span class="label">この求人の詳細をみる</span>
                    <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                  </a>
                </p>
              </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>検索条件に該当する求人が見つかりませんでした。</p>
            <?php endif; ?>
          </div>
          <!-- //list -->
          <?php if ($search_results['max_num_pages'] > 1): ?>
          <div class="search_main__nav">
            <div class="search_main__nav_list">
              <?php
              $current_page = $search_results['current_page'];
              $max_pages = $search_results['max_num_pages'];
              $base_url = add_query_arg(array('paged' => false), $_SERVER['REQUEST_URI']);

              // Previous button
              if ($current_page > 1) {
                  echo '<p class="search_main__nav_item prev"><a href="' . esc_url(add_query_arg('paged', $current_page - 1, $base_url)) . '"><i class="fa-solid fa-chevron-left"></i></a></p>';
              }

              // Page numbers
              for ($i = max(1, $current_page - 2); $i <= min($max_pages, $current_page + 2); $i++) {
                  $class = ($i == $current_page) ? ' active' : '';
                  echo '<p class="search_main__nav_item' . $class . '"><a href="' . esc_url(add_query_arg('paged', $i, $base_url)) . '">' . $i . '</a></p>';
              }

              // Next button
              if ($current_page < $max_pages) {
                  echo '<p class="search_main__nav_item next"><a href="' . esc_url(add_query_arg('paged', $current_page + 1, $base_url)) . '"><i class="fa-solid fa-chevron-right"></i></a></p>';
              }
              ?>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <aside class="search_side">
        <div class="search_side__container">
          <p class="search_side__title">条件を変更する</p>
          <form class="search_select" id="search_side_form" method="GET" action="<?php echo esc_url(get_permalink()); ?>">
            <div class="search_select__box" id="search_side_area">
              <div class="search_select__inner">
                <input type="checkbox" class="search_select__check" id="search_select__box01" />
                <label class="search_select__button" for="search_select__box01">
                  <!-- index -->
                  <span class="icon"><i class="fa-solid fa-location-dot"></i></span>
                  <span class="label">勤務地を選ぶ</span>
                  <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
                  <!-- //index -->
                  <!-- search -->
                  <p class="search_side__button">
                    <span class="search_side__button_label">勤務地</span>
                    <span class="search_side__button_icon">
                      <span class="plus"><i class="fa-solid fa-plus"></i></span>
                      <span class="minus"><i class="fa-solid fa-minus"></i></span>
                    </span>
                    <span class="search_side__button_caption">勤務地を変更</span>
                  </p>
                  <!-- //search -->
                </label>
                <div class="search_select__menu">
                  <div class="search_select__menu_list">
                    <!-- 全国 checkbox -->
                    <div class="search_select__area">
                      <input type="checkbox" class="search_select__area_check" id="search_select__area00" />
                      <label class="search_select__area_title" for="search_select__area00">
                        <span class="checkbox"></span>
                        <span class="label">全国</span>
                        <span class="count">(<?php echo number_format($search_results['found_posts']); ?>件)</span>
                      </label>
                    </div>

                    <!-- Dynamic prefectures from job posts -->
                    <?php
                    if (!empty($unique_prefectures)) :
                        $side_area_index = 1;
                        foreach ($unique_prefectures as $prefecture) :
                            $side_area_id = sprintf('search_side__area_%02d', $side_area_index);
                            $side_show_id = sprintf('search_side__area_show%02d__', $side_area_index);

                            // Get cities for this prefecture
                            $prefecture_cities = isset($unique_cities[$prefecture]) ? $unique_cities[$prefecture] : array();

                            // Count jobs in this prefecture
                            $prefecture_count = $wpdb->get_var($wpdb->prepare("
                                SELECT COUNT(DISTINCT p.ID)
                                FROM {$wpdb->posts} p
                                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                                WHERE p.post_type = 'job'
                                AND p.post_status = 'publish'
                                AND pm.meta_key = 'prefecture'
                                AND pm.meta_value = %s
                            ", $prefecture));
                    ?>
                    <div class="search_select__area">
                      <input type="checkbox" class="search_select__area_show" id="<?php echo $side_show_id; ?>" />
                      <input type="checkbox" class="search_select__area_check" id="<?php echo $side_area_id; ?>"
                        name="prefecture[]" value="<?php echo esc_attr($prefecture); ?>"
                        <?php if (isset($search_params['prefecture']) && in_array($prefecture, $search_params['prefecture'])) echo 'checked'; ?> />
                      <label class="search_select__area_title" for="<?php echo $side_area_id; ?>">
                        <span class="checkbox"></span>
                        <span class="label"><?php echo esc_html($prefecture); ?></span>
                        <span class="count">(<?php echo number_format($prefecture_count); ?>件)</span>
                        <label class="arrow" for="<?php echo $side_show_id; ?>">
                          <span class="plus"><i class="fa-solid fa-plus"></i></span>
                          <span class="minus"><i class="fa-solid fa-minus"></i></span>
                        </label>
                      </label>
                      <?php if (!empty($prefecture_cities)) : ?>
                      <div class="search_select__area_menu">
                        <div class="search_select__area_list">
                          <?php foreach ($prefecture_cities as $city) : ?>
                          <label class="search_select__area_item">
                            <input type="checkbox" class="search_select__area_item_check" name="city[]"
                              value="<?php echo esc_attr($city); ?>"
                              <?php if (isset($search_params['city']) && in_array($city, $search_params['city'])) echo 'checked'; ?> />
                            <span class="checkbox"></span>
                            <span class="label"><?php echo esc_html($city); ?></span>
                          </label>
                          <?php endforeach; ?>
                        </div>
                      </div>
                      <?php endif; ?>
                    </div>
                    <?php
                        $side_area_index++;
                        endforeach;
                    endif;
                    ?>
                    <!-- //select_area -->
                  </div>
                </div>
              </div>
            </div>
            <p class="search_select__x"><i class="fa-solid fa-xmark"></i></p>
            <div class="search_select__box" id="search_side_type">
              <div class="search_select__inner">
                <input type="checkbox" class="search_select__check" id="search_select__box02" />
                <label class="search_select__button" for="search_select__box02">
                  <!-- index -->
                  <span class="icon"><i class="fa-solid fa-location-dot"></i></span>
                  <span class="label">職種を選ぶ</span>
                  <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
                  <!-- //index -->
                  <!-- search -->
                  <p class="search_side__button">
                    <span class="search_side__button_label">職種</span>
                    <span class="search_side__button_icon">
                      <span class="plus"><i class="fa-solid fa-plus"></i></span>
                      <span class="minus"><i class="fa-solid fa-minus"></i></span>
                    </span>
                    <span class="search_side__button_caption">職種を変更</span>
                  </p>
                  <!-- //search -->
                </label>
                <div class="search_select__menu">
                  <div class="search_select__menu_list">
                    <div class="search_select__type">
                      <input type="checkbox" class="search_select__type_show" id="search_select__type_show01" />
                      <input type="checkbox" class="search_select__type_check" id="search_select__type01" name="type"
                        value="製造・技術" />
                      <label class="search_select__type_title" for="search_select__type01">
                        <span class="checkbox"></span>
                        <span class="label">製造・技術</span>
                        <span class="count">(123,456件)</span>
                        <label class="arrow" for="search_select__type_show01">
                          <span class="plus"><i class="fa-solid fa-plus"></i></span>
                          <span class="minus"><i class="fa-solid fa-minus"></i></span>
                        </label>
                      </label>
                      <div class="search_select__type_menu">
                        <div class="search_select__type_list">
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type"
                              value="機械オペレーター機械オペレーション機械オペレーター機械オペレーション" />
                            <span class="checkbox"></span>
                            <span class="label">機械オペレーター機械オペレーション機械オペレーター機械オペレーション</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type"
                              value="機械オペレーター(機械オペレーション)" />
                            <span class="checkbox"></span>
                            <span class="label">機械オペレーター(機械オペレーション)</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type" value="組立・加工" />
                            <span class="checkbox"></span>
                            <span class="label">組立・加工</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type"
                              value="製造（電気・電子・機械）" />
                            <span class="checkbox"></span>
                            <span class="label">製造（電気・電子・機械）</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type" value="検査・検品" />
                            <span class="checkbox"></span>
                            <span class="label">検査・検品</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type"
                              value="製造・技能工（化学・医療・食品）" />
                            <span class="checkbox"></span>
                            <span class="label">製造・技能工（化学・医療・食品）</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type" value="食品製造" />
                            <span class="checkbox"></span>
                            <span class="label">食品製造</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type"
                              value="その他（化学・医療・食品）" />
                            <span class="checkbox"></span>
                            <span class="label">その他（化学・医療・食品）</span>
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="search_select__type">
                      <input type="checkbox" class="search_select__type_show" id="search_select__type_show02" />
                      <input type="checkbox" class="search_select__type_check" id="search_select__type02" name="type"
                        value="物流・配送・軽作業" />
                      <label class="search_select__type_title" for="search_select__type02">
                        <span class="checkbox"></span>
                        <span class="label">物流・配送・軽作業</span>
                        <span class="count">(456件)</span>
                        <label class="arrow" for="search_select__type_show02">
                          <span class="plus"><i class="fa-solid fa-plus"></i></span>
                          <span class="minus"><i class="fa-solid fa-minus"></i></span>
                        </label>
                      </label>
                      <div class="search_select__type_menu">
                        <div class="search_select__type_list">
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type"
                              value="仕分け・梱包・ピッキング" />
                            <span class="checkbox"></span>
                            <span class="label">仕分け・梱包・ピッキング</span>
                          </label>
                          <label class="search_select__type_item">
                            <input type="checkbox" class="search_select__type_item_check" name="type" value="フォークリフト" />
                            <span class="checkbox"></span>
                            <span class="label">フォークリフト</span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- search -->
            <div class="search_select__income" id="search_side_income">
              <div class="search_select__income_title">
                <p class="search_side__subject">年収</p>
              </div>
              <div class="search_select__income_select">
                <select name="" id="">
                  <option value="">指定しない</option>
                  <option value="1000000">100万</option>
                  <option value="2000000">200万</option>
                  <option value="3000000">300万</option>
                  <option value="4000000">400万</option>
                  <option value="5000000">500万</option>
                  <option value="6000000">600万</option>
                  <option value="7000000">700万</option>
                  <option value="8000000">800万</option>
                  <option value="9000000">900万</option>
                  <option value="10000000">1000万</option>
                </select>
              </div>
            </div>
            <div class="search_select__keyword" id="search_side_keyword">
              <div class="search_select__keyword_title">
                <p class="search_side__subject">キーワード</p>
              </div>
              <p class="search_select__keyword_input">
                <input type="text" value="" placeholder="入力してください" />
              </p>
            </div>
            <div class="search_select__conditions" id="search_side_conditions">
              <div class="search_select__conditions_title">
                <p class="search_side__subject">こだわり条件</p>
              </div>
              <div class="search_select__conditions_list">
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]"
                    value="年間休日120日以上" />
                  <span class="checkbox"></span>
                  <span class="label">年間休日120日以上</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]"
                    value="寮・社宅・住宅手当あり" />
                  <span class="checkbox"></span>
                  <span class="label">寮・社宅・住宅手当あり</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="退職金制度" />
                  <span class="checkbox"></span>
                  <span class="label">退職金制度</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="資格取得支援制度" />
                  <span class="checkbox"></span>
                  <span class="label">資格取得支援制度</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]"
                    value="産休・育休・介護休暇取得実績あり" />
                  <span class="checkbox"></span>
                  <span class="label">産休・育休・介護休暇取得実績あり</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="女性が活躍" />
                  <span class="checkbox"></span>
                  <span class="label">女性が活躍</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="男性が活躍" />
                  <span class="checkbox"></span>
                  <span class="label">男性が活躍</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="インセンティブあり" />
                  <span class="checkbox"></span>
                  <span class="label">インセンティブあり</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="UIターン支援あり" />
                  <span class="checkbox"></span>
                  <span class="label">UIターン支援あり</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="リモート面接OK" />
                  <span class="checkbox"></span>
                  <span class="label">リモート面接OK</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="ミドル活躍中" />
                  <span class="checkbox"></span>
                  <span class="label">ミドル活躍中</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="シニア活躍中" />
                  <span class="checkbox"></span>
                  <span class="label">シニア活躍中</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="新卒採用" />
                  <span class="checkbox"></span>
                  <span class="label">新卒採用</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="第２新卒採用" />
                  <span class="checkbox"></span>
                  <span class="label">第２新卒採用</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="中途採用" />
                  <span class="checkbox"></span>
                  <span class="label">中途採用</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="固定残業代なし" />
                  <span class="checkbox"></span>
                  <span class="label">固定残業代なし</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="交通費支給" />
                  <span class="checkbox"></span>
                  <span class="label">交通費支給</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="完全週休2日制" />
                  <span class="checkbox"></span>
                  <span class="label">完全週休2日制</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="土日祝休み" />
                  <span class="checkbox"></span>
                  <span class="label">土日祝休み</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]"
                    value="残業少なめ(20時間未満)" />
                  <span class="checkbox"></span>
                  <span class="label">残業少なめ(20時間未満)</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]"
                    value="リモートワーク・在宅勤務制度あり" />
                  <span class="checkbox"></span>
                  <span class="label">リモートワーク・在宅勤務制度あり</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="転勤なし" />
                  <span class="checkbox"></span>
                  <span class="label">転勤なし</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="学歴不問" />
                  <span class="checkbox"></span>
                  <span class="label">学歴不問</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="正社員" />
                  <span class="checkbox"></span>
                  <span class="label">正社員</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]"
                    value="管理職・マネージャー" />
                  <span class="checkbox"></span>
                  <span class="label">管理職・マネージャー</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]"
                    value="設立10年以上の会社" />
                  <span class="checkbox"></span>
                  <span class="label">設立10年以上の会社</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="ベンチャー企業" />
                  <span class="checkbox"></span>
                  <span class="label">ベンチャー企業</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="車通勤可" />
                  <span class="checkbox"></span>
                  <span class="label">車通勤可</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="未経験でも可" />
                  <span class="checkbox"></span>
                  <span class="label">未経験でも可</span>
                </label>
                <label class="search_select__area_item">
                  <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="上場企業" />
                  <span class="checkbox"></span>
                  <span class="label">上場企業</span>
                </label>
              </div>
            </div>
            <!-- //search -->

            <button class="button_search" id="search_side_submit">
              <!-- index -->
              <span class="icon"><i class="fa-solid fa-search"></i></span>
              <span class="label">検索</span>
              <!-- //index -->
              <!-- search -->
              <span class="search">この条件で検索</span>
              <span class="arrow"><i class="fa-solid fa-chevron-right"></i></span>
              <!-- //search -->
            </button>
          </form>
        </div>
        <!--#include virtual="./includes/side_banner.html" -->
      </aside>
    </div>
  </section>
</main>

<script type="text/javascript">
jQuery(document).ready(function($) {
  // 全国チェックボックスの処理
  $('#search_select__area00').on('change', function() {
    var isChecked = $(this).is(':checked');
    // 全ての都道府県チェックボックスを選択/解除
    $('#search_side_area .search_select__area_check').not('#search_select__area00').prop('checked', isChecked);
    // 全ての市区町村チェックボックスを選択/解除
    $('#search_side_area .search_select__area_item_check').prop('checked', isChecked);
  });

  // 都道府県チェックボックスの処理
  $('#search_side_area').on('change', '.search_select__area_check:not(#search_select__area00)', function() {
    var $this = $(this);
    var isChecked = $this.is(':checked');
    var $container = $this.closest('.search_select__area');

    // その都道府県の全ての市区町村を選択/解除
    $container.find('.search_select__area_item_check').prop('checked', isChecked);

    // 全国チェックボックスの状態を更新
    updateNationalCheckbox();
  });

  // 市区町村チェックボックスの処理
  $('#search_side_area').on('change', '.search_select__area_item_check', function() {
    var $container = $(this).closest('.search_select__area');
    var $prefectureCheckbox = $container.find('.search_select__area_check');
    var $cityCheckboxes = $container.find('.search_select__area_item_check');
    var checkedCities = $cityCheckboxes.filter(':checked').length;
    var totalCities = $cityCheckboxes.length;

    // 都道府県チェックボックスの状態を更新
    if (checkedCities === 0) {
      $prefectureCheckbox.prop('checked', false).prop('indeterminate', false);
    } else if (checkedCities === totalCities) {
      $prefectureCheckbox.prop('checked', true).prop('indeterminate', false);
    } else {
      $prefectureCheckbox.prop('checked', false).prop('indeterminate', true);
    }

    // 全国チェックボックスの状態を更新
    updateNationalCheckbox();
  });

  // 全国チェックボックスの状態を更新する関数
  function updateNationalCheckbox() {
    var $prefectureCheckboxes = $('#search_side_area .search_select__area_check').not('#search_select__area00');
    var checkedPrefectures = $prefectureCheckboxes.filter(':checked').length;
    var indeterminatePrefectures = $prefectureCheckboxes.filter(function() {
      return $(this).prop('indeterminate');
    }).length;
    var totalPrefectures = $prefectureCheckboxes.length;

    if (checkedPrefectures === totalPrefectures && indeterminatePrefectures === 0) {
      // 全ての都道府県が完全にチェックされている
      $('#search_select__area00').prop('checked', true).prop('indeterminate', false);
    } else if (checkedPrefectures === 0 && indeterminatePrefectures === 0) {
      // 何もチェックされていない
      $('#search_select__area00').prop('checked', false).prop('indeterminate', false);
    } else {
      // 一部がチェックされている
      $('#search_select__area00').prop('checked', false).prop('indeterminate', true);
    }
  }

  // 初期状態の設定（ページ読み込み時にチェック状態を確認）
  updateNationalCheckbox();

  // サイドバー検索ボタンのクリックイベント
  $('#search_side_submit').on('click', function(e) {
    e.preventDefault();
    
    // メインフォームの全データをクリア
    var $mainForm = $('.search_main__panel_body');
    $mainForm.find('input[type="checkbox"]').prop('checked', false).prop('indeterminate', false);
    $mainForm.find('input[type="text"]').val('');
    $mainForm.find('select').val('');
    
    // サイドフォームのデータをメインフォームにコピー
    var $sideForm = $('#search_side_form');
    
    // 都道府県のコピー
    $sideForm.find('input[name="prefecture[]"]:checked').each(function() {
      var prefecture = $(this).val();
      $mainForm.find('input[name="prefecture[]"][value="' + prefecture + '"]').prop('checked', true);
    });
    
    // 市区町村のコピー
    $sideForm.find('input[name="city[]"]:checked').each(function() {
      var city = $(this).val();
      $mainForm.find('input[name="city[]"][value="' + city + '"]').prop('checked', true);
    });
    
    // 職種のコピー（もし実装されている場合）
    $sideForm.find('input[name="industry[]"]:checked, input[name="job_type_with_industry[]"]:checked').each(function() {
      var value = $(this).val();
      var name = $(this).attr('name');
      $mainForm.find('input[name="' + name + '"][value="' + value + '"]').prop('checked', true);
    });
    
    // 年収のコピー（もし実装されている場合）
    var annualIncome = $sideForm.find('select[name="annual_income"]').val();
    if (annualIncome) {
      $mainForm.find('select[name="annual_income"]').val(annualIncome);
    }
    
    // キーワードのコピー
    var keyword = $sideForm.find('input[name="keyword"]').val();
    if (keyword) {
      $mainForm.find('input[name="keyword"]').val(keyword);
    }
    
    // こだわり条件のコピー
    $sideForm.find('input[name="conditions[]"]:checked').each(function() {
      var condition = $(this).val();
      $mainForm.find('input[name="conditions[]"][value="' + condition + '"]').prop('checked', true);
    });
    
    // メインフォームを送信
    $mainForm.closest('form').submit();
  });
});
</script>

<?php get_footer(); ?>