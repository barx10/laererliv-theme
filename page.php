<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="page-header page-header--tall">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow page-anim" style="animation-delay:.1s">Side</p>
      <h1 class="page-title page-anim" style="animation-delay:.25s"><?php the_title(); ?></h1>
    </div>
  </div>
</div>

<div class="about-section reveal">
  <div class="about-body">
    <?php the_content(); ?>
  </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
