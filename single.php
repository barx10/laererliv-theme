<?php get_header(); ?>

<div class="read-progress" id="read-progress"></div>

<div class="breadcrumb">
  <div class="breadcrumb-inner">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Hjem</a>
    <span class="breadcrumb-sep">/</span>
    <?php $cats = get_the_category();
    if ( ! empty( $cats ) ) : ?>
      <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a>
      <span class="breadcrumb-sep">/</span>
    <?php endif; ?>
    <span class="breadcrumb-current"><?php the_title(); ?></span>
  </div>
</div>

<?php while ( have_posts() ) : the_post(); ?>

<header class="article-header">
  <div class="article-header-main">
    <?php if ( ! empty( $cats ) ) : ?>
      <p class="article-category"><?php echo esc_html( $cats[0]->name ); ?></p>
    <?php endif; ?>
    <h1 class="article-title"><?php the_title(); ?></h1>
    <div class="article-meta">
      <div class="article-author">
        <span class="article-author-name"><?php the_author(); ?></span>
      </div>
      <div class="article-meta-divider"></div>
      <span class="article-date"><?php echo laererliv_norsk_dato(); ?></span>
      <div class="article-meta-divider"></div>
      <span class="article-readtime"><?php echo laererliv_lesetid( get_the_ID() ); ?></span>
    </div>
  </div>
  <div class="article-header-aside">
    <?php if ( has_excerpt() ) : ?>
    <div class="article-excerpt-box">
      <p class="article-excerpt-label">Kortversjon</p>
      <p class="article-excerpt-text"><?php echo esc_html( get_the_excerpt() ); ?></p>
    </div>
    <?php endif; ?>
  </div>
</header>

<?php if ( has_post_thumbnail() ) : ?>
<div class="article-image">
  <div class="article-image-inner">
    <?php the_post_thumbnail( 'full' ); ?>
  </div>
</div>
<?php endif; ?>

<div class="article-layout">
  <article class="article-body">
    <?php the_content(); ?>
  </article>

  <aside class="article-sidebar">
    <?php $all_cats = get_the_category(); if ( ! empty( $all_cats ) ) : ?>
    <div class="sidebar-block">
      <p class="sidebar-label">Kategorier</p>
      <div class="sidebar-tags">
        <?php foreach ( $all_cats as $cat ) : ?>
          <a class="sidebar-tag" href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="sidebar-block">
      <p class="sidebar-label">Del innlegget</p>
      <div class="sidebar-share">
        <a class="share-btn" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" rel="noopener">&rarr; Del paa LinkedIn</a>
        <a class="share-btn" href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&amp;text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" rel="noopener">&rarr; Del paa Twitter/X</a>
        <a class="share-btn share-copy" href="#">&rarr; Kopier lenke</a>
      </div>
    </div>
  </aside>
</div>

<?php endwhile; ?>

<nav class="post-nav" aria-label="Naviger mellom innlegg">
  <div class="post-nav-inner">
    <?php $prev = get_previous_post(); $next = get_next_post(); ?>
    <?php if ( $prev ) : ?>
    <a class="post-nav-item prev" href="<?php echo esc_url( get_permalink( $prev ) ); ?>">
      <span class="post-nav-direction">&larr; Forrige innlegg</span>
      <span class="post-nav-title"><?php echo esc_html( $prev->post_title ); ?></span>
    </a>
    <?php else : ?><span class="post-nav-item prev"></span><?php endif; ?>

    <?php if ( $next ) : ?>
    <a class="post-nav-item next" href="<?php echo esc_url( get_permalink( $next ) ); ?>">
      <span class="post-nav-direction">Neste innlegg &rarr;</span>
      <span class="post-nav-title"><?php echo esc_html( $next->post_title ); ?></span>
    </a>
    <?php else : ?><span class="post-nav-item next"></span><?php endif; ?>
  </div>
</nav>

<?php get_footer(); ?>
