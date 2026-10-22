<?php
/**
 * Template Name: Apper og nettsider
 */
get_header(); ?>

<div class="page-header page-header--tall">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow page-anim" style="animation-delay:.1s">Digitale verktøy</p>
      <h1 class="page-title page-anim" style="animation-delay:.25s">Apper og <em>nettsider</em></h1>
    </div>
    <p class="page-intro page-anim" style="animation-delay:.4s"><?php echo esc_html( get_theme_mod( 'laererliv_apper_intro', 'Verktøy, apper og nettsider Kenneth har laget eller anbefaler for lærere.' ) ); ?></p>
  </div>
</div>

<?php
$app_cats = get_terms( array( 'taxonomy' => 'app_kategori', 'hide_empty' => true ) );
if ( ! empty( $app_cats ) && ! is_wp_error( $app_cats ) ) : ?>
<div class="filter-bar page-anim" style="animation-delay:.5s">
  <div class="filter-inner">
    <button class="filter-btn active" data-filter="alle">Alle</button>
    <?php foreach ( $app_cats as $cat ) : ?>
      <button class="filter-btn" data-filter="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php
$apps = new WP_Query( array( 'post_type' => 'app', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true ) );

// Utvalgt app: den nyeste som er huket av, ellers den nyeste appen.
$featured_id = 0;
foreach ( $apps->posts as $app_post ) {
    if ( get_post_meta( $app_post->ID, '_app_utvalgt', true ) ) { $featured_id = $app_post->ID; break; }
}
if ( ! $featured_id && $apps->posts ) $featured_id = $apps->posts[0]->ID;
?>

<section class="apps-section">
  <p class="section-group-label">Lanserte prosjekter</p>

  <?php while ( $apps->have_posts() ) : $apps->the_post();
      if ( get_the_ID() !== $featured_id ) continue;
      $url      = get_post_meta( get_the_ID(), '_app_url', true );
      $video_id = laererliv_youtube_id( get_post_meta( get_the_ID(), '_app_video_url', true ) );
      $terms    = get_the_terms( get_the_ID(), 'app_kategori' );
      $cat_slug = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
      $cat_name_app = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
  ?>
  <article class="app-featured" data-category="<?php echo esc_attr( $cat_slug ); ?>">
    <div class="app-media">
      <?php laererliv_app_media( 'featured-large', true ); ?>
      <?php if ( $video_id ) : ?>
        <button type="button" class="app-play app-video-btn" data-video-id="<?php echo esc_attr( $video_id ); ?>" data-video-title="<?php the_title_attribute(); ?>" aria-label="Se video om <?php the_title_attribute(); ?>">&#9654;</button>
      <?php endif; ?>
    </div>
    <div class="app-body">
      <p class="app-tag">Utvalgt<?php if ( $cat_name_app ) echo ' · ' . esc_html( $cat_name_app ); ?></p>
      <h2 class="app-title"><?php the_title(); ?></h2>
      <div class="app-desc"><?php echo wp_kses_post( get_the_content() ); ?></div>
      <div class="app-actions">
        <?php if ( $url ) : ?>
          <a class="download-btn" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">Besøk &rarr;</a>
        <?php endif; ?>
        <?php if ( $video_id ) : ?>
          <button type="button" class="app-link app-video-btn" data-video-id="<?php echo esc_attr( $video_id ); ?>" data-video-title="<?php the_title_attribute(); ?>">&#9654; Se video</button>
        <?php endif; ?>
      </div>
    </div>
  </article>
  <?php endwhile; $apps->rewind_posts(); ?>

  <ul class="apps-list">
    <?php
    $app_index = 0;
    while ( $apps->have_posts() ) : $apps->the_post();
        if ( get_the_ID() === $featured_id ) continue;
        $url      = get_post_meta( get_the_ID(), '_app_url', true );
        $video_id = laererliv_youtube_id( get_post_meta( get_the_ID(), '_app_video_url', true ) );
        $terms    = get_the_terms( get_the_ID(), 'app_kategori' );
        $cat_slug = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
        $cat_name_app = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
    ?>
    <li class="app-item reveal" data-category="<?php echo esc_attr( $cat_slug ); ?>" style="transition-delay:<?php echo ( $app_index % 3 ) * 0.07; ?>s">
      <div class="app-media">
        <?php laererliv_app_media( 'post-card' ); ?>
        <?php if ( $video_id ) : ?>
          <button type="button" class="app-play app-video-btn" data-video-id="<?php echo esc_attr( $video_id ); ?>" data-video-title="<?php the_title_attribute(); ?>" aria-label="Se video om <?php the_title_attribute(); ?>">&#9654;</button>
        <?php endif; ?>
      </div>
      <div class="app-body">
        <?php if ( $cat_name_app ) : ?><p class="app-tag"><?php echo esc_html( $cat_name_app ); ?></p><?php endif; ?>
        <h3 class="app-title">
          <?php if ( $url ) : ?>
            <a class="app-card-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php the_title(); ?></a>
          <?php else : the_title(); endif; ?>
        </h3>
        <div class="app-desc"><?php echo wp_kses_post( get_the_content() ); ?></div>
        <?php if ( $url ) : ?><span class="app-link" aria-hidden="true">Besøk &rarr;</span><?php endif; ?>
      </div>
    </li>
    <?php $app_index++; endwhile; wp_reset_postdata(); ?>
  </ul>
  <button type="button" class="apps-more" hidden>Vis flere</button>
</section>

<dialog class="video-modal" id="video-modal" aria-label="Video">
  <button type="button" class="video-modal-close" aria-label="Lukk video">&times;</button>
  <div class="video-modal-frame"></div>
</dialog>

<?php
$lokale = new WP_Query( array( 'post_type' => 'lokalt-prosjekt', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC', 'no_found_rows' => true ) );
if ( $lokale->have_posts() ) : ?>
<section class="projects-section">
  <p class="section-group-label">Lokale prosjekter</p>
  <ul class="projects-list">
    <?php $proj_index = 0;
    while ( $lokale->have_posts() ) : $lokale->the_post();
        $github_url = get_post_meta( get_the_ID(), '_prosjekt_github_url', true );
    ?>
    <li class="project-item reveal" style="transition-delay:<?php echo ( $proj_index % 3 ) * 0.07; ?>s">
      <div class="app-media">
        <?php if ( has_post_thumbnail() ) : ?>
          <?php the_post_thumbnail( 'post-card', array( 'alt' => get_the_title(), 'loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw' ) ); ?>
        <?php else : ?>
          <span class="project-icon" aria-hidden="true">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844a9.59 9.59 0 0 1 2.504.337c1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2z" fill="currentColor"/></svg>
          </span>
        <?php endif; ?>
      </div>
      <div class="app-body">
        <h3 class="app-title">
          <?php if ( $github_url ) : ?>
            <a class="app-card-link" href="<?php echo esc_url( $github_url ); ?>" target="_blank" rel="noopener"><?php the_title(); ?></a>
          <?php else : the_title(); endif; ?>
        </h3>
        <div class="app-desc"><?php echo wp_kses_post( get_the_content() ); ?></div>
        <?php if ( $github_url ) : ?><span class="app-link" aria-hidden="true">Se på GitHub &rarr;</span><?php endif; ?>
      </div>
    </li>
    <?php $proj_index++; endwhile; wp_reset_postdata(); ?>
  </ul>
</section>
<?php endif; ?>

<?php get_footer(); ?>
