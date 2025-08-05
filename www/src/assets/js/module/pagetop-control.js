/**
 * ページトップリンク制御
 * スクロール状態に応じて表示/非表示を切り替え、アニメーション適用
 */

document.addEventListener('DOMContentLoaded', () => {
  const pagetop = document.querySelector('.common_pagetop');

  if (!pagetop) return;

  // 初期状態: 非表示
  pagetop.classList.add('pagetop-hidden');

  // スクロール位置の閾値（px）
  const SCROLL_THRESHOLD = 500;

  // スクロールイベントリスナー
  window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > SCROLL_THRESHOLD) {
      // 閾値以上スクロールした場合、表示
      pagetop.classList.remove('pagetop-hidden');
      pagetop.classList.add('pagetop-visible');
    } else {
      // 閾値未満の場合、非表示
      pagetop.classList.remove('pagetop-visible');
      pagetop.classList.add('pagetop-hidden');
    }
  });

  // 初回チェック
  setTimeout(() => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > SCROLL_THRESHOLD) {
      pagetop.classList.remove('pagetop-hidden');
      pagetop.classList.add('pagetop-visible');
    }
  }, 100);
});
