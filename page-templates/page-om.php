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
    <p class="page-intro page-anim" style="animation-delay:.4s"><?php echo esc_html( get_theme_mod( 'laererliv_om_intro', 'Bloggen drives av Kenneth Bareksten, lektor og skribent i Oslo.' ) ); ?></p>
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

      <?php
      // --- Foredrag ---
      $foredrag = new WP_Query( array(
        'post_type'      => 'foredrag',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
      ) );
      if ( $foredrag->have_posts() ) : ?>
      <div class="cv-block">
        <p class="cv-label">Foredrag</p>
        <ul class="cv-list">
          <?php while ( $foredrag->have_posts() ) : $foredrag->the_post();
            $arrangement = get_post_meta( get_the_ID(), '_foredrag_arrangement', true );
            $dato        = get_post_meta( get_the_ID(), '_foredrag_dato', true );
            $sted        = get_post_meta( get_the_ID(), '_foredrag_sted', true );
            $url         = get_post_meta( get_the_ID(), '_foredrag_url', true );
          ?>
          <li>
            <?php if ( $dato ) : ?><span class="cv-year"><?php echo esc_html( $dato ); ?></span><?php endif; ?>
            <?php if ( $url ) : ?>
              <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php the_title(); ?></a>
            <?php else : ?>
              <?php the_title(); ?>
            <?php endif; ?>
            <?php if ( $arrangement ) : ?><br><small><?php echo esc_html( $arrangement ); ?><?php if ( $sted ) echo ', ' . esc_html( $sted ); ?></small><?php endif; ?>
          </li>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
      </div>
      <?php endif; ?>

      <?php
      // --- Manuskonsulent ---
      $manus = new WP_Query( array(
        'post_type'      => 'manuskonsulent',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
      ) );
      if ( $manus->have_posts() ) : ?>
      <div class="cv-block">
        <p class="cv-label">Manuskonsulent</p>
        <ul class="cv-list">
          <?php while ( $manus->have_posts() ) : $manus->the_post();
            $oppdragsgiver = get_post_meta( get_the_ID(), '_manus_oppdragsgiver', true );
            $aar           = get_post_meta( get_the_ID(), '_manus_aar', true );
            $url           = get_post_meta( get_the_ID(), '_manus_url', true );
          ?>
          <li>
            <?php if ( $aar ) : ?><span class="cv-year"><?php echo esc_html( $aar ); ?></span><?php endif; ?>
            <?php if ( $url ) : ?>
              <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php the_title(); ?></a>
            <?php else : ?>
              <?php the_title(); ?>
            <?php endif; ?>
            <?php if ( $oppdragsgiver ) : ?><br><small><?php echo esc_html( $oppdragsgiver ); ?></small><?php endif; ?>
          </li>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
      </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<?php get_footer(); ?>
