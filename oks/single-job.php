<?php
/**
 * Template for Single Job Post
 *
 * @package OKS
 */

get_header();

// Function to get job conditions dynamically
function get_single_job_conditions($post_id) {
    $conditions = array();

    // Boolean condition fields
    $condition_fields = array(
        'weekend_holiday' => '土日祝休み',
        'low_overtime' => '残業少なめ',
        'remote_work' => 'リモートワーク可',
        'car_commute' => '車通勤可',
        'bike_commute' => '自転車通勤可',
        'fixed_overtime_pay' => '固定残業代あり',
        'discretionary_work' => '裁量労働制',
        'passive_smoking' => '受動喫煙対策',
        'contract_period' => '契約期間あり',
        'probation_period' => '試用期間あり',
        'established_10years' => '設立10年以上の会社',
        'transfer_possibility' => '転勤なし'
    );

    // Check each boolean field
    foreach ($condition_fields as $field => $label) {
        if (get_field($field, $post_id)) {
            $conditions[] = $label;
        }
    }

    // Special fields that need custom handling

    // Application category
    $app_category = get_field('application_category', $post_id);
    if ($app_category) {
        if ($app_category === '新卒') {
            $conditions[] = '新卒採用';
        } elseif ($app_category === '第二新卒') {
            $conditions[] = '第二新卒採用';
        } elseif ($app_category === '中途') {
            $conditions[] = '中途採用';
        }
    }

    // Employment type
    $employment_type = get_field('employment_type', $post_id);
    if ($employment_type) {
        $conditions[] = $employment_type;
    }

    return $conditions;
}

?>
<main class="page_main">
  <section class="search_title">
    <div class="search_title__container">
      <p class="arrow"><img
          src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_title_arrow.svg" alt="" /></p>
      <div class="main">
        <p class="caption"><?php echo get_field('job_type'); ?></p>
        <h1 class="title"><?php echo get_field('display_title'); ?></h1>
        <ul class="badges">
          <?php
          $conditions = get_single_job_conditions(get_the_ID());
          if (!empty($conditions)):
          ?>
          <?php foreach ($conditions as $condition): ?>
          <li class="badges_item"><span><?php echo esc_html($condition); ?></span></li>
          <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
      <p class="arrow"><img
          src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_title_arrow.svg" alt="" /></p>
    </div>
  </section>
  <?php if(get_field('recommend_point_1')): ?>
  <section class="search_point">
    <div class="search_point__container">
      <h2 class="search_point__title">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_point_title_sp.svg"
          class="sp-only" alt="この求人のおすすめポイント" />
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_point_title.svg"
          class="pc-only" alt="この求人のおすすめポイント" />
      </h2>
      <ul class="search_point__list">
        <?php if(get_field('recommend_point_1')): ?>
        <li class="search_point__item">
          <p class="badge"><span class="label">POINT 1</span></p>
          <div class="contents">
            <p>
              <?php echo get_field('recommend_point_1'); ?>
            </p>
          </div>
        </li>
        <?php endif; ?>
        <?php if(get_field('recommend_point_2')): ?>
        <li class="search_point__item">
          <p class="badge"><span class="label">POINT 2</span></p>
          <div class="contents">
            <p>
              <?php echo get_field('recommend_point_2'); ?>
            </p>
          </div>
        </li>
        <?php endif; ?>
        <?php if(get_field('recommend_point_3')): ?>
        <li class="search_point__item">
          <p class="badge"><span class="label">POINT 3</span></p>
          <div class="contents">
            <p>
              <?php echo get_field('recommend_point_3'); ?>
            </p>
          </div>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </section>
  <?php endif; ?>

  <section class="featured_outline search_outline">
    <div class="featured_outline__container">
      <!-- <h3 class="featured_outline__title">会社概要</h3> -->
      <div class="featured_outline__main">
        <div class="featured_outline__list">
          <?php if(get_field('h_job_content')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">仕事内容</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('h_job_content'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_application_requirements')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">応募資格</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('h_application_requirements'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">契約期間<br />の有無</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  <?php if(get_field('contract_period')): ?>
                  契約期間の定め：あり
                  <?php else:?>
                  契約期間の定め：なし
                  <?php endif;?>
                </p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">契約期間</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  <?php echo get_field('contract_renewal'); ?>
                </p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">契約更新<br />のための条件</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('renewal_limit'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">試用期間<br />の有無</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  <?php if(get_field('probation_period')): ?>
                  試用期間：あり
                  <?php else:?>
                  試用期間：なし
                  <?php endif;?>
                </p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">試用期間</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  <?php echo get_field('probation_duration'); ?>
                </p>
              </div>
            </dd>
          </dl>

          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">試用期間中の条件</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('probation_conditions'); ?>
              </div>
            </dd>
          </dl>

          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">想定年収</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p><?php
                  $min_salary = get_field('min_salary');
                  $max_salary = get_field('max_salary');
                  if ($min_salary && $max_salary) {
                    echo number_format($min_salary / 10000) . '万円～' . number_format($max_salary / 10000) . '万円';
                  } elseif ($min_salary) {
                    echo number_format($min_salary / 10000) . '万円～';
                  } elseif ($max_salary) {
                    echo '～' . number_format($max_salary / 10000) . '万円';
                  }
                ?></p>
              </div>
            </dd>
          </dl>

          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">給与形態</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  <?php echo get_field('salary_type'); ?>
                </p>
              </div>
            </dd>
          </dl>

          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">給与</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  <?php echo get_field('salary'); ?>
                </p>
              </div>
            </dd>
          </dl>

          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">給与詳細</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('salary_details'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">固定残業<br />
                代の有無</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  <?php if(get_field('fixed_overtime_pay')): ?>
                  固定残業代：あり
                  <?php else:?>
                  固定残業代：なし
                  <?php endif;?>
                </p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">固定残業<br />代の詳細</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('fixed_overtime_details'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">賞与</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('bonus'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">昇給</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  <?php echo get_field('salary_increase'); ?>
                </p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">勤務地</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  <?php echo get_field('work_location'); ?>
                </p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">勤務地詳細</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('work_location_details'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">アクセス</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('access'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">諸手当</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('benefits'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">休日休暇</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('holidays'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">勤務時間</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('working_hours'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">募集背景</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('recruitment_background'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">募集要項</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
              </div>
            </dd>
          </dl>
        </div>
        <div class="button_section">
          <a class="button_more" href="#">
            <span class="label">この求人を問い合わせる</span>
            <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
          </a>
        </div>
      </div>
    </div>

    <div class="featured_outline__container">
      <h3 class="featured_outline__title">企業情報</h3>
      <div class="featured_outline__main">
        <div class="featured_outline__list">
          <?php if(get_field('h_employee_count')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">従業員数</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p><?php echo get_field('h_employee_count'); ?></p>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_head_office_address')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">本社住所</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p><?php echo get_field('h_head_office_address'); ?></p>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_url')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">URL</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p><a href="<?php echo get_field('h_url'); ?>" target="_blank"><?php echo get_field('h_url'); ?></a></p>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_established_date')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">設立年月</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p><?php echo get_field('h_established_date'); ?></p>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_stock_public')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">株式公開</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p><?php echo get_field('h_stock_public'); ?></p>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>

          <?php if(get_field('h_work_location')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">勤務地</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p><?php echo get_field('h_work_location'); ?></p>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_work_location_details')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">勤務地詳細</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('h_work_location_details'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_access')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">アクセス</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('h_access'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_allowances')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">諸手当</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('h_allowances'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_holidays')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">休日休暇</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('h_holidays'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
          <?php if(get_field('h_working_hours')): ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label">勤務時間</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('h_working_hours'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <?php endif; ?>
        </div>
      </div>
    </div>


  </section>

  <section class="search_banner">
    <div class="search_banner__container">
      <p class="search_banner__image">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_banner_image.jpg" alt="" />
      </p>
      <div class="search_banner__main">
        <h3 class="title">
          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_banner_title.svg"
            class="" alt="求人探しにお困りの方へ" />
        </h3>
        <div class="contents">
          <p>
            テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト
          </p>
        </div>
        <a class="button_more" href="#">
          <span class="label">非公開求人を紹介してもらう</span>
          <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
        </a>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>