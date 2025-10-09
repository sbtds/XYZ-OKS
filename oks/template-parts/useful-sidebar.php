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
      // 転職マニュアル関連の記事を取得
      $manual_posts = get_posts(array(
        'numberposts' => 6,
        'category_name' => 'manual',
        'post__not_in' => array(get_the_ID()), // 現在の記事を除外
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
        <a href="<?php echo home_url('/'); ?>">
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
      // 人気記事を取得
      $popular_posts = get_posts(array(
        'numberposts' => 6,
        'category_name' => 'popular',
        'post__not_in' => array(get_the_ID()),
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
        <a href="<?php echo home_url('/'); ?>">
          <span class="thb hover-image"><img src="https://placehold.co/55x50" alt="記事タイトル"></span>
          <span class="label">記事タイトル</span>
          <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
        </a>
      </li>
      <?php endif;
      wp_reset_postdata(); ?>
    </ul>
  </div>

  <?php if (is_single()) : ?>
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
            <img src="https://placehold.co/260x140" alt="<?php echo esc_attr($feature_post->post_title); ?>">
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
            <img src="https://placehold.co/260x140" alt="特集記事">
          </span>
        </a>
      </li>
      <?php endif;
      wp_reset_postdata(); ?>
    </ul>
  </div>
  <?php endif; ?>
</aside>