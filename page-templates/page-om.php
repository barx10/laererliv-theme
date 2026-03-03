<?php
/**
 * Template Name: Om Laererliv
 */
get_header(); ?>

<div class="page-header page-header--tall">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow page-anim" style="animation-delay:.1s">Hvem</p>
      <h1 class="page-title page-anim" style="animation-delay:.25s">Om <em>L&aelig;rerliv</em></h1>
    </div>
    <p class="page-intro page-anim" style="animation-delay:.4s">Bloggen drives av Kenneth Bareksten, lektor og skribent i Oslo.</p>
  </div>
</div>

<section class="about-section reveal">
  <div class="about-grid">
    <div class="about-body">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </div>
    <div class="about-sidebar">
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="about-photo"><?php the_post_thumbnail( 'hero-portrait' ); ?></div>
      <?php endif; ?>

      <div class="cv-block">
        <p class="cv-label">Utdanning</p>
        <ul class="cv-list">
          <li><span class="cv-year">2007</span> Lektor, UiO</li>
          <li><span class="cv-year">2002</span> Cand.mag., UiO</li>
        </ul>
      </div>

      <div class="cv-block">
        <p class="cv-label">Erfaring</p>
        <ul class="cv-list">
          <li><span class="cv-year">2007&ndash;</span> Lektor, ungdomsskole i Oslo</li>
          <li><span class="cv-year">2019&ndash;</span> Skribent, Utdanningsnytt</li>
          <li><span class="cv-year">2023&ndash;</span> Skribent, AI Avisen</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
