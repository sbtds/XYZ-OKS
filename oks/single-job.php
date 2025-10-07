<?php
/**
 * Template for Single Job Post
 *
 * @package OKS
 */

get_header(); ?>
<main class="page_main">
  <section class="search_title">
    <div class="search_title__container">
      <p class="arrow"><img
          src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_title_arrow.svg" alt="" /></p>
      <div class="main">
        <p class="caption">経理/財務/株式公開</p>
        <h1 class="title"><?php echo get_field('display_title'); ?></h1>
        <ul class="badges">
          <li class="badges_item">
            <a href="#">車通勤可</a>
          </li>
          <li class="badges_item">
            <a href="#">設立10年以上の会社</a>
          </li>
          <li class="badges_item">
            <a href="#">管理職・マネージャー</a>
          </li>
          <li class="badges_item">
            <a href="#">正社員</a>
          </li>
          <li class="badges_item">
            <a href="#">転勤なし</a>
          </li>
        </ul>
      </div>
      <p class="arrow"><img
          src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_title_arrow.svg" alt="" /></p>
    </div>
  </section>
  <section class="search_point">
    <div class="search_point__container">
      <h2 class="search_point__title">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_point_title_sp.svg"
          class="sp-only" alt="この求人のおすすめポイント" />
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/search_point_title.svg"
          class="pc-only" alt="この求人のおすすめポイント" />
      </h2>
      <ul class="search_point__list">
        <li class="search_point__item">
          <p class="badge"><span class="label">POINT 1</span></p>
          <div class="contents">
            <p>未経験歓迎！充実の研修制度◎</p>
          </div>
        </li>
        <li class="search_point__item">
          <p class="badge"><span class="label">POINT 2</span></p>
          <div class="contents">
            <p>和気藹々とした雰囲気の職場◎</p>
          </div>
        </li>
        <li class="search_point__item">
          <p class="badge"><span class="label">POINT 1</span></p>
          <div class="contents">
            <p>食堂・食事補助あり♪</p>
          </div>
        </li>
      </ul>
    </div>
  </section>

  <section class="featured_outline search_outline">
    <div class="featured_outline__container">
      <!-- <h3 class="featured_outline__title">会社概要</h3> -->
      <div class="featured_outline__main">
        <div class="featured_outline__list">
          <dl class="featured_outline__item">
            <dt>
              <span class="label">仕事内容</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <?php echo get_field('job_description'); ?>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">応募資格</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>
                  ■必須条件<br>
                  <?php echo get_field('required_conditions'); ?>
                </p>
                <p>
                  ■歓迎条件<br>
                  <?php echo get_field('welcome_conditions_2'); ?>
                </p>
              </div>
            </dd>
          </dl>
          <hr />
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
      <h3 class="featured_outline__title">募集要項</h3>
      <div class="featured_outline__main">
        <br />
      </div>
    </div>
    <div class="featured_outline__container">
      <h3 class="featured_outline__title">企業情報</h3>
      <div class="featured_outline__main">
        <div class="featured_outline__list">
          <dl class="featured_outline__item">
            <dt>
              <span class="label">業種</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>食品</p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">事業内容</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>高級食料品、嗜好品、飲料の輸入・生産・販売、酒類及び酒類原料の輸入・販売</p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">従業員数</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>310人</p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">企業名</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>片岡物産株式会社</p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">本社<br />所在地</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>東京都港区新橋6-21-6</p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">URL</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p><a href="https://www.kataoka.com/company/" target="_blank">https://www.kataoka.com/company/</a></p>
              </div>
            </dd>
          </dl>
          <hr />
          <dl class="featured_outline__item">
            <dt>
              <span class="label">設立年月</span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt="" /></span>
            </dt>
            <dd>
              <div class="contents">
                <p>0000年00月</p>
              </div>
            </dd>
          </dl>
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