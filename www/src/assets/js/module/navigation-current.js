export function NavigationCurrentClass() {
  document.addEventListener('DOMContentLoaded', function () {
    const navList = document.querySelector('.common_header__nav_list');
    if (!navList) return;

    const anchors = navList.querySelectorAll('a');
    const currentUrl = window.location.href;

    anchors.forEach(function (anchor) {
      const anchorHref = anchor.getAttribute('href');

      if (anchorHref !== null && anchorHref !== '#') {
        // 相対パスの処理
        if (anchorHref.indexOf('/') === 0) {
          const absoluteHref = new URL(anchorHref, window.location.origin).href;
          if (absoluteHref === currentUrl) {
            anchor.classList.add('navigation_current');
          }
        } else {
          // 絶対パスの処理
          try {
            const absoluteHref = new URL(anchorHref).href;
            if (absoluteHref === currentUrl) {
              anchor.classList.add('navigation_current');
            }
          } catch (e) {
            // 無効なURLの場合はエラーをログに記録しますが、スクリプトの実行を続行します。
            // console.warn(`Invalid URL: ${anchorHref}`, e);
          }
        }
      }
    });
  });
}

NavigationCurrentClass();
