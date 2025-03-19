jQuery(document).ready(function ($) {
  // カテゴリーのクリアボタン
  $("#clear-filters").on("click", function (e) {
    e.preventDefault();
    console.log("Categories clear button clicked"); // デバッグ用
    $('input[name="category[]"]').prop("checked", false);
  });

  // リージョンのクリアボタン
  $("#clear-regions").on("click", function (e) {
    e.preventDefault();
    console.log("Region clear button clicked"); // デバッグ用
    $('input[name="region[]"]').prop("checked", false);
  });

  // フォーム送信時に product-list へスクロール
  $("form").on("submit", function () {
    // フォームが送信される前にローカルストレージにフラグを設定
    localStorage.setItem("scrollToResults", "true");
  });

  // ページロード時にスクロールするかチェック
  if (localStorage.getItem("scrollToResults") === "true") {
    // フラグをクリア
    localStorage.removeItem("scrollToResults");

    // URLにパラメータが含まれている場合はスクロール（検索結果がある場合）
    if (window.location.search) {
      $("html, body").animate(
        {
          scrollTop: $("#product-list").offset().top - 100,
        },
        500
      );
    }
  }
});
