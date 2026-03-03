<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="site-header">
  <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">Lærerliv</a>
  <button class="menu-toggle" aria-label="Meny">
    <span></span><span></span><span></span>
  </button>
  <nav class="main-nav">
    <?php
    if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu( array(
            'theme_location' => 'primary',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'walker'         => new Laererliv_Nav_Walker(),
        ) );
    } else {
        // Fallback hvis meny ikke er satt opp
        ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Hjem</a>
        <a href="<?php echo esc_url( home_url( '/nedlastninger/' ) ); ?>">Nedlastninger</a>
        <a href="<?php echo esc_url( home_url( '/apper-og-nettsider/' ) ); ?>">Apper og nettsider</a>
        <a href="<?php echo esc_url( home_url( '/andre-publikasjoner/' ) ); ?>">Andre publikasjoner</a>
        <a href="<?php echo esc_url( home_url( '/om-laererliv/' ) ); ?>">Om Lærerliv</a>
        <?php
    }
    ?>
  </nav>
</header>
