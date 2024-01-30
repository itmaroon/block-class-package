<?php
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

  function add_enqueue() {
    //jquery-easingを読み込む
    if (!wp_script_is('itmar_jquery_easing', 'enqueued')) {
      wp_enqueue_script( 'itmar_jquery_easing', plugins_url('assets/jquery.easing.min.js', __FILE__ ), array('jquery' ), true );
    }
    //vegasを読み込む
    if (!wp_script_is('itmar_vegas_js', 'enqueued')) {
      wp_enqueue_script('itmar_vegas_js', plugins_url('assets/vegas.min.js', __FILE__ ), array('jquery'), true);
    }
    
    if (!wp_style_is('itmar_vegas_css', 'enqueued')) {
        wp_enqueue_style('itmar_vegas_css', plugins_url('assets/vegas.min.css', __FILE__));
    }

    //swiperを読み込む
    if (!wp_script_is('itmar_swiper_js', 'enqueued')) {
      wp_enqueue_script('itmar_swiper_js', plugins_url('assets/swiper-bundle.min.js', __FILE__ ), array('jquery'), true);
    }
    
    if (!wp_style_is('itmar_swiper_css', 'enqueued')) {
        wp_enqueue_style('itmar_swiper_css', plugins_url('assets/swiper-bundle.min.css', __FILE__));
    }
  }

  function add_front_enqueue(){
    if(!is_admin()){
      //独自jsのエンキュー
      $script_path = plugin_dir_path(__FILE__) . 'assets/mvBlocks.js';
      wp_enqueue_script(
        'itmar_mv_blocks_js',
        plugins_url('/assets/mvBlocks.js', __FILE__),
        array('jquery','wp-i18n'),
        filemtime($script_path),
        true
      );
      //jsで使えるようにhome_urlをローカライズ
      wp_localize_script('itmar_mv_blocks_js', 'itmar_block_option', array(
        'home_url' => home_url(),
        'plugin_url' => plugins_url('', __FILE__)
      ));
    }
  }
}