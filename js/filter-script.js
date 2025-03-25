jQuery(document).ready(function ($) {
  // 1. Categoriesのクリアボタン
  $("#clear-filters")
    .off("click")
    .on("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      var categoryCheckboxes = $('input[name="category[]"]');
      categoryCheckboxes.each(function () {
        $(this).prop("checked", false);
      });
      return false;
    });

  // 2. リージョンのクリアボタン
  $("#clear-regions")
    .off("click")
    .on("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      var regionCheckboxes = $('input[name="region[]"]');
      regionCheckboxes.each(function () {
        $(this).prop("checked", false);
      });
      return false;
    });

  // 3. カントリーのクリアボタン
  $("#clear-countries")
    .off("click")
    .on("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      var countryCheckboxes = $('input[name="country[]"]');
      countryCheckboxes.each(function () {
        $(this).prop("checked", false);
      });
      return false;
    });

  // フォーム送信時の処理
  $("form").on("submit", function () {
    localStorage.setItem("scrollToResults", "true");
    const isBuyerPage = $("#buyer-list").length > 0;
    localStorage.setItem(
      "resultListId",
      isBuyerPage ? "buyer-list" : "product-list"
    );
  });

  // ページロード時のスクロール処理
  if (localStorage.getItem("scrollToResults") === "true") {
    localStorage.removeItem("scrollToResults");
    const resultListId = localStorage.getItem("resultListId") || "product-list";
    localStorage.removeItem("resultListId");

    if (window.location.search) {
      const targetElement = $("#" + resultListId);
      if (targetElement.length > 0) {
        $("html, body").animate(
          {
            scrollTop: targetElement.offset().top - 100,
          },
          500
        );
      }
    }
  }
});
