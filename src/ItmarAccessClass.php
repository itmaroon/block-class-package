<?php

namespace Itmar\BlockClassPackage;

class ItmarAccessClass
{
  /**
   * アクセス数を取得
   *
   */

  function get_post_count($id = 0)
  {
    global $post;
    if ($id === 0) {
      $id = $post->ID;
    }

    $count_key = 'view_counter';
    $count = get_post_meta($id, $count_key, true);
    if ($count === '') {
      delete_post_meta($id, $count_key);
      add_post_meta($id, $count_key, '0');
    }
    return $count;
  }

  /**
   * アクセス数をセット
   *
   */

  function set_post_count()
  {
    // 管理画面や特定の条件では実行しない
    if (is_admin() || is_feed() || is_robots() || is_trackback()) {
      return;
    }
    if (is_singular()) {
      global $post;
      $count = 0;
      $count_key = 'view_counter';

      if ($post) {
        $id = $post->ID;
        $count = get_post_meta($id, $count_key, true);
      }

      if ($count === '') {
        delete_post_meta($id, $count_key);
        add_post_meta($id, $count_key, '1');
      } elseif ($count > 0) {
        $count++;
        update_post_meta($id, $count_key, $count);
      }
    }
  }
}
