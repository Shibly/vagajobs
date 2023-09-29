<?php 
/*  Employer Active jobs */ 
global $nokri;
/* Post Job Page */
$page_job_post = '';
if((isset($nokri['sb_post_ad_page'])) && $nokri['sb_post_ad_page']  != '' )
{
	$page_job_post =  ($nokri['sb_post_ad_page']);
} 
$current_id =  get_current_user_id();
$job_name   =  $job_order = $meta_key = $job_filter = '';
	$job_name    =  (isset($_GET['job_name']) && $_GET['job_name'] != "") ? $_GET['job_name'] : '';
	$job_order   =  (isset($_GET['job_order']) && $_GET['job_order'] != "") ? $_GET['job_order'] : 'date'; 
	$job_class   =  (isset($_GET['job_class']) && $_GET['job_class'] != "" ) ? $_GET['job_class'] : '';
	$meta_key    =  ( $job_class != "" ) ? 'package_job_class_'.$job_class : '';
        
        
	if ($job_class != '')
	{
		$job_filter = array(
            'key' => $meta_key,
            'value' => $job_class,
            'compare' => '='
        );
	
                
               
	}
$query_title = '';
if($job_name != '')
{
	 $query_title =  $job_name;
}
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$args = array(
	'post_type'   => 'job_post',
	'orderby'     => 'date',
	'order'       => $job_order,
	'author' 	  => $current_id,
	'paged'       => $paged,
	'post_status' => array('publish'), 
	 'meta_query' => array(
       
        $job_filter,
        array(
            'key' => '_job_status',
            'value' => 'active',
            'compare' => '='
        )
    )
);
if($job_name != '')
{
	$args['s'] = $job_name;
}
$args = nokri_wpml_show_all_posts_callback($args);
?>
<div class="main-body dashboard-title">
    <h4><?php echo esc_html__( 'Active Jobs', 'nokri' ); ?></h4>
</div>

<div class="main-body dashboard-job-filters">
    <div class="row">
    <form  method="get" id="emp_active_job">
    <input type="hidden" name="tab-data" value="active-jobs" />
    <input type="hidden" name="form" >
        <div class="col-md-6 col-xs-12 col-sm-3">
            <div class="form-group">
                <label class=""><?php echo esc_html__( 'Keyword', 'nokri' ); ?></label>
                <input type="text" name="job_name" class="form-control" placeholder="<?php echo esc_html__( 'Keyword or tag', 'nokri' ); ?>" value="<?php echo ($job_name); ?>">
                <a href="javascript:void(0);" class="a-btn submit_emp_active_job_form" ><i class="fa fa-search"></i></a>
            </div>
        </div>
        <div style="display: none;" class="col-md-4 col-xs-12 col-sm-3">
            <div class="form-group">
                <label class=""><?php echo esc_html__( 'Job Class', 'nokri' ); ?> </label>
                <select name="job_class" class="select-generat form-control emp_active_job">
                    <option value=""><?php echo esc_html__( 'Select Job Class', 'nokri' ); ?></option>
                   <?php echo nokri_job_post_taxonomies('job_class',$job_class); ?>
                </select>
            </div>
        </div>
        <div class="col-md-6 col-xs-12 col-sm-3">
            <div class="form-group">
                <label class=""><?php echo esc_html__( 'Sort by', 'nokri' ); ?></label>
                <select name="job_order" class="select-generat form-control emp_active_job">
                    <option value="">&nbsp;</option>
                    <option value="date" <?php if ($job_order == 'date') { echo "selected"; } ?>><?php echo esc_html__( 'Newest', 'nokri' ); ?></option>
                    <option value="ASC" <?php if ($job_order == 'ASC') { echo "selected"; } ?> ><?php echo esc_html__( 'Oldest', 'nokri' ); ?></option>
                </select>
            </div>
        </div>
        <?php echo nokri_form_lang_field_callback(true); ?>
        </form>
    </div>
</div>
<div class="main-body dashboard-job-results">
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
        <h4><?php echo esc_html__( 'No job found', 'nokri' ); ?></h4>
    </div>
</div>
<?php } ?>
</div>
<div class="pagination-box clearfix">
<?php echo nokri_job_pagination( $query );?>
</div>
</div>
</div>