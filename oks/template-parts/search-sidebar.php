<?php
/**
 * Template Part: Search Sidebar
 *
 * Common search sidebar for both page-search.php and single-job.php
 *
 * @package OKS
 */

// Include search handler
require_once get_template_directory() . '/includes/job-search/job-search-loader.php';

// Handle search params
$search_params = array();
if (!empty($_GET)) {
    $search_params = $_GET;
} elseif (!empty($_POST)) {
    $search_params = $_POST;
}

$search_handler = new OKS_Search_Handler();
$search_results = $search_handler->search($search_params);

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

global $wpdb;

// Get total count of all job posts
$total_job_count = $wpdb->get_var("
    SELECT COUNT(*)
    FROM {$wpdb->posts}
    WHERE post_type = 'job'
    AND post_status = 'publish'
");
?>

<aside class="search_side">
  <div class="search_side__container">
    <p class="search_side__title">条件を変更する</p>
    <form class="search_select" id="search_side_form" method="GET"
      action="<?php echo esc_url(get_permalink(get_page_by_path('search'))); ?>">
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
                  <span class="count">(<?php echo number_format($total_job_count); ?>件)</span>
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
                      <input type="checkbox" class="search_select__type_item_check" name="type" value="製造（電気・電子・機械）" />
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
                      <input type="checkbox" class="search_select__type_item_check" name="type" value="その他（化学・医療・食品）" />
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
                      <input type="checkbox" class="search_select__type_item_check" name="type" value="仕分け・梱包・ピッキング" />
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
          <select name="salary_range" id="search_side_salary_range">
            <option value="">指定しない</option>
            <?php
              // 実際のmin_salaryの値から100万円台を抽出
              $salary_hundreds = $wpdb->get_col("
                  SELECT DISTINCT FLOOR(CAST(pm.meta_value AS UNSIGNED) / 1000000) as salary_hundred
                  FROM {$wpdb->posts} p
                  INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                  WHERE p.post_type = 'job'
                  AND p.post_status = 'publish'
                  AND pm.meta_key = 'min_salary'
                  AND pm.meta_value != ''
                  AND pm.meta_value REGEXP '^[0-9]+$'
                  ORDER BY salary_hundred ASC
              ");

              if (!empty($salary_hundreds)) {
                  foreach ($salary_hundreds as $hundred) {
                      $min_range = $hundred * 1000000;
                      $max_range = ($hundred + 1) * 1000000 - 1;
                      $range_value = $min_range . '-' . $max_range;
                      $selected = (isset($search_params['salary_range']) && $search_params['salary_range'] == $range_value) ? 'selected' : '';

                      if ($hundred >= 10) {
                          // 1000万以上の表示
                          echo '<option value="' . $range_value . '" ' . $selected . '>' . number_format($hundred / 10, 1) . ',000万円台</option>';
                      } else {
                          // 1000万未満の表示
                          echo '<option value="' . $range_value . '" ' . $selected . '>' . $hundred . '00万円台</option>';
                      }
                  }
              } else {
                  // デフォルトの選択肢（データがない場合）
                  ?>
            <option value="2000000-2999999">200万円台</option>
            <option value="3000000-3999999">300万円台</option>
            <option value="4000000-4999999">400万円台</option>
            <option value="5000000-5999999">500万円台</option>
            <?php
              }
              ?>
          </select>
        </div>
      </div>
      <div class="search_select__keyword" id="search_side_keyword">
        <div class="search_select__keyword_title">
          <p class="search_side__subject">キーワード</p>
        </div>
        <p class="search_select__keyword_input">
          <input type="text" name="keyword"
            value="<?php echo isset($search_params['keyword']) ? esc_attr($search_params['keyword']) : ''; ?>"
            placeholder="入力してください" />
        </p>
      </div>
      <div class="search_select__conditions" id="search_side_conditions">
        <div class="search_select__conditions_title">
          <p class="search_side__subject">こだわり条件</p>
        </div>
        <div class="search_select__conditions_list">
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="完全週休二日制"
              <?php if (isset($search_params['conditions']) && in_array('完全週休二日制', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">完全週休二日制</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="土日祝休み"
              <?php if (isset($search_params['conditions']) && in_array('土日祝休み', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">土日祝休み</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="年間休日120日以上"
              <?php if (isset($search_params['conditions']) && in_array('年間休日120日以上', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">年間休日120日以上</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="残業少なめ（月20時間未満）"
              <?php if (isset($search_params['conditions']) && in_array('残業少なめ（月20時間未満）', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">残業少なめ（月20時間未満）</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="産休・育休・介護休暇取得実績あり"
              <?php if (isset($search_params['conditions']) && in_array('産休・育休・介護休暇取得実績あり', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">産休・育休・介護休暇取得実績あり</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="リモートワーク・在宅勤務制度あり"
              <?php if (isset($search_params['conditions']) && in_array('リモートワーク・在宅勤務制度あり', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">リモートワーク・在宅勤務制度あり</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="フレックスタイム制度あり"
              <?php if (isset($search_params['conditions']) && in_array('フレックスタイム制度あり', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">フレックスタイム制度あり</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="退職金制度"
              <?php if (isset($search_params['conditions']) && in_array('退職金制度', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">退職金制度</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="寮・社宅・住宅手当あり"
              <?php if (isset($search_params['conditions']) && in_array('寮・社宅・住宅手当あり', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">寮・社宅・住宅手当あり</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="U・Iターン支援あり"
              <?php if (isset($search_params['conditions']) && in_array('U・Iターン支援あり', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">U・Iターン支援あり</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="交通費支給"
              <?php if (isset($search_params['conditions']) && in_array('交通費支給', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">交通費支給</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="固定残業代なし"
              <?php if (isset($search_params['conditions']) && in_array('固定残業代なし', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">固定残業代なし</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="資格取得支援制度"
              <?php if (isset($search_params['conditions']) && in_array('資格取得支援制度', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">資格取得支援制度</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="研修制度あり"
              <?php if (isset($search_params['conditions']) && in_array('研修制度あり', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">研修制度あり</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="女性活躍中"
              <?php if (isset($search_params['conditions']) && in_array('女性活躍中', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">女性活躍中</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="ミドル活躍中"
              <?php if (isset($search_params['conditions']) && in_array('ミドル活躍中', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">ミドル活躍中</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="シニア活躍中"
              <?php if (isset($search_params['conditions']) && in_array('シニア活躍中', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">シニア活躍中</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="上場企業"
              <?php if (isset($search_params['conditions']) && in_array('上場企業', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">上場企業</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="設立10年以上の会社"
              <?php if (isset($search_params['conditions']) && in_array('設立10年以上の会社', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">設立10年以上の会社</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="ベンチャー企業"
              <?php if (isset($search_params['conditions']) && in_array('ベンチャー企業', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">ベンチャー企業</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="車通勤可"
              <?php if (isset($search_params['conditions']) && in_array('車通勤可', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">車通勤可</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="職種未経験歓迎"
              <?php if (isset($search_params['conditions']) && in_array('職種未経験歓迎', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">職種未経験歓迎</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="業種未経験歓迎"
              <?php if (isset($search_params['conditions']) && in_array('業種未経験歓迎', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">業種未経験歓迎</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="社会人経験不問"
              <?php if (isset($search_params['conditions']) && in_array('社会人経験不問', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">社会人経験不問</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="学歴不問"
              <?php if (isset($search_params['conditions']) && in_array('学歴不問', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">学歴不問</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="ITスキル不問"
              <?php if (isset($search_params['conditions']) && in_array('ITスキル不問', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">ITスキル不問</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="新卒採用"
              <?php if (isset($search_params['conditions']) && in_array('新卒採用', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">新卒採用</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="第二新卒採用"
              <?php if (isset($search_params['conditions']) && in_array('第二新卒採用', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">第二新卒採用</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="中途採用"
              <?php if (isset($search_params['conditions']) && in_array('中途採用', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">中途採用</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="転勤なし"
              <?php if (isset($search_params['conditions']) && in_array('転勤なし', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">転勤なし</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="正社員"
              <?php if (isset($search_params['conditions']) && in_array('正社員', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">正社員</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" name="conditions[]" value="契約社員"
              <?php if (isset($search_params['conditions']) && in_array('契約社員', $search_params['conditions'])) echo 'checked'; ?> />
            <span class="checkbox"></span>
            <span class="label">契約社員</span>
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
  <?php get_template_part('template-parts/search-sidebar--banner'); ?>
</aside>

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

    // フォームを送信（search ページにリダイレクト）
    $('#search_side_form').submit();
  });
});
</script>