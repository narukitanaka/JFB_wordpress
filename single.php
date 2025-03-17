<?php get_header(); ?>
  <main>

  <div class="contents">
    <?php the_content(); ?>
<?php
static $test_executed = false;

// 1回だけ実行する
if (!$test_executed) {
    $test_executed = true;
    
    // テスト用：閲覧数を手動で更新
    global $post;
    $post_id = $post->ID;
    $views = get_post_meta($post_id, 'post_views_count', true);
    $views = $views ? intval($views) + 1 : 1;
    update_post_meta($post_id, 'post_views_count', $views);
    echo "<div style='background: #f8f8f8; padding: 10px; margin: 10px;'>";
    echo "Post ID: {$post_id}<br>";
    echo "Updated View Count: {$views}<br>";
    echo "Test execution: 1";
    echo "</div>";
}
?>

  </main>
<?php get_footer(); ?>