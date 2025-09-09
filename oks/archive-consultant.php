<?php
/**
 * コンサルタント一覧テンプレート
 *
 * @package OKS
 */

get_header(); ?>

<main class="page_main">
  <div class="page_title bg-primary">
    <h1 class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/consultant_title_sp.svg"
        class="sp-only" alt="コンサルタント紹介">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/consultant_title.svg"
        class="pc-only" alt="コンサルタント紹介">
    </h1>
  </div>

  <section class="index_consultant consultant_list">
    <div class="index_consultant__container">
      <?php if (have_posts()) : ?>
      <div class="index_consultant__list">
        <?php while (have_posts()) : the_post();
          // ACFフィールドを取得
          $consultant_top = get_field('consultant_top');

          // タクソノミーからエリアを取得
          $areas = get_the_terms(get_the_ID(), 'company_area');
        ?>
        <div class="index_consultant__item">
          <div class="index_consultant__inner">
            <p class="index_consultant__thb">
              <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('medium'); ?>
              <?php else : ?>
              <img src="https://placehold.co/320x320" alt="<?php echo esc_attr(get_the_title()); ?>">
              <?php endif; ?>
            </p>
            <div class="index_consultant__main">
              <?php if (!empty($consultant_top['eng'])) : ?>
              <p class="en"><?php echo esc_html($consultant_top['eng']); ?></p>
              <?php endif; ?>

              <?php if (!empty($consultant_top['name'])) : ?>
              <p class="ja"><?php echo esc_html($consultant_top['name']); ?></p>
              <?php else : ?>
              <p class="ja"><?php the_title(); ?></p>
              <?php endif; ?>

              <?php if ($areas && !is_wp_error($areas)) : ?>
              <p class="area">
                <?php
              $area_names = array();
              foreach ($areas as $area) {
                $area_names[] = $area->name;
              }
              echo esc_html(implode('、', $area_names));
              ?>
              </p>
              <?php endif; ?>

              <?php if (!empty($consultant_top['text'])) : ?>
              <p class="desc"><?php echo esc_html($consultant_top['text']); ?></p>
              <?php endif; ?>

              <p class="link">
                <a href="<?php the_permalink(); ?>">
                  <span class="label hover-underline">READ MORE</span>
                  <span class="icon"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
              </p>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>

      <?php else : ?>
      <div class="no-results">
        <p>コンサルタントが見つかりませんでした。</p>
      </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>