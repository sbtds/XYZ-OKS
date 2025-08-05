export function SmoothScroll() {
  const header = document.querySelector('header');
  const headerHeight = header ? header.offsetHeight : 0;
  const anchorLinks = [...document.querySelectorAll('a[href*="#"].link_scroll')];

  function scrollToElement(targetElementId) {
    const targetElement = document.getElementById(targetElementId);
    if (targetElement) {
      const rect = targetElement.getBoundingClientRect().top;
      const offset = window.pageYOffset;
      const gap = 80;
      const target = rect + offset - gap;

      window.scrollTo({
        top: target,
        behavior: 'smooth',
      });
    }
  }

  anchorLinks.forEach((anchorLink) => {
    anchorLink.addEventListener('click', (e) => {
      const href = anchorLink.getAttribute('href');
      const hrefFirstLetter = href.charAt(0);

      if (hrefFirstLetter === '#') {
        e.preventDefault();
        const targetElementId = href.replace('#', '');

        if (href === '#' || href === '#top') {
          window.scrollTo({
            top: 0,
            behavior: 'smooth',
          });
        } else {
          scrollToElement(targetElementId);
        }
      }
    });
  });

  if (window.location.hash) {
    const targetElementId = window.location.hash.replace('#', '');
    scrollToElement(targetElementId);
  }
}

SmoothScroll();
