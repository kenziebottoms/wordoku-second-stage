<?php
/**
 * Template for Shows
 */

get_header();
?>
    <section id="primary" class="content-area play">
        <main id="main" class="site-main row" role="main">
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="col col-8 offset-2 offset-md-0 col-md-6 col-lg-4">
                    <?php the_post_thumbnail(); ?>
                    <?php if (get_field("location")) { ?>
                        <h4>Location</h4>
                        <?php $location = get_field("location"); ?>
                        <div class="acf-map">
                            <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                        </div>
                        <?php the_field("address"); ?>
                    <?php } ?>
                </div>
                <div class="col col-12 col-md-6 px-5 px-md-3 col-lg-8">
                    <h3 class="show-title"><?php the_title(); ?></h3>
                    <?php if (get_field("playwright")) { ?>
                        <h4 class="playwright">by <?php the_field("playwright"); ?></h4>
                    <?php } ?>
                    <?php get_template_part( 'template-parts/content', 'notitle' ); ?>
                    <div class="row">
                        <?php if (get_field("dates") && get_field("times")) { ?>
                            <div class="col col-12 col-md-6">
                                <?php $dates = explode(",", get_field("dates"));
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
                        <?php } ?>
                        <?php if (get_field("admission") && get_field("ticket_link")) { ?>
                            <div class="col col-12 col-lg-6">
                                <h4>Admission</h4>
                                <div><?php the_field("admission"); ?></div>
                                <a target="blank" class="btn-block col-6 offset-3 btn btn-outline-light" href="<?php the_field("ticket_link"); ?>">Buy Tickets</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php $show_id = get_the_ID(); ?>
                <?php $cast = new WP_Query(array(
                    'max_num_pages' => '-1',
                    'posts_per_page' => '-1',
                    'post_type'		=> 'cast',
                    'meta_key'      => 'show',
                    'meta_value'    => $show_id,
                    'orderby'       => 'date',
                    'order'         => 'ASC',
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key'   => 'show',
                            'value' => $show_id
                        ),
                        array(
                            'key'   => 'cast_or_crew',
                            'value' => 'cast'
                        )
                    )
                )); ?>

                <?php $crew = new WP_Query(array(
                    'max_num_pages' => '-1',
                    'posts_per_page' => '-1',
                    'post_type'		=> 'cast',
                    'meta_key'      => 'show',
                    'meta_value'    => $show_id,
                    'orderby'       => 'date',
                    'order'         => 'ASC',
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key'   => 'show',
                            'value' => $show_id
                        ),
                        array(
                            'key'   => 'cast_or_crew',
                            'value' => 'crew'
                        )
                    )
                )); ?>
                <?php if ($cast->have_posts()) : ?>
                    <div class="col col-12 px-5 px-md-3" id="cast-verbose">
                        <h3>Cast</h3>
                        <div class="grid">
                            <?php while ($cast->have_posts()) : $cast->the_post(); ?>
                                <div class="block" id="<?php the_ID(); ?>">
                                    <?php if (has_post_thumbnail()) {
                                        the_post_thumbnail();
                                    } ?>
                                    <h4><?php the_title(); ?></h4>
                                    <h5><?php the_field("role"); ?></h5>
                                    <?php the_content(); ?>
                                </div>
                                <?php endwhile; ?>            
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($crew->have_posts()) : ?>
                    <div class="col col-12 px-5 px-md-3" id="cast-verbose">
                        <h3>Crew</h3>
                        <div class="grid">
                            <?php while ($crew->have_posts()) : $crew->the_post(); ?>
                                <div class="block" id="<?php the_ID(); ?>">
                                    <?php if (has_post_thumbnail()) {
                                        the_post_thumbnail();
                                    } ?>
                                    <h4><?php the_title(); ?></h4>
                                    <h5><?php the_field("role"); ?></h5>
                                    <?php the_content(); ?>
                                </div>
                                <?php endwhile; ?>            
                        </div>
                    </div>
                <?php endif;
                wp_reset_query(); ?>
                <?php if (get_field("gallery")) { ?>
                    <div class="col col-12">
                        <h3>Gallery</h3>
                        <?php the_field("gallery"); ?>
                    </div>
                <?php } ?>
            <?php endwhile; ?>
        </main><!-- #main -->
    </section><!-- #primary -->

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwcCspNaN_qtrylz3QrhoBvuce_S71T_U"></script>
    <script type="text/javascript">
        (function($) {
            function new_map( $el ) {
        
                // var
                var $markers = $el.find('.marker');
                
                
                // vars
                var args = {
                    zoom		: 16,
                    center		: new google.maps.LatLng(0, 0),
                    mapTypeId	: google.maps.MapTypeId.ROADMAP
                };
                
                
                // create map	        	
                var map = new google.maps.Map( $el[0], args);
                
                
                // add a markers reference
                map.markers = [];
                
                
                // add markers
                $markers.each(function(){
                    
                    add_marker( $(this), map );
                    
                });
                
                
                // center map
                center_map( map );
                
                
                // return
                return map;
                
            }
            
            function add_marker( $marker, map ) {

                // var
                var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );

                // create marker
                var marker = new google.maps.Marker({
                    position	: latlng,
                    map			: map
                });

                // add to array
                map.markers.push( marker );

                // if marker contains HTML, add it to an infoWindow
                if( $marker.html() )
                {
                    // create info window
                    var infowindow = new google.maps.InfoWindow({
                        content		: $marker.html()
                    });

                    // show info window when marker is clicked
                    google.maps.event.addListener(marker, 'click', function() {

                        infowindow.open( map, marker );

                    });
                }

            }
            function center_map( map ) {

                // vars
                var bounds = new google.maps.LatLngBounds();

                // loop through all markers and create bounds
                $.each( map.markers, function( i, marker ){

                    var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

                    bounds.extend( latlng );

                });

                // only 1 marker?
                if( map.markers.length == 1 )
                {
                    // set center of map
                    map.setCenter( bounds.getCenter() );
                    map.setZoom( 16 );
                }
                else
                {
                    // fit to bounds
                    map.fitBounds( bounds );
                }

            }

            var map = null;

            $(document).ready(function(){

                $('.acf-map').each(function(){

                    // create map
                    map = new_map( $(this) );

                });

            });

        })(jQuery);

    </script>
<?php
get_footer();
