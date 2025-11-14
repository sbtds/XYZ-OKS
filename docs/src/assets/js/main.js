console.log("OKS Theme Documentation loaded");

function initializeApp() {
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll("nav a");

  navLinks.forEach((link) => {
    const linkPath = link.getAttribute("href");
    if (currentPath.includes(linkPath.replace("./", ""))) {
      link.classList.add("bg-gray-700", "font-semibold");
    } else {
      link.classList.remove("bg-gray-700", "font-semibold");
    }
  });

  const menuToggle = document.getElementById("menu-toggle");
  const mobileMenu = document.getElementById("mobile-menu");
  const menuClose = document.getElementById("menu-close");

  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener("click", function () {
      mobileMenu.classList.remove("-translate-x-full");
    });
  }

  if (menuClose && mobileMenu) {
    menuClose.addEventListener("click", function () {
      mobileMenu.classList.add("-translate-x-full");
    });
  }

  if (mobileMenu) {
    mobileMenu.addEventListener("click", function (e) {
      if (e.target === mobileMenu) {
        mobileMenu.classList.add("-translate-x-full");
      }
    });
  }

  document.querySelectorAll(".submenu-toggle").forEach((button) => {
    const submenu = button.nextElementSibling;
    const icon = button.querySelector(".submenu-icon");
    const submenuLinks = submenu.querySelectorAll("a");
    
    let isCurrentPage = false;
    submenuLinks.forEach((link) => {
      const linkPath = link.getAttribute("href");
      if (currentPath.includes(linkPath.split("#")[0].replace("./", ""))) {
        isCurrentPage = true;
      }
    });

    if (isCurrentPage) {
      submenu.classList.remove("hidden");
      icon.classList.add("rotate-180");
    }

    button.addEventListener("click", () => {
      submenu.classList.toggle("hidden");
      icon.classList.toggle("rotate-180");
    });
  });

  const trigger = document.getElementById("trigger");
  const sidebar = document.getElementById("sidebar");
  const closeSidebar = document.getElementById("closeSidebar");

  if (trigger && sidebar) {
    trigger.addEventListener("click", () => {
      sidebar.classList.remove("hidden");
      setTimeout(() => {
        sidebar.classList.remove("-translate-x-full");
      }, 10);
    });
  }

  if (closeSidebar && sidebar) {
    closeSidebar.addEventListener("click", () => {
      sidebar.classList.add("-translate-x-full");
      setTimeout(() => {
        sidebar.classList.add("hidden");
      }, 300);
    });
  }
}

document.addEventListener("includesLoaded", initializeApp);
document.addEventListener("DOMContentLoaded", function () {
  if (document.querySelector("[data-include]")) {
    return;
  }
  initializeApp();
});
