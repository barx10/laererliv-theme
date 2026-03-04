<?php
/**
 * Enkeltside for nedlastning-CPT
 */
get_header();

while ( have_posts() ) : the_post();
    $filtype  = get_post_meta( get_the_ID(), '_nedlastning_filtype', true );
    $filstr   = get_post_meta( get_the_ID(), '_nedlastning_filstr', true );
    $fil_url  = get_post_meta( get_the_ID(), '_nedlastning_fil_url', true );
    $aar      = get_post_meta( get_the_ID(), '_nedlastning_aar', true );
    $dl_terms = get_the_terms( get_the_ID(), 'nedlastning_kategori' );
    $cat_name = ( $dl_terms && ! is_wp_error( $dl_terms ) ) ? $dl_terms[0]->name : '';
?>

<div class="breadcrumb">
  <div class="breadcrumb-inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Hjem</a>
    <span class="breadcrumb-sep">/</span>
    <?php
    // Finn nedlastningssiden (med page template)
    $dl_pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/page-nedlastninger.php' ) );
    if ( ! empty( $dl_pages ) ) : ?>
      <a href="<?php echo esc_url( get_permalink( $dl_pages[0]->ID ) ); ?>">Nedlastninger</a>
      <span class="breadcrumb-sep">/</span>
    <?php endif; ?>
    <span class="breadcrumb-current"><?php the_title(); ?></span>
  </div>
</div>

<article class="single-download">
  <header class="single-download-header">
    <div class="single-download-header-inner">
      <div>
        <?php if ( $cat_name ) : ?>
          <p class="page-eyebrow"><?php echo esc_html( $cat_name ); ?></p>
        <?php endif; ?>
        <h1 class="single-download-title"><?php the_title(); ?></h1>
        <?php if ( $filtype || $filstr || $aar ) : ?>
          <p class="single-download-meta">
            <?php echo esc_html( strtoupper( $filtype ) ); ?>
            <?php if ( $filstr ) echo ' &middot; ' . esc_html( $filstr ); ?>
            <?php if ( $aar ) echo ' &middot; ' . esc_html( $aar ); ?>
          </p>
        <?php endif; ?>
      </div>
      <?php if ( $fil_url ) : ?>
      <div class="single-download-actions">
        <a class="download-btn" href="<?php echo esc_url( $fil_url ); ?>" download>&darr; Last ned</a>
        <a class="download-btn secondary" href="<?php echo esc_url( $fil_url ); ?>" target="_blank" rel="noopener">&nearr; Åpne / forhåndsvis</a>
      </div>
      <?php endif; ?>
    </div>
  </header>

  <?php if ( has_post_thumbnail() ) : ?>
  <div class="single-download-image">
    <?php the_post_thumbnail( 'featured-large' ); ?>
  </div>
  <?php endif; ?>

  <div class="single-download-body">
    <?php the_content(); ?>
  </div>

  <?php if ( $fil_url ) : ?>
  <div class="single-download-cta">
    <a class="download-btn" href="<?php echo esc_url( $fil_url ); ?>" download>&darr; Last ned <?php echo esc_html( strtoupper( $filtype ) ); ?></a>
    <a class="download-btn secondary" href="<?php echo esc_url( $fil_url ); ?>" target="_blank" rel="noopener">&nearr; Åpne i nettleser</a>
  </div>
  <?php endif; ?>
</article>

<nav class="post-nav" aria-label="Naviger mellom nedlastninger">
  <div class="post-nav-inner">
    <?php $prev = get_previous_post(); $next = get_next_post(); ?>
    <?php if ( $prev ) : ?>
    <a class="post-nav-item prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>">
      <span class="post-nav-direction">&larr; Forrige</span>
      <span class="post-nav-title"><?php echo esc_html( $prev->post_title ); ?></span>
    </a>
    <?php else : ?><span class="post-nav-item prev"></span><?php endif; ?>

    <?php if ( $next ) : ?>
    <a class="post-nav-item next" href="<?php echo esc_url( get_permalink( $next ) ); ?>">
      <span class="post-nav-direction">Neste &rarr;</span>
      <span class="post-nav-title"><?php echo esc_html( $next->post_title ); ?></span>
    </a>
    <?php else : ?><span class="post-nav-item next"></span><?php endif; ?>
  </div>
</nav>

<?php endwhile; ?>

<?php get_footer(); ?>
