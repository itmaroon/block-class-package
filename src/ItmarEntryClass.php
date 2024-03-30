<?php

namespace Itmar\BlockClassPakage;

class ItmarEntryClass
{
  function block_init($text_domain, $file_path)
  {
    //ブロックの登録
    foreach (glob(plugin_dir_path($file_path) . 'build/blocks/*') as $block) {
      $block_name = basename($block);
      $script_handle = 'itmar-handle-' . $block_name;
      $script_file = plugin_dir_path($file_path) . 'build/blocks/' . $block_name . '/index.js';
      // スクリプトの登録
      wp_register_script(
        $script_handle,
        plugins_url('build/blocks/' . $block_name . '/index.js', $file_path),
        array('wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor'),
        filemtime($script_file)
      );

      // ブロックの登録
      register_block_type(
        $block,
        array(
          'editor_script' => $script_handle
        )
      );

      // その後、このハンドルを使用してスクリプトの翻訳をセット
      wp_set_script_translations($script_handle, $text_domain, plugin_dir_path($file_path) . 'languages');
      //jsで使えるようにhome_urlをローカライズ
      $js_name = str_replace("-", "_", $text_domain);
      wp_localize_script($script_handle, $js_name, array(
        'home_url' => home_url(),
        'plugin_url' => plugins_url('', $file_path)
      ));
    }

    //PHP用のテキストドメインの読込（国際化）
    load_plugin_textdomain($text_domain, false, basename(dirname($file_path)) . '/languages');
  }

  // 依存関係のチェック関数

  function check_dependencies($text_domain, $pluin_slug)
  {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php'); //is_plugin_active() 関数の使用

    $required_plugins = [$pluin_slug]; // 依存するプラグインのスラッグ
    $ret_obj = null; //インストールされているかの通知オブジェクト

    foreach ($required_plugins as $plugin) {
      $plugin_path = WP_PLUGIN_DIR . '/' . $plugin;
      if (!is_plugin_active($plugin . '/' . $plugin . '.php')) {
        if (file_exists($plugin_path)) {
          // プラグインはインストールされているが有効化されていない
          $plugin_file = $plugin . '/' . $plugin . '.php';
          $activate_url = wp_nonce_url(admin_url('plugins.php?action=activate&plugin=' . $plugin_file), 'activate-plugin_' . $plugin_file);
          $link = __("Activate Plugin", $text_domain);
          $message = 'Form Send Blocks:' . __("Required plugin is not active.", $text_domain);
          $ret_obj = array("message" => $message, "link" => $link, "url" => $activate_url);
        } else {
          // プラグインがインストールされていない
          $install_url = admin_url('plugin-install.php?s=' . $plugin . '&tab=search&type=term');
          $link = __("Install Plugin", $text_domain);
          $message = 'Form Send Blocks:' . __("Required plugin is not installed.", $text_domain);
          $ret_obj = array("message" => $message, "link" => $link, "url" => $install_url);
        }
        return $ret_obj;
      }
    }
    return $ret_obj;
  }
}
