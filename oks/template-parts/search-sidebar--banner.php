<?php
/**
 * Template Part: Search Sidebar Banner
 *
 * Banner section for search sidebar - shows on search page, single job page, and single post page
 *
 * @package OKS
 */

// Check if we should display banners on current page
// $show_banners = is_single() || is_page('search') || is_singular('job');
$show_banners = is_single() || is_singular('job');

if ($show_banners) : ?>
<div class="useful_side__bottom">
  <h3 class="title">特集から探す</h3>
  <ul class="list">
    <?php
    // 特集記事を取得（カテゴリースラッグ 'feature' の投稿）
    $feature_posts = get_posts(array(
      'numberposts' => 6,
      'category_name' => 'feature',
      'post__not_in' => array(get_the_ID()),
      'post_status' => 'publish'
    ));

    if ($feature_posts) :
      foreach ($feature_posts as $feature_post) : ?>
    <li class="item">
      <h4 class="subject"><?php echo esc_html($feature_post->post_title); ?></h4>
      <a class="banner" href="<?php echo get_permalink($feature_post->ID); ?>">
        <span class="image hover-image">
          <?php if (has_post_thumbnail($feature_post->ID)) : ?>
          <?php echo get_the_post_thumbnail($feature_post->ID, 'medium'); ?>
          <?php else : ?>
          <picture>
            <source srcset="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_thumb_md.webp"
              media="(max-width: 1119px)" type="image/webp" />
            <source srcset="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_thumb.webp"
              type="image/webp" />
            <img alt=""
              src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_thumb.jpg?size=260x140"
              loading="lazy" />
          </picture>post_title); ?>">
          <?php endif; ?>
        </span>
      </a>
    </li>
    <?php endforeach;
    else : ?>
    <li class="item">
      <h4 class="subject">特集記事</h4>
      <a class="banner" href="<?php echo home_url('/'); ?>">
        <span class="image hover-image">
          <picture>
            <source srcset="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_thumb_md.webp"
              media="(max-width: 1119px)" type="image/webp" />
            <source srcset="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_thumb.webp"
              type="image/webp" />
            <img alt="特集記事"
              src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/page/common_thumb.jpg?size=260x140"
              loading="lazy" />
          </picture>
        </span>
      </a>
    </li>
    <?php endif;
    wp_reset_postdata(); ?>
  </ul>
</div>
<?php endif; ?>