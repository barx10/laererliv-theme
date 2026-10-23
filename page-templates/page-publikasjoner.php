<?php
/**
 * Template Name: Andre publikasjoner
 */
get_header(); ?>

<div class="page-header page-header--tall">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow page-anim" style="animation-delay:.1s">Publisert</p>
      <h1 class="page-title page-anim" style="animation-delay:.25s">Andre <em>publikasjoner</em></h1>
    </div>
    <p class="page-intro page-anim" style="animation-delay:.4s"><?php echo esc_html( get_theme_mod( 'laererliv_publikasjoner_intro', 'Artikler og kronikker publisert i Utdanningsnytt, AI Avisen og andre medier.' ) ); ?></p>
  </div>
</div>

<?php
$pub_kilder = get_terms( array( 'taxonomy' => 'pub_kilde', 'hide_empty' => true ) );
if ( ! empty( $pub_kilder ) && ! is_wp_error( $pub_kilder ) ) : ?>
<div class="filter-bar page-anim" style="animation-delay:.5s">
  <div class="filter-inner">
    <button class="filter-btn active" data-filter="alle">Alle</button>
    <?php foreach ( $pub_kilder as $kilde ) : ?>
      <button class="filter-btn" data-filter="<?php echo esc_attr( $kilde->slug ); ?>"><?php echo esc_html( $kilde->name ); ?></button>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<section class="publications-section">

  <?php
  $pubs = new WP_Query( array( 'post_type' => 'publikasjon', 'posts_per_page' => -1, 'no_found_rows' => true ) );

  // Sorter etter faktisk publiseringsdato og grupper etter år
  $pub_aar = array();
  foreach ( $pubs->posts as $pub ) {
      $pub->sorteringsdato = laererliv_pub_sorteringsdato( $pub->ID );
  }
  usort( $pubs->posts, function ( $a, $b ) { return strcmp( $b->sorteringsdato, $a->sorteringsdato ); } );
  foreach ( $pubs->posts as $pub ) {
      $pub_aar[ substr( $pub->sorteringsdato, 0, 4 ) ][] = $pub;
  }

  foreach ( $pub_aar as $aar => $aarets_pubs ) : ?>
  <div class="pub-year-group reveal">
    <h2 class="pub-year"><?php echo esc_html( $aar ); ?></h2>
    <ul class="publications-list">
      <?php foreach ( $aarets_pubs as $post ) : setup_postdata( $post );
          $url     = get_post_meta( get_the_ID(), '_pub_url', true );
          $dato    = get_post_meta( get_the_ID(), '_pub_dato', true );
          $kilder  = get_the_terms( get_the_ID(), 'pub_kilde' );
          $kilde_slug = ( $kilder && ! is_wp_error( $kilder ) ) ? $kilder[0]->slug : '';
          $kilde_name = ( $kilder && ! is_wp_error( $kilder ) ) ? $kilder[0]->name : '';
          list( $pub_tittel, $sjanger ) = laererliv_pub_sjanger( get_the_title() );
          $merkelapp = implode( ' · ', array_filter( array( $sjanger, $kilde_name ) ) );
      ?>
      <li class="pub-item" data-category="<?php echo esc_attr( $kilde_slug ); ?>">
        <?php if ( has_post_thumbnail() ) : ?>
          <div class="pub-thumb"><?php the_post_thumbnail( 'cpt-thumb', array( 'loading' => 'lazy' ) ); ?></div>
        <?php endif; ?>
        <div>
          <?php if ( $merkelapp ) : ?><p class="pub-source"><?php echo esc_html( $merkelapp ); ?></p><?php endif; ?>
          <p class="pub-title">
            <?php if ( $url ) : ?>
              <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo wp_kses_post( $pub_tittel ); ?></a>
            <?php else : echo wp_kses_post( $pub_tittel ); endif; ?>
          </p>
          <?php if ( $dato ) : ?><span class="pub-date"><?php echo esc_html( $dato ); ?></span><?php endif; ?>
        </div>
        <?php if ( $url ) : ?>
          <a class="pub-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" tabindex="-1" aria-hidden="true">Les &rarr;</a>
        <?php endif; ?>
      </li>
      <?php endforeach; wp_reset_postdata(); ?>
    </ul>
  </div>
  <?php endforeach; ?>

  <?php
  while ( have_posts() ) : the_post();
    $page_content = apply_filters( 'the_content', get_the_content() );
    if ( trim( $page_content ) ) :
      $episoder = laererliv_podkast_episoder( $page_content ); ?>
  <div class="pub-page-content reveal">
    <div class="section-divider">
      <p class="section-divider-label">Podkast</p>
    </div>
    <?php if ( $episoder ) : ?>
    <ol class="podcast-list">
      <?php foreach ( $episoder as $i => $ep ) : ?>
      <li class="podcast-item">
        <button type="button" class="podcast-play" data-embed-src="<?php echo esc_url( $ep['src'] ); ?>" data-embed-title="<?php echo esc_attr( $ep['tittel'] ); ?>" aria-expanded="false" aria-label="Spill av <?php echo esc_attr( $ep['tittel'] ); ?>">&#9654;</button>
        <div class="podcast-info">
          <?php if ( $ep['kilde'] ) : ?><p class="pub-source"><?php echo esc_html( $ep['kilde'] ); ?></p><?php endif; ?>
          <h3 class="podcast-title"><?php echo esc_html( $ep['tittel'] ); ?></h3>
        </div>
        <div class="podcast-player"></div>
      </li>
      <?php endforeach; ?>
    </ol>
    <?php else : ?>
    <div class="pub-embeds"><?php echo $page_content; ?></div>
    <?php endif; ?>
  </div>
  <?php endif; endwhile; ?>

</section>

<?php get_footer(); ?>
