<?php
/**
 * 固定ページテンプレート
 *
 * @package OKS
 */

get_header(); ?>

<main class="page_main">
  <div class="page_title bg-primary">
    <h1 class="title_section h55">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <span class="title"><?php the_title(); ?></span>
      <?php endwhile; endif; rewind_posts(); ?>
    </h1>
  </div>

  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <section class="page_contents">
    <div class="page_detail">
      <div class="page_entry__contents">


        <div class="page_entry__title">
          <?php if (has_post_thumbnail()) : ?>
          <p class="thb">
            <?php the_post_thumbnail('large'); ?>
          </p>
          <?php endif; ?>
        </div>

        <div class="page_detail__body">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
  </section>
  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>