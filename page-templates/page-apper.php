<?php
/**
 * Template Name: Apper og nettsider
 */
get_header(); ?>

<div class="page-header page-header--tall">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow page-anim" style="animation-delay:.1s">Digitale verktoey</p>
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

<section class="apps-section">
  <div class="apps-grid reveal">
    <?php
    $apps = new WP_Query( array( 'post_type' => 'app', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
    $app_index = 0;
    while ( $apps->have_posts() ) : $apps->the_post();
        $url      = get_post_meta( get_the_ID(), '_app_url', true );
        $emoji    = get_post_meta( get_the_ID(), '_app_emoji', true );
        $terms    = get_the_terms( get_the_ID(), 'app_kategori' );
        $cat_slug = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
        $cat_name_app = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
    ?>
    <div class="app-card" data-category="<?php echo esc_attr( $cat_slug ); ?>" style="animation-delay:<?php echo 0.6 + $app_index * 0.08; ?>s">
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="app-thumb"><?php the_post_thumbnail( 'cpt-thumb' ); ?></div>
      <?php else : ?>
        <div class="app-icon"><?php echo esc_html( $emoji ?: '🔗' ); ?></div>
      <?php endif; ?>
      <?php if ( $cat_name_app ) : ?><span class="app-tag"><?php echo esc_html( $cat_name_app ); ?></span><?php endif; ?>
      <h3 class="app-title"><?php the_title(); ?></h3>
      <p class="app-desc"><?php echo wp_trim_words( get_the_content(), 25 ); ?></p>
      <?php if ( $url ) : ?>
        <a class="app-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">Besoek &rarr;</a>
      <?php endif; ?>
    </div>
    <?php $app_index++; endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php get_footer(); ?>
