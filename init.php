<?php
namespace LGP\Livegamepro;

/**
 * Plugin Name: LiveGame.pro Statistics API
 * Description: Спортивная статистика, для PRO игроков  в лайве
 * Plugin URI:  https://github.com/evgrezanov/livegame-pro-statistics-api
 * Author URI:  https://evgrezanov.github.io/
 * Author:      Evgeniy Rezanov
 * Version:     1.5.2
 * GitHub Plugin URI: evgrezanov/livegame-pro-statistics-api
 * GitHub Plugin URI: https://github.com/evgrezanov/livegame-pro-statistics-api
 * Text Domain: livegame-pro-statistics-api
 * Domain Path: /languages
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
      require_once('inc/class-settings.php');
    }, 9999);

    add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);

  }

  public static function assets(){
    wp_enqueue_script('wp-api');
    wp_enqueue_script(
        'livegame_form',
        plugins_url('livegame-pro-statistics-api/asset/formajax.js'), 
        '',
        '1.5.2',
        true
    );
    wp_enqueue_style( 'lgp_styles', plugins_url('asset/style.css', __FILE__) );
  }

}

Livegamepro_Core::init();