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
  <div class="apps-pagination-nav" id="apps-pagination-nav" style="display:none">
    <button class="apps-page-btn" id="apps-prev" aria-label="Forrige" disabled>
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M11 13l-4-4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    <span class="apps-page-count"><span id="apps-page-current">1</span> / <span id="apps-page-total">1</span></span>
    <button class="apps-page-btn" id="apps-next" aria-label="Neste">
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M7 5l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
  </div>
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

<?php
$lokale = new WP_Query( array( 'post_type' => 'lokalt-prosjekt', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
if ( $lokale->have_posts() ) : ?>
<section class="projects-section">
  <div class="projects-header">
    <p class="page-eyebrow">Lokale prosjekter</p>
    <h2 class="projects-heading">Verktøy som kjøres <em>lokalt</em></h2>
    <p class="projects-intro">Repoer som ikke er deployert — last ned og kjør på din egen maskin.</p>
  </div>
  <ul class="projects-list">
    <?php while ( $lokale->have_posts() ) : $lokale->the_post();
        $github_url = get_post_meta( get_the_ID(), '_prosjekt_github_url', true );
    ?>
    <li class="project-item reveal">
      <div class="project-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0 1 12 6.844a9.59 9.59 0 0 1 2.504.337c1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.02 10.02 0 0 0 22 12.017C22 6.484 17.522 2 12 2z" fill="currentColor"/></svg>
      </div>
      <div>
        <p class="project-title"><?php the_title(); ?></p>
        <p class="project-desc"><?php echo wp_kses_post( get_the_content() ); ?></p>
      </div>
      <?php if ( $github_url ) : ?>
        <a class="project-link" href="<?php echo esc_url( $github_url ); ?>" target="_blank" rel="noopener">Se på GitHub &rarr;</a>
      <?php endif; ?>
    </li>
    <?php endwhile; wp_reset_postdata(); ?>
  </ul>
</section>
<?php endif; ?>

<?php get_footer(); ?>
