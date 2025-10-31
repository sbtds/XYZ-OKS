<?php
/**
 * @package OKS
 */

get_header(); ?>

<main class="page_main">
  <div class="page_title bg-primary">
    <h1 class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/story_title_sp.svg" class="sp-only"
        alt="転職者ストーリー">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/story_title.svg" class="pc-only"
        alt="転職者ストーリー">
    </h1>
  </div>

  <section class="index_story">
    <div class="index_story__container">
      <?php
      // カスタム投稿「story」の一覧を取得
      $story_query = new WP_Query(array(
        'post_type' => 'story',
        'posts_per_page' => -1, // 全件表示
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
      ));
      ?>

      <?php if ($story_query->have_posts()) : ?>
      <div class="index_story__list">
        <?php while ($story_query->have_posts()) : $story_query->the_post();
          // ACFフィールドを取得
          $story_top = get_field('story_top');

          // story_block01〜06を取得（タイトルがある場合のみ）
          $story_blocks = array();
          for ($i = 1; $i <= 6; $i++) {
            $block = get_field('story_block' . str_pad($i, 2, '0', STR_PAD_LEFT));
            if ($block && !empty($block['title'])) {
              $story_blocks[] = $block;
            }
          }

          // タクソノミーからエリアを取得
          $areas = get_the_terms(get_the_ID(), 'company_area');
        ?>
        <a class="index_story__item" href="<?php the_permalink(); ?>">
          <div class="index_story__inner">
            <div class="index_story__upper">
              <p class="index_story__thb hover-image">
                <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
                <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/story_img01.jpg"
                  alt="<?php echo esc_attr(get_the_title()); ?>">
                <?php endif; ?>
              </p>
              <?php if (!empty($story_top['catch'])) : ?>
              <p class="index_story__lead"><span><?php echo wp_kses_post($story_top['catch']); ?></span></p>
              <?php endif; ?>
            </div>
            <div class="index_story__contents">
              <div class="index_story__main">
                <?php if (!empty($story_top['eng'])) : ?>
                <p class="en"><?php echo esc_html($story_top['eng']); ?></p>
                <?php endif; ?>

                <?php if (!empty($story_top['name'])) : ?>
                <p class="ja"><?php echo esc_html($story_top['name']); ?></p>
                <?php endif; ?>

                <?php if (!empty($story_top['text'])) : ?>
                <p class="caption"><?php echo esc_html($story_top['text']); ?></p>
                <?php endif; ?>
              </div>
              <p class="index_story__arrow">
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </p>
            </div>
            <hr />
            <div class="index_story__text">
              <?php if (!empty($story_blocks) && isset($story_blocks[0]['title'])) : ?>
              <p><?php echo esc_html($story_blocks[0]['title']); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </a>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
      </div>

      <?php else : ?>
      <div class="no-results">
        <p>転職者ストーリーが見つかりませんでした。</p>
      </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>