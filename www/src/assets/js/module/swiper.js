import Swiper from "swiper/bundle";
import "swiper/css/bundle";

export function Carousel() {
  window.addEventListener("load", () => {
    createSwiper();
  });

  const createSwiper = () => {
    const carousels = document.querySelectorAll(".carousel");

    carousels.forEach((element, index) => {
      // 基本設定
      let options = {
        loop: true,
        slidesPerView: 1.2,
        centeredSlides: true,
        spaceBetween: 10,
        speed: 750,
        autoplay: {
          delay: 4000,
          disableOnInteraction: false,
        },
        navigation: {},
        pagination: {
          clickable: true,
        },
      };

      // data属性から設定を取得
      const perView = element.dataset.carouselPerView;
      if (perView && perView !== "auto") {
        // レスポンシブ設定
        options.breakpoints = {
          768: {
            slidesPerView: 2,
            spaceBetween: 15,
            centeredSlides: false,
          },
          1024: {
            slidesPerView: parseInt(perView),
            spaceBetween: parseInt(element.dataset.carouselBetween) || 20,
            centeredSlides: false,
          },
        };
      }

      // スペース設定
      const spaceBetween = element.dataset.carouselBetween;
      if (spaceBetween) {
        options.spaceBetween = parseInt(spaceBetween);
      }

      // 自動再生設定
      const delay = element.dataset.carouselDelay;
      if (delay !== undefined) {
        if (parseInt(delay) === 0) {
          options.autoplay = false;
        } else {
          options.autoplay = {
            delay: parseInt(delay),
            disableOnInteraction: false,
          };
        }
      }

      // ループ設定
      const loop = element.dataset.carouselLoop;
      if (loop === "0" || loop === "false") {
        options.loop = false;
      }

      // ユニークなクラス名を追加
      const uniqueClass = `carousel-${String(index).padStart(2, "0")}`;
      element.classList.add(uniqueClass);

      // ナビゲーション設定
      const prevButton = element.querySelector(".swiper-button-prev");
      const nextButton = element.querySelector(".swiper-button-next");
      const pagination = element.querySelector(".swiper-pagination");

      if (prevButton && nextButton) {
        const prevClass = `swiper-button-prev-${String(index).padStart(2, "0")}`;
        const nextClass = `swiper-button-next-${String(index).padStart(2, "0")}`;
        prevButton.classList.add(prevClass);
        nextButton.classList.add(nextClass);
        options.navigation = {
          prevEl: `.${prevClass}`,
          nextEl: `.${nextClass}`,
        };
      }

      if (pagination) {
        const paginationClass = `swiper-pagination-${String(index).padStart(2, "0")}`;
        pagination.classList.add(paginationClass);
        options.pagination.el = `.${paginationClass}`;
      }

      // Swiperを初期化
      new Swiper(`.${uniqueClass}`, options);
    });
  };
}

Carousel();
