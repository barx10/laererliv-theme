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
    add_image_size( 'cpt-thumb', 160, 120, true );
}
add_action( 'after_setup_theme', 'laererliv_setup' );

/**
 * Auto-tilordne menyer til menyplasseringer ved temabytte.
 * Leter etter menyer kalt «Hovedmeny»/«primary» og «Footermeny»/«footer»
 * og kobler dem automatisk — slipper å gjøre det manuelt etter zip-opplasting.
 */
function laererliv_auto_assign_menus() {
    $locations = get_theme_mod( 'nav_menu_locations' );

    // Sjekk om menyplasseringene allerede er satt
    $primary_set = ! empty( $locations['primary'] ) && is_nav_menu( $locations['primary'] );
    $footer_set  = ! empty( $locations['footer'] ) && is_nav_menu( $locations['footer'] );

    if ( $primary_set && $footer_set ) {
        return; // Begge er allerede koblet, ingenting å gjøre
    }

    $menus = wp_get_nav_menus();
    if ( empty( $menus ) ) return;

    // Mulige navn å lete etter for hver menyplassering
    $primary_names = array( 'hovedmeny', 'primary', 'main', 'main menu', 'hoved' );
    $footer_names  = array( 'footermeny', 'footer', 'bunnmeny', 'footer menu' );

    foreach ( $menus as $menu ) {
        $slug = strtolower( $menu->slug );
        $name = strtolower( $menu->name );

        if ( ! $primary_set && ( in_array( $slug, $primary_names ) || in_array( $name, $primary_names ) ) ) {
            $locations['primary'] = $menu->term_id;
            $primary_set = true;
        }
        if ( ! $footer_set && ( in_array( $slug, $footer_names ) || in_array( $name, $footer_names ) ) ) {
            $locations['footer'] = $menu->term_id;
            $footer_set = true;
        }
    }

    // Hvis primary fortsatt mangler, bruk første meny som finnes
    if ( ! $primary_set && ! empty( $menus ) ) {
        $locations['primary'] = $menus[0]->term_id;
    }

    set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'after_switch_theme', 'laererliv_auto_assign_menus' );
add_action( 'load-themes.php', 'laererliv_auto_assign_menus' );

/**
 * Bruk klassisk editor for CPT-er med egne metabokser.
 * Gutenberg skjuler metaboksene — klassisk editor viser dem tydelig.
 */
function laererliv_disable_gutenberg_for_cpts( $use_block_editor, $post_type ) {
    $classic_types = array( 'nedlastning', 'app', 'publikasjon', 'foredrag', 'manuskonsulent' );
    if ( in_array( $post_type, $classic_types ) ) {
        return false;
    }
    return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'laererliv_disable_gutenberg_for_cpts', 10, 2 );

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
        'rewrite'      => array( 'slug' => 'nedlastninger', 'with_front' => false ),
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
        'rewrite'      => array( 'slug' => 'apper-og-nettsider', 'with_front' => false ),
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
        'rewrite'      => array( 'slug' => 'andre-publikasjoner', 'with_front' => false ),
        'menu_icon'    => 'dashicons-media-document',
        'supports'     => array( 'title', 'thumbnail', 'custom-fields' ),
        'show_in_rest' => true,
    ) );

    // Foredrag (vises kun på om-siden, trenger ikke egne URL-er)
    register_post_type( 'foredrag', array(
        'labels' => array(
            'name'               => 'Foredrag',
            'singular_name'      => 'Foredrag',
            'add_new'            => 'Legg til foredrag',
            'add_new_item'       => 'Legg til nytt foredrag',
            'edit_item'          => 'Rediger foredrag',
            'all_items'          => 'Alle foredrag',
        ),
        'public'       => false,
        'show_ui'      => true,
        'has_archive'  => false,
        'rewrite'      => false,
        'menu_icon'    => 'dashicons-megaphone',
        'supports'     => array( 'title', 'custom-fields' ),
        'show_in_rest' => true,
    ) );

    // Lokale prosjekter (GitHub-repos uten deploy)
    register_post_type( 'lokalt-prosjekt', array(
        'labels' => array(
            'name'          => 'Lokale prosjekter',
            'singular_name' => 'Lokalt prosjekt',
            'add_new'       => 'Legg til prosjekt',
            'add_new_item'  => 'Legg til nytt prosjekt',
            'edit_item'     => 'Rediger prosjekt',
            'all_items'     => 'Alle prosjekter',
        ),
        'public'       => false,
        'show_ui'      => true,
        'has_archive'  => false,
        'rewrite'      => false,
        'menu_icon'    => 'dashicons-editor-code',
        'supports'     => array( 'title', 'editor', 'page-attributes' ),
        'show_in_rest' => true,
    ) );

    // Manuskonsulent (vises kun på om-siden, trenger ikke egne URL-er)
    register_post_type( 'manuskonsulent', array(
        'labels' => array(
            'name'               => 'Manuskonsulent',
            'singular_name'      => 'Manuskonsulentoppdrag',
            'add_new'            => 'Legg til oppdrag',
            'add_new_item'       => 'Legg til nytt oppdrag',
            'edit_item'          => 'Rediger oppdrag',
            'all_items'          => 'Alle oppdrag',
        ),
        'public'       => false,
        'show_ui'      => true,
        'has_archive'  => false,
        'rewrite'      => false,
        'menu_icon'    => 'dashicons-edit-page',
        'supports'     => array( 'title', 'custom-fields' ),
        'show_in_rest' => true,
    ) );
}
add_action( 'init', 'laererliv_register_post_types' );

/**
 * Noindex for eventuelle CPT-arkivsider (sikkerhetsnett).
 * Enkelt-CPT-sider beholdes indeksbare — de har unikt innhold.
 */
add_action( 'wp_head', function () {
    if ( is_post_type_archive( array( 'app', 'nedlastning', 'publikasjon', 'foredrag', 'manuskonsulent' ) ) ) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
    }
} );

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

    // Foredrag: arrangementnavn, dato, sted, lenke
    add_meta_box( 'foredrag_detaljer', 'Foredragsdetaljer', 'laererliv_foredrag_meta_box', 'foredrag', 'normal', 'high' );

    // Manuskonsulent: oppdragsgiver, år, lenke
    add_meta_box( 'manuskonsulent_detaljer', 'Oppdragsdetaljer', 'laererliv_manuskonsulent_meta_box', 'manuskonsulent', 'normal', 'high' );

    // Lokalt prosjekt: GitHub-lenke
    add_meta_box( 'lokalt_prosjekt_detaljer', 'Prosjektdetaljer', 'laererliv_lokalt_prosjekt_meta_box', 'lokalt-prosjekt', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'laererliv_add_meta_boxes' );

/**
 * Last inn mediebibliotek-script for nedlastnings-CPT i admin
 */
function laererliv_admin_scripts( $hook ) {
    global $post_type;
    if ( in_array( $post_type, array( 'nedlastning', 'app', 'publikasjon' ) ) && in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
        wp_enqueue_media();
        wp_enqueue_script(
            'laererliv-admin-upload',
            get_template_directory_uri() . '/assets/js/admin-upload.js',
            array( 'jquery' ),
            wp_get_theme()->get( 'Version' ),
            true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'laererliv_admin_scripts' );

function laererliv_nedlastning_meta_box( $post ) {
    wp_nonce_field( 'laererliv_nedlastning_nonce', 'laererliv_nedlastning_nonce' );
    $filtype     = get_post_meta( $post->ID, '_nedlastning_filtype', true );
    $filstr      = get_post_meta( $post->ID, '_nedlastning_filstr', true );
    $fil_url     = get_post_meta( $post->ID, '_nedlastning_fil_url', true );
    $aar         = get_post_meta( $post->ID, '_nedlastning_aar', true );
    ?>
    <p>
        <label><strong>Fil:</strong></label><br>
        <input type="url" id="nedlastning_fil_url" name="_nedlastning_fil_url" value="<?php echo esc_url( $fil_url ); ?>" style="width:calc(100% - 120px);" placeholder="Klikk Velg fil →">
        <button type="button" class="button" id="nedlastning_velg_fil">📎 Velg fil</button>
        <?php if ( $fil_url ) : ?>
            <br><small style="color:green;">✓ Fil er koblet til</small>
        <?php else : ?>
            <br><small style="color:#B8965A;">⚠ Ingen fil valgt — klikk «Velg fil» for å laste opp eller velge fra mediebiblioteket</small>
        <?php endif; ?>
    </p>
    <p>
        <label>Filtype (fylles ut automatisk):</label><br>
        <input type="text" id="nedlastning_filtype" name="_nedlastning_filtype" value="<?php echo esc_attr( $filtype ); ?>" style="width:200px;" readonly>
    </p>
    <p>
        <label>Filstørrelse (fylles ut automatisk):</label><br>
        <input type="text" id="nedlastning_filstr" name="_nedlastning_filstr" value="<?php echo esc_attr( $filstr ); ?>" style="width:200px;" readonly>
    </p>
    <p>
        <label>Utgivelsesår:</label><br>
        <input type="text" name="_nedlastning_aar" value="<?php echo esc_attr( $aar ); ?>" style="width:100px;">
    </p>
    <?php
}

function laererliv_app_meta_box( $post ) {
    wp_nonce_field( 'laererliv_app_nonce', 'laererliv_app_nonce' );
    $url       = get_post_meta( $post->ID, '_app_url', true );
    $emoji     = get_post_meta( $post->ID, '_app_emoji', true );
    $ikon_url  = get_post_meta( $post->ID, '_app_ikon_url', true );
    ?>
    <p>
        <label><strong>Lenke til app/nettside:</strong></label><br>
        <input type="url" id="app_url" name="_app_url" value="<?php echo esc_url( $url ); ?>" style="width:100%;" placeholder="https://eksempel.no">
        <?php if ( $url ) : ?>
            <br><small style="color:green;">✓ Lenke er satt — <a href="<?php echo esc_url( $url ); ?>" target="_blank">test lenken</a></small>
        <?php else : ?>
            <br><small style="color:#B8965A;">⚠ Ingen lenke satt — lim inn URL til appen eller nettsiden</small>
        <?php endif; ?>
    </p>
    <p>
        <label><strong>Ikon/bilde:</strong></label><br>
        <span id="app_ikon_preview" style="display:<?php echo $ikon_url ? 'inline-block' : 'none'; ?>; margin-bottom:8px;">
            <img src="<?php echo esc_url( $ikon_url ); ?>" style="max-width:80px; max-height:80px; border-radius:12px; border:1px solid #D8D4CC;" id="app_ikon_img">
            <br>
        </span>
        <input type="hidden" id="app_ikon_url" name="_app_ikon_url" value="<?php echo esc_url( $ikon_url ); ?>">
        <button type="button" class="button" id="app_velg_ikon">🖼 Velg bilde</button>
        <button type="button" class="button" id="app_fjern_ikon" style="<?php echo $ikon_url ? '' : 'display:none;'; ?>">Fjern bilde</button>
        <br><small>Valgfritt — vises som ikon på app-kortet. Ellers brukes emoji nedenfor.</small>
    </p>
    <p>
        <label><strong>Ikon (emoji):</strong></label><br>
        <input type="text" name="_app_emoji" value="<?php echo esc_attr( $emoji ); ?>" style="width:100px;" placeholder="🔗">
        <br><small>Brukes som fallback hvis ikke bilde er valgt. Standard: 🔗</small>
    </p>
    <?php
}

function laererliv_pub_meta_box( $post ) {
    wp_nonce_field( 'laererliv_pub_nonce', 'laererliv_pub_nonce' );
    $url  = get_post_meta( $post->ID, '_pub_url', true );
    $dato = get_post_meta( $post->ID, '_pub_dato', true );
    ?>
    <p>
        <label><strong>Lenke til publikasjonen:</strong></label><br>
        <input type="url" name="_pub_url" value="<?php echo esc_url( $url ); ?>" style="width:100%;" placeholder="https://utdanningsnytt.no/artikkel...">
        <?php if ( $url ) : ?>
            <br><small style="color:green;">✓ Lenke er satt — <a href="<?php echo esc_url( $url ); ?>" target="_blank">test lenken</a></small>
        <?php else : ?>
            <br><small style="color:#B8965A;">⚠ Ingen lenke satt — lim inn URL til artikkelen/kronikken</small>
        <?php endif; ?>
    </p>
    <p>
        <label><strong>Publiseringsdato:</strong></label><br>
        <input type="text" name="_pub_dato" value="<?php echo esc_attr( $dato ); ?>" style="width:200px;" placeholder="f.eks. 15. mars 2024">
        <br><small>Vises på publikasjonssiden ved siden av tittelen</small>
    </p>
    <?php
}

function laererliv_foredrag_meta_box( $post ) {
    wp_nonce_field( 'laererliv_foredrag_nonce', 'laererliv_foredrag_nonce' );
    $arrangement = get_post_meta( $post->ID, '_foredrag_arrangement', true );
    $dato        = get_post_meta( $post->ID, '_foredrag_dato', true );
    $sted        = get_post_meta( $post->ID, '_foredrag_sted', true );
    $url         = get_post_meta( $post->ID, '_foredrag_url', true );
    ?>
    <p>
        <label><strong>Arrangement/konferanse:</strong></label><br>
        <input type="text" name="_foredrag_arrangement" value="<?php echo esc_attr( $arrangement ); ?>" style="width:100%;" placeholder="f.eks. Fleksibel Utdanning">
    </p>
    <p>
        <label><strong>Dato/periode:</strong></label><br>
        <input type="text" name="_foredrag_dato" value="<?php echo esc_attr( $dato ); ?>" style="width:250px;" placeholder="f.eks. sept. 2023">
    </p>
    <p>
        <label><strong>Sted:</strong></label><br>
        <input type="text" name="_foredrag_sted" value="<?php echo esc_attr( $sted ); ?>" style="width:250px;" placeholder="f.eks. Oslo">
    </p>
    <p>
        <label><strong>Lenke (valgfritt):</strong></label><br>
        <input type="url" name="_foredrag_url" value="<?php echo esc_url( $url ); ?>" style="width:100%;" placeholder="https://...">
        <br><small>Lenke til program, video e.l.</small>
    </p>
    <?php
}

function laererliv_manuskonsulent_meta_box( $post ) {
    wp_nonce_field( 'laererliv_manuskonsulent_nonce', 'laererliv_manuskonsulent_nonce' );
    $oppdragsgiver = get_post_meta( $post->ID, '_manus_oppdragsgiver', true );
    $aar           = get_post_meta( $post->ID, '_manus_aar', true );
    $url           = get_post_meta( $post->ID, '_manus_url', true );
    ?>
    <p>
        <label><strong>Oppdragsgiver/forfatter:</strong></label><br>
        <input type="text" name="_manus_oppdragsgiver" value="<?php echo esc_attr( $oppdragsgiver ); ?>" style="width:100%;" placeholder="f.eks. Forfatternavn eller forlag">
    </p>
    <p>
        <label><strong>År:</strong></label><br>
        <input type="text" name="_manus_aar" value="<?php echo esc_attr( $aar ); ?>" style="width:100px;" placeholder="f.eks. 2024">
    </p>
    <p>
        <label><strong>Lenke (valgfritt):</strong></label><br>
        <input type="url" name="_manus_url" value="<?php echo esc_url( $url ); ?>" style="width:100%;" placeholder="https://...">
        <br><small>Lenke til publikasjonen du konsulerte på</small>
    </p>
    <?php
}

function laererliv_lokalt_prosjekt_meta_box( $post ) {
    wp_nonce_field( 'laererliv_lokalt_prosjekt_nonce', 'laererliv_lokalt_prosjekt_nonce' );
    $github_url = get_post_meta( $post->ID, '_prosjekt_github_url', true );
    ?>
    <p>
        <label><strong>GitHub-lenke:</strong></label><br>
        <input type="url" name="_prosjekt_github_url" value="<?php echo esc_url( $github_url ); ?>" style="width:100%;" placeholder="https://github.com/bruker/repo">
        <?php if ( $github_url ) : ?>
            <br><small style="color:green;">✓ Lenke er satt — <a href="<?php echo esc_url( $github_url ); ?>" target="_blank">åpne repo</a></small>
        <?php else : ?>
            <br><small style="color:#B8965A;">⚠ Lim inn GitHub-URL til repoet</small>
        <?php endif; ?>
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
        if ( isset( $_POST['_app_ikon_url'] ) ) update_post_meta( $post_id, '_app_ikon_url', esc_url_raw( $_POST['_app_ikon_url'] ) );
    }

    // Publikasjon
    if ( isset( $_POST['laererliv_pub_nonce'] ) && wp_verify_nonce( $_POST['laererliv_pub_nonce'], 'laererliv_pub_nonce' ) ) {
        if ( isset( $_POST['_pub_url'] ) ) update_post_meta( $post_id, '_pub_url', esc_url_raw( $_POST['_pub_url'] ) );
        if ( isset( $_POST['_pub_dato'] ) ) update_post_meta( $post_id, '_pub_dato', sanitize_text_field( $_POST['_pub_dato'] ) );
    }

    // Foredrag
    if ( isset( $_POST['laererliv_foredrag_nonce'] ) && wp_verify_nonce( $_POST['laererliv_foredrag_nonce'], 'laererliv_foredrag_nonce' ) ) {
        if ( isset( $_POST['_foredrag_arrangement'] ) ) update_post_meta( $post_id, '_foredrag_arrangement', sanitize_text_field( $_POST['_foredrag_arrangement'] ) );
        if ( isset( $_POST['_foredrag_dato'] ) ) update_post_meta( $post_id, '_foredrag_dato', sanitize_text_field( $_POST['_foredrag_dato'] ) );
        if ( isset( $_POST['_foredrag_sted'] ) ) update_post_meta( $post_id, '_foredrag_sted', sanitize_text_field( $_POST['_foredrag_sted'] ) );
        if ( isset( $_POST['_foredrag_url'] ) ) update_post_meta( $post_id, '_foredrag_url', esc_url_raw( $_POST['_foredrag_url'] ) );
    }

    // Manuskonsulent
    if ( isset( $_POST['laererliv_manuskonsulent_nonce'] ) && wp_verify_nonce( $_POST['laererliv_manuskonsulent_nonce'], 'laererliv_manuskonsulent_nonce' ) ) {
        if ( isset( $_POST['_manus_oppdragsgiver'] ) ) update_post_meta( $post_id, '_manus_oppdragsgiver', sanitize_text_field( $_POST['_manus_oppdragsgiver'] ) );
        if ( isset( $_POST['_manus_aar'] ) ) update_post_meta( $post_id, '_manus_aar', sanitize_text_field( $_POST['_manus_aar'] ) );
        if ( isset( $_POST['_manus_url'] ) ) update_post_meta( $post_id, '_manus_url', esc_url_raw( $_POST['_manus_url'] ) );
    }

    // Lokalt prosjekt
    if ( isset( $_POST['laererliv_lokalt_prosjekt_nonce'] ) && wp_verify_nonce( $_POST['laererliv_lokalt_prosjekt_nonce'], 'laererliv_lokalt_prosjekt_nonce' ) ) {
        if ( isset( $_POST['_prosjekt_github_url'] ) ) update_post_meta( $post_id, '_prosjekt_github_url', esc_url_raw( $_POST['_prosjekt_github_url'] ) );
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
// CUSTOMIZER
// ========================================
function laererliv_customize_register( $wp_customize ) {

    // --- Hero-seksjon ---
    $wp_customize->add_section( 'laererliv_hero', array(
        'title'    => 'Hero / Landingsside',
        'priority' => 30,
    ) );

    // Eyebrow
    $wp_customize->add_setting( 'laererliv_hero_eyebrow', array(
        'default'           => 'Fra klasserom til storsamfunn',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_hero_eyebrow', array(
        'label'   => 'Eyebrow-tekst',
        'section' => 'laererliv_hero',
        'type'    => 'text',
    ) );

    // Overskrift
    $wp_customize->add_setting( 'laererliv_hero_headline', array(
        'default'           => 'Ressurser og artikler om <em>skole</em>, teknologi og KI – for lærere',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'laererliv_hero_headline', array(
        'label'       => 'Overskrift (bruk <em> for kursiv)',
        'section'     => 'laererliv_hero',
        'type'        => 'textarea',
    ) );

    // Undertekst
    $wp_customize->add_setting( 'laererliv_hero_sub', array(
        'default'           => 'Kenneth Bareksten skriver om undervisning, digital kompetanse og hva som skjer naar laerere tar pennen.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_hero_sub', array(
        'label'   => 'Undertekst',
        'section' => 'laererliv_hero',
        'type'    => 'textarea',
    ) );

    // CTA-tekst
    $wp_customize->add_setting( 'laererliv_hero_cta_text', array(
        'default'           => 'Les innleggene',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_hero_cta_text', array(
        'label'   => 'CTA-knapp tekst',
        'section' => 'laererliv_hero',
        'type'    => 'text',
    ) );

    // CTA-lenke
    $wp_customize->add_setting( 'laererliv_hero_cta_url', array(
        'default'           => '#featured',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'laererliv_hero_cta_url', array(
        'label'   => 'CTA-knapp lenke',
        'section' => 'laererliv_hero',
        'type'    => 'url',
    ) );

    // Hero-bilde
    $wp_customize->add_setting( 'laererliv_hero_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'laererliv_hero_image', array(
        'label'   => 'Portrettbilde',
        'section' => 'laererliv_hero',
    ) ) );

    // Navn under portrett
    $wp_customize->add_setting( 'laererliv_hero_name', array(
        'default'           => 'Kenneth Bareksten',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_hero_name', array(
        'label'   => 'Navn (under portrett)',
        'section' => 'laererliv_hero',
        'type'    => 'text',
    ) );

    // Rolle under portrett
    $wp_customize->add_setting( 'laererliv_hero_role', array(
        'default'           => 'Lektor · Skribent · Foredragsholder',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_hero_role', array(
        'label'   => 'Rolle (under portrett)',
        'section' => 'laererliv_hero',
        'type'    => 'text',
    ) );

    // --- Seksjonsoverskrifter ---
    $wp_customize->add_section( 'laererliv_sections', array(
        'title'    => 'Seksjonsoverskrifter',
        'priority' => 31,
    ) );

    $wp_customize->add_setting( 'laererliv_featured_label', array(
        'default'           => 'Siste innlegg',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_featured_label', array(
        'label'   => 'Label over siste innlegg',
        'section' => 'laererliv_sections',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'laererliv_random_heading', array(
        'default'           => 'Tilfeldig utvalg',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_random_heading', array(
        'label'   => 'Overskrift tilfeldig utvalg',
        'section' => 'laererliv_sections',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'laererliv_archive_heading', array(
        'default'           => 'Arkiv',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_archive_heading', array(
        'label'   => 'Overskrift arkiv',
        'section' => 'laererliv_sections',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'laererliv_archive_years_label', array(
        'default'           => 'Velg aar',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_archive_years_label', array(
        'label'   => 'Label over årsvalg',
        'section' => 'laererliv_sections',
        'type'    => 'text',
    ) );

    // --- Footer ---
    $wp_customize->add_section( 'laererliv_footer', array(
        'title'    => 'Footer',
        'priority' => 32,
    ) );

    $wp_customize->add_setting( 'laererliv_footer_desc', array(
        'default'           => 'Kenneth skriver om skole, undervisning, teknologi og det som skjer naar disse verdener moetes. Lektor ved ungdomsskole i Oslo.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_footer_desc', array(
        'label'   => 'Footer-beskrivelse',
        'section' => 'laererliv_footer',
        'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'laererliv_footer_email', array(
        'default'           => 'kenneth@laererliv.no',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'laererliv_footer_email', array(
        'label'   => 'E-postadresse',
        'section' => 'laererliv_footer',
        'type'    => 'email',
    ) );

    $wp_customize->add_setting( 'laererliv_footer_link1_text', array(
        'default'           => 'Utdanningsnytt',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_footer_link1_text', array(
        'label'   => 'Ekstern lenke 1 — tekst',
        'section' => 'laererliv_footer',
        'type'    => 'text',
    ) );
    $wp_customize->add_setting( 'laererliv_footer_link1_url', array(
        'default'           => 'https://www.utdanningsnytt.no',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'laererliv_footer_link1_url', array(
        'label'   => 'Ekstern lenke 1 — URL',
        'section' => 'laererliv_footer',
        'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'laererliv_footer_link2_text', array(
        'default'           => 'AI Avisen',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_footer_link2_text', array(
        'label'   => 'Ekstern lenke 2 — tekst',
        'section' => 'laererliv_footer',
        'type'    => 'text',
    ) );
    $wp_customize->add_setting( 'laererliv_footer_link2_url', array(
        'default'           => 'https://aiavisen.no',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'laererliv_footer_link2_url', array(
        'label'   => 'Ekstern lenke 2 — URL',
        'section' => 'laererliv_footer',
        'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'laererliv_footer_tagline', array(
        'default'           => 'Fra klasserom til storsamfunn',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'laererliv_footer_tagline', array(
        'label'   => 'Tagline (nederst i footer)',
        'section' => 'laererliv_footer',
        'type'    => 'text',
    ) );

    // ----------------------------------------
    // UNDERSIDER — intro-tekst
    // ----------------------------------------
    $wp_customize->add_section( 'laererliv_undersider', array(
        'title'    => 'Undersider',
        'priority' => 35,
    ) );

    $undersider = array(
        'nedlastninger' => array(
            'label'   => 'Nedlastninger — intro',
            'default' => 'Bøker, artikler og undervisningsmateriell til fri nedlasting.',
        ),
        'apper' => array(
            'label'   => 'Apper og nettsider — intro',
            'default' => 'Verktøy, apper og nettsider Kenneth har laget eller anbefaler for lærere.',
        ),
        'publikasjoner' => array(
            'label'   => 'Andre publikasjoner — intro',
            'default' => 'Artikler og kronikker publisert i Utdanningsnytt, AI Avisen og andre medier.',
        ),
        'om' => array(
            'label'   => 'Om Lærerliv — intro',
            'default' => 'Bloggen drives av Kenneth Bareksten, lektor og skribent i Oslo.',
        ),
    );

    foreach ( $undersider as $key => $args ) {
        $wp_customize->add_setting( 'laererliv_' . $key . '_intro', array(
            'default'           => $args['default'],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'laererliv_' . $key . '_intro', array(
            'label'   => $args['label'],
            'section' => 'laererliv_undersider',
            'type'    => 'textarea',
        ) );
    }
}
add_action( 'customize_register', 'laererliv_customize_register' );

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
