<?php get_header();
global $nokri; 
global $post;
the_post();
$pid = get_the_ID();
/* Side Bar Check */
$blog_sidebar = isset( $nokri['single_blog_side_bar'] ) ? $nokri['single_blog_side_bar'] : '1';
$right_sidebar = $left_sidebar = '';
$layout = $main_col = '';
/* Side Bar In Variable */
ob_start();
dynamic_sidebar('blog_sidebar');
$sidebar = '<div class="col-md-4 col-sm-12 col-xs-12">
                            <aside class="blog-sidebar">'.ob_get_contents().'</aside>
                        </div>';
ob_end_clean();
?>
<section class="blog-detail-page">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
        <div class="col-md-8 col-sm-12 col-xs-12">
        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <div class="blog-post">
           
            <div class="blog-single post-desc entry-content">
              <h1 class="post-title"><?php echo the_title(); ?></h1>
              <?php the_content();
			  if(has_tag()) { ?>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <nav aria-label="Page navigation">
					<?php echo nokri_pagination_unit_test(); ?>
              </nav>
                </div>
              <div class="tagcloud"> 
              <i class="fa fa-tags"></i> <?php  echo nokri_posts_tags() ; ?>
              </div>
              <?php }  ?>
            </div>
          </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php get_footer(); ?>