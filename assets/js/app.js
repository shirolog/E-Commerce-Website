(() => {
  //ヘッダーナビゲーションバー設定
  const $userBtn = document.querySelector("#user-btn");
  const $profile = document.querySelector(".header .flex .profile");
  const $menuBtn = document.querySelector("#menu-btn");
  const $navbar = document.querySelector(".header .flex  .navbar");

  $userBtn.addEventListener("click", () => {
    $profile.classList.toggle("active");
    $navbar.classList.remove("active");
    $menuBtn.classList.remove("fa-times");
  });

  $menuBtn.addEventListener("click", () => {
    $navbar.classList.toggle("active");
    $profile.classList.remove("active");
    $menuBtn.classList.toggle("fa-times");
  });

  window.addEventListener("scroll", () => {
    $profile.classList.remove("active");
    $navbar.classList.remove("active");
    $menuBtn.classList.remove("fa-times");
  });

    
  //quick_view.phpページの画像の切り替え設定
  const $largeImage = document.querySelector(".quick-view .box .image-container .big-image img");
  const $smallImage = document.querySelectorAll(".quick-view .box .image-container .small-image img");

  $smallImage.forEach((small) => {
    small.addEventListener("click", () => {
      $largeImage.setAttribute("src", small.getAttribute("src"));
    });
  });

  //swiper設定
  var swiper = new Swiper(".home-slider", {
    loop: true,
    grabCursor: true,
    pagination: {
      el: ".swiper-pagination",
    },
  });

  var swiper = new Swiper(".category-slider", {
    loop: true,
    grabCursor: true,
    spaceBetween: 20,
    pagination: {
      el: ".swiper-pagination",
    },
    breakpoints: {
        0: {
            slidesPerView: 2,          
        },
        640: {
          slidesPerView: 3,
        },
        768: {
          slidesPerView: 4,
        },
        1024: {
          slidesPerView: 5,
        },
      },
  });

  var swiper = new Swiper(".products-slider", {
    loop: true,
    grabCursor: true,
    spaceBetween: 20,
    pagination: {
      el: ".swiper-pagination",
    },
    breakpoints: {
   
        550: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      },
  });

  var swiper = new Swiper(".reviews-slider", {
    loop: true,
    grabCursor: true,
    spaceBetween: 20,
    pagination: {
      el: ".swiper-pagination",
    },
    breakpoints: {
   
        550: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      },
  });


})();
