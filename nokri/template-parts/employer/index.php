<?php
/* Employer Dashboard */
global $nokri;
$user_id 		 = get_current_user_id();
$post_found    = '';
$args = array(
	'post_type'   => 'job_post',
	'orderby'     => 'date',
	'order'       => 'DESC',
	'author' 	  => $user_id,
	'post_status' => array('publish'),
	'meta_query' => array(
        array(
            'key' => '_job_status',
            'value' => 'active',
            'compare' => '='
        )
    )
);
$args = nokri_wpml_show_all_posts_callback($args);
$query = new WP_Query( $args ); 
	$job_html = '';
	if ( $query->have_posts() )
	{
        $post_found    =  $query->found_posts;
		while ( $query->have_posts()  )
	  	{ 
			$query->the_post();
		    $job_id	       =  get_the_ID();
            $resume_counts =  nokri_get_resume_count($job_id);
			$post_status   =  get_post_status( $job_id);
			$class		   =  'warning';
			if($post_status == 'publish')
			{
				$post_status   =  esc_html__( 'Publish', 'nokri' );
				$class         =  'success';
			}
			// check for plugin post-views-counter
			$job_views = '';
			if(class_exists( 'Post_Views_Counter' ))
			{
			  $job_views = pvc_post_views( $post_id = $job_id, '' );
			} 
		    $job_html .= '<tr>
						<td><a href="'.get_the_permalink().'">'.get_the_title($job_id).'</a></td>
						<td><span class="label label-'.esc_attr($class).'">'.$post_status.'</span></td>
						<td>'.$resume_counts.'</td>
						<td>'.$job_views.'</td>
                    </tr>';
		}
		wp_reset_postdata();
	}
?>
<div class="main-body dashbord-height-fix">
<div class="dashboard-stats">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="stat-box parallex">
                <div class="stat-box-meta">
                    <div class="stat-box-meta-text">
                        <h4><?php echo esc_html__( 'Published Jobs', 'nokri' ); ?></h4>
                        <h3><?php echo $post_found != '' ? $post_found : 0; ?></h3>
                    </div>
                    <div class="stat-box-meta-icon">
                        <img src="<?php echo get_template_directory_uri();?>/images/icons/job1.png" class="img-responsive" alt="<?php echo esc_attr__( 'image', 'nokri' ); ?>">
                    </div>
                </div>
                <p><a href="<?php echo get_the_permalink(); ?>?tab-data=active-jobs"><?php echo esc_html__( 'View All Published Jobs', 'nokri' ); ?></a></p>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="stat-box blue">
                <div class="stat-box-meta">
                    <div class="stat-box-meta-text">
                        <h4><?php echo esc_html__( 'Pending Jobs', 'nokri' ); ?></h4>
                        <h3><?php echo nokri_get_jobs_count($user_id,'pending'); ?></h3>
                    </div>
                    <div class="stat-box-meta-icon">
                        <img src="<?php echo get_template_directory_uri();?>/images/icons/warning.png" class="img-responsive" alt="published jobs">
                    </div>
                </div>
                <p><a href="<?php echo get_the_permalink(); ?>?tab-data=pending-jobs"><?php echo esc_html__( 'View All Pending Jobs', 'nokri' ); ?></a></p>
            </div>
        </div>
    </div>
</div>
<div class="dashboard-job-stats">
    <h4><?php echo esc_html__( 'Recent Jobs Overview', 'nokri' ); ?></h4>
    <div class="table-responsive dashboard-job-stats-table">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th scope="row"><?php echo esc_html__( 'Job Title', 'nokri' ); ?></th>
                    <th scope="row"><?php echo esc_html__( 'Status', 'nokri' ); ?></th>
                    <th scope="row"><?php echo esc_html__( 'Resume', 'nokri' ); ?></th>
                    <th scope="row"><?php echo esc_html__( 'Views', 'nokri' ); ?></th>
                </tr>
                <?php echo "".$job_html; ?>
                </tbody>
        </table>
    </div>
</div>
</div>