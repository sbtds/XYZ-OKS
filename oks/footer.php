      <footer class="common_footer">
        <div class="common_footer__container container-base">
          <div class="common_footer__main">
            <p class="common_footer__id">
              <a href="<?php echo home_url(); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/common_id.png" alt="<?php bloginfo('name'); ?>" class="default">
              </a>
            </p>
            <p class="common_footer__add">〒989-6135<br>宮城県大崎市古川稲葉新堀58-1</p>
            <p class="common_footer__tel">
              <a href="tel:0120-873-908">0120-873-908</a>
              <span class="caption">8:00~18:30受付</span>
            </p>
            <ul class="common_footer__sns">
              <li>
                <a href="http://facebook.com/oks.miyagi.kanagawa" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/common_sns01.png" alt=""></a>
              </li>
              <li>
                <a href="https://www.instagram.com/oks.miyagi.kanagawa/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/common_sns02.png" alt="Instagram"></a>
              </li>
              <li>
                <a href="https://lin.ee/jSxJC4Z" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/common_sns03.png" alt="LINE登録"></a>
              </li>
              <li>
                <a href="https://www.youtube.com/@oksjpn" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/common_sns04.png" alt="Youtube"></a>
              </li>
            </ul>
          </div>
          <nav class="common_footer__nav">
            <ul class="common_footer__list">
              <li class="common_footer__item">
                <a href="<?php echo home_url('/search/'); ?>">
                  <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                  <span class="default hover-underline">検索ページ</span>
                </a>
              </li>
              <li class="common_footer__item">
                <a href="<?php echo home_url('/consultant/'); ?>">
                  <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                  <span class="default hover-underline">コンサルタント紹介</span>
                </a>
              </li>
              <li class="common_footer__item">
                <a href="<?php echo home_url(); ?>#register">
                  <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                  <span class="default hover-underline">カンタン登録</span>
                </a>
              </li>
              <li class="common_footer__item">
                <a href="<?php echo get_post_type_archive_link('company'); ?>">
                  <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                  <span class="default hover-underline">注目企業</span>
                </a>
              </li>
              <li class="common_footer__item">
                <a href="<?php echo home_url('/useful/'); ?>">
                  <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                  <span class="default hover-underline">お役立ち情報</span>
                </a>
              </li>
              <li class="common_footer__item">
                <a href="<?php echo home_url('/faq/'); ?>">
                  <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                  <span class="default hover-underline">Q&amp;A</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
        <div class="common_footer__copy">&copy;<?php echo date('Y'); ?> 株式会社オーケーエス</div>
      </footer>
    </div>
    <?php wp_footer(); ?>
  </body>
</html>