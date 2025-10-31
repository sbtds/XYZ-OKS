<?php
/**
 * 注目企業エリア別アーカイブテンプレート
 *
 * @package OKS
 */

get_header();

$term = get_queried_object(); // 現在のタームを取得
?>

<main class="page_main">
  <div class="page_title bg-primary">
    <h1 class="title_section h55">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/featured_title_sp.svg" class="sp-only" alt="注目企業">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/featured_title.svg" class="pc-only" alt="注目企業">
    </h1>
  </div>

  <section class="featured_index">
    <div class="featured_index__container">
      <div class="featured_index__area">
        <h2 class="featured_index__title">
          <span class="arrow"><img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/section_title_arrow.svg" alt=""></span>
          <span class="label"><?php echo esc_html($term->name); ?></span>
          <span class="arrow"><img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/section_title_arrow.svg" alt=""></span>
        </h2>

        <?php
        // company投稿タイプのみに制限するためのカスタムクエリ
        $args = array(
          'post_type' => 'company',
          'tax_query' => array(
            array(
              'taxonomy' => 'company_area',
              'field'    => 'slug',
              'terms'    => $term->slug,
            ),
          ),
          'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        );
        $company_query = new WP_Query($args);

        if ($company_query->have_posts()) : ?>
          <div class="featured_index__list">
            <?php while ($company_query->have_posts()) : $company_query->the_post();
              $company_top = get_field('company_top');
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
                    <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_thumb.jpg?size=170x110" alt="<?php the_title_attribute(); ?>">
                  <?php endif; ?>
                </div>
              </a>
            <?php endwhile; ?>
          </div>

          <!-- ページネーション -->
          <div class="pagination">
            <?php
            // カスタムクエリ用のページネーション
            $pagination = paginate_links(array(
              'total' => $company_query->max_num_pages,
              'current' => max(1, get_query_var('paged')),
              'mid_size' => 2,
              'prev_text' => '<i class="fa-solid fa-chevron-left"></i>',
              'next_text' => '<i class="fa-solid fa-chevron-right"></i>',
              'type' => 'list',
            ));
            echo $pagination;
            ?>
          </div>
        <?php else : ?>
          <p>現在、<?php echo esc_html($term->name); ?>の注目企業はありません。</p>
        <?php endif;

        // クエリをリセット
        wp_reset_postdata();
        ?>

        <div class="button_section">
          <a class="button_more" href="<?php echo get_post_type_archive_link('company'); ?>">
            <span class="label">注目企業一覧へ戻る</span>
            <span class="icon"><i class="fa-solid fa-chevron-left"></i></span>
          </a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>