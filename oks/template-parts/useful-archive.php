<main class="index_main">
  <?php
      // $page_title = 'お役立ち情報';
      // if (is_tag()) {
      //   $page_title = 'タグ: ' . single_tag_title('', false);
      // } elseif (is_category()) {
      //   $page_title = 'カテゴリー: ' . single_cat_title('', false);
      // } elseif (is_date()) {
      //   $page_title = get_the_date('Y年m月');
      // } elseif (is_author()) {
      //   $page_title = '著者: ' . get_the_author();
      // }
      // echo $page_title;
      ?>

  <div class="page_title bg-primary">
    <h1 class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title02_sp.svg"
        class="sp-only" alt="お役立ち情報" />
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title02.svg" class="pc-only"
        alt="お役立ち情報" />
    </h1>
  </div>

  <?php if (is_page('useful')) : ?>
  <section class="index_hero__slide mt">
    <div class="index_hero__slide_list carousel swiper" data-carousel-per-view="3" data-carousel-between="0"
      data-carousel-delay="5000">
      <div class="swiper-wrapper">
        <?php
        $hero_query = new WP_Query(array(
          'post_type' => 'post',
          'category_name' => 'feature',
          'posts_per_page' => -1,
          'post_status' => 'publish'
        ));

        if ($hero_query->have_posts()) :
          while ($hero_query->have_posts()) : $hero_query->the_post();
        ?>
        <div class="index_hero__slide_item swiper-slide">
          <a href="<?php the_permalink(); ?>">
            <p class="hover-image">
              <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('large', array('alt' => get_the_title())); ?>
              <?php else : ?>
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_img01.jpg"
                alt="<?php the_title(); ?>" />
              <?php endif; ?>
            </p>
          </a>
        </div>
        <?php
          endwhile;
          wp_reset_postdata();
        endif;
        ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="useful_contents">
    <div class="useful_main">
      <div class="index_useful__list col02">
        <?php
        $query_args = array(
          'post_type' => 'post',
          'posts_per_page' => get_option('posts_per_page'),
          'post_status' => 'publish'
        );

        if (is_tag()) {
          $query_args['tag'] = get_query_var('tag');
        } elseif (is_category()) {
          $query_args['category_name'] = get_query_var('category_name');
        } elseif (is_date()) {
          $query_args['year'] = get_query_var('year');
          $query_args['monthnum'] = get_query_var('monthnum');
          $query_args['day'] = get_query_var('day');
        } elseif (is_author()) {
          $query_args['author'] = get_query_var('author');
        }

        if (get_query_var('paged')) {
          $query_args['paged'] = get_query_var('paged');
        }

        $useful_query = new WP_Query($query_args);

        if ($useful_query->have_posts()) :
          while ($useful_query->have_posts()) : $useful_query->the_post();
        ?>
        <div class="index_useful__item">
          <a class="" href="<?php the_permalink(); ?>">
            <p class="index_useful__thb hover-image">
              <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
              <?php else : ?>
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_img01.jpg"
                alt="<?php the_title(); ?>" />
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
            <hr />
          </div>
          <ul class="index_useful__tags">
            <?php
              $tags = get_the_tags();
              if ($tags) :
                foreach ($tags as $tag) :
              ?>
            <li>
              <a href="<?php echo get_tag_link($tag->term_id); ?>"><span
                  class="hover-underline">#<?php echo $tag->name; ?></span></a>
            </li>
            <?php
                endforeach;
              endif;
              ?>
          </ul>
        </div>
        <?php
          endwhile;
          wp_reset_postdata();
        endif;
        ?>
      </div>
    </div>
    <?php get_template_part('template-parts/useful-sidebar'); ?>
  </section>
  <?php if (is_page('useful')) : ?>
  <section class="useful_bottom">
    <h2 class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/feature_title_sp.svg"
        class="sp-only" alt="特集記事" />
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/feature_title.svg" class="pc-only"
        alt="特集記事" />
    </h2>
    <div class="useful_bottom__contents">
      <div class="useful_bottom__main">
        <?php
        $featured_query = new WP_Query(array(
          'post_type' => 'post',
          'category_name' => 'feature',
          'posts_per_page' => 1,
          'post_status' => 'publish'
        ));

        if ($featured_query->have_posts()) :
          while ($featured_query->have_posts()) : $featured_query->the_post();
        ?>
        <a class="index_useful__item" href="<?php the_permalink(); ?>">
          <p class="index_useful__thb hover-image">
            <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large', array('alt' => get_the_title())); ?>
            <?php else : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_img01.jpg"
              alt="<?php the_title(); ?>" />
            <?php endif; ?>
          </p>
          <div class="index_useful__main">
            <div class="index_useful__contents">
              <h3 class="index_useful__title"><?php the_title(); ?></h3>
              <p class="index_useful__arrow">
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </p>
            </div>
            <hr />
            <div class="index_useful__text">
              <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
            </div>
          </div>
        </a>
        <?php
          endwhile;
          wp_reset_postdata();
        endif;
        ?>
      </div>
      <div class="useful_bottom__side">
        <ul class="useful_bottom__list">
          <?php
          $side_query = new WP_Query(array(
            'post_type' => 'post',
            'category_name' => 'feature',
            'posts_per_page' => 3,
            'offset' => 1,
            'post_status' => 'publish'
          ));

          if ($side_query->have_posts()) :
            while ($side_query->have_posts()) : $side_query->the_post();
          ?>
          <li class="useful_bottom__item">
            <a href="<?php the_permalink(); ?>">
              <span class="thb hover-image">
                <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('thumbnail', array('alt' => get_the_title())); ?>
                <?php else : ?>
                <img class="" src="https://placehold.co/140x100" alt="<?php the_title(); ?>" />
                <?php endif; ?>
              </span>
              <span class="label"><?php the_title(); ?></span>
              <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
          </li>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>
        </ul>
      </div>
    </div>
    <div class="button_section">
      <a class="button_more" href="<?php echo home_url('/category/feature/'); ?>">
        <span class="label">もっと見る</span>
        <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
      </a>
    </div>
  </section>
  <?php endif; ?>
</main>