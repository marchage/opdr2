<?php
/**
 * Plugin Name:       Joke-o-Matic
 * Description:       Display jokes in a styled card grid with punchline reveal. Works as shortcode [joke-o-matic] or Elementor widget.
 * Version:           1.0.0
 * Author:            Your Name
 * Text Domain:       joke-o-matic
 * Domain Path:       /languages
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants.
define( 'JOKE_O_MATIC_VERSION', '1.0.0' );
define( 'JOKE_O_MATIC_FILE', __FILE__ );
define( 'JOKE_O_MATIC_DIR', plugin_dir_path( __FILE__ ) );
define( 'JOKE_O_MATIC_URL', plugin_dir_url( __FILE__ ) );

// Load textdomain for translations.
add_action( 'init', function () {
    load_plugin_textdomain( 'joke-o-matic', false, dirname( plugin_basename( JOKE_O_MATIC_FILE ) ) . '/languages' );
} );

// Register assets (styles and scripts) once.
add_action( 'init', function () {
    wp_register_style( 'joke-o-matic', JOKE_O_MATIC_URL . 'assets/css/joke-o-matic.css', [], JOKE_O_MATIC_VERSION );
    wp_register_script( 'joke-o-matic', JOKE_O_MATIC_URL . 'assets/js/joke-o-matic.js', [ 'jquery' ], JOKE_O_MATIC_VERSION, true );
} );

// Always load core classes
require_once JOKE_O_MATIC_DIR . 'includes/class-joke-provider.php';

// Initialize shortcode (works without Elementor)
add_action( 'init', function () {
    require_once JOKE_O_MATIC_DIR . 'includes/class-joke-shortcode.php';
    \Joke_O_Matic\Joke_Shortcode::init();
} );

// Elementor integration (optional)
add_action( 'plugins_loaded', function () {
    if ( did_action( 'elementor/loaded' ) ) {
        add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
            require_once JOKE_O_MATIC_DIR . 'includes/class-joke-o-matic-widget.php';
            $widgets_manager->register( new \Joke_O_Matic\Widget_Joke_O_Matic() );
        } );
    }
} );
