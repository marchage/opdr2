<?php
namespace Joke_O_Matic;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Joke_Shortcode {
    
    public static function init() {
        add_shortcode( 'joke-o-matic', [ __CLASS__, 'render_shortcode' ] );
    }

    public static function render_shortcode( $atts ) {
        $atts = shortcode_atts( [
            'count' => 6,
            'reveal' => 'flip', // flip, fade, slide
        ], $atts, 'joke-o-matic' );

        $count = max( 1, min( 24, absint( $atts['count'] ) ) );
        $reveal = sanitize_key( $atts['reveal'] );
        if ( ! in_array( $reveal, [ 'flip', 'fade', 'slide' ] ) ) {
            $reveal = 'flip';
        }

        // Enqueue assets for shortcode
        wp_enqueue_style( 'joke-o-matic' );
        wp_enqueue_script( 'joke-o-matic' );

        $jokes = Joke_Provider::get_jokes( $count );

        ob_start();
        echo '<div class="jom-grid" data-reveal="' . esc_attr( $reveal ) . '">';
        if ( is_wp_error( $jokes ) ) {
            echo '<p>' . esc_html__( 'Could not load jokes at this time.', 'joke-o-matic' ) . '</p>';
        } else {
            foreach ( (array) $jokes as $joke ) {
                $setup = isset( $joke['setup'] ) ? $joke['setup'] : '';
                $punch = isset( $joke['punchline'] ) ? $joke['punchline'] : '';
                echo '<div class="jom-card" tabindex="0" role="button" aria-expanded="false">';
                echo '  <div class="jom-card__inner">';
                echo '    <div class="jom-card__front">';
                echo '      <div class="jom-card__setup">' . esc_html( $setup ) . '</div>';
                echo '      <button class="jom-reveal" type="button" aria-label="' . esc_attr__( 'Reveal punchline', 'joke-o-matic' ) . '">' . esc_html__( 'Reveal', 'joke-o-matic' ) . '</button>';
                echo '    </div>';
                echo '    <div class="jom-card__back">';
                echo '      <div class="jom-card__punchline">' . esc_html( $punch ) . '</div>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
            }
        }
        echo '</div>';
        return ob_get_clean();
    }
}
