<?php
namespace Livegame_Settings;

defined( 'ABSPATH' ) || exit;

class Livegame_Settings {

  public static $sport_types = array(
    'soccer',
    'hokkey',
    'basketball',
    'tennis'
  );

  public static $leagues = array(
    'khl',
    'rpl',
    'fnl',
    'apl',
    'france',
    'germany',
    'spain',
    'nhl'
  );

  public static function init(){
    add_action('init', [ __CLASS__, 'lgp_register_taxonomy'], 20);
    add_action('init', [ __CLASS__, 'lgp_register_cpt'], 30);
  }

  /**
   * Регистрирует custom taxonomy
   *
   * @return void
   */
  public static function lgp_register_taxonomy(){
    //Таксономия вид спорта
    register_taxonomy('sports', array('teams'), array(
      'label'                 => __( 'Sport types', 'socialbet' ),
      'labels'                => array(
        'name'                => __( 'Виды спорта', 'socialbet' ),
        'singular_name'       => __( 'Вид спорта', 'socialbet' ),
        'search_items'        => __( 'Наити вид спорта', 'socialbet' ),
        'all_items'           => __( 'Все виды спорта', 'socialbet' ),
        'view_item '          => __( 'Просмотреть вид спорта', 'socialbet' ),
        'parent_item'         => __( 'Родительский вид спорта', 'socialbet' ),
        'parent_item_colon'   => __( 'Родительский вид спорта:', 'socialbet' ),
        'edit_item'           => __( 'Редактировать вид спорта', 'socialbet' ),
        'update_item'         => __( 'Обновить вид спорта', 'socialbet' ),
        'add_new_item'        => __( 'Добавить вид спорта', 'socialbet' ),
        'new_item_name'       => __( 'Название вида спорта', 'socialbet' ),
        'menu_name'           => __( 'Виды спорта', 'socialbet' ),
      ),
      'description'           => __( 'Название вида спорта', 'socialbet' ),
      'public'                => true,
      'publicly_queryable'    => true, // равен аргументу public
      'show_in_nav_menus'     => true, // равен аргументу public
      'show_ui'               => true, // равен аргументу public
      'show_in_menu'          => true, // равен аргументу show_ui
      'show_tagcloud'         => true, // равен аргументу show_ui
      'show_in_rest'          => true, // добавить в REST API
      'hierarchical'          => true,
      'rewrite'               => true,
      'capabilities'          => array(),
      'meta_box_cb'           => null, // callback функция. Отвечает за html код метабокса (с версии 3.8): post_categories_meta_box или post_tags_meta_box. Если указать false, то метабокс будет отключен вообще
      'show_admin_column'     => true, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
      //'_builtin'              => false,
      'show_in_quick_edit'    => null, // по умолчанию значение show_ui
    ) );

  }

  /**
   * Регистрирует custom post type
   *
   * @return void
   */
  public static function lgp_register_cpt(){
    register_post_type( 'teams', [
        'label'  => null,
        'labels' => [
          'name'               => 'Команды',
          'singular_name'      => 'Команда',
          'add_new'            => 'Добавить новую команду',
          'add_new_item'       => 'Добавление команды',
          'edit_item'          => 'Редактирование команды',
          'new_item'           => 'Новая команда',
          'view_item'          => 'Смотреть команду',
          'search_items'       => 'Искать команду',
          'not_found'          => 'Не найдено',
          'not_found_in_trash' => 'Не найдено в корзине',
          'menu_name'          => 'Команды',
        ],
        'show_in_rest'        => true,
        'description'         => '',
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'menu_icon'           => 'dashicons-chart-pie',
        'hierarchical'        => false,
        'supports'            => array('title','editor','comments','author', 'thumbnail', 'excerpt', 'custom-fields', 'comments'),
        'taxonomies'          => array('sports'),
        'has_archive'         => 'teams',
        'query_var'           => true,
        'exclude_from_search' => false,
      ]
    );
  }

}

Livegame_Settings::init();
