// 子カテゴリーを選択したら親カテゴリーを自動で選択
wp.domReady(function () {
  const { subscribe } = wp.data;

  let previousCheckedCategories = [];

  subscribe(() => {
    const checkedCategories = wp.data
      .select("core/editor")
      .getEditedPostAttribute("categories");

    if (
      JSON.stringify(previousCheckedCategories) !==
      JSON.stringify(checkedCategories)
    ) {
      previousCheckedCategories = checkedCategories;

      checkedCategories.forEach((categoryId) => {
        wp.apiFetch({ path: `/wp/v2/categories/${categoryId}` }).then(
          (category) => {
            if (
              category.parent !== 0 &&
              !checkedCategories.includes(category.parent)
            ) {
              wp.data.dispatch("core/editor").editPost({
                categories: [...checkedCategories, category.parent],
              });
            }
          }
        );
      });
    }
  });
});
