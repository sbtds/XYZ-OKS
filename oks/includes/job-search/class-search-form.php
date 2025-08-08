<?php
/**
 * Search Form Class
 * 
 * @package OKS
 * @subpackage Job_Search
 */

// Direct access protection
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Search Form Class
 */
class OKS_Search_Form {
    
    /**
     * Render search form
     */
    public function render() {
        $search_data = new OKS_Search_Data();
        $prefectures = $search_data->get_prefectures_with_cities();
        $job_types = $search_data->get_job_types();
        $conditions = $search_data->get_conditions();
        ?>
        <div class="oks-job-search-form">
            <form id="oks-job-search-form" method="get" action="">
                
                <!-- 勤務地 -->
                <div class="search-section">
                    <h3>勤務地から探す</h3>
                    <div class="prefecture-list">
                        <?php foreach ($prefectures as $prefecture => $cities): ?>
                            <div class="prefecture-group">
                                <label class="prefecture-label">
                                    <input type="checkbox" class="prefecture-checkbox" data-prefecture="<?php echo esc_attr($prefecture); ?>">
                                    <span class="prefecture-name"><?php echo esc_html($prefecture); ?></span>
                                    <span class="toggle-cities">▼</span>
                                </label>
                                <div class="city-list" style="display: none;">
                                    <?php foreach ($cities as $city): ?>
                                        <label class="city-label">
                                            <input type="checkbox" name="city[]" value="<?php echo esc_attr($city); ?>" 
                                                   data-prefecture="<?php echo esc_attr($prefecture); ?>">
                                            <?php echo esc_html($city); ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- 職種 -->
                <div class="search-section">
                    <h3>職種から探す</h3>
                    <div class="job-type-list">
                        <?php foreach ($job_types as $job_type): ?>
                            <label class="job-type-label">
                                <input type="checkbox" name="job_type[]" value="<?php echo esc_attr($job_type); ?>">
                                <?php echo esc_html($job_type); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- 年収 -->
                <div class="search-section">
                    <h3>年収から探す</h3>
                    <div class="salary-range">
                        <select name="salary_min" class="salary-select">
                            <option value="">下限なし</option>
                            <option value="2000000">200万円以上</option>
                            <option value="3000000">300万円以上</option>
                            <option value="4000000">400万円以上</option>
                            <option value="5000000">500万円以上</option>
                            <option value="6000000">600万円以上</option>
                            <option value="7000000">700万円以上</option>
                            <option value="8000000">800万円以上</option>
                            <option value="9000000">900万円以上</option>
                            <option value="10000000">1000万円以上</option>
                        </select>
                        <span class="salary-separator">〜</span>
                        <select name="salary_max" class="salary-select">
                            <option value="">上限なし</option>
                            <option value="3000000">300万円以下</option>
                            <option value="4000000">400万円以下</option>
                            <option value="5000000">500万円以下</option>
                            <option value="6000000">600万円以下</option>
                            <option value="7000000">700万円以下</option>
                            <option value="8000000">800万円以下</option>
                            <option value="9000000">900万円以下</option>
                            <option value="10000000">1000万円以下</option>
                            <option value="15000000">1500万円以下</option>
                        </select>
                    </div>
                </div>
                
                <!-- こだわり条件 -->
                <div class="search-section">
                    <h3>こだわり条件から探す</h3>
                    <div class="condition-list">
                        <?php foreach ($conditions as $key => $label): ?>
                            <label class="condition-label">
                                <input type="checkbox" name="conditions[]" value="<?php echo esc_attr($key); ?>">
                                <?php echo esc_html($label); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- キーワード検索 -->
                <div class="search-section">
                    <h3>キーワードから探す</h3>
                    <div class="keyword-search">
                        <input type="text" name="keyword" placeholder="キーワードを入力" 
                               value="<?php echo esc_attr($_GET['keyword'] ?? ''); ?>">
                    </div>
                </div>
                
                <!-- 検索ボタン -->
                <div class="search-buttons">
                    <button type="submit" class="search-submit">検索する</button>
                    <button type="reset" class="search-reset">条件をクリア</button>
                </div>
                
            </form>
        </div>
        <?php
    }
}