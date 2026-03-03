<?php get_header(); ?>

<div class="page-header">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow">Soek</p>
      <h1 class="page-title">Resultat for &laquo;<?php echo esc_html( get_search_query() ); ?>&raquo;</h1>
    </div>
  </div>
</div>

<div class="section-header">
  <h2><?php printf( '%d treff', $wp_query->found_posts ); ?></h2>
</div>

<?php if ( have_posts() ) : ?>
<div class="posts-grid">
  <?php while ( have_posts() ) : the_post();
      get_template_part( 'template-parts/post-card' );
  endwhile; ?>
</div>
<?php the_posts_pagination( array( 'prev_text' => '&larr;', 'next_text' => '&rarr;' ) ); ?>
<?php else : ?>
<div class="about-section"><p>Ingen innlegg funnet. Proev et annet soekeord.</p></div>
<?php endif; ?>

<?php get_footer(); ?>
