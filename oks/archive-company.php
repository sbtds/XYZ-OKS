<?php
/**
 * 注目企業アーカイブテンプレート
 *
 * @package OKS
 */

get_header(); ?>

<main class="page_main">
  <div class="page_title bg-primary">
    <h1 class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/featured_title_sp.svg" class="sp-only" alt="注目企業">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/featured_title.svg" class="pc-only" alt="注目企業">
    </h1>
  </div>
  
  <section class="featured_index">
    <div class="featured_index__container">
      <?php
      // company_areaタクソノミーのタームを取得
      $terms = get_terms(array(
        'taxonomy' => 'company_area',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC'
      ));
      
      if (!empty($terms) && !is_wp_error($terms)) :
        foreach ($terms as $term) :
          // 各エリアの企業を取得
          $args = array(
            'post_type' => 'company',
            'posts_per_page' => -1,
            'tax_query' => array(
              array(
                'taxonomy' => 'company_area',
                'field' => 'term_id',
                'terms' => $term->term_id
              )
            )
          );
          $companies_query = new WP_Query($args);
          
          if ($companies_query->have_posts()) : ?>
            <div class="featured_index__area">
              <h2 class="featured_index__title">
                <span class="arrow"><img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/section_title_arrow.svg" alt=""></span>
                <a href="<?php echo get_term_link($term); ?>" class="label"><?php echo esc_html($term->name); ?></a>
                <span class="arrow"><img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/section_title_arrow.svg" alt=""></span>
              </h2>
              <div class="featured_index__list">
                <?php 
                $count = 0;
                while ($companies_query->have_posts()) : 
                  $companies_query->the_post();
                  $company_top = get_field('company_top');
                  $count++;
                  
                  // 一覧ページでは最大4件まで表示
                  if ($count > 4) break;
                ?>
                  <a class="featured_index__item" href="<?php the_permalink(); ?>">
                    <div class="featured_index__inner">
                      <?php if (has_post_thumbnail()) : ?>
                        <p class="featured_index__thb hover-image">
                          <?php the_post_thumbnail('large'); ?>
                        </p>
                      <?php else : ?>
                        <p class="featured_index__thb hover-image">
                          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/flow_img01.jpg" alt="">
                        </p>
                      <?php endif; ?>
                      <div class="featured_index__main">
                        <p class="name"><?php the_title(); ?></p>
                        <?php if (!empty($company_top['text'])) : ?>
                          <p class="desc"><?php echo mb_substr(strip_tags($company_top['text']), 0, 50); ?>...</p>
                        <?php endif; ?>
                        <hr>
                        <p class="date">更新日：<?php echo get_the_modified_date('Y年m月d日'); ?></p>
                      </div>
                    </div>
                    <div class="featured_index__logo">
                      <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('thumbnail'); ?>
                      <?php else : ?>
                        <img src="https://placehold.co/170x110" alt="<?php the_title_attribute(); ?>">
                      <?php endif; ?>
                    </div>
                  </a>
                <?php endwhile; ?>
              </div>
              
              <div class="button_section">
                <a class="button_more secondary" href="<?php echo get_term_link($term); ?>">
                  <span class="label"><?php echo esc_html($term->name); ?>の注目企業を見る</span>
                  <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                </a>
              </div>
            </div>
          <?php endif; 
          wp_reset_postdata();
        endforeach;
      else : ?>
        <div class="featured_index__area">
          <p>現在、注目企業はありません。</p>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>