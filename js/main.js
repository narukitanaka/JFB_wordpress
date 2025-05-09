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
        el: ".swiper-pagination",
        clickable: true,
      },
      navigation: {
        nextEl: ".kv-next",
        prevEl: ".kv-prev",
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
        nextEl: ".popular-next",
        prevEl: ".popular-prev",
      },
    });
  }
}

document.addEventListener("DOMContentLoaded", function () {
  initSwipers();
});

///////////////////////////////////////////
//swiperのaタグ不具合の対策
//////////////////////////////////////////
document.querySelectorAll(".swiper-slide a").forEach(function (link) {
  // 元のhref属性を保存
  const originalHref = link.getAttribute("href");
  if (originalHref && originalHref !== "#") {
    // hrefを一時的に削除し、data属性に保存
    link.setAttribute("data-href", originalHref);
    link.removeAttribute("href");
    // クリックイベントで手動遷移
    link.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      // 保存したURLに遷移
      window.location.href = this.getAttribute("data-href");
    });
  }
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

////////////////////////////////////////////////////////////////////////////////////////
// ヘッダー アカウントメニュー
///////////////////////////////////////////////////////////////////////////////////////
$(".user .acc-menu").hide();
$(".user button").on("click", function (e) {
  $(this).siblings(".acc-menu").slideToggle("fast");
  $(this).toggleClass("open");
});
// メニュー外クリックで閉じる
$(document).on("click", function (e) {
  if (!$(e.target).closest(".user").length) {
    $(".user .acc-menu").slideUp("fast");
    $(".user button").removeClass("open");
  }
});

////////////////////////////////////////////////////////////////////////////////////////
// .wpmem_msgの要素でCongratulationsやsuccessの文字を含む場合、文字色を緑に変更
///////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
  $(
    '.wpmem_msg:contains("Congratulations"), .wpmem_msg:contains("Success"), .wpmem_msg:contains("success"), .wpmem_msg:contains("おめでとう"), .wpmem_msg:contains("成功")'
  ).css("color", "#28a745");
});

////////////////////////////////////////////////////////////////////////////////////////
// .wpmem_msgの要素でERRORやfailedの文字を含む場合、文字色を赤に変更
///////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
  $(
    '.wpmem_msg:contains("ERROR"), .wpmem_msg:contains("erroe"), .wpmem_msg:contains("faile"), .wpmem_msg:contains("Faile"), .wpmem_msg:contains("失敗")'
  ).css("color", "#d93924");
});

////////////////////////////////////////////////////////////////////////////////////////
// GSAP アニメーション
///////////////////////////////////////////////////////////////////////////////////////
// フェードイン
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
