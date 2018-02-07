<?php
/**
 * Template Name: Play w/Featured Poster
 */

get_header();
?>
    <section id="primary" class="content-area play">
        <main id="main" class="site-main" role="main">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php the_post_thumbnail(); ?>
                <div class="container">
                    <?php get_template_part( 'template-parts/content', 'notitle' );
                    $dates = explode(",", get_field("dates"));
                    $times = explode(",", get_field("times")); ?>
                    <h4>Showtimes</h4>
                    <table class="table">
                        <?php for ($i=0; $i<strlen(dates)-1; $i++) { ?>
                            <tr>
                                <td><?php echo $times[$i]; ?></td>
                                <td><?php echo $dates[$i]; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php endwhile; ?>
        </main><!-- #main -->
    </section><!-- #primary -->

<?php
get_footer();
