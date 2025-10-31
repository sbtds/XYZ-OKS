<?php
/**
 * 注目企業詳細テンプレート
 *
 * @package OKS
 */

get_header(); ?>

<main class="page_main">
  <div class="page_title bg-primary">
    <h1 class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/featured_title_sp.svg"
        class="sp-only" alt="注目企業">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/featured_title.svg" class="pc-only"
        alt="注目企業">
    </h1>
  </div>

  <?php if (have_posts()) : while (have_posts()) : the_post();
    // ACFフィールドを取得
    $company_top = get_field('company_top');
    $company_consultant = get_field('company_consultant');
    $company_recruit = get_field('company_recruit');
    $company_outline = get_field('company_outline');

    // company_block01〜06を取得
    $company_blocks = array();
    for ($i = 1; $i <= 6; $i++) {
      $block = get_field('company_block' . str_pad($i, 2, '0', STR_PAD_LEFT));
      if ($block && !empty($block['title'])) {
        $company_blocks[] = $block;
      }
    }
  ?>

  <section class="featured_detail">
    <!-- 企業トップセクション -->
    <div class="featured_top">
      <?php if (has_post_thumbnail()) : ?>
      <p class="featured_top__thb">
        <?php the_post_thumbnail('large'); ?>
      </p>
      <?php else : ?>
      <p class="featured_top__thb">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_thumb.jpg?size=550x360" alt="">
      </p>
      <?php endif; ?>

      <div class="featured_top__main">
        <?php if (!empty($company_top['eng'])) : ?>
        <p class="name"><?php echo esc_html($company_top['eng']); ?></p>
        <?php endif; ?>

        <?php if (!empty($company_top['name'])) : ?>
        <p class="company"><?php echo esc_html($company_top['name']); ?></p>
        <?php endif; ?>

        <?php
          // タクソノミーからエリアを取得
          $areas = get_the_terms(get_the_ID(), 'company_area');
          if ($areas && !is_wp_error($areas)) : ?>
        <p class="area">
          <?php foreach ($areas as $area) : ?>
          <a class="label" href="<?php echo esc_url(get_term_link($area)); ?>"><?php echo esc_html($area->name); ?></a>
          <?php endforeach; ?>
        </p>
        <?php endif; ?>

        <hr>

        <?php if (!empty($company_top['text'])) : ?>
        <p class="desc"><?php echo nl2br(esc_html($company_top['text'])); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="featured_main">
      <!-- コンサルタントからのコメント -->
      <div class="featured_main__intro">
        <?php if (!empty($company_consultant['image'])) : ?>
        <p class="image">
          <img src="<?php echo esc_url($company_consultant['image']['url']); ?>"
            alt="<?php echo esc_attr($company_consultant['image']['alt']); ?>">
        </p>
        <?php endif; ?>


        <?php if (!empty($company_consultant['title'])) : ?>
        <h2 class="title"><?php echo esc_html($company_consultant['title']); ?></h2>
        <?php else : ?>
        <h2 class="title">コンサルタントからのコメント</h2>
        <?php endif; ?>

        <?php if (!empty($company_consultant['text'])) : ?>
        <div class="contents">
          <p><?php echo nl2br(esc_html($company_consultant['text'])); ?></p>
        </div>
        <?php endif; ?>
      </div>

      <!-- 各ブロックセクション -->
      <?php if (!empty($company_blocks)) : ?>
      <div class="featured_main__contents">
        <?php foreach ($company_blocks as $block) : ?>
        <div class="featured_main__item">
          <h3 class="title">
            <span class="label"><?php echo esc_html($block['title']); ?></span>
            <span class="arrow"><img
                src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/page_title_arrow.svg"
                alt=""></span>
          </h3>
          <?php if (!empty($block['text'])) : ?>
          <div class="contents">
            <?php echo wp_kses_post($block['text']); ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- 公開中の求人情報セクション -->
  <?php
    // ACFで指定された求人情報がある場合はそれを表示、なければ企業名で検索
    $recruit_jobs = array();
    if (!empty($company_recruit['list'])) {
      $recruit_jobs = $company_recruit['list'];
    } else {
      // 企業名で求人を検索（後方互換性）
      $company_name = get_the_title();
      $args = array(
        'post_type' => 'job',
        'posts_per_page' => 4,
        'meta_query' => array(
          array(
            'key' => 'company',
            'value' => $company_name,
            'compare' => 'LIKE'
          )
        )
      );
      $jobs_query = new WP_Query($args);
      if ($jobs_query->have_posts()) {
        while ($jobs_query->have_posts()) {
          $jobs_query->the_post();
          $recruit_jobs[] = get_post();
        }
        wp_reset_postdata();
      }
    }

    if (!empty($recruit_jobs)) : ?>
  <section class="featured_recruit">
    <div class="featured_recruit__container">
      <h2 class="title_section h55">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_recruit_sp.svg"
          class="sp-only" alt="公開中の求人情報">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_recruit.svg"
          class="pc-only" alt="公開中の求人情報">
        <span class="caption">
          <?php
              if (!empty($company_recruit['text'])) {
                echo esc_html($company_recruit['text']);
              } else {
                echo esc_html(get_the_title()) . 'の求人情報';
              }
              ?>
        </span>
      </h2>
      <div class="featured_recruit__list">
        <?php
            $count = 0;
            foreach ($recruit_jobs as $job) :
              if ($count >= 2) break; // 最大2件まで表示
              $count++;

              // 求人の詳細情報を取得
              $job_type = get_field('job_type', $job->ID);
              $employment_type = get_field('employment_type', $job->ID);
              $work_location = get_field('work_location', $job->ID);
              $min_salary = get_field('min_salary', $job->ID);
              $max_salary = get_field('max_salary', $job->ID);
              $working_hours = get_field('working_hours', $job->ID);
            ?>
        <div class="featured_recruit__item">
          <div class="featured_recruit__contents">
            <?php if ($employment_type) : ?>
            <p class="caption"><span class="label"><?php echo esc_html($employment_type); ?></span></p>
            <?php endif; ?>

            <h3 class="title"><?php echo esc_html($job->post_title); ?></h3>
            <hr>
            <ul class="list">
              <?php if ($work_location) : ?>
              <li class="item">
                <span class="icon"><img
                    src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_icon_map.svg"
                    alt=""></span>
                <p class="text"><?php echo esc_html($work_location); ?></p>
              </li>
              <?php endif; ?>

              <?php if ($min_salary || $max_salary) : ?>
              <li class="item">
                <span class="icon"><img
                    src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_icon_yen.svg"
                    alt=""></span>
                <p class="text">
                  <?php
                          if ($min_salary && $max_salary) {
                            echo number_format($min_salary / 10000) . '万円〜' . number_format($max_salary / 10000) . '万円';
                          } elseif ($min_salary) {
                            echo number_format($min_salary / 10000) . '万円〜';
                          } elseif ($max_salary) {
                            echo '〜' . number_format($max_salary / 10000) . '万円';
                          }
                          ?>
                </p>
              </li>
              <?php endif; ?>

              <?php if ($working_hours) : ?>
              <li class="item">
                <span class="icon"><img
                    src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_icon_time.svg"
                    alt=""></span>
                <p class="text"><?php echo esc_html($working_hours); ?></p>
              </li>
              <?php endif; ?>
            </ul>
            <div class="contents"></div>
            <p class="link">
              <a href="<?php echo get_permalink($job->ID); ?>">
                <span class="label hover-underline">READ MORE</span>
                <span class="icon"><i class="fa-solid fa-arrow-right"></i></span>
              </a>
            </p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="button_section">
        <a class="button_more white" href="<?php echo home_url('/search/'); ?>">
          <span class="label">もっと見る</span>
          <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
        </a>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- 会社概要セクション -->
  <section class="featured_outline">
    <div class="featured_outline__container">
      <h3 class="featured_outline__title">会社概要</h3>
      <div class="featured_outline__main">
        <div class="featured_outline__list">
          <?php
            // 会社概要項目を表示（item01〜item10）
            if ($company_outline) {
              for ($i = 1; $i <= 10; $i++) {
                $item_key = 'item' . str_pad($i, 2, '0', STR_PAD_LEFT);
                $item = $company_outline[$item_key] ?? null;

                if ($item && !empty($item['subject']) && !empty($item['text'])) : ?>
          <dl class="featured_outline__item">
            <dt>
              <span class="label"><?php echo esc_html($item['subject']); ?></span>
              <span class="arrow"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/featured_arrow.svg"
                  alt=""></span>
            </dt>
            <dd>
              <div class="contents">
                <?php
                // タグの不備による崩れを防ぐため、wp_kses_postで安全なHTMLのみ許可
                // その後wpautopで改行を適切に処理
                echo wpautop(wp_kses_post($block['text']));
                ?>
              </div>
            </dd>
          </dl>
          <hr>
          <?php endif;
              }
            }
            ?>
        </div>
      </div>
      <div class="button_section">
        <a class="button_more" href="<?php echo home_url('/#register'); ?>">
          <span class="label">転職相談・派遣登録をする</span>
          <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
        </a>
      </div>
    </div>
  </section>

  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>