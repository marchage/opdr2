<?php
namespace Joke_O_Matic;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Joke_Provider {
    const TRANSIENT_KEY = 'joke_o_matic_jokes_v1';
    const TRANSIENT_TTL = 10 * MINUTE_IN_SECONDS; // Cache for 10 minutes

    // Fetch jokes from API with caching
    public static function get_jokes( $count = 6 ) {
        $count = max( 1, min( 24, absint( $count ) ) );

        $cached = get_transient( self::TRANSIENT_KEY . '_' . $count );
        if ( false !== $cached ) {
            return $cached;
        }

        $jokes = self::fetch_from_api( $count );
        if ( ! is_wp_error( $jokes ) && is_array( $jokes ) ) {
            set_transient( self::TRANSIENT_KEY . '_' . $count, $jokes, self::TRANSIENT_TTL );
        }

        return $jokes;
    }

    private static function fetch_from_api( $count ) {
        // API: https://github.com/15Dkatz/official_joke_api
    // Endpoint ideas: https://official-joke-api.appspot.com/random_ten (10 jokes)
    //                 https://official-joke-api.appspot.com/random_joke (1 joke)
    $endpoint = $count >= 10 ? 'https://official-joke-api.appspot.com/random_ten' : 'https://official-joke-api.appspot.com/random_joke';

        $jokes = [];
        if ( $count >= 10 ) {
            $response = wp_remote_get( $endpoint, [ 'timeout' => 10 ] );
            $jokes = self::normalize_response( $response );
        } else {
            for ( $i = 0; $i < $count; $i++ ) {
                $response = wp_remote_get( $endpoint, [ 'timeout' => 10 ] );
                $one = self::normalize_response( $response );
                if ( is_array( $one ) && isset( $one[0] ) ) {
                    $jokes[] = $one[0];
                }
            }
        }

        // Trim to desired count
        if ( is_array( $jokes ) ) {
            $jokes = array_slice( $jokes, 0, $count );
        }

        // Fallback if empty
        if ( empty( $jokes ) ) {
            return [
                [
                    'setup' => __( 'Why did the developer go broke?', 'joke-o-matic' ),
                    'punchline' => __( 'Because they used up all their cache.', 'joke-o-matic' ),
                ],
            ];
        }

        return $jokes;
    }

    private static function normalize_response( $response ) {
        if ( is_wp_error( $response ) ) {
            return [];
        }
        $code = wp_remote_retrieve_response_code( $response );
        if ( 200 !== $code ) {
            return [];
        }
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        if ( ! $data ) {
            return [];
        }
        // Normalize to array of jokes with 'setup' and 'punchline'
        if ( isset( $data['setup'] ) ) {
            return [
                [
                    'setup' => sanitize_text_field( wp_strip_all_tags( $data['setup'] ) ),
                    'punchline' => sanitize_text_field( wp_strip_all_tags( $data['punchline'] ?? '' ) ),
                ],
            ];
        }
        $out = [];
        foreach ( (array) $data as $joke ) {
            if ( isset( $joke['setup'] ) ) {
                $out[] = [
                    'setup' => sanitize_text_field( wp_strip_all_tags( $joke['setup'] ) ),
                    'punchline' => sanitize_text_field( wp_strip_all_tags( $joke['punchline'] ?? '' ) ),
                ];
            }
        }
        return $out;
    }
}
