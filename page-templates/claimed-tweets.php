<?php
/**
 * Template Name: Claimed Tweets
 *
 * Template for displaying Tweets that haven't been claimed
 *
 * @package understrap
 */

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="full-width-page-wrapper">

	<div class="<?php echo esc_html( $container ); ?>" id="content">

		<div class="row">

			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main" role="main">

					<?php while ( have_posts() ) : the_post(); ?>
						<?php the_content();?>
						<?php
						$claimed = get_term_by('slug','claimed','post_tag'); //gets claimed tag 
						$claimedID = $claimed->term_id;
						$args = array( 
							'post_type' => 'tweet',
							'tag' => array( $claimedID ), //INCLUDES tweets with tag 'claimed'
							'orderby' => 'rand',
							'posts_per_page' => 10, 
							);

						$the_query = new WP_Query( $args );
						// The Loop
						if ( $the_query->have_posts() ) :
						while ( $the_query->have_posts() ) : $the_query->the_post();
						  

							if (get_post_meta(get_the_ID(), 'tweet', true)) {

								echo '<div class="wtf-tweet-holder" data-link="' . get_the_ID() . '">';
						        echo wp_oembed_get(get_post_meta(get_the_ID(), 'tweet', true));
						        echo '</div>';
						      } //gets Tweet url from custom field tweet and displays		

						endwhile;
						endif;
						// Reset Post Data
						wp_reset_postdata();
			      
						?>


						<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :

							comments_template();

						endif;
						?>

					<?php endwhile; // end of the loop. ?>

				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row end -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
