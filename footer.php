<footer>
  <div class="footer-top">
    <div>
      <a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">Laererliv</a>
      <p class="footer-desc">Kenneth skriver om skole, undervisning, teknologi og det som skjer naar disse verdener moetes. Lektor ved ungdomsskole i Oslo.</p>
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
            <li><a href="<?php echo esc_url( home_url( '/om-laererliv/' ) ); ?>">Om Laererliv</a></li>
        <?php } ?>
      </ul>
    </div>
    <div>
      <p class="footer-col-title">Kontakt</p>
      <ul class="footer-links">
        <li><a href="mailto:kenneth@laererliv.no">kenneth@laererliv.no</a></li>
        <li><a href="https://www.utdanningsnytt.no" target="_blank" rel="noopener">Utdanningsnytt</a></li>
        <li><a href="https://aiavisen.no" target="_blank" rel="noopener">AI Avisen</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>&copy; <?php echo date( 'Y' ); ?> Laererliv</span>
    <span>Fra klasserom til storsamfunn</span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>