<?php

if ( is_active_sidebar( 'footer-1' )) {?>
        <div id="footer-widget" class="row m-0">

            <?php if ( is_active_sidebar( 'footer-1' )) : ?>
                <div class="col-12 p-3"><?php dynamic_sidebar( 'footer-1' ); ?></div>
            <?php endif; ?>

        </div>

<?php }