<?php
namespace Joke_O_Matic;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Widget_Joke_O_Matic extends Widget_Base {
    public function get_name() {
        return 'joke-o-matic';
    }

    public function get_title() {
        return __( 'Joke-o-Matic', 'joke-o-matic' );
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_style_depends() {
        return [ 'joke-o-matic' ];
    }

    public function get_script_depends() {
        return [ 'joke-o-matic' ];
    }

    protected function register_controls() {
        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'joke-o-matic' ),
        ] );

        $this->add_control( 'count', [
            'label' => __( 'Number of jokes', 'joke-o-matic' ),
            'type' => Controls_Manager::NUMBER,
            'min' => 1,
            'max' => 24,
            'step' => 1,
            'default' => 6,
        ] );

        $this->add_control( 'reveal_style', [
            'label' => __( 'Reveal animation', 'joke-o-matic' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'flip',
            'options' => [
                'flip' => __( 'Flip', 'joke-o-matic' ),
                'fade' => __( 'Fade', 'joke-o-matic' ),
                'slide' => __( 'Slide', 'joke-o-matic' ),
            ],
        ] );

        $this->end_controls_section();

        $this->start_controls_section( 'section_style', [
            'label' => __( 'Style', 'joke-o-matic' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'setup_typo',
            'label' => __( 'Setup Typography', 'joke-o-matic' ),
            'selector' => '{{WRAPPER}} .jom-card__setup',
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name' => 'punch_typo',
            'label' => __( 'Punchline Typography', 'joke-o-matic' ),
            'selector' => '{{WRAPPER}} .jom-card__punchline',
        ] );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $count = isset( $settings['count'] ) ? absint( $settings['count'] ) : 6;
        $reveal = isset( $settings['reveal_style'] ) ? sanitize_key( $settings['reveal_style'] ) : 'flip';

        $jokes = Joke_Provider::get_jokes( $count );

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
    }
}
