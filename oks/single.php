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
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title02_sp.svg" class="sp-only" alt="お役立ち情報">
      <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title02.svg" class="pc-only" alt="お役立ち情報">
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
              <li><a href="#<?php echo esc_attr($anchor_id); ?>"><span class="label"><?php echo esc_html($clean_text); ?></span></a></li>
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
            'numberposts' => 3,
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
            'numberposts' => 3,
            'orderby' => 'comment_count',
            'order' => 'DESC',
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

      <div class="useful_side__bottom">
        <h3 class="title">特集から探す</h3>
        <ul class="list">
          <?php
          // 特集記事を取得（カスタムフィールドで管理する場合）
          $featured_banners = array(
            array('title' => 'バナーテキスト', 'image' => 'https://placehold.co/260x140', 'link' => '#'),
            array('title' => 'バナーテキストバナーテキストバナーテキスト', 'image' => 'https://placehold.co/260x140', 'link' => '#'),
            array('title' => 'バナーテキスト', 'image' => 'https://placehold.co/260x140', 'link' => '#'),
            array('title' => 'バナーテキストバナーテキストバナーテキスト', 'image' => 'https://placehold.co/260x140', 'link' => '#'),
          );
          
          foreach ($featured_banners as $banner) : ?>
          <li class="item">
            <h4 class="subject"><?php echo esc_html($banner['title']); ?></h4>
            <a class="banner" href="<?php echo esc_url($banner['link']); ?>">
              <span class="image hover-image">
                <img src="<?php echo esc_url($banner['image']); ?>" alt="<?php echo esc_attr($banner['title']); ?>">
              </span>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </aside>
  </section>
  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>