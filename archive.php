<?php get_header(); ?>

<div class="page-header page-header--tall">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow page-anim" style="animation-delay:.1s"><?php echo is_category() ? 'Kategori' : 'Arkiv'; ?></p>
      <h1 class="page-title page-anim" style="animation-delay:.25s"><?php the_archive_title(); ?></h1>
    </div>
    <?php if ( get_the_archive_description() ) : ?>
    <p class="page-intro page-anim" style="animation-delay:.4s"><?php echo get_the_archive_description(); ?></p>
    <?php endif; ?>
  </div>
</div>

<div class="section-header reveal"><h2>Innlegg</h2></div>
<div class="posts-grid reveal">
  <?php while ( have_posts() ) : the_post();
      get_template_part( 'template-parts/post-card' );
  endwhile; ?>
</div>
<?php the_posts_pagination( array( 'prev_text' => '&larr;', 'next_text' => '&rarr;' ) ); ?>

<?php get_footer(); ?>
