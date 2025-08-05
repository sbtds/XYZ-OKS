export function NavigationDrawer() {
  const handler = () => {
    let trigger;
    let body;
    let globalNav;
    let focusableEls;
    let firstFocusableEl;
    let lastFocusableEl;
    let currentTrigger;

    function _setInit() {
      body = document.body;
      trigger = document.querySelectorAll('.common_trigger');
      globalNav = document.querySelector('.common_global');
      
      // フォーカス可能な要素のセレクタ
      const focusableSelectors = 'a[href], button, input, textarea, select, [tabindex]:not([tabindex="-1"])';
      
      if (globalNav) {
        // モバイルメニュー内のフォーカス可能な要素を取得
        focusableEls = globalNav.querySelectorAll(focusableSelectors);
        if (focusableEls.length > 0) {
          firstFocusableEl = focusableEls[0];
          lastFocusableEl = focusableEls[focusableEls.length - 1];
        }
      }
    }

    function _setTrigger() {
      if (trigger) {
        trigger.forEach((el) => {
          document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && body.classList.contains('drawer-active')) {
              _triggerFunction(el);
            }
          });
          
          el.addEventListener('click', function() {
            currentTrigger = this;
            _triggerFunction(this);
          });
        });
      }
      
      // フォーカストラップの設定
      document.addEventListener('keydown', function(e) {
        if (!body.classList.contains('drawer-active')) return;
        
        // Tab キーのフォーカス制御
        if (e.key === 'Tab') {
          // Shift + Tab で最初の要素にフォーカスがある場合、最後の要素にフォーカスを移動
          if (e.shiftKey && document.activeElement === firstFocusableEl) {
            e.preventDefault();
            lastFocusableEl.focus();
          }
          // Tab で最後の要素にフォーカスがある場合、最初の要素にフォーカスを移動
          else if (!e.shiftKey && document.activeElement === lastFocusableEl) {
            e.preventDefault();
            firstFocusableEl.focus();
          }
        }
      });
    }

    function _triggerFunction(triggerEl) {
      if (body.classList.contains('drawer-active')) {
        // メニューを閉じる時の処理
        globalNav.style.transition = 'opacity 0.3s ease, visibility 0s ease 0.3s';
        globalNav.style.opacity = '0';
        
        // aria-expanded 属性を更新
        if (triggerEl) triggerEl.setAttribute('aria-expanded', 'false');
        // aria-label も更新
        if (triggerEl) triggerEl.setAttribute('aria-label', 'モバイルメニューを開く');

        // アニメーションが終わったらクラスを削除
        setTimeout(() => {
          body.classList.remove('drawer-active');
          globalNav.style.visibility = 'hidden';
          
          // メニューが閉じられたらフォーカスをトリガーボタンに戻す
          if (currentTrigger) {
            currentTrigger.focus();
          }
        }, 300); // 300ms後（トランジション完了後）にクラスを削除
      } else {
        // メニューを開く時の処理
        globalNav.style.transition = 'opacity 0.3s ease, visibility 0s';
        globalNav.style.opacity = '1';
        globalNav.style.visibility = 'visible';
        body.classList.add('drawer-active');
        
        // aria-expanded 属性を更新
        if (triggerEl) triggerEl.setAttribute('aria-expanded', 'true');
        // aria-label も更新
        if (triggerEl) triggerEl.setAttribute('aria-label', 'モバイルメニューを閉じる');
        
        // 最初のフォーカス可能な要素にフォーカスを移動
        setTimeout(() => {
          if (firstFocusableEl) {
            firstFocusableEl.focus();
          }
        }, 100);
      }
    }

    return (function () {
      _setInit();
      _setTrigger();
      
      // トリガーボタンの初期状態を設定
      trigger.forEach((el) => {
        el.setAttribute('aria-expanded', 'false');
      });
    })();
  };

  window.addEventListener('load', () => {
    handler();
  });
}

NavigationDrawer();
