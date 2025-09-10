<?php
/**
 * お役立ち情報一覧ページ（投稿アーカイブ）
 *
 * @package OKS
 */

get_header(); ?>

<main class="index_main">
  <div class="page_title bg-primary">
    <h1 class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title02_sp.svg" class="sp-only" alt="お役立ち情報">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title02.svg" class="pc-only" alt="お役立ち情報">
    </h1>
  </div>
  
  <!-- ヒーロースライダー部分 -->
  <section class="index_hero__slide mt">
    <div class="index_hero__slide_list carousel swiper" data-carousel-per-view="3" data-carousel-between="0" data-carousel-delay="5000">
      <div class="swiper-wrapper">
        <?php
        // 最新の投稿を5件取得してスライダーに表示
        $slider_posts = get_posts(array(
          'numberposts' => 5,
          'post_status' => 'publish'
        ));
        
        foreach ($slider_posts as $slider_post) : ?>
        <div class="index_hero__slide_item swiper-slide">
          <a href="<?php echo get_permalink($slider_post->ID); ?>">
            <p class="hover-image">
              <?php if (has_post_thumbnail($slider_post->ID)) : ?>
                <?php echo get_the_post_thumbnail($slider_post->ID, 'large'); ?>
              <?php else : ?>
                <img src="https://placehold.co/1100x840" alt="<?php echo esc_attr($slider_post->post_title); ?>">
              <?php endif; ?>
            </p>
          </a>
        </div>
        <?php endforeach;
        wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

  <section class="useful_contents">
    <div class="useful_main">
      <?php if (have_posts()) : ?>
      <div class="index_useful__list col02">
        <?php while (have_posts()) : the_post(); ?>
        <div class="index_useful__item">
          <a href="<?php the_permalink(); ?>">
            <p class="index_useful__thb hover-image">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium'); ?>
              <?php else : ?>
                <img src="https://placehold.co/400x300" alt="<?php the_title_attribute(); ?>">
              <?php endif; ?>
            </p>
          </a>
          <div class="index_useful__main">
            <a class="index_useful__contents" href="<?php the_permalink(); ?>">
              <h3 class="index_useful__subject"><?php the_title(); ?></h3>
              <p class="index_useful__arrow">
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </p>
            </a>
            <hr>
          </div>
          <?php 
          $tags = get_the_tags();
          if ($tags) : ?>
          <ul class="index_useful__tags">
            <?php foreach ($tags as $tag) : ?>
            <li>
              <a href="<?php echo get_tag_link($tag->term_id); ?>"><span class="hover-underline">#<?php echo esc_html($tag->name); ?></span></a>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
        </div>
        <?php endwhile; ?>
      </div>
      
      <!-- ページネーション -->
      <?php
      $pagination = paginate_links(array(
        'type' => 'array',
        'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
        'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
      ));
      
      if ($pagination) : ?>
      <div class="pagination">
        <ul class="pagination-list">
          <?php foreach ($pagination as $page) : ?>
          <li class="pagination-item"><?php echo $page; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
      
      <?php else : ?>
      <div class="no-results">
        <p>お役立ち情報が見つかりませんでした。</p>
      </div>
      <?php endif; ?>
    </div>
    
    <aside class="useful_side">
      <div class="useful_side__button">
        <div class="button">
          <a class="button_side" href="<?php echo home_url('/#register'); ?>">
            <span class="label">会員登録</span>
          </a>
          <p class="text">
            ログインは<a href="#"><span class="link-underline">こちら</span></a>
          </p>
        </div>
        <div class="button">
          <a class="button_side secondary" href="<?php echo home_url('/search/'); ?>">
            <span class="label">求人はこちら</span>
          </a>
        </div>
      </div>

      <div class="useful_side__menu">
        <h3 class="title">必読！転職完全マニュアル</h3>
        <ul class="list">
          <?php
          // 特定のカテゴリーの投稿を取得（例：「転職マニュアル」カテゴリー）
          $manual_posts = get_posts(array(
            'numberposts' => 3,
            'category_name' => 'manual', // カテゴリースラッグを設定
            'post_status' => 'publish'
          ));
          
          if ($manual_posts) :
            foreach ($manual_posts as $manual_post) : ?>
            <li class="item">
              <a href="<?php echo get_permalink($manual_post->ID); ?>">
                <span class="thb hover-image">
                  <?php if (has_post_thumbnail($manual_post->ID)) : ?>
                    <?php echo get_the_post_thumbnail($manual_post->ID, 'thumbnail'); ?>
                  <?php else : ?>
                    <img src="https://placehold.co/55x50" alt="<?php echo esc_attr($manual_post->post_title); ?>">
                  <?php endif; ?>
                </span>
                <span class="label"><?php echo esc_html($manual_post->post_title); ?></span>
                <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
              </a>
            </li>
            <?php endforeach;
          else : ?>
          <li class="item">
            <a href="#">
              <span class="thb hover-image"><img src="https://placehold.co/55x50" alt="履歴書の書き方"></span>
              <span class="label">履歴書の書き方</span>
              <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
          </li>
          <?php endif;
          wp_reset_postdata(); ?>
        </ul>
      </div>

      <div class="useful_side__menu">
        <h3 class="title">人気記事</h3>
        <ul class="list">
          <?php
          // 人気記事（コメント数やPV数で並べ替え - ここでは最新3件）
          $popular_posts = get_posts(array(
            'numberposts' => 3,
            'orderby' => 'comment_count',
            'order' => 'DESC',
            'post_status' => 'publish'
          ));
          
          if ($popular_posts) :
            foreach ($popular_posts as $popular_post) : ?>
            <li class="item">
              <a href="<?php echo get_permalink($popular_post->ID); ?>">
                <span class="thb hover-image">
                  <?php if (has_post_thumbnail($popular_post->ID)) : ?>
                    <?php echo get_the_post_thumbnail($popular_post->ID, 'thumbnail'); ?>
                  <?php else : ?>
                    <img src="https://placehold.co/55x50" alt="<?php echo esc_attr($popular_post->post_title); ?>">
                  <?php endif; ?>
                </span>
                <span class="label"><?php echo esc_html($popular_post->post_title); ?></span>
                <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
              </a>
            </li>
            <?php endforeach;
          else : ?>
          <li class="item">
            <a href="#">
              <span class="thb hover-image"><img src="https://placehold.co/55x50" alt="記事タイトル"></span>
              <span class="label">記事タイトル</span>
              <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
          </li>
          <?php endif;
          wp_reset_postdata(); ?>
        </ul>
      </div>
    </aside>
  </section>

  <section class="useful_bottom">
    <h2 class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/feature_title_sp.svg" class="sp-only" alt="特集記事">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/feature_title.svg" class="pc-only" alt="特集記事">
    </h2>
    <div class="useful_bottom__contents">
      <?php
      // 特集記事（最新1件をメインに表示）
      $featured_post = get_posts(array(
        'numberposts' => 1,
        'meta_key' => 'featured',
        'meta_value' => '1',
        'post_status' => 'publish'
      ));
      
      if (empty($featured_post)) {
        $featured_post = get_posts(array('numberposts' => 1, 'post_status' => 'publish'));
      }
      
      if ($featured_post) :
        $post = $featured_post[0]; setup_postdata($post); ?>
      <div class="useful_bottom__main">
        <a class="index_useful__item" href="<?php the_permalink(); ?>">
          <p class="index_useful__thb hover-image">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('large'); ?>
            <?php else : ?>
              <img src="https://placehold.co/600x400" alt="<?php the_title_attribute(); ?>">
            <?php endif; ?>
          </p>
          <div class="index_useful__main">
            <div class="index_useful__contents">
              <h3 class="index_useful__title"><?php the_title(); ?></h3>
              <p class="index_useful__arrow">
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </p>
            </div>
            <hr>
            <div class="index_useful__text">
              <p><?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?></p>
            </div>
          </div>
        </a>
      </div>
      <?php wp_reset_postdata(); endif; ?>
      
      <div class="useful_bottom__side">
        <ul class="useful_bottom__list">
          <?php
          // 特集記事サイド（2-4番目の記事）
          $featured_side_posts = get_posts(array(
            'numberposts' => 3,
            'offset' => 1,
            'post_status' => 'publish'
          ));
          
          foreach ($featured_side_posts as $side_post) : ?>
          <li class="useful_bottom__item">
            <a href="<?php echo get_permalink($side_post->ID); ?>">
              <span class="thb hover-image">
                <?php if (has_post_thumbnail($side_post->ID)) : ?>
                  <?php echo get_the_post_thumbnail($side_post->ID, 'thumbnail'); ?>
                <?php else : ?>
                  <img src="https://placehold.co/140x100" alt="<?php echo esc_attr($side_post->post_title); ?>">
                <?php endif; ?>
              </span>
              <span class="label"><?php echo esc_html($side_post->post_title); ?></span>
              <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
          </li>
          <?php endforeach;
          wp_reset_postdata(); ?>
        </ul>
      </div>
    </div>
    <div class="button_section">
      <a class="button_more" href="<?php echo home_url('/'); ?>">
        <span class="label">もっと見る</span>
        <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
      </a>
    </div>
  </section>
</main>

<?php get_footer(); ?>