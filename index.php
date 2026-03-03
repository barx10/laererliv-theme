<?php get_header(); ?>

<?php if ( is_front_page() ) : ?>

<section class="hero">
  <div class="hero-text">
    <p class="hero-eyebrow"><?php echo esc_html( get_theme_mod( 'laererliv_hero_eyebrow', 'Fra klasserom til storsamfunn' ) ); ?></p>
    <h1 class="hero-headline"><?php echo wp_kses_post( get_theme_mod( 'laererliv_hero_headline', 'Tanker om <em>skole</em>, teknologi og alt imellom' ) ); ?></h1>
    <p class="hero-sub"><?php echo esc_html( get_theme_mod( 'laererliv_hero_sub', 'Kenneth Bareksten skriver om undervisning, digital kompetanse og hva som skjer naar laerere tar pennen.' ) ); ?></p>
    <?php $cta_text = get_theme_mod( 'laererliv_hero_cta_text', 'Les innleggene' ); if ( $cta_text ) : ?>
    <a class="hero-cta" href="<?php echo esc_url( get_theme_mod( 'laererliv_hero_cta_url', '#featured' ) ); ?>"><?php echo esc_html( $cta_text ); ?> &rarr;</a>
    <?php endif; ?>
  </div>
  <div class="hero-portrait">
    <?php $hero_img = get_theme_mod( 'laererliv_hero_image' );
    if ( $hero_img ) : ?>
      <img class="hero-portrait-img" src="<?php echo esc_url( $hero_img ); ?>" alt="<?php echo esc_attr( get_theme_mod( 'laererliv_hero_name', 'Kenneth Bareksten' ) ); ?>">
    <?php else : ?>
      <div class="hero-portrait-svg"></div>
    <?php endif; ?>
    <div class="hero-portrait-overlay"></div>
    <div class="hero-portrait-caption">
      <p class="hero-portrait-name"><?php echo esc_html( get_theme_mod( 'laererliv_hero_name', 'Kenneth Bareksten' ) ); ?></p>
      <p class="hero-portrait-role"><?php echo esc_html( get_theme_mod( 'laererliv_hero_role', 'Lektor · Skribent · Foredragsholder' ) ); ?></p>
    </div>
  </div>
</section>

<?php
$featured = new WP_Query( array( 'posts_per_page' => 1, 'post_status' => 'publish' ) );
if ( $featured->have_posts() ) : $featured->the_post();
    $cats = get_the_category();
    $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
?>
<section class="featured reveal" id="featured">
  <div>
    <p class="featured-label"><?php echo esc_html( get_theme_mod( 'laererliv_featured_label', 'Siste innlegg' ) ); ?></p>
    <h2 class="featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <p class="featured-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 35 ); ?></p>
    <a class="hero-cta" href="<?php the_permalink(); ?>">Les videre &rarr;</a>
  </div>
  <div class="featured-img">
    <?php if ( has_post_thumbnail() ) the_post_thumbnail( 'featured-large' ); ?>
    <?php if ( $cat_name ) : ?><span class="featured-img-label"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
  </div>
</section>
<?php wp_reset_postdata(); endif; ?>

<div class="section-header reveal"><h2><?php echo esc_html( get_theme_mod( 'laererliv_random_heading', 'Tilfeldig utvalg' ) ); ?></h2></div>
<div class="posts-grid reveal">
  <?php
  $random = new WP_Query( array( 'posts_per_page' => 3, 'orderby' => 'rand', 'post_status' => 'publish', 'offset' => 1 ) );
  while ( $random->have_posts() ) : $random->the_post();
      get_template_part( 'template-parts/post-card' );
  endwhile; wp_reset_postdata(); ?>
</div>

<div class="section-header reveal"><h2><?php echo esc_html( get_theme_mod( 'laererliv_archive_heading', 'Arkiv' ) ); ?></h2></div>
<section class="archive-section reveal">
  <div class="archive-years">
    <p class="archive-years-label"><?php echo esc_html( get_theme_mod( 'laererliv_archive_years_label', 'Velg aar' ) ); ?></p>
    <div class="year-wheel-wrapper">
      <div class="year-wheel-mask">
        <div class="year-wheel-highlight"></div>
        <ul class="year-list" id="year-wheel">
          <?php
          global $wpdb;
          $years = $wpdb->get_col( "SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status='publish' AND post_type='post' ORDER BY YEAR(post_date) DESC" );
          foreach ( $years as $i => $year ) : ?>
            <li><button class="year-btn<?php echo $i === 0 ? ' active' : ''; ?>" data-year="<?php echo esc_attr( $year ); ?>"><?php echo esc_html( $year ); ?></button></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
  <ul class="archive-post-list">
    <?php
    $archive = new WP_Query( array( 'posts_per_page' => -1, 'post_status' => 'publish', 'orderby' => 'date', 'order' => 'DESC' ) );
    while ( $archive->have_posts() ) : $archive->the_post();
        $cats = get_the_category();
        $cat_name = ! empty( $cats ) ? $cats[0]->name : '';
    ?>
    <li class="archive-post-item" data-year="<?php echo get_the_date( 'Y' ); ?>">
      <span class="archive-post-month"><?php echo laererliv_norsk_maaned( get_the_date( 'n' ) ); ?></span>
      <div>
        <p class="archive-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
        <?php if ( $cat_name ) : ?><p class="archive-post-tag"><?php echo esc_html( $cat_name ); ?></p><?php endif; ?>
      </div>
    </li>
    <?php endwhile; wp_reset_postdata(); ?>
  </ul>
</section>

<?php else : ?>

<div class="page-header page-header--tall">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow page-anim" style="animation-delay:.1s">Blogg</p>
      <h1 class="page-title page-anim" style="animation-delay:.25s">Alle <em>innlegg</em></h1>
    </div>
    <p class="page-intro page-anim" style="animation-delay:.4s">Artikler og refleksjoner fra Kenneth Bareksten.</p>
  </div>
</div>
<div class="section-header reveal"><h2>Innlegg</h2></div>
<div class="posts-grid reveal">
  <?php while ( have_posts() ) : the_post();
      get_template_part( 'template-parts/post-card' );
  endwhile; ?>
</div>
<?php the_posts_pagination( array( 'prev_text' => '&larr;', 'next_text' => '&rarr;' ) ); ?>

<?php endif; ?>

<?php get_footer(); ?>
