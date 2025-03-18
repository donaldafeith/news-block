<?php
/**
 * Plugin Name: News Tech Block
 * Description: A Gutenberg block to display tech news.
 * Version: 0.1.0
 * Author: Donalda Feith
 */

// To define your API key securely, add the following line to your wp-config.php:
// define( 'NEWS_TECH_BLOCK_API_KEY', 'your-api-key-here' );
// Remove the fallback below once the constant is defined.
if ( ! defined( 'NEWS_TECH_BLOCK_API_KEY' ) ) {
    // Fallback API key for development; remove this in production
    define( 'NEWS_TECH_BLOCK_API_KEY', 'Get Your Free API Key From https://newsapi.org/' );
}

function news_tech_block_register_block() {
    wp_register_script(
        'news-tech-block-script',
        plugins_url('block.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data')
    );

    wp_register_style(
        'news-tech-block-style',
        plugins_url('style.css', __FILE__)
    );

    register_block_type('news-tech/block', array(
        'editor_script'   => 'news-tech-block-script',
        'style'           => 'news-tech-block-style',
        'render_callback' => 'news_tech_block_render'
    ));
}
add_action('init', 'news_tech_block_register_block');

function news_tech_block_render( $attributes ) {
    // Get current page from query parameter (default: 1)
    $current_page = isset($_GET['news_page']) ? max(1, intval($_GET['news_page'])) : 1;
    $page_size    = 5; // Limit number of articles per page

    // Build API URL with pagination parameters and replace literal API key with the constant.
    $api_url = add_query_arg( array(
        'q'        => 'wordpress',
        'apiKey'   => NEWS_TECH_BLOCK_API_KEY,
        'page'     => $current_page,
        'pageSize' => $page_size
    ), 'https://newsapi.org/v2/everything' );

    $response = wp_remote_get( $api_url, array(
        'sslverify' => false,
        'headers'   => array(
            'User-Agent' => 'NewsTechBlock/1.0 (+https://yourwebsite.com)'
        )
    ) );
    if ( is_wp_error( $response ) ) {
        return '<div class="news-tech-block">Unable to load news.</div>';
    }
    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );
    
    // Check API response status and output error message if needed
    if ( empty($data) || !isset($data['status']) ) {
        return '<div class="news-tech-block">No articles available.</div>';
    }
    if ( $data['status'] !== 'ok' ) {
        $error_mesg = isset($data['message']) ? $data['message'] : 'Unknown error';
        return sprintf('<div class="news-tech-block">Error: %s</div>', esc_html($error_mesg));
    }
    
    $output = '<div class="news-tech-block">';
    foreach ( $data['articles'] as $article ) {
        $output .= '<div class="news-article">';
        $output .= sprintf(
            '<h3><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></h3>',
            esc_url( $article['url'] ),
            esc_html( $article['title'] )
        );
        $output .= sprintf(
            '<p>%s</p>',
            esc_html( $article['description'] )
        );
        $output .= '</div>';
    }
    $output .= '</div>';

    // Pagination logic
    $total_results = isset( $data['totalResults'] ) ? intval( $data['totalResults'] ) : 0;
    $total_pages   = ceil( $total_results / $page_size );
    if ( $total_pages > 1 ) {
        $current_url = get_permalink();
        $output .= '<div class="news-pagination">';
        if ( $current_page > 1 ) {
            $prev_url = esc_url( add_query_arg( 'news_page', $current_page - 1, $current_url ) );
            $output .= sprintf( '<a class="news-prev" href="%s">Previous</a> ', $prev_url );
        }
        if ( $current_page < $total_pages ) {
            $next_url = esc_url( add_query_arg( 'news_page', $current_page + 1, $current_url ) );
            $output .= sprintf( '<a class="news-next" href="%s">Next</a>', $next_url );
        }
        $output .= '</div>';
    }

    return $output;
}