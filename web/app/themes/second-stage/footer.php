<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?>
<?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
			</div><!-- .row -->
		</div><!-- .container -->
	</div><!-- #content -->
    <?php get_template_part( 'footer-widget' ); ?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container-fluid">
			<div id="social-links">
				<?php if (get_option("phone")) { ?>
					<a target="blank" href="tel:<?php echo get_option('phone'); ?>">
						<i class="ion-android-call"></i>
					</a>
				<?php } ?>
				<?php if (get_option("facebook_url")) { ?>
					<a target="blank" href="<?php echo get_option('facebook_url'); ?>">
						<i class="ion-social-facebook"></i>
					</a>
				<?php } ?>
				<?php if (get_option("instagram_url")) { ?>
					<a target="blank" href="<?php echo get_option('instagram_url'); ?>">
						<i class="ion-social-instagram"></i>
					</a>
				<?php } ?>
				<?php if (get_option("twitter_url")) { ?>
					<a target="blank" href="<?php echo get_option('twitter_url'); ?>">
						<i class="ion-social-twitter"></i>
					</a>
				<?php } ?>
				<?php if (get_option("gplus_url")) { ?>
					<a target="blank" href="<?php echo get_option('gplus_url'); ?>">
						<i class="ion-social-googleplus"></i>
					</a>
				<?php } ?>
				<?php if (get_option("email")) { ?>
					<a target="blank" href="mailto:<?php echo get_option('email'); ?>">
						<i class="ion-android-mail"></i>
					</a>
				<?php } ?>
			</div>
            <div class="site-info">
                &copy; <?php echo date('Y'); ?> <?php echo '<a href="'.home_url().'">'.get_bloginfo('description').'</a>'; ?>
            </div><!-- close .site-info -->
		</div>
	</footer><!-- #colophon -->
<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
