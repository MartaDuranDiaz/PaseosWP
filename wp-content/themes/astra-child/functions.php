<?php
// Seguridad básica
if ( !defined( 'ABSPATH' ) ) exit;

// Cargar estilos del hijo después del padre
add_action('wp_enqueue_scripts', function () {

  wp_enqueue_style(
    'astra-child',
    get_stylesheet_directory_uri() . '/style.css',
    ['astra-theme-css'], // dependencia del CSS de Astra
    wp_get_theme()->get('Version')
  );

}, 10);

function ps_enqueue_paseos_styles() {

    if ( is_singular('paseos') ) {

        wp_enqueue_style(
            'ps-paseos-style',
            get_stylesheet_directory_uri() . '/assets/css/paseos.css',
            array(),
            '1.0'
        );

    }

}
add_action('wp_enqueue_scripts', 'ps_enqueue_paseos_styles');

function ps_enqueue_paseos_archive_styles() {

  // Carga SOLO en el archivo /paseos/
  if ( is_post_type_archive('paseos') ) {
    wp_enqueue_style(
      'ps-paseos-archive-style',
      get_stylesheet_directory_uri() . '/assets/css/paseos-archivo.css',
      array(),
      '1.0'
    );
  }

}
add_action('wp_enqueue_scripts', 'ps_enqueue_paseos_archive_styles');

function rs_enqueue_restaurante_styles() {

    if ( is_singular('restaurante') ) {

        wp_enqueue_style(
            'rs-restaurante-style',
            get_stylesheet_directory_uri() . '/assets/css/restaurante.css',
            array(),
            '1.0'
        );

    }

}
add_action('wp_enqueue_scripts', 'rs_enqueue_restaurante_styles');

function rs_enqueue_restaurante_archive_styles() {

    // Carga SOLO en el archivo /restaurante/
    if ( is_post_type_archive('restaurante') ) {

        wp_enqueue_style(
            'rs-restaurante-archive-style',
            get_stylesheet_directory_uri() . '/assets/css/restaurante-archive.css',
            array(),
            '1.0'
        );

    }

}
add_action('wp_enqueue_scripts', 'rs_enqueue_restaurante_archive_styles');

function rs_restaurante_template() {

    $post_type_object = get_post_type_object('restaurante');

    if ( ! $post_type_object ) return;

    $post_type_object->template = array(

        array('core/paragraph', array(
            'placeholder' => 'Escribe aquí la descripción del restaurante...'
        )),

        array('core/heading', array(
            'level' => 2,
            'content' => 'Tipo de cocina'
        )),
        array('core/paragraph', array(
            'placeholder' => 'Ej: Cocina andaluza tradicional'
        )),

        array('core/heading', array(
            'level' => 2,
            'content' => 'Precio medio'
        )),
        array('core/paragraph', array(
            'placeholder' => 'Ej: 25€ por persona'
        )),

        array('core/heading', array(
            'level' => 2,
            'content' => 'Ubicación'
        )),
        array('core/paragraph', array(
            'placeholder' => 'Dirección completa del restaurante'
        )),

        array('core/heading', array(
            'level' => 2,
            'content' => 'Horario'
        )),
        array('core/paragraph', array(
            'placeholder' => 'Ej: Lunes a domingo de 13:00 a 23:30'
        )),

        array('core/heading', array(
            'level' => 2,
            'content' => 'Incluye'
        )),
        array('core/list'),

        array('core/heading', array(
            'level' => 2,
            'content' => 'No incluye'
        )),
        array('core/list')

    );

}
add_action('init', 'rs_restaurante_template');