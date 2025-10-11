<?php
/**
 * Template Name: 求人検索ページ
 * Template for Job Search Page
 *
 * @package OKS
 */

// 検索ページの動的タイトル設定（area IDベース）
add_filter('document_title_parts', function($title_parts) {
    // area-mapping.phpを読み込み
    if (file_exists(get_template_directory() . '/includes/area-mapping.php')) {
        require_once get_template_directory() . '/includes/area-mapping.php';
    }
    
    $custom_title = '';
    
    // area IDパラメータから都道府県名を取得
    if (!empty($_GET['area'])) {
        $area_ids = is_array($_GET['area']) ? $_GET['area'] : array($_GET['area']);
        $area_mapping = oks_get_area_name_mapping();
        
        if (count($area_ids) == 1) {
            // 単一の地域
            $area_id = intval($area_ids[0]);
            if (isset($area_mapping[$area_id])) {
                $custom_title = $area_mapping[$area_id] . 'の求人';
            }
        } elseif (count($area_ids) >= 40) {
            // 全国検索
            $custom_title = '全国の求人';
        } else {
            // 複数地域
            $custom_title = '求人検索結果';
        }
    }
    
    // keyword（キーワード）パラメータ
    if (!empty($_GET['keyword']) && empty($custom_title)) {
        $custom_title = esc_html($_GET['keyword']) . 'の求人';
    }
    
    // カスタムタイトルが設定されている場合
    if (!empty($custom_title)) {
        $title_parts['title'] = $custom_title;
    }
    
    return $title_parts;
}, 99999);

get_header();

// Handle search
$search_params = array();
if (!empty($_GET)) {
    $search_params = $_GET;
} elseif (!empty($_POST)) {
    $search_params = $_POST;
}

// area IDをprefecture[]に変換してチェックボックス状態を復元
$selected_prefectures_from_area = array();
if (!empty($search_params['area'])) {
    // area-mapping.phpを読み込み
    require_once get_template_directory() . '/includes/area-mapping.php';
    
    $area_ids = is_array($search_params['area']) ? $search_params['area'] : array($search_params['area']);
    $area_mapping = oks_get_area_name_mapping();
    
    foreach ($area_ids as $area_id) {
        $area_id_int = intval($area_id);
        if (isset($area_mapping[$area_id_int])) {
            $selected_prefectures_from_area[] = $area_mapping[$area_id_int];
        }
    }
    
    // 重複を削除して設定
    if (!empty($selected_prefectures_from_area)) {
        $search_params['prefecture'] = array_unique($selected_prefectures_from_area);
    }
}


// Handle pagination from URL path (e.g., /search/page/2/)
if (!isset($search_params['paged'])) {
    $current_url = $_SERVER['REQUEST_URI'];
    if (preg_match('/\/page\/(\d+)\/?/', $current_url, $matches)) {
        $search_params['paged'] = intval($matches[1]);
    }
}

$search_handler = new OKS_Search_Handler();
$search_results = $search_handler->search($search_params);

// Get search summary
$search_summary = $search_handler->get_search_summary($search_params);

// Get unique prefectures and cities from job posts
$unique_prefectures = $search_handler->get_unique_prefectures();
$unique_cities = $search_handler->get_unique_cities_by_prefecture();

// 都道府県が選択されている場合、その県の市区町村も選択状態にする
if (!empty($selected_prefectures_from_area) && empty($search_params['city'])) {
    $selected_cities = array();
    foreach ($selected_prefectures_from_area as $prefecture) {
        if (isset($unique_cities[$prefecture])) {
            $selected_cities = array_merge($selected_cities, $unique_cities[$prefecture]);
        }
    }
    if (!empty($selected_cities)) {
        $search_params['city'] = array_unique($selected_cities);
    }
}

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
                <select name="posts_per_page" id="posts_per_page_select" onchange="changePostsPerPage(this.value)">
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
                        <?php checked(isset($search_params['prefecture']) && in_array($prefecture, $search_params['prefecture'])); ?> />
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
                              data-prefecture="<?php echo esc_attr($prefecture); ?>"
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
                    <?php
                    // TODO: 職種の動的表示
                    // 現在はデータがないため、職種検索機能を一時的に非表示にしています。
                    // 実装時は $unique_industries と $unique_job_types_by_industry を使用して
                    // 勤務地と同様の動的な職種選択機能を実装してください。
                    ?>
                    <p style="padding: 20px; text-align: center; color: #666;">
                      職種検索機能は準備中です
                    </p>
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
                      <p class="search_select__conditions_title">休日・働き方</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="完全週休二日制"
                            <?php if (isset($search_params['conditions']) && in_array('完全週休二日制', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">完全週休二日制</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="土日祝休み"
                            <?php if (isset($search_params['conditions']) && in_array('土日祝休み', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">土日祝休み</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="年間休日120日以上"
                            <?php if (isset($search_params['conditions']) && in_array('年間休日120日以上', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">年間休日120日以上</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="残業少なめ（月20時間未満）"
                            <?php if (isset($search_params['conditions']) && in_array('残業少なめ（月20時間未満）', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">残業少なめ（月20時間未満）</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="産休・育休・介護休暇取得実績あり"
                            <?php if (isset($search_params['conditions']) && in_array('産休・育休・介護休暇取得実績あり', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">産休・育休・介護休暇取得実績あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="リモートワーク・在宅勤務制度あり"
                            <?php if (isset($search_params['conditions']) && in_array('リモートワーク・在宅勤務制度あり', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">リモートワーク・在宅勤務制度あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="フレックスタイム制度あり"
                            <?php if (isset($search_params['conditions']) && in_array('フレックスタイム制度あり', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">フレックスタイム制度あり</span>
                        </label>
                      </div>
                      <p class="search_select__conditions_title">待遇・福利厚生</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="退職金制度"
                            <?php if (isset($search_params['conditions']) && in_array('退職金制度', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">退職金制度</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="寮・社宅・住宅手当あり"
                            <?php if (isset($search_params['conditions']) && in_array('寮・社宅・住宅手当あり', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">寮・社宅・住宅手当あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="U・Iターン支援あり"
                            <?php if (isset($search_params['conditions']) && in_array('U・Iターン支援あり', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">U・Iターン支援あり</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="交通費支給"
                            <?php if (isset($search_params['conditions']) && in_array('交通費支給', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">交通費支給</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="固定残業代なし"
                            <?php if (isset($search_params['conditions']) && in_array('固定残業代なし', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">固定残業代なし</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="資格取得支援制度"
                            <?php if (isset($search_params['conditions']) && in_array('資格取得支援制度', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">資格取得支援制度</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="研修制度あり"
                            <?php if (isset($search_params['conditions']) && in_array('研修制度あり', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">研修制度あり</span>
                        </label>
                      </div>
                      <p class="search_select__conditions_title">会社・職場環境</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="女性活躍中"
                            <?php if (isset($search_params['conditions']) && in_array('女性活躍中', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">女性活躍中</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="ミドル活躍中"
                            <?php if (isset($search_params['conditions']) && in_array('ミドル活躍中', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">ミドル活躍中</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="シニア活躍中"
                            <?php if (isset($search_params['conditions']) && in_array('シニア活躍中', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">シニア活躍中</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="上場企業"
                            <?php if (isset($search_params['conditions']) && in_array('上場企業', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">上場企業</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="設立10年以上の会社"
                            <?php if (isset($search_params['conditions']) && in_array('設立10年以上の会社', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">設立10年以上の会社</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="ベンチャー企業"
                            <?php if (isset($search_params['conditions']) && in_array('ベンチャー企業', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">ベンチャー企業</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="車通勤可"
                            <?php if (isset($search_params['conditions']) && in_array('車通勤可', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">車通勤可</span>
                        </label>
                      </div>
                      <p class="search_select__conditions_title">募集情報</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="職種未経験歓迎"
                            <?php if (isset($search_params['conditions']) && in_array('職種未経験歓迎', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">職種未経験歓迎</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="業種未経験歓迎"
                            <?php if (isset($search_params['conditions']) && in_array('業種未経験歓迎', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">業種未経験歓迎</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="社会人経験不問"
                            <?php if (isset($search_params['conditions']) && in_array('社会人経験不問', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">社会人経験不問</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="学歴不問"
                            <?php if (isset($search_params['conditions']) && in_array('学歴不問', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">学歴不問</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="ITスキル不問"
                            <?php if (isset($search_params['conditions']) && in_array('ITスキル不問', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">ITスキル不問</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="新卒採用"
                            <?php if (isset($search_params['conditions']) && in_array('新卒採用', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">新卒採用</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="第二新卒採用"
                            <?php if (isset($search_params['conditions']) && in_array('第二新卒採用', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">第二新卒採用</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="中途採用"
                            <?php if (isset($search_params['conditions']) && in_array('中途採用', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">中途採用</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="転勤なし"
                            <?php if (isset($search_params['conditions']) && in_array('転勤なし', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">転勤なし</span>
                        </label>
                      </div>
                      <p class="search_select__conditions_title">雇用形態</p>
                      <div class="search_select__conditions_list">
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="正社員"
                            <?php if (isset($search_params['conditions']) && in_array('正社員', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">正社員</span>
                        </label>
                        <label class="search_select__conditions_item">
                          <input type="checkbox" class="search_select__conditions_item_check" name="conditions[]"
                            value="契約社員"
                            <?php if (isset($search_params['conditions']) && in_array('契約社員', $search_params['conditions'])) echo 'checked'; ?> />
                          <span class="checkbox"></span>
                          <span class="label">契約社員</span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- //item -->
                <!-- item -->
                <div class="search_main__panel_item" id="search_main_keyword">
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
              <li class="badges_item"><span><?php echo esc_html($condition); ?></span></li>
              <?php endforeach; ?>
              <?php endif; ?>
            </ul>
            <div class="contents">
              <dl>
                <dt>応募資格</dt>
                <dd><?php echo wp_kses_post(wpautop($job['h_application_requirements'])); ?></dd>
              </dl>
              <dl>
                <dt>仕事内容</dt>
                <dd><?php echo wp_kses_post(wpautop($job['job_description'])); ?></dd>
              </dl>
              <dl>
                <dt>想定年収</dt>
                <dd><?php
                  if ($job['min_salary'] && $job['max_salary']) {
                    echo number_format($job['min_salary'] / 10000) . '万円～' . number_format($job['max_salary'] / 10000) . '万円';
                  } elseif ($job['min_salary']) {
                    echo number_format($job['min_salary'] / 10000) . '万円～';
                  } elseif ($job['max_salary']) {
                    echo '～' . number_format($job['max_salary'] / 10000) . '万円';
                  } else {
                    echo '未設定';
                  }
                ?></dd>
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
              
              // Get current page URL without paged parameter
              $current_url = $_SERVER['REQUEST_URI'];
              // Remove existing page and paged parameters
              $current_url = preg_replace('/\/page\/\d+\//', '/', $current_url);
              $current_url = remove_query_arg('paged', $current_url);
              
              // Ensure URL ends with /
              if (substr($current_url, -1) !== '/') {
                  $current_url .= '/';
              }

              // Previous button
              if ($current_page > 1) {
                  if ($current_page == 2) {
                      $prev_url = $current_url;
                  } else {
                      $prev_url = rtrim($current_url, '/') . '/page/' . ($current_page - 1) . '/';
                  }
                  echo '<p class="search_main__nav_item prev"><a href="' . esc_url($prev_url) . '"><i class="fa-solid fa-chevron-left"></i></a></p>';
              }

              // Page numbers
              for ($i = max(1, $current_page - 2); $i <= min($max_pages, $current_page + 2); $i++) {
                  $class = ($i == $current_page) ? ' active' : '';
                  if ($i == 1) {
                      $page_url = $current_url;
                  } else {
                      $page_url = rtrim($current_url, '/') . '/page/' . $i . '/';
                  }
                  echo '<p class="search_main__nav_item' . $class . '"><a href="' . esc_url($page_url) . '">' . $i . '</a></p>';
              }

              // Next button
              if ($current_page < $max_pages) {
                  $next_url = rtrim($current_url, '/') . '/page/' . ($current_page + 1) . '/';
                  echo '<p class="search_main__nav_item next"><a href="' . esc_url($next_url) . '"><i class="fa-solid fa-chevron-right"></i></a></p>';
              }
              ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <?php get_template_part('template-parts/search-sidebar'); ?>
    </div>
  </section>
</main>


<script>
function changePostsPerPage(value) {
    // Get current URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    
    // Update posts_per_page parameter
    urlParams.set('posts_per_page', value);
    
    // Remove paged parameter to go back to page 1
    urlParams.delete('paged');
    
    // Build new URL
    const currentPath = window.location.pathname.replace(/\/page\/\d+\/?/, '/');
    const newUrl = currentPath + (urlParams.toString() ? '?' + urlParams.toString() : '');
    
    // Navigate to new URL
    window.location.href = newUrl;
}

// ページロード時にチェックボックス状態を復元
jQuery(document).ready(function($) {
    <?php if (!empty($search_params['area'])): ?>
    <?php 
    // area-mapping.phpが読み込まれていない場合は読み込む
    if (!function_exists('oks_get_area_name_mapping')) {
        require_once get_template_directory() . '/includes/area-mapping.php';
    }
    ?>
    
    // 検索条件パネルを開く
    $('#search_main__panel_check').prop('checked', true);
    
    // 選択されたarea IDに基づいて都道府県チェックボックスを設定
    const selectedAreas = <?php echo json_encode(is_array($search_params['area']) ? $search_params['area'] : array($search_params['area'])); ?>;
    const areaMapping = <?php echo json_encode(oks_get_area_name_mapping()); ?>;
    
    console.log('Selected area IDs:', selectedAreas);
    console.log('Area mapping:', areaMapping);
    
    // area IDから都道府県名を取得してチェックボックスを設定
    selectedAreas.forEach(function(areaId) {
        const prefecture = areaMapping[parseInt(areaId)];
        if (prefecture) {
            console.log('Setting checkbox for:', prefecture);
            const prefectureCheckbox = $('input[name="prefecture[]"][value="' + prefecture + '"]');
            prefectureCheckbox.prop('checked', true);
            
            // その県に属する市区町村も全てチェック
            const prefectureContainer = prefectureCheckbox.closest('.search_select__area');
            const cityCheckboxes = prefectureContainer.find('input[name="city[]"][data-prefecture="' + prefecture + '"]');
            cityCheckboxes.prop('checked', true);
            
            console.log('Also checked', cityCheckboxes.length, 'cities for', prefecture);
        }
    });
    
    // 全国チェックボックスの状態を更新
    if (selectedAreas.length >= 40) {
        $('.js-select-all-areas').prop('checked', true);
        console.log('Set all areas checkbox to checked');
    }
    
    <?php endif; ?>
    
    // メインフォームの都道府県チェックボックスのイベントハンドラー
    $('#search_main_area').on('change', '.search_select__area_check', function() {
        var isChecked = $(this).prop('checked');
        var prefecture = $(this).val();
        var container = $(this).closest('.search_select__area');
        
        // この都道府県の市区町村をすべてチェック/アンチェック
        container.find('.search_select__area_item_check[data-prefecture="' + prefecture + '"]').prop('checked', isChecked);
    });
});

</script>

<?php get_footer(); ?>