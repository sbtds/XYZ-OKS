<!DOCTYPE html>
<html lang="ja" class="h-full">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <?php wp_head(); ?>
</head>
<body <?php body_class('home'); ?>>
  <div id="root" class="">
    <a id="skip-to-content" href="#main-content"
      class="sr-only focus:not-sr-only focus:fixed focus:top-[140px] focus:left-4 focus:z-[9999] focus:p-4 focus:bg-primary-dark focus:text-white font-bold rounded shadow-lg">メインコンテンツへスキップ</a>
    <nav class="common_global" role="navigation" aria-label="グローバルナビゲーション">
      <div class="common_global__header">
        <div class="common_header__logo">
          <a href="<?php echo home_url(); ?>" class="common_header__id">
            <span class="logo">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/common_id.png"
                alt="<?php bloginfo('name'); ?>" class="h-[43px] xl:h-[60px] default">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/common_id.png"
                alt="<?php bloginfo('name'); ?>" class="h-[43px] xl:h-[60px] scroll">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/common_id.png"
                alt="<?php bloginfo('name'); ?>" class="h-[43px] xl:h-[60px] page">
            </span>
          </a>
        </div>
        <button class="common_trigger" aria-expanded="false" aria-controls="global-menu" aria-label="メニューを開く">
          <span class="common_trigger_button">
            <span class="close">
              <span class="fill"><span class="before bg-black"></span><span class="after bg-black"></span></span>
              <span class="fill"><span class="before bg-black"></span><span class="after bg-black"></span></span>
            </span>
          </span>
        </button>
      </div>
      <div class="common_global__inner" id="global-menu">
        <div class="common_global__main">
          <ul class="common_global__list" role="menubar">
            <li class="common_global__item">
              <a href="<?php echo home_url('/search/'); ?>">
                <span class="default">検索ページ</span>
              </a>
            </li>
            <li class="common_global__item">
              <a href="<?php echo home_url('/consultant/'); ?>">
                <span class="default">コンサルタント紹介</span>
              </a>
            </li>
            <li class="common_global__item">
              <a href="<?php echo home_url('/story/'); ?>">
                <span class="default">転職者ストーリー</span>
              </a>
            </li>
            <li class="common_global__item">
              <a href="<?php echo home_url('/entry/'); ?>">
                <span class="default">カンタン登録</span>
              </a>
            </li>
            <li class="common_global__item">
              <a href="<?php echo get_post_type_archive_link('company'); ?>">
                <span class="default">注目企業</span>
              </a>
            </li>
            <li class="common_global__item">
              <a href="<?php echo home_url('/useful/'); ?>">
                <span class="default">お役立ち情報</span>
              </a>
            </li>
            <li class="common_global__item">
              <a href="<?php echo home_url('/faq/'); ?>">
                <span class="default">Q&amp;A</span>
              </a>
            </li>
          </ul>

          <ul class="common_global__side">
            <li class="common_navigation__button primary">
              <a href="#">
                <span class="label">マイページ</span>
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </a>
            </li>
            <li class="common_navigation__button">
              <a href="<?php echo home_url('/search/'); ?>">
                <span class="label">求人検索</span>
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <input type="checkbox" name="navigation" id="header_navigation_toggle" value="header_navigation_toggle"
      class="header_toggle">

    <header class="common_header is-fixed-child">
      <div class="common_header__container">
        <div class="common_header__logo">
          <a href="<?php echo home_url(); ?>" class="common_header__id">
            <span class="logo">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/common_id.png"
                alt="<?php bloginfo('name'); ?>">
            </span>
          </a>
        </div>
        <div class="common_navigation">
          <div class="common_navigation__contents">
            <ul class="common_navigation__side">
              <li class="common_navigation__button primary">
                <a href="#">
                  <span class="label">マイページ</span>
                  <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                </a>
              </li>
              <li class="common_navigation__button">
                <a href="<?php echo home_url('/search/'); ?>">
                  <span class="label">求人検索</span>
                  <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                </a>
              </li>
            </ul>
            <div class="common_navigation__main">
              <ul class="common_navigation__list">
                <li class="common_navigation__item">
                  <a href="<?php echo home_url('/search/'); ?>">
                    <span class="default hover-underline">検索ページ</span>
                  </a>
                </li>
                <li class="common_navigation__item">
                  <a href="<?php echo home_url('/consultant/'); ?>">
                    <span class="default hover-underline">コンサルタント紹介</span>
                  </a>
                </li>
                <li class="common_navigation__item">
                  <a href="<?php echo home_url('/story/'); ?>">
                    <span class="default hover-underline">転職者ストーリー</span>
                  </a>
                </li>
                <li class="common_navigation__item">
                  <a href="<?php echo home_url('/entry/'); ?>">
                    <span class="default hover-underline">カンタン登録</span>
                  </a>
                </li>
                <li class="common_navigation__item">
                  <a href="<?php echo get_post_type_archive_link('company'); ?>">
                    <span class="default hover-underline">注目企業</span>
                  </a>
                </li>
                <li class="common_navigation__item">
                  <a href="<?php echo home_url('/useful/'); ?>">
                    <span class="default hover-underline">お役立ち情報</span>
                  </a>
                </li>
                <li class="common_navigation__item">
                  <a href="<?php echo home_url('/faq/'); ?>">
                    <span class="default hover-underline">Q&amp;A</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <button class="common_trigger flex lg:hidden w-[70px] h-full border-2 border-primary-black" aria-expanded="false"
        aria-controls="mobile-menu" aria-label="モバイルメニューを開く">
        <span class="common_trigger_button">
          <span class="open">
            <span class="fill"><span class="before bg-black"></span><span class="after bg-black"></span></span>
            <span class="fill"><span class="before bg-black"></span><span class="after bg-black"></span></span>
            <span class="fill"><span class="before bg-black"></span><span class="after bg-black"></span></span>
          </span>
          <span class="close">
            <span class="fill"><span class="before bg-black"></span><span class="after bg-black"></span></span>
            <span class="fill"><span class="before bg-black"></span><span class="after bg-black"></span></span>
          </span>
        </span>
      </button>
    </header>