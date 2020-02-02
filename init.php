<?php
namespace LGP\Livegamepro;

/*
Plugin Name: LiveGame.pro Statistics
Description: Спортивная статистика, для PRO игроков  в лайве
Author: Evgeniy Rezanov
Author URI: https://evgrezanov.github.io/
Version: 1.4
*/

defined('ABSPATH') || exit;


/**
 * Core
 */
class Livegamepro_Core {

  /**
   * The init
   */
  public static function init() {

    add_action('plugins_loaded', function() {
      require_once('inc/class-rest-api-endpoint.php');
      require_once('inc/class-input-form.php');
      require_once('inc/class-table-result.php');
      require_once('inc/class-settings.php');
    }, 9999);

    add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);

  }

  public static function assets(){
    wp_enqueue_script('wp-api');
    wp_enqueue_script(
        'livegame_form',
        plugins_url('wph_sports_statics_filter/asset/formajax.js'), '',
        '1.4.2',
        true
      );
    wp_enqueue_style( 'lgp_styles', plugins_url('asset/style.css', __FILE__) );
  }

}

Livegamepro_Core::init();
