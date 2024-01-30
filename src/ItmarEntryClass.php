<?php
namespace Itmar\BlockClassPakage;

class ItmarEntryClass {
  function block_init($text_domain)
  {
    //ブロックの登録
    foreach (glob(plugin_dir_path(__FILE__) . 'build/blocks/*') as $block) {
      $block_name = basename($block);
      $script_handle = 'itmar-handle-' . $block_name;
      $script_file = plugin_dir_path( __FILE__ ) . 'build/blocks/'.$block_name.'/index.js';
      // スクリプトの登録
      wp_register_script(
        $script_handle,
        plugins_url( 'build/blocks/'.$block_name.'/index.js', __FILE__ ),
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
      wp_set_script_translations( $script_handle, $text_domain, plugin_dir_path( __FILE__ ) . 'languages' );
      //jsで使えるようにhome_urlをローカライズ
      wp_localize_script($script_handle, 'itmar_block_option', array(
        'home_url' => home_url(),
        'plugin_url' => plugins_url('', __FILE__)
      ));
      
    }

    //PHP用のテキストドメインの読込（国際化）
    load_plugin_textdomain( $text_domain, false, basename( dirname( __FILE__ ) ) . '/languages' );
  }
}