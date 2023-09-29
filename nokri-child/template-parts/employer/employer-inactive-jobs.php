<?php 
/*  Employer Active jobs */ 
global $nokri;
/* Post Job Page */
$page_job_post = '';
if((isset($nokri['sb_post_ad_page'])) && $nokri['sb_post_ad_page']  != '' )
{
$page_job_post =  ($nokri['sb_post_ad_page']);
} 
$current_id = get_current_user_id();
$job_name = '';
$job_status = '';
$job_order = '';
$meta_key = '';
$job_class = '';
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
	'author'  => $current_id, 'post_type' => 'job_post' , 'post_status' =>  array('publish',  'draft'),'paged'       => $paged,
	'meta_query' => array(
		array(
			'key'     => '_job_status',
			'value'   => 'inactive',
			'compare' => '=',
		),
	), 
);
?>

<div class="main-body dashboard-title">
    <h4><?php echo esc_html__( 'Inactive jobs ', 'nokri' ); ?></h4>
</div>

<div class="main-body">
<div class="dashboard-job-stats">
    <div class="dashboard-posted-jobs">
        <div class="posted-job-list header-title">
            <ul class="list-inline">
                <li class="posted-job-title"><?php echo esc_html__( 'Job Title', 'nokri' ); ?> </li>
                <li class="posted-job-status"><?php echo esc_html__( 'Status', 'nokri' ); ?> </li>
                <li class="posted-job-applicants"> <?php echo esc_html__( 'Applications', 'nokri' ); ?></li>
                <!--<li class="posted-job-expiration"> <?php echo esc_html__( 'Expired On', 'nokri' ); ?></li>-->
                <li class="posted-job-action text-center"><?php echo esc_html__( 'Action', 'nokri' ); ?> </li>
                
            </ul>
        </div>
<?php
$query = new WP_Query( $args ); 
	if ( $query->have_posts() )
	{
	  while ( $query->have_posts()  )
	  { 
			$query->the_post(); 
			get_template_part( 'template-parts/layouts/job-style/style', '3');
	  }
	  wp_reset_postdata();
  }
else
{
?>
 <div class="dashboard-posted-jobs">
    <div class="notification-box">
        <h4><?php echo esc_html__( 'You do not have any inactive job', 'nokri' ); ?></h4>
    </div>
</div>
<?php } ?>
</div>
    <div class="pagination-box clearfix">
    <?php echo nokri_job_pagination( $query ); ?>
</div>
</div>
</div>