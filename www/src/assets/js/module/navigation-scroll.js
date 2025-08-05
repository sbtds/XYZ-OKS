export function NavigationScroll() {
  console.log("NavigationScroll");

  const handler = () => {
    let body;
    let window_height;
    let window_gap = 0.95;

    function _setInit() {
      body = document.body;
      window_height = window.innerHeight * window_gap;
    }
    function _setTrigger() {
      window.addEventListener("load", _setInit, false);
      window.addEventListener("resize", _setInit, false);
      window.addEventListener("scroll", _triggerFunction, false);
    }
    function _triggerFunction() {
      let distance = window.scrollY;

      if (window_height <= distance) {
        body.classList.add("common_menu_scroll");
        body.classList.add("window-scrolled");
      } else {
        body.classList.remove("common_menu_scroll");
        body.classList.remove("window-scrolled");
      }
    }
    return (function () {
      _setInit();
      _setTrigger();
    })();
  };

  window.addEventListener("load", () => {
    handler();
  });
}

NavigationScroll();
