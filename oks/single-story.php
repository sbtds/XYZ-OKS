<?php
/**
 * コンサルタント詳細テンプレート
 *
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

  <?php if (have_posts()) : while (have_posts()) : the_post();
    // ACFフィールドを取得
    $story_top = get_field('story_top');
    // $story_message = get_field('story_message');

    // story_block01〜06を取得（タイトルがある場合のみ）
    $story_blocks = array();
    for ($i = 1; $i <= 6; $i++) {
      $block = get_field('story_block' . str_pad($i, 2, '0', STR_PAD_LEFT));
      if ($block && !empty($block['title'])) {
        $story_blocks[] = $block;
      }
    }
  ?>

  <div class="consultant_detail">
    <!-- コンサルタント基本情報 -->
    <section class="consultant_top">
      <?php if (has_post_thumbnail()) : ?>
      <p class="consultant_top__thb round">
        <?php the_post_thumbnail('large'); ?>
      </p>
      <?php endif; ?>

      <div class="consultant_top__main">
        <?php if (!empty($story_top['catch'])) : ?>
        <p class="catch">
          <span><?php echo wp_kses_post($story_top['catch']); ?></span>
        </p>
        <?php endif; ?>

        <?php if (!empty($story_top['name'])) : ?>
        <p class="ja"><?php echo esc_html($story_top['name']); ?></p>
        <?php else : ?>
        <p class="ja"><?php the_title(); ?></p>
        <?php endif; ?>

        <?php
          // タクソノミーからエリアを取得（spanタグで表示、リンクなし）
          $areas = get_the_terms(get_the_ID(), 'company_area');
          if ($areas && !is_wp_error($areas)) : ?>
        <p class="area">
          <?php foreach ($areas as $area) : ?>
          <span class="label"><?php echo esc_html($area->name); ?></span>
          <?php endforeach; ?>
        </p>
        <?php endif; ?>

        <hr>

        <?php if (!empty($story_top['text'])) : ?>
        <p class="desc"><?php echo nl2br(esc_html($story_top['text'])); ?></p>
        <?php endif; ?>
      </div>
    </section>

    <section class="consultant_main">
      <!-- コンサルタントブロックセクション -->
      <?php if (!empty($story_blocks)) : ?>
      <div class="consultant_main__contents">
        <?php foreach ($story_blocks as $block) : ?>
        <div class="consultant_main__item">
          <?php
          $heading_tag = !empty($block['title_size']) ? $block['title_size'] : 'h3';
          ?>
          <<?php echo $heading_tag; ?> class="title">
            <span class="label"><?php echo esc_html($block['title']); ?></span>
            <span class="arrow"><img
                src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/page_title_arrow.svg"
                alt=""></span>
          </<?php echo $heading_tag; ?>>
          <?php if (!empty($block['text'])) : ?>
          <div class="contents">
            <?php
            // タグの不備による崩れを防ぐため、wp_kses_postで安全なHTMLのみ許可
            // その後wpautopで改行を適切に処理
            echo wpautop(wp_kses_post($block['text']));
            ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </section>
  </div>

  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>