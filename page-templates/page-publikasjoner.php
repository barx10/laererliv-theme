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
    <p class="page-intro page-anim" style="animation-delay:.4s">Artikler og kronikker publisert i Utdanningsnytt, AI Avisen og andre medier.</p>
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
  $pubs = new WP_Query( array( 'post_type' => 'publikasjon', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC' ) );
  if ( $pubs->have_posts() ) : ?>
  <ul class="publications-list">
    <?php
    $pub_index = 0;
    while ( $pubs->have_posts() ) : $pubs->the_post();
        $url     = get_post_meta( get_the_ID(), '_pub_url', true );
        $dato    = get_post_meta( get_the_ID(), '_pub_dato', true );
        $kilder  = get_the_terms( get_the_ID(), 'pub_kilde' );
        $kilde_slug = ( $kilder && ! is_wp_error( $kilder ) ) ? $kilder[0]->slug : '';
        $kilde_name = ( $kilder && ! is_wp_error( $kilder ) ) ? $kilder[0]->name : '';
    ?>
    <li class="pub-item reveal" data-category="<?php echo esc_attr( $kilde_slug ); ?>" style="transition-delay:<?php echo $pub_index * 0.07; ?>s">
      <div>
        <?php if ( $kilde_name ) : ?><p class="pub-source"><?php echo esc_html( $kilde_name ); ?></p><?php endif; ?>
        <p class="pub-title">
          <?php if ( $url ) : ?>
            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php the_title(); ?></a>
          <?php else : the_title(); endif; ?>
        </p>
        <?php if ( $dato ) : ?><span class="pub-date"><?php echo esc_html( $dato ); ?></span><?php endif; ?>
      </div>
      <?php if ( $url ) : ?>
        <a class="pub-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">Les &rarr;</a>
      <?php endif; ?>
    </li>
    <?php $pub_index++; endwhile; wp_reset_postdata(); ?>
  </ul>
  <?php endif; ?>

  <?php
  while ( have_posts() ) : the_post();
    $page_content = get_the_content();
    if ( $page_content ) : ?>
  <div class="pub-page-content reveal">
    <div class="section-divider">
      <p class="section-divider-label">Podkast</p>
    </div>
    <div class="pub-embeds">
      <?php the_content(); ?>
    </div>
  </div>
  <?php endif; endwhile; ?>

</section>

<?php get_footer(); ?>
