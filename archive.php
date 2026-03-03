<?php get_header(); ?>

<div class="page-header">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow"><?php echo is_category() ? 'Kategori' : 'Arkiv'; ?></p>
      <h1 class="page-title"><?php the_archive_title(); ?></h1>
    </div>
    <?php if ( get_the_archive_description() ) : ?>
    <p class="page-intro"><?php echo get_the_archive_description(); ?></p>
    <?php endif; ?>
  </div>
</div>

<div class="section-header"><h2>Innlegg</h2></div>
<div class="posts-grid">
  <?php while ( have_posts() ) : the_post();
      get_template_part( 'template-parts/post-card' );
  endwhile; ?>
</div>
<?php the_posts_pagination( array( 'prev_text' => '&larr;', 'next_text' => '&rarr;' ) ); ?>

<?php get_footer(); ?>
