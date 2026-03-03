<?php
/**
 * Laererliv tema functions
 */

// ========================================
// TEMAOPPSETT
// ========================================
function laererliv_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'editor-styles' );

    register_nav_menus( array(
        'primary'    => 'Hovedmeny',
        'footer'     => 'Footermeny',
    ) );

    // Bildestørrelser
    add_image_size( 'featured-large', 800, 600, true );
    add_image_size( 'post-card', 600, 375, true );
    add_image_size( 'hero-portrait', 800, 1200, true );
}
add_action( 'after_setup_theme', 'laererliv_setup' );

// ========================================
// STIL OG SKRIPT
// ========================================
function laererliv_scripts() {
    // Google Fonts
    wp_enqueue_style( 'google-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Outfit:wght@300;400;500&display=swap',
        array(), null
    );

    // Tema-CSS
    wp_enqueue_style( 'laererliv-style', get_stylesheet_uri(), array( 'google-fonts' ), wp_get_theme()->get( 'Version' ) );

    // Tema-JS
    wp_enqueue_script( 'laererliv-main', get_template_directory_uri() . '/assets/js/main.js', array(), wp_get_theme()->get( 'Version' ), true );

    // Send data til JS
    wp_localize_script( 'laererliv-main', 'laererliv', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'homeurl' => home_url( '/' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'laererliv_scripts' );

// ========================================
// CUSTOM POST TYPES
// ========================================
function laererliv_register_post_types() {

    // Nedlastninger
    register_post_type( 'nedlastning', array(
        'labels' => array(
            'name'               => 'Nedlastninger',
            'singular_name'      => 'Nedlastning',
            'add_new'            => 'Legg til nedlastning',
            'add_new_item'       => 'Legg til ny nedlastning',
            'edit_item'          => 'Rediger nedlastning',
            'all_items'          => 'Alle nedlastninger',
        ),
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-download',
        'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'show_in_rest' => true,
    ) );

    // Apper
    register_post_type( 'app', array(
        'labels' => array(
            'name'               => 'Apper og nettsider',
            'singular_name'      => 'App',
            'add_new'            => 'Legg til app',
            'add_new_item'       => 'Legg til ny app',
            'edit_item'          => 'Rediger app',
            'all_items'          => 'Alle apper',
        ),
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-smartphone',
        'supports'     => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'show_in_rest' => true,
    ) );

    // Publikasjoner
    register_post_type( 'publikasjon', array(
        'labels' => array(
            'name'               => 'Publikasjoner',
            'singular_name'      => 'Publikasjon',
            'add_new'            => 'Legg til publikasjon',
            'add_new_item'       => 'Legg til ny publikasjon',
            'edit_item'          => 'Rediger publikasjon',
            'all_items'          => 'Alle publikasjoner',
        ),
        'public'       => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-media-document',
        'supports'     => array( 'title', 'custom-fields' ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'laererliv_register_post_types' );

// ========================================
// TAKSONOMIER FOR CPT
// ========================================
function laererliv_register_taxonomies() {

    // Nedlastningskategori
    register_taxonomy( 'nedlastning_kategori', 'nedlastning', array(
        'labels' => array(
            'name'          => 'Nedlastningskategorier',
            'singular_name' => 'Kategori',
            'add_new_item'  => 'Legg til kategori',
        ),
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ) );

    // Appkategori
    register_taxonomy( 'app_kategori', 'app', array(
        'labels' => array(
            'name'          => 'Appkategorier',
            'singular_name' => 'Kategori',
            'add_new_item'  => 'Legg til kategori',
        ),
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ) );

    // Publikasjonskilde
    register_taxonomy( 'pub_kilde', 'publikasjon', array(
        'labels' => array(
            'name'          => 'Kilder',
            'singular_name' => 'Kilde',
            'add_new_item'  => 'Legg til kilde',
        ),
        'hierarchical' => true,
        'show_in_rest' => true,
        'show_admin_column' => true,
    ) );
}
add_action( 'init', 'laererliv_register_taxonomies' );

// ========================================
// META BOXES FOR CPT
// ========================================
function laererliv_add_meta_boxes() {
    // Nedlastning: filtype, filstørrelse, fil-URL
    add_meta_box( 'nedlastning_detaljer', 'Nedlastningsdetaljer', 'laererliv_nedlastning_meta_box', 'nedlastning', 'normal', 'high' );

    // App: ekstern URL, apikategori
    add_meta_box( 'app_detaljer', 'Appdetaljer', 'laererliv_app_meta_box', 'app', 'normal', 'high' );

    // Publikasjon: ekstern URL, dato
    add_meta_box( 'pub_detaljer', 'Publikasjonsdetaljer', 'laererliv_pub_meta_box', 'publikasjon', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'laererliv_add_meta_boxes' );

function laererliv_nedlastning_meta_box( $post ) {
    wp_nonce_field( 'laererliv_nedlastning_nonce', 'laererliv_nedlastning_nonce' );
    $filtype     = get_post_meta( $post->ID, '_nedlastning_filtype', true );
    $filstr      = get_post_meta( $post->ID, '_nedlastning_filstr', true );
    $fil_url     = get_post_meta( $post->ID, '_nedlastning_fil_url', true );
    $aar         = get_post_meta( $post->ID, '_nedlastning_aar', true );
    ?>
    <p>
        <label>Filtype (pdf/doc/pptx):</label><br>
        <input type="text" name="_nedlastning_filtype" value="<?php echo esc_attr( $filtype ); ?>" style="width:200px;">
    </p>
    <p>
        <label>Filstørrelse (f.eks. 2.4 MB):</label><br>
        <input type="text" name="_nedlastning_filstr" value="<?php echo esc_attr( $filstr ); ?>" style="width:200px;">
    </p>
    <p>
        <label>Fil-URL (last opp via Mediebiblioteket):</label><br>
        <input type="url" name="_nedlastning_fil_url" value="<?php echo esc_url( $fil_url ); ?>" style="width:100%;">
    </p>
    <p>
        <label>Utgivelsesår:</label><br>
        <input type="text" name="_nedlastning_aar" value="<?php echo esc_attr( $aar ); ?>" style="width:100px;">
    </p>
    <?php
}

function laererliv_app_meta_box( $post ) {
    wp_nonce_field( 'laererliv_app_nonce', 'laererliv_app_nonce' );
    $url   = get_post_meta( $post->ID, '_app_url', true );
    $emoji = get_post_meta( $post->ID, '_app_emoji', true );
    ?>
    <p>
        <label>Ekstern URL:</label><br>
        <input type="url" name="_app_url" value="<?php echo esc_url( $url ); ?>" style="width:100%;">
    </p>
    <p>
        <label>Ikon (emoji):</label><br>
        <input type="text" name="_app_emoji" value="<?php echo esc_attr( $emoji ); ?>" style="width:100px;">
    </p>
    <?php
}

function laererliv_pub_meta_box( $post ) {
    wp_nonce_field( 'laererliv_pub_nonce', 'laererliv_pub_nonce' );
    $url  = get_post_meta( $post->ID, '_pub_url', true );
    $dato = get_post_meta( $post->ID, '_pub_dato', true );
    ?>
    <p>
        <label>Ekstern URL:</label><br>
        <input type="url" name="_pub_url" value="<?php echo esc_url( $url ); ?>" style="width:100%;">
    </p>
    <p>
        <label>Publiseringsdato (f.eks. 15. mars 2024):</label><br>
        <input type="text" name="_pub_dato" value="<?php echo esc_attr( $dato ); ?>" style="width:200px;">
    </p>
    <?php
}

function laererliv_save_meta( $post_id ) {
    // Nedlastning
    if ( isset( $_POST['laererliv_nedlastning_nonce'] ) && wp_verify_nonce( $_POST['laererliv_nedlastning_nonce'], 'laererliv_nedlastning_nonce' ) ) {
        if ( isset( $_POST['_nedlastning_filtype'] ) ) update_post_meta( $post_id, '_nedlastning_filtype', sanitize_text_field( $_POST['_nedlastning_filtype'] ) );
        if ( isset( $_POST['_nedlastning_filstr'] ) ) update_post_meta( $post_id, '_nedlastning_filstr', sanitize_text_field( $_POST['_nedlastning_filstr'] ) );
        if ( isset( $_POST['_nedlastning_fil_url'] ) ) update_post_meta( $post_id, '_nedlastning_fil_url', esc_url_raw( $_POST['_nedlastning_fil_url'] ) );
        if ( isset( $_POST['_nedlastning_aar'] ) ) update_post_meta( $post_id, '_nedlastning_aar', sanitize_text_field( $_POST['_nedlastning_aar'] ) );
    }

    // App
    if ( isset( $_POST['laererliv_app_nonce'] ) && wp_verify_nonce( $_POST['laererliv_app_nonce'], 'laererliv_app_nonce' ) ) {
        if ( isset( $_POST['_app_url'] ) ) update_post_meta( $post_id, '_app_url', esc_url_raw( $_POST['_app_url'] ) );
        if ( isset( $_POST['_app_emoji'] ) ) update_post_meta( $post_id, '_app_emoji', sanitize_text_field( $_POST['_app_emoji'] ) );
    }

    // Publikasjon
    if ( isset( $_POST['laererliv_pub_nonce'] ) && wp_verify_nonce( $_POST['laererliv_pub_nonce'], 'laererliv_pub_nonce' ) ) {
        if ( isset( $_POST['_pub_url'] ) ) update_post_meta( $post_id, '_pub_url', esc_url_raw( $_POST['_pub_url'] ) );
        if ( isset( $_POST['_pub_dato'] ) ) update_post_meta( $post_id, '_pub_dato', sanitize_text_field( $_POST['_pub_dato'] ) );
    }
}
add_action( 'save_post', 'laererliv_save_meta' );

// ========================================
// HJELPEFUNKSJONER
// ========================================

/**
 * Norske månedsnavn
 */
function laererliv_norsk_maaned( $month_num ) {
    $maaneder = array(
        1 => 'januar', 2 => 'februar', 3 => 'mars', 4 => 'april',
        5 => 'mai', 6 => 'juni', 7 => 'juli', 8 => 'august',
        9 => 'september', 10 => 'oktober', 11 => 'november', 12 => 'desember'
    );
    return $maaneder[ (int) $month_num ] ?? '';
}

/**
 * Norsk datoformat: "22. februar 2026"
 */
function laererliv_norsk_dato( $post_id = null ) {
    $timestamp = get_the_time( 'U', $post_id );
    $dag       = date( 'j', $timestamp );
    $maaned    = laererliv_norsk_maaned( date( 'n', $timestamp ) );
    $aar       = date( 'Y', $timestamp );
    return $dag . '. ' . $maaned . ' ' . $aar;
}

/**
 * Kort norsk dato: "22. feb 2026"
 */
function laererliv_kort_dato( $post_id = null ) {
    $timestamp = get_the_time( 'U', $post_id );
    $dag       = date( 'j', $timestamp );
    $maaned    = mb_substr( laererliv_norsk_maaned( date( 'n', $timestamp ) ), 0, 3 );
    $aar       = date( 'Y', $timestamp );
    return $dag . '. ' . $maaned . ' ' . $aar;
}

/**
 * Estimert lesetid
 */
function laererliv_lesetid( $post_id = null ) {
    $content    = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( strip_tags( $content ) );
    $minutes    = max( 1, ceil( $word_count / 200 ) );
    return $minutes . ' min lesetid';
}

/**
 * Walker for flat navigasjonsmeny (ingen dropdowns)
 */
class Laererliv_Nav_Walker extends Walker_Nav_Menu {
    function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes = in_array( 'current-menu-item', $item->classes ) ? ' class="current-menu-item"' : '';
        $output .= '<a href="' . esc_url( $item->url ) . '"' . $classes . '>' . esc_html( $item->title ) . '</a>';
    }
    function end_el( &$output, $item, $depth = 0, $args = null ) {}
    function start_lvl( &$output, $depth = 0, $args = null ) {}
    function end_lvl( &$output, $depth = 0, $args = null ) {}
}

/**
 * Sjekk om en side bruker en bestemt page template
 */
function laererliv_is_page_template_slug( $slug ) {
    return is_page() && get_page_template_slug() === $slug;
}

// ========================================
// ADMIN: SORTERINGSREKKEFØLGE FOR CPT
// ========================================
function laererliv_cpt_order( $query ) {
    if ( is_admin() ) return;

    if ( $query->is_main_query() && ! is_admin() ) {
        if ( is_post_type_archive( 'nedlastning' ) || ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'nedlastning' ) ) {
            $query->set( 'orderby', 'menu_order' );
            $query->set( 'order', 'ASC' );
        }
        if ( is_post_type_archive( 'app' ) || ( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'app' ) ) {
            $query->set( 'orderby', 'menu_order' );
            $query->set( 'order', 'ASC' );
        }
    }
}
add_action( 'pre_get_posts', 'laererliv_cpt_order' );
