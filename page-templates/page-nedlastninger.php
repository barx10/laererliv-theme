<?php
/**
 * Template Name: Nedlastninger
 */
get_header(); ?>

<div class="page-header page-header--tall">
  <div class="page-header-inner">
    <div>
      <p class="page-eyebrow page-anim" style="animation-delay:.1s">Ressurser</p>
      <h1 class="page-title page-anim" style="animation-delay:.25s">Ned&shy;lastninger</h1>
    </div>
    <p class="page-intro page-anim" style="animation-delay:.4s">Boeker, artikler og undervisningsmateriell til fri nedlasting.</p>
  </div>
</div>

<?php
$dl_cats = get_terms( array( 'taxonomy' => 'nedlastning_kategori', 'hide_empty' => true ) );
if ( ! empty( $dl_cats ) && ! is_wp_error( $dl_cats ) ) : ?>
<div class="filter-bar page-anim" style="animation-delay:.5s">
  <div class="filter-inner">
    <button class="filter-btn active" data-filter="alle">Alle</button>
    <?php foreach ( $dl_cats as $cat ) : ?>
      <button class="filter-btn" data-filter="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<section class="downloads-section">
  <ul class="downloads-list">
    <?php
    $downloads = new WP_Query( array( 'post_type' => 'nedlastning', 'posts_per_page' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
    $dl_index = 0;
    while ( $downloads->have_posts() ) : $downloads->the_post();
        $filtype  = get_post_meta( get_the_ID(), '_nedlastning_filtype', true );
        $filstr   = get_post_meta( get_the_ID(), '_nedlastning_filstr', true );
        $fil_url  = get_post_meta( get_the_ID(), '_nedlastning_fil_url', true );
        $aar      = get_post_meta( get_the_ID(), '_nedlastning_aar', true );
        $dl_terms = get_the_terms( get_the_ID(), 'nedlastning_kategori' );
        $cat_slug = ( $dl_terms && ! is_wp_error( $dl_terms ) ) ? $dl_terms[0]->slug : '';
        $cat_name_dl = ( $dl_terms && ! is_wp_error( $dl_terms ) ) ? $dl_terms[0]->name : '';
        $icon_class = strtolower( $filtype );
    ?>
    <li class="download-item reveal" data-category="<?php echo esc_attr( $cat_slug ); ?>" style="transition-delay:<?php echo $dl_index * 0.07; ?>s">
      <div class="download-icon <?php echo esc_attr( $icon_class ); ?>">
        <?php echo esc_html( strtoupper( $filtype ?: 'PDF' ) ); ?>
      </div>
      <div class="download-body">
        <?php if ( $cat_name_dl ) : ?><p class="download-tag"><?php echo esc_html( $cat_name_dl ); ?></p><?php endif; ?>
        <h3 class="download-title"><?php the_title(); ?></h3>
        <p class="download-desc"><?php echo wp_trim_words( get_the_content(), 30 ); ?></p>
        <?php if ( $filtype || $filstr || $aar ) : ?>
          <p class="download-meta">
            <?php echo esc_html( strtoupper( $filtype ) ); ?>
            <?php if ( $filstr ) echo ' &middot; ' . esc_html( $filstr ); ?>
            <?php if ( $aar ) echo ' &middot; ' . esc_html( $aar ); ?>
          </p>
        <?php endif; ?>
      </div>
      <div class="download-actions">
        <?php if ( $fil_url ) : ?>
          <a class="download-btn" href="<?php echo esc_url( $fil_url ); ?>" download>&darr; Last ned</a>
          <a class="download-btn secondary" href="<?php echo esc_url( $fil_url ); ?>" target="_blank" rel="noopener">&nearr; Se</a>
        <?php endif; ?>
      </div>
    </li>
    <?php $dl_index++; endwhile; wp_reset_postdata(); ?>
  </ul>
</section>

<?php get_footer(); ?>
