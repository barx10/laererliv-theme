<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="page-header">
  <div class="page-header-inner">
    <div>
      <h1 class="page-title"><?php the_title(); ?></h1>
    </div>
  </div>
</div>

<div class="about-section">
  <div class="article-body">
    <?php the_content(); ?>
  </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
