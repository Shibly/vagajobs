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
$job_name    =  (isset($_GET['job_name']) && $_GET['job_name'] != "") ? $_GET['job_name'] : '';
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array('author'  => $current_id, 'post_type' => 'job_post' , 'post_status' => 'pending', 's' => $job_name,'paged'  => $paged);
$args = nokri_wpml_show_all_posts_callback($args);
?>

<div class="main-body dashboard-title">
    <h4><?php echo esc_html__( 'Pending jobs ', 'nokri' ); ?></h4>
</div>

<div class="main-body">
<div class="dashboard-job-stats">
    <div class="dashboard-posted-jobs">
    <div class="posted-job-list header-title">
            <ul class="list-inline">
                <li class="posted-job-title"><?php echo esc_html__( 'Job Details', 'nokri' ); ?> </li>
                <li class="posted-job-status"><?php echo esc_html__( 'Status', 'nokri' ); ?> </li>
                <li class="posted-job-applicants"> <?php echo esc_html__( 'Applications', 'nokri' ); ?></li>
                <li class="posted-job-action"><?php echo esc_html__( 'Action', 'nokri' ); ?> </li>
            </ul>
        </div>
<?php
$query = new WP_Query( $args ); 
	if ( $query->have_posts() )
	{
	  while ( $query->have_posts()  )
	  { 
			$query->the_post(); 
			get_template_part( 'template-parts/layouts/job-style/pending', 'jobs');
	 }
	 wp_reset_postdata();
}
else
{
?>
<div class="dashboard-posted-jobs">
    <div class="notification-box">
        <div class="notification-box-icon"><span class="ti-info-alt"></span></div>
        <h4><?php echo esc_html__( 'You do not have any pending job', 'nokri' ); ?></h4>
    </div>
</div>
<?php } ?>
</div>
    <div class="pagination-box clearfix">
    <?php echo nokri_job_pagination( $query ); ?>
</div>
</div>
</div>