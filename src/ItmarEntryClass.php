<?php
namespace Itmar\BlockClassPakage;

class ItmarEntryClass {
  function block_init($text_domain, $file_path)
  {
    //ブロックの登録
    foreach (glob(plugin_dir_path($file_path) . 'build/blocks/*') as $block) {
      $block_name = basename($block);
      $script_handle = 'itmar-handle-' . $block_name;
      $script_file = plugin_dir_path($file_path) . 'build/blocks/'.$block_name.'/index.js';
      // スクリプトの登録
      wp_register_script(
        $script_handle,
        plugins_url( 'build/blocks/'.$block_name.'/index.js', $file_path ),
        array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor' ),
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
      wp_set_script_translations( $script_handle, $text_domain, plugin_dir_path($file_path) . 'languages' );
      //jsで使えるようにhome_urlをローカライズ
      $js_name = str_replace("-", "_", $text_domain);
      wp_localize_script($script_handle, $js_name, array(
        'home_url' => home_url(),
        'plugin_url' => plugins_url('', $file_path)
      ));
      
    }

    //PHP用のテキストドメインの読込（国際化）
    load_plugin_textdomain( $text_domain, false, basename( dirname( $file_path ) ) . '/languages' );
  }
}