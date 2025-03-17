////////////////////////////////////////////////////////////////////////////////////////
// Swiper
///////////////////////////////////////////////////////////////////////////////////////
function initSwipers() {
  const kvSlider = document.querySelector(".top-bannerSwiper");
  if (kvSlider) {
    const kvSwiperInstance = new Swiper(kvSlider, {
      loop: true,
      speed: 1500,
      allowTouchMove: false,
      slidesPerView: 1,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination", // 変更
        clickable: true,
      },
      navigation: {
        nextEl: ".swiper-button-next", // 変更
        prevEl: ".swiper-button-prev", // 変更
      },
    });
  }

  const PopularSwiper = document.querySelector(".PopularSwiper");
  if (PopularSwiper) {
    const popularSwiperInstance = new Swiper(PopularSwiper, {
      loop: true,
      speed: 1000,
      allowTouchMove: false,
      slidesPerView: 2,
      spaceBetween: 10,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
      },
      breakpoints: {
        // 画面幅が769px以上の場合
        769: {
          slidesPerView: 5,
          spaceBetween: 20,
        },
      },
      navigation: {
        nextEl: ".swiper-button-next", // 変更
        prevEl: ".swiper-button-prev", // 変更
      },
    });
  }
}

document.addEventListener("DOMContentLoaded", function () {
  initSwipers();
});

///////////////////////////////////////////
//ハンバーガーメニュー
//////////////////////////////////////////
$(".hambager").on("click", function () {
  navOpen();
});
let navFlg = false;
function navOpen() {
  if (!navFlg) {
    $(".hamberger-wrap").addClass("is-ham-open");
    $(".drawer-menu").addClass("is-drawermenu-open");
    $("header").addClass("is-drawermenu-header");
    navFlg = true;
  } else {
    $(".hamberger-wrap").removeClass("is-ham-open");
    $(".drawer-menu").removeClass("is-drawermenu-open");
    $("header").removeClass("is-drawermenu-header");
    navFlg = false;
  }
}
// ページ内リンクをクリックしたときにメニューを閉じる
$('.drawer-menu a[href^="#"]').on("click", function () {
  if (navFlg) {
    navOpen(); // メニューが開いている場合は閉じる
  }
});

///////////////////////////////////////////
//archive-flter アコーディオン
///////////////////////////////////////////
$(document).ready(function () {
  function setAccordionState() {
    if ($(window).width() <= 768) {
      // スマホ表示の場合
      $(".sp_accordion-hide").hide(); // 初期状態で非表示
    } else {
      // PC表示の場合
      $(".sp_accordion-hide").show(); // 常に表示
    }
  }
  // 初期実行
  setAccordionState();
  $(window).resize(function () {
    setAccordionState();
  });
  // アコーディオントリガーのクリックイベント（スマホ時のみ動作）
  $(".sp_accordion-trigger").on("click", function () {
    if ($(window).width() <= 768) {
      $(this).next(".sp_accordion-hide").slideToggle("fast");
      if ($(this).hasClass("open")) {
        $(this).removeClass("open");
      } else {
        $(this).addClass("open");
      }
    }
  });
  // Clearボタンのクリックイベント
  $("#clear-filters").on("click", function (e) {
    e.stopPropagation(); // アコーディオンのトグルを防止
    $("input[type='checkbox']").prop("checked", false);
  });
});

///////////////////////////////////////////
//サイドバー アコーディオン
///////////////////////////////////////////
$(".sidebar-accordion nav").hide(); // 初期状態で非表示
$(".sidebar-accordion p").on("click", function (e) {
  $(this).next("nav").slideToggle("fast");
  if ($(this).hasClass("open")) {
    $(this).removeClass("open");
  } else {
    $(this).addClass("open");
  }
});

////////////////////////////////////////////////////////////////////////////////////////
// GSAP アニメーション
///////////////////////////////////////////////////////////////////////////////////////
// GSAPフェードイン
const textElements = document.querySelectorAll(".fadeIn");
if (textElements.length > 0) {
  textElements.forEach((element) => {
    gsap.from(element, {
      opacity: 0,
      y: 20,
      duration: 0.8,
      ease: "power2.out",
      scrollTrigger: {
        trigger: element, // 各要素をトリガーに
        start: "top 60%",
        once: true,
      },
    });
  });
}
