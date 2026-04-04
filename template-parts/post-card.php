<?php
$cats = get_the_category();
$cat_name = ! empty( $cats ) ? $cats[0]->name : '';
?>
<div class="post-card">
  <a class="post-card-link" href="<?php the_permalink(); ?>">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="post-card-img">
      <?php the_post_thumbnail( 'post-card', array( 'alt' => get_the_title() ) ); ?>
    </div>
    <?php endif; ?>
    <?php if ( $cat_name ) : ?>
      <span class="post-tag"><?php echo esc_html( $cat_name ); ?></span>
    <?php endif; ?>
    <h3 class="post-card-title"><?php the_title(); ?></h3>
    <p class="post-card-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
    <div class="post-card-foot">
      <span class="post-date"><?php echo laererliv_kort_dato(); ?></span>
      <span class="post-arrow">&rarr;</span>
    </div>
  </a>
</div>
