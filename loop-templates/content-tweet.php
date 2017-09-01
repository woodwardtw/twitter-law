<?php
/**
 * tweets post partial template.
 *
 * @package understrap
 */

?>

<?php 

//allowed HTML for the editor 
$html = array(
    'a' => array(
        'href' => array(),
        'title' => array()
    ),
    'br' => array(),
    'em' => array(),
    'strong' => array(),
);


//form stuff
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && ! empty($_POST['post_id']) && ! empty($_POST['post_title']) && isset($_POST['update_post_nonce']) && isset($_POST['postcontent']) )
{
    $post_id   = $_POST['post_id'];
    $post_type = get_post_type($post_id);
    $capability = ( 'page' == $post_type ) ? 'edit_page' : 'edit_post';
    if ( current_user_can($capability, $post_id) && wp_verify_nonce( $_POST['update_post_nonce'], 'update_post_'. $post_id ) )
    {
        $post = array(
            'ID'             => esc_sql($post_id),
            'post_content'   => wp_kses($_POST['postcontent'], $html),
            'post_title'     => esc_sql($_POST['post_title'])
        );
        wp_update_post($post);
        wp_set_post_tags($post_id, 'claimed', true);//tag them as claimed to make showing claimed vs unclaimed easier
        //category updates
        $clean_cats = esc_sql($_POST['cat_list']);       
        $cats = array_map('intval', explode(',', $clean_cats));// get it as integers the way we need it

        if ( isset($_POST['cat_list']) ) wp_set_object_terms($post_id, $cats, 'category');        

    }
    else
    {
        wp_die("You can't do that");
    }

    global $post;
    $post_slug = $post->post_name;

      echo '<script>window.location = "' . $post_slug . '"</script>';//THIS IS SUPER UGLY  but kept failing on a regular update to behave as I expected
    
}?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header"><!-- .entry-header -->
   <div class="the-tweet">
      <?php 
      if (get_post_meta(get_the_ID(), 'tweet', true)) {
        echo wp_oembed_get(get_post_meta(get_the_ID(), 'tweet', true));
      } //gets Tweet url from custom field tweet and displays?>
    </div>  
	</header><!-- .entry-header -->
  
  <div class="row justify-content-center">
    <div class="col-md-10 analysis-text">
      <?php 
        $title = get_the_title();
        $claimed = 'claimed by'; //checks to see if the post has been claimed and shows content if true
        $open = strpos($title, $claimed); 
        if($open === 0) { 
          echo '<h3 class="tweet-title">The Analysis</h3>';
          the_category(' ');
          echo '<div class="the-analysis">';
           the_content();
          echo '</div>'; 
         };?>
      </div>   

  </div>

<!--restrict form option to authors or higher-->
<?php if( current_user_can('editor') || current_user_can('author') ) {  ?> 

<!--restrict form option to unclaimed posts-->
  <?php 
    $title = get_the_title();
    $claimed = 'claimed by'; //checks to see if the title has been updated as claimed and hides if that is true
    $open = strpos($title, $claimed); 
    if($open === false) {  ?> 

<h3 class="tweet-title">Analyze the Tweet</h3>

<!--FORM FOR UPDATING-->
    <form id="post" class="post-edit front-end-form" method="post" enctype="multipart/form-data">

      <input type="hidden" name="post_id" value="<?php the_ID(); ?>" />
      <?php wp_nonce_field( 'update_post_'. get_the_ID(), 'update_post_nonce' ); ?>

      <input type="hidden" id="post_title" name="post_title" value="<?php echo claimTitle(); ?>" />
  
      <p>
      <?php //the editor
       global $post;
       $content = apply_filters('the_content', $post->post_content);
       wp_editor( $content , 'postcontent' ); ?></p>

      <p><label for="post_title">Categories</label>
      <?php $value = get_post_meta(get_the_ID(), 'edit_test2', true); ?>
      <input type="hidden" id="cat_list" name="cat_list" /></p>
      
      <ul id="cat_list_tweet">
        <?php wp_category_checklist();?>
      </ul>


      <input onclick="categoriesChecked()" type="submit" id="submit" value="Submit Your Analysis" />

    </form>
<!-- END FORM FOR UPDATING-->

  <?php } ?><!-- END check for claimed vs open tweet-->
<?php } ?><!-- END check for author or admin level-->

  <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :

              comments_template();

            endif;
  ?>

<?php 
wp
?>

	<footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
