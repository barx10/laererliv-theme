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
  <ul class="apps-list">
    <?php
    $apps = new WP_Query( array( 'post_type' => 'app', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
    $app_index = 0;
    while ( $apps->have_posts() ) : $apps->the_post();
        $url      = get_post_meta( get_the_ID(), '_app_url', true );
        $emoji    = get_post_meta( get_the_ID(), '_app_emoji', true );
        $ikon_url = get_post_meta( get_the_ID(), '_app_ikon_url', true );
        $terms    = get_the_terms( get_the_ID(), 'app_kategori' );
        $cat_slug = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : '';
        $cat_name_app = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
    ?>
    <li class="app-item reveal" data-category="<?php echo esc_attr( $cat_slug ); ?>" style="transition-delay:<?php echo $app_index * 0.07; ?>s">
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="app-thumb"><?php the_post_thumbnail( 'cpt-thumb' ); ?></div>
      <?php elseif ( $ikon_url ) : ?>
        <div class="app-thumb"><img src="<?php echo esc_url( $ikon_url ); ?>" alt="<?php the_title_attribute(); ?>"></div>
      <?php else : ?>
        <div class="app-icon"><?php echo esc_html( $emoji ?: '🔗' ); ?></div>
      <?php endif; ?>
      <div>
        <?php if ( $cat_name_app ) : ?><p class="app-tag"><?php echo esc_html( $cat_name_app ); ?></p><?php endif; ?>
        <p class="app-title"><?php the_title(); ?></p>
        <p class="app-desc"><?php echo wp_kses_post( get_the_content() ); ?></p>
      </div>
      <?php if ( $url ) : ?>
        <a class="app-link" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">Besøk &rarr;</a>
      <?php endif; ?>
    </li>
    <?php $app_index++; endwhile; wp_reset_postdata(); ?>
  </ul>
</section>

<?php get_footer(); ?>
