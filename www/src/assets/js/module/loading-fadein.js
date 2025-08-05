import './loading-fadein.scss';

function LoadingFadeIn() {
  const loadingContainer = document.getElementById('loading_container');
  const mainContainer = document.getElementById('main_container');
  const isHomePage = document.querySelector('body').classList.contains('home');

  if (loadingContainer && mainContainer) {
    if (isHomePage && !localStorage.getItem('homeLoaded')) {
      loadingContainer.style.display = 'flex';
      window.addEventListener('load', function () {
        setTimeout(() => {
          loadingContainer.classList.add('fade-out');
          mainContainer.classList.add('fade-in');
          setTimeout(() => {
            loadingContainer.style.display = 'none';
          }, 800);
        }, 2000);
      });
      localStorage.setItem('homeLoaded', 'true');
    } else {
      mainContainer.classList.add('fade-in');
    }
  }
}

LoadingFadeIn();
