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

  //update.phpページの画像の切り替え設定
  const $largeImage = document.querySelector(".main-image img");
  const $smallImage = document.querySelectorAll(".sub-images img");

  $smallImage.forEach((small) => {
    small.addEventListener("click", () => {
      $largeImage.setAttribute("src", small.getAttribute("src"));
    });
  });
})();
