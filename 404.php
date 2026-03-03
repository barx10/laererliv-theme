<?php get_header(); ?>

<div class="page-header page-header--tall">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow page-anim" style="animation-delay:.1s">404</p>
      <h1 class="page-title page-anim" style="animation-delay:.25s">Siden finnes <em>ikke</em></h1>
    </div>
    <p class="page-intro page-anim" style="animation-delay:.4s">Beklager, men siden du leter etter ser ut til aa ha forsvunnet. <a class="page-intro-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">Gaa til forsiden</a>.</p>
  </div>
</div>

<?php get_footer(); ?>
