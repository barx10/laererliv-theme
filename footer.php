<footer>
  <div class="footer-top">
    <div>
      <a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">L&aelig;rerliv</a>
      <p class="footer-desc"><?php echo esc_html( get_theme_mod( 'laererliv_footer_desc', 'Tanker om skole, teknologi og alt som er imellom.' ) ); ?></p>
      <img class="footer-logo-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/laererliv-logo.png' ); ?>" alt="L&aelig;rerliv logo" />
    </div>
    <div>
      <p class="footer-col-title">Sider</p>
      <ul class="footer-links">
        <?php
        if ( has_nav_menu( 'footer' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'footer',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'depth'          => 1,
            ) );
        } else {
        ?>
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Hjem</a></li>
            <li><a href="<?php echo esc_url( home_url( '/nedlastninger/' ) ); ?>">Nedlastninger</a></li>
            <li><a href="<?php echo esc_url( home_url( '/apper-og-nettsider/' ) ); ?>">Apper og nettsider</a></li>
            <li><a href="<?php echo esc_url( home_url( '/andre-publikasjoner/' ) ); ?>">Andre publikasjoner</a></li>
            <li><a href="<?php echo esc_url( home_url( '/om-laererliv/' ) ); ?>">Om L&aelig;rerliv</a></li>
        <?php } ?>
      </ul>
    </div>
    <div>
      <p class="footer-col-title">Kontakt</p>
      <ul class="footer-links">
        <li><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'om-laererliv' ) ) . '#kontakt' ); ?>">Kontakt</a></li>
        <?php
        $link1_text = get_theme_mod( 'laererliv_footer_link1_text', 'Utdanningsnytt' );
        $link1_url  = get_theme_mod( 'laererliv_footer_link1_url', 'https://www.utdanningsnytt.no' );
        $link2_text = get_theme_mod( 'laererliv_footer_link2_text', 'AI Avisen' );
        $link2_url  = get_theme_mod( 'laererliv_footer_link2_url', 'https://aiavisen.no' );
        if ( $link1_text && $link1_url ) : ?>
          <li><a href="<?php echo esc_url( $link1_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $link1_text ); ?></a></li>
        <?php endif;
        if ( $link2_text && $link2_url ) : ?>
          <li><a href="<?php echo esc_url( $link2_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $link2_text ); ?></a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; <?php echo date( 'Y' ); ?> L&aelig;rerliv</span>
    <span><?php echo esc_html( get_theme_mod( 'laererliv_footer_tagline', 'Fra klasserom til storsamfunn' ) ); ?></span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>