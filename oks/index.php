<?php
/**
 * トップページ
 *
 * @package OKS
 */

get_header(); ?>

<main class="index_main">
  <section class="index_hero">
    <div class="index_hero__main">
      <div class="index_hero__main_visual">
        <p class="image01">
          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_img01.jpg" alt="">
        </p>
        <p class="image02">
          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_img02.jpg" alt="">
        </p>
        <p class="image03">
          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_img03.jpg" alt="">
        </p>
      </div>
      <div class="index_hero__main_contents">
        <div class="container-hero">
          <p class="index_hero__main_title">
            <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_title.svg"
              alt="あなたの「やってみたい」は、 きっと誰かの「やってほしい」">
          </p>
          <p class="index_hero__main_text text-h2">未経験でも、地元で安心して働ける<br
              class="md-only">チャンスを。<br>オーケーエスは、あなたの「やってみたい」を応援します。</p>
          <div class="index_hero__main_cv">
            <div class="index_hero__main_button">
              <a class="button_cv" href="index.html#register">
                <div class="contents">
                  <span class="badge">無料</span>
                  <span class="caption">登録して</span>
                  <span class="label">非公開求人を見る</span>
                </div>
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </a>
            </div>
            <div class="index_hero__main_bg">
              <div class="index_hero__main_bg_wrap">
                <div class="index_hero__main_bg_list index_hero__main_bg_list--left">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                </div>
                <div class="index_hero__main_bg_list index_hero__main_bg_list--left">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_bg.svg"
                    class="index_hero__main_bg_item" alt="">
                </div>
              </div>
            </div>
          </div>
          <p class="index_hero__main_arrow01">
            <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_arrow01.png" alt="">
          </p>
          <p class="index_hero__main_arrow02">
            <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_arrow02.png" alt="">
          </p>
          <p class="index_hero__main_arrow03">
            <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_arrow02.png" alt="">
          </p>

        </div>
      </div>
      <div class="index_hero__main_frame">
        <p class="index_hero__main_frame01">
          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_frame01_sp.png"
            class="sp-only" alt="">
          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_frame01.png"
            class="pc-only" alt="">
        </p>
        <p class="index_hero__main_frame02">
          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_frame02.png" alt="">
        </p>
        <p class="index_hero__main_frame03">
          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_frame03_sp.png"
            class="sp-only" alt="">
          <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/hero_frame03.png"
            class="pc-only" alt="">
        </p>
      </div>
    </div>
    <div class="index_hero__slide">
      <div class="index_hero__slide_list carousel swiper" data-carousel-per-view="3" data-carousel-between="0"
        data-carousel-delay="5000">
        <div class="swiper-wrapper">
          <div class="index_hero__slide_item swiper-slide">
            <a href="featured.html">
              <p class="hover-image">
                <img class="" src="https://placehold.co/1100x840" alt="">
              </p>
            </a>
          </div>
          <div class="index_hero__slide_item swiper-slide">
            <a href="featured.html">
              <p class="hover-image">
                <img class="" src="https://placehold.co/1200x640" alt="">
              </p>
            </a>
          </div>
          <div class="index_hero__slide_item swiper-slide">
            <a href="featured.html">
              <p class="hover-image">
                <img class="" src="https://placehold.co/1000x1000" alt="">
              </p>
            </a>
          </div>
          <div class="index_hero__slide_item swiper-slide">
            <a href="featured.html">
              <p class="hover-image">
                <img class="" src="https://placehold.co/1400x840" alt="">
              </p>
            </a>
          </div>
          <div class="index_hero__slide_item swiper-slide">
            <a href="featured.html">
              <p class="hover-image">
                <img class="" src="https://placehold.co/1200x840" alt="">
              </p>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="index_reason">
    <p class="index_reason__frame"></p>
    <div class="index_reason__container">
      <h2 class="title_section h55">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/reason_title_sp.svg"
          class="sp-only" alt="オーケーエスが選ばれる理由">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/reason_title.svg" class="pc-only"
          alt="オーケーエスが選ばれる理由">
      </h2>
      <div class="index_reason__list">
        <div class="index_reason__item">
          <div class="index_reason__inner">
            <p class="index_reason__image">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/reason_img01.jpg?311*311"
                alt="">
              <span class="number">
                <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/reason_num01.svg"
                  alt="01">
              </span>
            </p>
            <p class="index_reason__desc">長年の信頼と実績で“顔の見えるマッチング”を実現しています。</p>
          </div>
        </div>
        <div class="index_reason__item">
          <div class="index_reason__inner">
            <p class="index_reason__image">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/reason_img02.jpg?311*311"
                alt="">
              <span class="number">
                <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/reason_num02.svg"
                  alt="02">
              </span>
            </p>
            <p class="index_reason__desc">一人ひとりに専任のコンサルタントがつきます。手厚いサポートで転職まで伴走します。</p>
          </div>
        </div>
        <div class="index_reason__item">
          <div class="index_reason__inner">
            <p class="index_reason__image">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/reason_img03.jpg?311*311"
                alt="">
              <span class="number">
                <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/reason_num03.svg"
                  alt="03">
              </span>
            </p>
            <p class="index_reason__desc">自分だけのマイページで気になる求人をキープすることができます。<br>今すぐじゃなくても残しておける。</p>
          </div>
        </div>
      </div>
      <div class="button_section">
        <a class="button_more white" href="#">
          <span class="label">まずは登録する</span>
          <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
        </a>
      </div>
    </div>
  </section>
  <section class="index_search">
    <div class="index_search__container">
      <h2 class="title_section">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/search_title_sp.svg"
          class="sp-only" alt="求人を探す">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/search_title.svg" class="pc-only"
          alt="求人を探す">
      </h2>
      <form class="search_select" action="./search.html">
        <div class="search_select__box">
          <div class="search_select__inner">
            <input type="checkbox" class="search_select__check" id="search_select__box01">
            <label class="search_select__button" for="search_select__box01">

              <span class="icon"><i class="fa-solid fa-location-dot"></i></span>
              <span class="label">勤務地を選ぶ</span>
              <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>


              <p class="search_side__button">
                <span class="search_side__button_label">勤務地</span>
                <span class="search_side__button_icon">
                  <span class="plus"><i class="fa-solid fa-plus"></i></span>
                  <span class="minus"><i class="fa-solid fa-minus"></i></span>
                </span>
                <span class="search_side__button_caption">勤務地を変更</span>
              </p>

            </label>
            <div class="search_select__menu">
              <div class="search_select__menu_list">
                <div class="search_select__area">
                  <input type="checkbox" class="search_select__area_check" id="search_select__area00">
                  <label class="search_select__area_title" for="search_select__area00">
                    <span class="checkbox"></span>
                    <span class="label">全国</span>
                    <span class="count">(987,654件)</span>
                  </label>
                </div>

                <div class="search_select__area">
                  <input type="checkbox" class="search_select__area_show" id="search_select__area_show01">
                  <input type="checkbox" class="search_select__area_check" id="search_select__area01">
                  <label class="search_select__area_title" for="search_select__area01">
                    <span class="checkbox"></span>
                    <span class="label">岩手県</span>
                    <span class="count">(123,456件)</span>
                    <label class="arrow" for="search_select__area_show01">
                      <span class="plus"><i class="fa-solid fa-plus"></i></span>
                      <span class="minus"><i class="fa-solid fa-minus"></i></span>
                    </label>
                  </label>

                  <div class="search_select__area_menu">
                    <div class="search_select__area_list">
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="一関市">
                        <span class="checkbox"></span>
                        <span class="label">一関市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="西磐井郡平泉町">
                        <span class="checkbox"></span>
                        <span class="label">西磐井郡平泉町</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="search_select__area">
                  <input type="checkbox" class="search_select__area_show" id="search_select__area_show02">
                  <input type="checkbox" class="search_select__area_check" id="search_select__area02">
                  <label class="search_select__area_title" for="search_select__area02">
                    <span class="checkbox"></span>
                    <span class="label">宮城県</span>
                    <span class="count">(123,456件)</span>
                    <label class="arrow" for="search_select__area_show02">
                      <span class="plus"><i class="fa-solid fa-plus"></i></span>
                      <span class="minus"><i class="fa-solid fa-minus"></i></span>
                    </label>
                  </label>
                  <div class="search_select__area_menu">
                    <div class="search_select__area_list">
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="仙台市宮城野区">
                        <span class="checkbox"></span>
                        <span class="label">仙台市宮城野区</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="仙台市若林区">
                        <span class="checkbox"></span>
                        <span class="label">仙台市若林区</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="石巻市">
                        <span class="checkbox"></span>
                        <span class="label">石巻市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="登米市">
                        <span class="checkbox"></span>
                        <span class="label">登米市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="栗原市">
                        <span class="checkbox"></span>
                        <span class="label">栗原市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="東松島市">
                        <span class="checkbox"></span>
                        <span class="label">東松島市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="大崎市">
                        <span class="checkbox"></span>
                        <span class="label">大崎市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="富谷市">
                        <span class="checkbox"></span>
                        <span class="label">富谷市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="宮城郡利府町">
                        <span class="checkbox"></span>
                        <span class="label">宮城郡利府町</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="黒川郡大和町">
                        <span class="checkbox"></span>
                        <span class="label">黒川郡大和町</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="黒川郡大衡村">
                        <span class="checkbox"></span>
                        <span class="label">黒川郡大衡村</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="加美郡色麻町">
                        <span class="checkbox"></span>
                        <span class="label">加美郡色麻町</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="加美郡加美町">
                        <span class="checkbox"></span>
                        <span class="label">加美郡加美町</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="遠田郡美里町">
                        <span class="checkbox"></span>
                        <span class="label">遠田郡美里町</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="search_select__area">
                  <input type="checkbox" class="search_select__area_show" id="search_select__area_show03">
                  <input type="checkbox" class="search_select__area_check" id="search_select__area03">
                  <label class="search_select__area_title" for="search_select__area03">
                    <span class="checkbox"></span>
                    <span class="label">埼玉県</span>
                    <span class="count">(123,456件)</span>
                    <label class="arrow" for="search_select__area_show03">
                      <span class="plus"><i class="fa-solid fa-plus"></i></span>
                      <span class="minus"><i class="fa-solid fa-minus"></i></span>
                    </label>
                  </label>
                  <div class="search_select__area_menu">
                    <div class="search_select__area_list">
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="鴻巣市">
                        <span class="checkbox"></span>
                        <span class="label">鴻巣市</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="search_select__area">
                  <input type="checkbox" class="search_select__area_show" id="search_select__area_show04">
                  <input type="checkbox" class="search_select__area_check" id="search_select__area04">
                  <label class="search_select__area_title" for="search_select__area04">
                    <span class="checkbox"></span>
                    <span class="label">神奈川県</span>
                    <span class="count">(123,456件)</span>
                    <label class="arrow" for="search_select__area_show04">
                      <span class="plus"><i class="fa-solid fa-plus"></i></span>
                      <span class="minus"><i class="fa-solid fa-minus"></i></span>
                    </label>
                  </label>
                  <div class="search_select__area_menu">
                    <div class="search_select__area_list">
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="横浜市戸塚区">
                        <span class="checkbox"></span>
                        <span class="label">横浜市戸塚区</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="横浜市栄区">
                        <span class="checkbox"></span>
                        <span class="label">横浜市栄区</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="茅ヶ崎市">
                        <span class="checkbox"></span>
                        <span class="label">茅ヶ崎市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="秦野市">
                        <span class="checkbox"></span>
                        <span class="label">秦野市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="藤沢市">
                        <span class="checkbox"></span>
                        <span class="label">藤沢市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="小田原市">
                        <span class="checkbox"></span>
                        <span class="label">小田原市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="厚木市">
                        <span class="checkbox"></span>
                        <span class="label">厚木市</span>
                      </label>
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="海老名市">
                        <span class="checkbox"></span>
                        <span class="label">海老名市</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="search_select__area">
                  <input type="checkbox" class="search_select__area_show" id="search_select__show05">
                  <input type="checkbox" class="search_select__area_check" id="search_select__area05">
                  <label class="search_select__area_title" for="search_select__area05">
                    <span class="checkbox"></span>
                    <span class="label">静岡県</span>
                    <span class="count">(123,456件)</span>
                    <label class="arrow" for="search_select__show05">
                      <span class="plus"><i class="fa-solid fa-plus"></i></span>
                      <span class="minus"><i class="fa-solid fa-minus"></i></span>
                    </label>
                  </label>
                  <div class="search_select__area_menu">
                    <div class="search_select__area_list">
                      <label class="search_select__area_item">
                        <input type="checkbox" class="search_select__area_item_check" value="駿東郡清水町">
                        <span class="checkbox"></span>
                        <span class="label">駿東郡清水町</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <p class="search_select__x"><i class="fa-solid fa-xmark"></i></p>
        <div class="search_select__box">
          <div class="search_select__inner">
            <input type="checkbox" class="search_select__check" id="search_select__box02">
            <label class="search_select__button" for="search_select__box02">

              <span class="icon"><i class="fa-solid fa-briefcase"></i></span>
              <span class="label">職種を選ぶ</span>
              <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>


              <p class="search_side__button">
                <span class="search_side__button_label">職種</span>
                <span class="search_side__button_icon">
                  <span class="plus"><i class="fa-solid fa-plus"></i></span>
                  <span class="minus"><i class="fa-solid fa-minus"></i></span>
                </span>
                <span class="search_side__button_caption">職種を変更</span>
              </p>

            </label>
            <div class="search_select__menu">
              <div class="search_select__menu_list">
                <div class="search_select__type">
                  <input type="checkbox" class="search_select__type_show" id="search_select__type_show01">
                  <input type="checkbox" class="search_select__type_check" id="search_select__type01" name="type"
                    value="製造・技術">
                  <label class="search_select__type_title" for="search_select__type01">
                    <span class="checkbox"></span>
                    <span class="label">製造・技術</span>
                    <span class="count">(123,456件)</span>
                    <label class="arrow" for="search_select__type_show01">
                      <span class="plus"><i class="fa-solid fa-plus"></i></span>
                      <span class="minus"><i class="fa-solid fa-minus"></i></span>
                    </label>
                  </label>
                  <div class="search_select__type_menu">
                    <div class="search_select__type_list">
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type"
                          value="機械オペレーター機械オペレーション機械オペレーター機械オペレーション">
                        <span class="checkbox"></span>
                        <span class="label">機械オペレーター機械オペレーション機械オペレーター機械オペレーション</span>
                      </label>
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type"
                          value="機械オペレーター(機械オペレーション)">
                        <span class="checkbox"></span>
                        <span class="label">機械オペレーター(機械オペレーション)</span>
                      </label>
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type" value="組立・加工">
                        <span class="checkbox"></span>
                        <span class="label">組立・加工</span>
                      </label>
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type" value="製造（電気・電子・機械）">
                        <span class="checkbox"></span>
                        <span class="label">製造（電気・電子・機械）</span>
                      </label>
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type" value="検査・検品">
                        <span class="checkbox"></span>
                        <span class="label">検査・検品</span>
                      </label>
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type"
                          value="製造・技能工（化学・医療・食品）">
                        <span class="checkbox"></span>
                        <span class="label">製造・技能工（化学・医療・食品）</span>
                      </label>
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type" value="食品製造">
                        <span class="checkbox"></span>
                        <span class="label">食品製造</span>
                      </label>
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type" value="その他（化学・医療・食品）">
                        <span class="checkbox"></span>
                        <span class="label">その他（化学・医療・食品）</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="search_select__type">
                  <input type="checkbox" class="search_select__type_show" id="search_select__type_show02">
                  <input type="checkbox" class="search_select__type_check" id="search_select__type02" name="type"
                    value="物流・配送・軽作業">
                  <label class="search_select__type_title" for="search_select__type02">
                    <span class="checkbox"></span>
                    <span class="label">物流・配送・軽作業</span>
                    <span class="count">(456件)</span>
                    <label class="arrow" for="search_select__type_show02">
                      <span class="plus"><i class="fa-solid fa-plus"></i></span>
                      <span class="minus"><i class="fa-solid fa-minus"></i></span>
                    </label>
                  </label>
                  <div class="search_select__type_menu">
                    <div class="search_select__type_list">
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type" value="仕分け・梱包・ピッキング">
                        <span class="checkbox"></span>
                        <span class="label">仕分け・梱包・ピッキング</span>
                      </label>
                      <label class="search_select__type_item">
                        <input type="checkbox" class="search_select__type_item_check" name="type" value="フォークリフト">
                        <span class="checkbox"></span>
                        <span class="label">フォークリフト</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div class="search_select__income">
          <div class="search_select__income_title">
            <p class="search_side__subject">年収</p>
          </div>
          <div class="search_select__income_select">
            <select name="" id="">
              <option value="">指定しない</option>
              <option value="1000000">100万</option>
              <option value="2000000">200万</option>
              <option value="3000000">300万</option>
              <option value="4000000">400万</option>
              <option value="5000000">500万</option>
              <option value="6000000">600万</option>
              <option value="7000000">700万</option>
              <option value="8000000">800万</option>
              <option value="9000000">900万</option>
              <option value="10000000">1000万</option>
            </select>
          </div>
        </div>
        <div class="search_select__keyword">
          <div class="search_select__keyword_title">
            <p class="search_side__subject">キーワード</p>
          </div>
          <p class="search_select__keyword_input">
            <input type="text" value="" placeholder="入力してください">
          </p>
        </div>
        <div class="search_select__conditions">
          <div class="search_select__conditions_title">
            <p class="search_side__subject">こだわり条件</p>
          </div>
          <div class="search_select__conditions_list">
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="年間休日120日以上">
              <span class="checkbox"></span>
              <span class="label">年間休日120日以上</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="寮・社宅・住宅手当あり">
              <span class="checkbox"></span>
              <span class="label">寮・社宅・住宅手当あり</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="退職金制度">
              <span class="checkbox"></span>
              <span class="label">退職金制度</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="資格取得支援制度">
              <span class="checkbox"></span>
              <span class="label">資格取得支援制度</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="産休・育休・介護休暇取得実績あり">
              <span class="checkbox"></span>
              <span class="label">産休・育休・介護休暇取得実績あり</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="女性が活躍">
              <span class="checkbox"></span>
              <span class="label">女性が活躍</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="男性が活躍">
              <span class="checkbox"></span>
              <span class="label">男性が活躍</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="インセンティブあり">
              <span class="checkbox"></span>
              <span class="label">インセンティブあり</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="UIターン支援あり">
              <span class="checkbox"></span>
              <span class="label">UIターン支援あり</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="リモート面接OK">
              <span class="checkbox"></span>
              <span class="label">リモート面接OK</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="ミドル活躍中">
              <span class="checkbox"></span>
              <span class="label">ミドル活躍中</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="シニア活躍中">
              <span class="checkbox"></span>
              <span class="label">シニア活躍中</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="新卒採用">
              <span class="checkbox"></span>
              <span class="label">新卒採用</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="第２新卒採用">
              <span class="checkbox"></span>
              <span class="label">第２新卒採用</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="中途採用">
              <span class="checkbox"></span>
              <span class="label">中途採用</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="固定残業代なし">
              <span class="checkbox"></span>
              <span class="label">固定残業代なし</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="交通費支給">
              <span class="checkbox"></span>
              <span class="label">交通費支給</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="完全週休2日制">
              <span class="checkbox"></span>
              <span class="label">完全週休2日制</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="土日祝休み">
              <span class="checkbox"></span>
              <span class="label">土日祝休み</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="残業少なめ(20時間未満)">
              <span class="checkbox"></span>
              <span class="label">残業少なめ(20時間未満)</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="リモートワーク・在宅勤務制度あり">
              <span class="checkbox"></span>
              <span class="label">リモートワーク・在宅勤務制度あり</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="転勤なし">
              <span class="checkbox"></span>
              <span class="label">転勤なし</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="学歴不問">
              <span class="checkbox"></span>
              <span class="label">学歴不問</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="正社員">
              <span class="checkbox"></span>
              <span class="label">正社員</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="管理職・マネージャー">
              <span class="checkbox"></span>
              <span class="label">管理職・マネージャー</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="設立10年以上の会社">
              <span class="checkbox"></span>
              <span class="label">設立10年以上の会社</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="ベンチャー企業">
              <span class="checkbox"></span>
              <span class="label">ベンチャー企業</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="車通勤可">
              <span class="checkbox"></span>
              <span class="label">車通勤可</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="未経験でも可">
              <span class="checkbox"></span>
              <span class="label">未経験でも可</span>
            </label>
            <label class="search_select__area_item">
              <input type="checkbox" class="search_select__area_item_check" name="conditions" value="上場企業">
              <span class="checkbox"></span>
              <span class="label">上場企業</span>
            </label>
          </div>
        </div>


        <button class="button_search">

          <span class="icon"><i class="fa-solid fa-search"></i></span>
          <span class="label">検索</span>


          <span class="search">この条件で検索</span>
          <span class="arrow"><i class="fa-solid fa-chevron-right"></i></span>

        </button>
      </form>
    </div>
  </section>
  <section class="index_recommend">
    <div class="index_recommend__container">
      <h2 class="title_section">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/recommend_title_sp.svg"
          class="sp-only" alt="おすすめ求人">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/recommend_title.svg"
          class="pc-only" alt="おすすめ求人">
      </h2>
      <div class="index_recommend__contents">
        <div class="index_recommend__list">
          <a class="index_recommend__item" href="./search_detail.html">
            <p class="index_recommend__image hover-image">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/recommend_img01.jpg"
                alt="営業職のイメージ">
            </p>
            <h3 class="index_recommend__subject">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/recommend_sub01.svg"
                alt="営業職">
            </h3>
          </a>
          <a class="index_recommend__item" href="./search_detail.html">
            <p class="index_recommend__image hover-image">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/recommend_img02.jpg"
                alt="技術職のイメージ">
            </p>
            <h3 class="index_recommend__subject">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/recommend_sub02.svg"
                alt="技術職">
            </h3>
          </a>
          <a class="index_recommend__item" href="./search_detail.html">
            <p class="index_recommend__image hover-image">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/recommend_img03.jpg"
                alt="経理・財務職のイメージ">
            </p>
            <h3 class="index_recommend__subject">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/recommend_sub03.svg"
                alt="経理・財務職">
            </h3>
          </a>
        </div>
      </div>
    </div>
  </section>
  <section class="index_area">
    <div class="index_area__container">
      <h2 class="title_section">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/area_title_sp.svg"
          class="sp-only" alt="勤務地から探す">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/area_title.svg" class="pc-only"
          alt="勤務地から探す">
      </h2>
      <div class="index_area__contents">
        <form class="index_area__box" action="./search.html">
          <p class="index_area__icon">
            <span class="icon"><i class="fa-solid fa-search"></i></span>
          </p>
          <div class="index_area__main">
            <div class="inner">

              <h3 class="title">東北</h3>
              <div class="contents">
                <div class="contents_list">

                  <label>
                    <input type="radio" name="area" id="" value="宮城">
                    <span class="circle"></span>
                    <span class="label">宮城</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="岩手">
                    <span class="circle"></span>
                    <span class="label">岩手</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="秋田">
                    <span class="circle"></span>
                    <span class="label">秋田</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="青森">
                    <span class="circle"></span>
                    <span class="label">青森</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="山形">
                    <span class="circle"></span>
                    <span class="label">山形</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="福島">
                    <span class="circle"></span>
                    <span class="label">福島</span>
                  </label>
                </div>
              </div>
              <p class="button">
                <button class="search">
                  <span class="icon"><i class="fa-solid fa-search"></i></span>
                  <span class="label">検索</span>
                </button>
              </p>
              <p class="map"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/area_map01.png" alt=""></p>
            </div>
          </div>
        </form>

        <form class="index_area__box type-green" action="./search.html">
          <p class="index_area__icon">
            <span class="icon"><i class="fa-solid fa-search"></i></span>
          </p>
          <div class="index_area__main">
            <div class="inner">

              <h3 class="title">関東</h3>
              <div class="contents">
                <div class="contents_list">

                  <label>
                    <input type="radio" name="area" id="" value="神奈川">
                    <span class="circle"></span>
                    <span class="label">神奈川</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="東京">
                    <span class="circle"></span>
                    <span class="label">東京</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="埼玉">
                    <span class="circle"></span>
                    <span class="label">埼玉</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="群馬">
                    <span class="circle"></span>
                    <span class="label">群馬</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="栃木">
                    <span class="circle"></span>
                    <span class="label">栃木</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="茨城">
                    <span class="circle"></span>
                    <span class="label">茨城</span>
                  </label>
                  <label>
                    <input type="radio" name="area" id="" value="千葉">
                    <span class="circle"></span>
                    <span class="label">千葉</span>
                  </label>
                </div>
              </div>
              <p class="button">
                <button class="search">
                  <span class="icon"><i class="fa-solid fa-search"></i></span>
                  <span class="label">検索</span>
                </button>
              </p>
              <p class="map"><img
                  src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/area_map02.png" alt=""></p>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
  <section class="index_type">
    <div class="index_type__container">
      <h2 class="title_section">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/type_title_sp.svg"
          class="sp-only" alt="働き方から探す">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/type_title.svg" class="pc-only"
          alt="働き方から探す">
      </h2>
      <div class="index_type__contents">
        <div class="index_type__list">
          <div class="index_type__item">
            <a class="index_type__inner" href="./search.html">
              <div class="index_type__thb">
                <p class="hover-image">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/type_img.jpg" alt="">
                </p>
              </div>
              <div class="index_type__text">
                <p><strong>テキスト職</strong></p>
                <p>テキスト職テキスト職</p>
              </div>
              <div class="index_type__arrow">
                <i class="fa-solid fa-angle-right"></i>
              </div>
            </a>
          </div>
          <div class="index_type__item">
            <a class="index_type__inner" href="./search.html">
              <div class="index_type__thb">
                <p class="hover-image">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/type_img.jpg" alt="">
                </p>
              </div>
              <div class="index_type__text">
                <p><strong>テキスト職</strong></p>
                <p>テキスト職テキスト職テキスト職テキスト職</p>
              </div>
              <div class="index_type__arrow">
                <i class="fa-solid fa-angle-right"></i>
              </div>
            </a>
          </div>
          <div class="index_type__item">
            <a class="index_type__inner" href="./search.html">
              <div class="index_type__thb">
                <p class="hover-image">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/type_img.jpg" alt="">
                </p>
              </div>
              <div class="index_type__text">
                <p><strong>テキスト職テキスト職</strong></p>
                <p>テキスト職テキスト職</p>
              </div>
              <div class="index_type__arrow">
                <i class="fa-solid fa-angle-right"></i>
              </div>
            </a>
          </div>
          <div class="index_type__item">
            <a class="index_type__inner" href="./search.html">
              <div class="index_type__thb">
                <p class="hover-image">
                  <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/type_img.jpg" alt="">
                </p>
              </div>
              <div class="index_type__text">
                <p><strong>テキスト職</strong></p>
                <p>テキスト職テキスト職</p>
              </div>
              <div class="index_type__arrow">
                <i class="fa-solid fa-angle-right"></i>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="index_consultant">
    <div class="index_consultant__container">
      <h2 class="title_section h55">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/consultant_title_sp.svg"
          class="sp-only" alt="コンサルタント紹介">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/consultant_title.svg"
          class="pc-only" alt="コンサルタント紹介">
      </h2>
      <div class="index_consultant__desc">
        <p>どうやって転職活動を進めたらいいかわからない、自分に合う会社を見つけられない、といった不安を OKSのコンサルタントにご相談ください。転職完了まで寄り添ってサポートいたします。</p>
      </div>
      <div class="index_consultant__list">
        <?php
        $consultant_query = new WP_Query(array(
          'post_type' => 'consultant',
          'posts_per_page' => 3,
        ));
        if ($consultant_query->have_posts()) :
          while ($consultant_query->have_posts()) : $consultant_query->the_post();
            // ACFフィールドを取得
            $consultant_top = get_field('consultant_top');

            // タクソノミーからエリアを取得
            $areas = get_the_terms(get_the_ID(), 'company_area');
        ?>
        <div class="index_consultant__item">
          <a class="index_consultant__inner" href="<?php the_permalink(); ?>">
            <p class="index_consultant__thb hover-image">
              <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('medium'); ?>
              <?php else : ?>
              <img src="https://placehold.co/320x320" alt="<?php echo esc_attr(get_the_title()); ?>">
              <?php endif; ?>
            </p>
            <div class="index_consultant__main">
              <?php if (!empty($consultant_top['eng'])) : ?>
              <p class="en"><?php echo esc_html($consultant_top['eng']); ?></p>
              <?php endif; ?>

              <?php if (!empty($consultant_top['name'])) : ?>
              <p class="ja"><?php echo esc_html($consultant_top['name']); ?></p>
              <?php else : ?>
              <p class="ja"><?php the_title(); ?></p>
              <?php endif; ?>

              <?php if ($areas && !is_wp_error($areas)) : ?>
              <p class="caption">
                <?php
                $area_names = array();
                foreach ($areas as $area) {
                  $area_names[] = $area->name;
                }
                echo esc_html(implode('、', $area_names));
                ?>
              </p>
              <?php endif; ?>

              <hr>
              <?php if (!empty($consultant_top['text'])) : ?>
              <p class="desc"><?php echo esc_html($consultant_top['text']); ?></p>
              <?php endif; ?>

              <p class="link">
                <span class="label hover-underline">READ MORE</span>
                <span class="icon"><i class="fa-solid fa-arrow-right"></i></span>
              </p>
            </div>
          </a>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
        <?php endif; ?>

      </div>
      <div class="button_section">
        <a class="button_more white" href="<?php echo home_url('/consultant/'); ?>">
          <span class="label">もっと見る</span>
          <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
        </a>
      </div>
    </div>
  </section>
  <section class="index_story">
    <div class="index_story__container">
      <h2 class="title_section">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/story_title_sp.svg"
          class="sp-only" alt="転職者ストーリー">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/story_title.svg" class="pc-only"
          alt="転職者ストーリー">
        <span class="caption">転職に成功した方の声をご紹介します。</span>
      </h2>

      <div class="index_story__list">

        <a class="index_story__item" href="#">
          <div class="index_story__inner">
            <div class="index_story__upper">
              <p class="index_story__thb hover-image">
                <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/story_img01.jpg" alt="">
              </p>
              <p class="index_story__lead"><span>テキストが入りますテキストが入ります</span></p>
            </div>
            <div class="index_story__contents">
              <div class="index_story__main">
                <p class="en">NAME NAME</p>
                <p class="ja">名前 名前</p>
                <p class="caption">テキストテキスト</p>
              </div>
              <p class="index_story__arrow">
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </p>
            </div>
            <hr>
            <div class="index_story__text">
              <p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
            </div>
          </div>
        </a>
        <a class="index_story__item" href="#">
          <div class="index_story__inner">
            <div class="index_story__upper">
              <p class="index_story__thb hover-image">
                <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/story_img01.jpg" alt="">
              </p>
              <p class="index_story__lead"><span>テキストが入りますテキストが入ります</span></p>
            </div>
            <div class="index_story__contents">
              <div class="index_story__main">
                <p class="en">NAME NAME</p>
                <p class="ja">名前 名前</p>
                <p class="caption">テキストテキスト</p>
              </div>
              <p class="index_story__arrow">
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </p>
            </div>
            <hr>
            <div class="index_story__text">
              <p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
            </div>
          </div>
        </a>
        <a class="index_story__item" href="#">
          <div class="index_story__inner">
            <div class="index_story__upper">
              <p class="index_story__thb hover-image">
                <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/story_img01.jpg" alt="">
              </p>
              <p class="index_story__lead"><span>テキストが入りますテキストが入ります</span></p>
            </div>
            <div class="index_story__contents">
              <div class="index_story__main">
                <p class="en">NAME NAME</p>
                <p class="ja">名前 名前</p>
                <p class="caption">テキストテキスト</p>
              </div>
              <p class="index_story__arrow">
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </p>
            </div>
            <hr>
            <div class="index_story__text">
              <p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
            </div>
          </div>
        </a>


      </div>
      <div class="button_section">
        <a class="button_more" href="#">
          <span class="label">もっと見る</span>
          <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
        </a>
      </div>
    </div>
  </section>
  <section class="index_faq">
    <div class="index_faq__container container-base">
      <h2 class="title_section white">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/faq_title_sp.svg" class="sp-only"
          alt="Q&A">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/faq_title.svg" class="pc-only"
          alt="Q&A">
        <span class="caption">よくあるご質問</span>
      </h2>
      <div class="index_faq__list">
        <?php
        $faq_query = new WP_Query(array(
          'post_type' => 'faq',
          'posts_per_page' => 5,
        ));
        if ($faq_query->have_posts()) :
          while ($faq_query->have_posts()) : $faq_query->the_post();
        ?>
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
              <?php the_content(); ?>
            </div>
          </div>
        </label>
        <?php endwhile; wp_reset_postdata(); ?>
        <?php endif; ?>
      </div>
      <div class="button_section">
        <a class="button_more white" href="<?php echo home_url('/faq/'); ?>">
          <span class="label">もっと見る</span>
          <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
        </a>
      </div>
    </div>
  </section>
  <section class="index_flow">
    <div class="index_flow__container container-base">
      <h2 class="title_section">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/flow_title_sp.svg"
          class="sp-only" alt="FLOW">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/flow_title.svg" class="pc-only"
          alt="FLOW">
        <span class="caption">転職者フロー</span>
      </h2>
      <div class="index_flow__main">
        <div class="index_flow__head">
          <div class="index_flow__head_item">
            <p class="label">
              カンタン登録・応募
            </p>
            <p class="number">1</p>
            <p class="sep"></p>
          </div>
          <div class="index_flow__head_item">
            <p class="label">
              カウンセリング・<br>求人紹介
            </p>
            <p class="number">2</p>
            <p class="sep"></p>

          </div>
          <div class="index_flow__head_item">
            <p class="label">
              書類作成・<br>応募・選考
            </p>
            <p class="number">3</p>
            <p class="sep"></p>

          </div>
          <div class="index_flow__head_item">
            <p class="label">
              内定・入社
            </p>
            <p class="number">4</p>
          </div>
        </div>

        <div class="index_flow__body">
          <div class="index_flow__box">
            <div class="index_flow__contents">
              <p class="step"><span class="label">STEP1</span></p>
              <p class="name"><strong>カンタン登録・応募</strong></p>
              <div class="contents">
                <p>
                  どんな求人がいいかわからない、<br>
                  求人を探すのが大変、、<br>
                  という方はまずはカンタン登録を！<br>
                  この求人がいい！という方はぜひ求人からご応募ください。
                </p>
              </div>
            </div>
            <p class="index_flow__thb">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/flow_img01.jpg" alt="">
            </p>
          </div>
          <div class="index_flow__box">
            <div class="index_flow__contents">
              <p class="step"><span class="label">STEP2</span></p>
              <p class="name"><strong>カウンセリング・求人紹介</strong></p>
              <div class="contents">
                <p>
                  あなたが登録した情報をもとに、強みやキャリアを一緒に整理しながら、転職の考えや希望をうかがいます。<br>
                  今後の進め方についてもアドバイスします。<br>
                  面談は、状況に応じて電話やZoom、メールでも対応可能です。<br>
                  ご相談のうえ、対面での面談も可能です。<br>
                  そのうえで、あなたの経験やスキル、希望に合った求人をお探しします。必要に応じて、求人の開拓も行います。一般には出回っていない求人も含めて、職場の雰囲気や背景まで理解したうえで、自信を持ってご紹介します。<br>
                  ※求人の状況によっては、<br class="pc-only">
                  ご紹介が難しい場合もありますのでご了承ください。
                </p>
              </div>
            </div>
            <p class="index_flow__thb">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/flow_img02.jpg" alt="">
            </p>
          </div>
          <div class="index_flow__box">
            <div class="index_flow__contents">
              <p class="step"><span class="label">STEP3</span></p>
              <p class="name"><strong>書類作成・応募・選考</strong></p>
              <div class="contents">
                <p>
                  まず、あなたの「応募したい気持ち」<br class="pc-only">
                  があるかを確認します。<br>
                  応募の意思がある場合のみ、書類の作成をお願いしています。<br>
                  書類は、キャリアコンサルタントが内容をチェックし、<br class="pc-only">
                  より良くなるようアドバイスも行います。<br>
                  完成したら、求人企業へ私たちが応募手続きをします。<br>
                  書類選考に通過したら、面接の日程や場所は<br class="pc-only">
                  キャリアコンサルタントがあなたに代わって調整します。<br>
                  面接が決まったら、企業ごとの面接対策を一緒に行います。<br>
                  よくある質問への準備や、面接官の雰囲気などもお伝えし、<br class="pc-only">
                  安心して面接に臨めるようサポートします。
                </p>
              </div>
            </div>
            <p class="index_flow__thb">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/flow_img03.jpg" alt="">
            </p>
          </div>
          <div class="index_flow__box">
            <div class="index_flow__contents">
              <p class="step"><span class="label">STEP4</span></p>
              <p class="name"><strong>内定・入社</strong></p>
              <div class="contents">
                <p>
                  内定が出たあとは、入社日や条件の調整を<br class="pc-only">
                  キャリアコンサルタントがあなたに代わって行います。<br>
                  新しい職場には不安がつきものです。<br>
                  気になることがあれば、いつでもご相談ください。<br>
                  あなたと企業の合意が取れた時点で、採用が正式に決まります。
                </p>
              </div>
            </div>
            <p class="index_flow__thb">
              <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/flow_img04.jpg" alt="">
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="index_featured">
    <div class="index_featured__container">
      <h2 class="title_section">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/featured_title_sp.svg"
          class="sp-only" alt="注目企業">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/featured_title.svg"
          class="pc-only" alt="注目企業">
        <span class="caption">テキストが入りますテキストが入りますテキストが入りますテキストが入ります</span>
      </h2>
      <div class="index_featured__list">
        <div class="index_featured__item">
          <p class="index_featured__image">
            <img class="" src="https://placehold.co/480x630" alt="">
          </p>
          <div class="index_featured__contents">
            <p class="name">○○○○○○○○○○○○○○様</p>
            <h3 class="title">タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイ</h3>
            <hr>
            <div class="contents">
              <p>
                テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト
              </p>
            </div>
            <p class="link">
              <a href="./featured_detail.html">
                <span class="label hover-underline">READ MORE</span>
                <span class="icon"><i class="fa-solid fa-arrow-right"></i></span>
              </a>
            </p>
          </div>
        </div>
        <div class="index_featured__item">
          <p class="index_featured__image">
            <img class="" src="https://placehold.co/480x630" alt="">
          </p>
          <div class="index_featured__contents">
            <p class="name">○○○○○○○○○○○○○○様</p>
            <h3 class="title">タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイ</h3>
            <hr>
            <div class="contents">
              <p>
                テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト
              </p>
            </div>
            <p class="link">
              <a href="./featured_detail.html">
                <span class="label hover-underline">READ MORE</span>
                <span class="icon"><i class="fa-solid fa-arrow-right"></i></span>
              </a>
            </p>
          </div>
        </div>
      </div>
      <div class="button_section">
        <a class="button_more white" href="<?php echo home_url('/featured/'); ?>">
          <span class="label">もっと見る</span>
          <span class="icon"><i class="fa-solid fa-chevron-right"></i></span>
        </a>
      </div>
    </div>
  </section>
  <section class="index_useful">
    <div class="index_useful__container container-base">
      <h2 class="title_section">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title_sp.svg"
          class="sp-only" alt="お役立ち情報">
        <img src="<?php echo get_template_directory_uri(); ?>/dist/assets/images/index/useful_title.svg" class="pc-only"
          alt="お役立ち情報">
        <span class="caption">テキストが入りますテキストが入りますテキストが入りますテキストが入ります</span>
      </h2>
      <div class="index_useful__list">
        <?php
        $useful_query = new WP_Query(array(
          'post_type' => 'post',
          'posts_per_page' => 6,
        ));
        if ($useful_query->have_posts()) :
          while ($useful_query->have_posts()) : $useful_query->the_post();
        ?>
        <a class="index_useful__item" href="<?php the_permalink(); ?>">
          <p class="index_useful__thb hover-image">
            <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('medium'); ?>
            <?php else : ?>
            <img src="https://placehold.co/320x240" alt="<?php echo esc_attr(get_the_title()); ?>">
            <?php endif; ?>
          </p>
          <div class="index_useful__main">
            <div class="index_useful__contents">
              <h3 class="index_useful__title">
                <?php the_title(); ?>
              </h3>
              <p class="index_useful__arrow">
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
              </p>
            </div>
            <hr>
            <div class="index_useful__text">
              <?php the_excerpt(); ?>
            </div>
          </div>
        </a>
        <?php endwhile; wp_reset_postdata(); ?>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<?php get_footer(); ?>