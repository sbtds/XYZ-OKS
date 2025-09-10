<?php
/**
 * FAQ一覧テンプレート
 *
 * @package OKS
 */

get_header(); ?>

<main class="page_main">
  <div class="page_title bg-primary">
    <h1 class="title_section">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/faq_title_sp.svg" class="sp-only" alt="Q&amp;A">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/faq_title.svg" class="pc-only" alt="Q&amp;A">
    </h1>
  </div>
  
  <section class="index_faq faq_index">
    <div class="index_faq__container container-base">
      <?php if (have_posts()) : ?>
      <div class="index_faq__list">
        <?php while (have_posts()) : the_post(); ?>
        <label class="index_faq__item">
          <input type="checkbox" />
          <div class="index_faq__item_head">
            <span class="icon">Q</span>
            <p class="label"><?php the_title(); ?></p>
            <span class="arrow">
              <span class="plus"><i class="fa-solid fa-plus"></i></span>
              <span class="minus"><i class="fa-solid fa-minus"></i></span>
            </span>
          </div>
          <div class="index_faq__item_body">
            <span class="icon">A</span>
            <div class="contents">
              <?php 
              // グーテンベルグエディターの内容をそのまま表示
              the_content(); 
              ?>
            </div>
          </div>
        </label>
        <?php endwhile; ?>
      </div>
      <?php else : ?>
      <div class="no-results">
        <p>FAQが見つかりませんでした。</p>
      </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php get_footer(); ?>