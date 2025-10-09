<?php
/**
 * お役立ち情報詳細ページ（投稿詳細）
 *
 * @package OKS
 */

get_header(); ?>

<main class="page_main">
  <div class="page_title bg-primary">
    <p class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title02_sp.svg"
        class="sp-only" alt="お役立ち情報">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title02.svg" class="pc-only"
        alt="お役立ち情報">
    </p>
  </div>

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <section class="useful_contents">
    <div class="useful_entry">
      <div class="useful_entry__contents">
        <div class="useful_entry__title">
          <p class="date">更新日：<?php echo get_the_modified_date('Y年m月d日'); ?></p>
          <h1 class="title"><?php the_title(); ?></h1>
          <?php if (has_post_thumbnail()) : ?>
          <p class="thb">
            <?php the_post_thumbnail('large'); ?>
          </p>
          <?php endif; ?>

          <!-- 記事まとめ（要約）セクション - コンテンツ内のh2タグを抽出 -->
          <?php
          // コンテンツを取得
          $content = get_the_content();

          // h2タグを抽出
          preg_match_all('/<h2[^>]*>(.*?)<\/h2>/i', $content, $h2_matches);

          if (!empty($h2_matches[1])) : ?>
          <div class="cot">
            <p class="subject">記事まとめ(要約)</p>
            <ul class="list">
              <?php foreach ($h2_matches[1] as $index => $h2_text) :
                // HTMLタグを除去してテキストのみ取得
                $clean_text = wp_strip_all_tags($h2_text);
                if (!empty($clean_text)) :
                  // アンカー用のIDを生成
                  $anchor_id = 'heading-' . ($index + 1);
                ?>
              <li><a href="#<?php echo esc_attr($anchor_id); ?>"><span
                    class="label"><?php echo esc_html($clean_text); ?></span></a></li>
              <?php endif; endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>
        </div>

        <div class="useful_entry__body">
          <?php the_content(); ?>
        </div>
      </div>

      <div class="button_section">
        <a class="button_more" href="<?php echo home_url('/search/'); ?>">
          <span class="label">サービス紹介を見る</span>
          <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
        </a>
      </div>
    </div>

    <?php get_template_part('template-parts/useful-sidebar'); ?>
  </section>
  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>