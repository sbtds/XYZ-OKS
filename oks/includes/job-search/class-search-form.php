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
     * Render search form panel
     */
    public function render_panel() {
        $search_data = new OKS_Search_Data();
        $prefectures = $search_data->get_prefectures_with_cities();
        $job_types = $search_data->get_job_types();
        $conditions = $search_data->get_conditions();
        ?>

<div class="search_main__panel_item">
  <input type="checkbox" id="search_main__panel_subject01">
  <label class="search_main__panel_subject" for="search_main__panel_subject01">
    <span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/page/common_icon_area.svg"
        alt=""></span>
    <span class="label">勤務地</span>
    <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
  </label>
  <div class="search_main__panel_block">

    <div class="search_select__area">
      <input type="checkbox" class="search_select__area_show" id="search_select__area_show01__">
      <input type="checkbox" class="search_select__area_check" id="search_select__area01">
      <label class="search_select__area_title" for="search_select__area01">
        <span class="checkbox"></span>
        <span class="label">岩手県</span>
        <span class="count">(123,456件)</span>
        <label class="arrow" for="search_select__area_show01__">
          <span class="plus"><i class="fa-solid fa-plus"></i></span>
          <span class="minus"><i class="fa-solid fa-minus"></i></span>
        </label>
      </label>
      <div class="search_select__area_menu">
        <div class="search_select__area_list">
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" value="一関市">
            <span class="checkbox"></span>
            <span class="label">一関市</span>
          </label>
          <label class="search_select__area_item">
            <input type="checkbox" class="search_select__area_item_check" value="西磐井郡平泉町">
            <span class="checkbox"></span>
            <span class="label">西磐井郡平泉町</span>
          </label>
        </div>
      </div>
    </div>
    <!-- 他の都道府県も同様に -->

  </div>
</div>

<div class="search_main__panel_item">
  <input type="checkbox" id="search_main__panel_subject02">
  <label class="search_main__panel_subject" for="search_main__panel_subject02">
    <span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/page/common_icon_type.svg"
        alt=""></span>
    <span class="label">職種</span>
    <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
  </label>
  <div class="search_main__panel_block">

    <div class="search_select__type">
      <input type="checkbox" class="search_select__type_show" id="search_select__type_show01__">
      <input type="checkbox" class="search_select__type_check" id="search_select__type01" name="type" value="製造・技術">
      <label class="search_select__type_title" for="search_select__type01">
        <span class="checkbox"></span>
        <span class="label">製造・技術</span>
        <span class="count">(123,456件)</span>
        <label class="arrow" for="search_select__type_show01__">
          <span class="plus"><i class="fa-solid fa-plus"></i></span>
          <span class="minus"><i class="fa-solid fa-minus"></i></span>
        </label>
      </label>
      <div class="search_select__type_menu">
        <div class="search_select__type_list">
          <label class="search_select__type_item">
            <input type="checkbox" class="search_select__type_item_check" name="type"
              value="機械オペレーター機械オペレーション機械オペレーター機械オペレーション">
            <span class="checkbox"></span>
            <span class="label">機械オペレーター機械オペレーション機械オペレーター機械オペレーション</span>
          </label>
          <!-- 他の職種も -->
        </div>
      </div>
    </div>

  </div>
</div>

<div class="search_main__panel_item">
  <input type="checkbox" id="search_main__panel_subject03">
  <label class="search_main__panel_subject" for="search_main__panel_subject03">
    <span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/page/common_icon_income.svg"
        alt=""></span>
    <span class="label">給与</span>
    <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
  </label>
  <div class="search_main__panel_block">

    <div class="search_select__type">
      <input type="checkbox" class="search_select__type_show" id="search_select__type_show01__">
      <input type="checkbox" class="search_select__type_check" id="search_select__income01" name="income" value="時給">
      <label class="search_select__type_title" for="search_select__income01">
        <span class="checkbox"></span>
        <span class="label">時給</span>

        <label class="arrow" for="search_select__type_show01__">
          <span class="plus"><i class="fa-solid fa-plus"></i></span>
          <span class="minus"><i class="fa-solid fa-minus"></i></span>
        </label>
      </label>
      <div class="search_select__type_menu">
        <div class="search_select__type_list">
          <label class="search_select__type_item">
            <input type="checkbox" class="search_select__type_item_check" name="income" value="1,300円未満">
            <span class="checkbox"></span>
            <span class="label">1,300円未満</span>
          </label>
          <!-- 他の給与条件も -->
        </div>
      </div>
    </div>

  </div>
</div>

<div class="search_main__panel_item">
  <input type="checkbox" id="search_main__panel_subject04">
  <label class="search_main__panel_subject" for="search_main__panel_subject04">
    <span class="icon"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/page/common_icon_feature.svg"
        alt=""></span>
    <span class="label">特徴</span>
    <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
  </label>
  <div class="search_main__panel_block block-last">
    <div class="search_select__conditions">
      <p class="search_select__conditions_title">人気の特徴</p>
      <div class="search_select__conditions_list">
        <?php
                        $popular_conditions = array_slice($conditions, 0, 6, true);
                        foreach ($popular_conditions as $key => $label):
                        ?>
        <label class="search_select__conditions_item">
          <input type="checkbox" class="search_select__conditions_item_check" name="conditions"
            value="<?php echo esc_attr($key); ?>">
          <span class="checkbox"></span>
          <span class="label"><?php echo esc_html($label); ?></span>
        </label>
        <?php endforeach; ?>
      </div>
      <!-- 他の条件カテゴリも -->
    </div>
  </div>
</div>

<div class="search_main__panel_item">
  <input type="checkbox" id="" checked="checked">
  <div class="search_main__panel_block">
    <p class="search_select__conditions_title">キーワード</p>
    <p class="search_select__keyword_input">
      <input type="text" value="" placeholder="入力してください">
    </p>
  </div>
</div>

<?php
    }

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