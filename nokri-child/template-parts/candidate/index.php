<?php
/* Template Name: Candidate Resume */ 
$cand_skills = $skill_tags =  $portfolio_html = '';
$user_crnt_id = get_current_user_id();
/* Getting Candidate Portfolio */
if( get_user_meta( $user_crnt_id, '_cand_portfolio', true ) != "" )
 {	
	$port = get_user_meta( $user_crnt_id, '_cand_portfolio', true );
	$portfolios = explode(',', $port);
	if((array)$portfolios && count($portfolios) > 0)
	{
		foreach($portfolios as $portfolio)
		{	
			$portfolio_image_sm = wp_get_attachment_image_src( $portfolio, 'nokri_job_hundred' );
			$portfolio_image_lg = wp_get_attachment_image_src( $portfolio, 'nokri_cand_large' );       
                                          
			$portfolio_html .= '<li><a class="portfolio-gallery" data-fancybox="gallery" href="'.esc_url($portfolio_image_lg[0]).'"><img src="'.esc_url($portfolio_image_sm[0]).'"></a></li>';
		}
	}
 }
/* Getting Count Apllied Jobs */	 
 $args  = array(
'post_type'   => 'job_post',
'orderby'     => 'date',
'order'       => 'DESC',
'post_status' => array('publish'), 
'meta_query'  => array(
'relation'    => 'AND',
				array( 'key'   => '_job_applied_resume_'.$user_crnt_id),
				array(
					'key'     => '_job_status',
					'value'   => 'active',
					'compare' => '='
				),
				),
);
$query = new WP_Query( $args );
$applied_jobs = $query->found_posts;
/* Getting Followed Companies Count  */
 $get_result      =  nokri_following_company_ids($user_crnt_id);
 $follow_comp     =   count( (array) $get_result);
 /* Getting User Skills Tags */
$cand_skills	= get_user_meta($user_crnt_id, '_cand_skills', true);
if($cand_skills != '') 
  {
	$taxonomies = get_terms('job_skills', array('hide_empty' => false , 'orderby'=> 'id', 'order' => 'ASC' ,  'parent'   => 0  ));
	if(count($taxonomies) > 0) {
		foreach($taxonomies as $taxonomy)
 			{
	 			if (in_array( $taxonomy->term_id, $cand_skills ))
				$skill_tags .= '<a class="bluebutton n-btn-flat" href="javascript:void(0)">'.esc_html($taxonomy->name).'</a>';
 			}
		}

	}
$intro       = get_user_meta($user_crnt_id, '_cand_intro', true);
$cand_video	 = get_user_meta($user_crnt_id, '_cand_video', true);
/* Low profile txt*/
$profile_percent = get_user_meta($user_crnt_id, '_cand_profile_percent', true);
$user_low_profile_txt = ( isset($nokri['user_low_profile_txt']) && $nokri['user_low_profile_txt'] != ""  ) ? $nokri['user_low_profile_txt'] : ""; 
?>
<style type="text/css">
    .widget-heading{
        font-size: 27px !important;
        font-weight: 700 !important;
        color: #09283c;
        margin-bottom: 20px;
    }
    .resume-action-modal .company-search-toggle .btn-custom{
        background: #fff !important;
    }
</style>
<div class="main-body n-candidate-detail no-bottom-padding">
    <div class="dashboard-stats">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="stat-box parallex">
                    <div class="stat-box-meta">
                        <div class="stat-box-meta-text">
                            <h4><?php echo esc_html__( 'Applied Jobs', 'nokri' ); ?></h4>
                            <h3><?php echo esc_html($applied_jobs); ?></h3>
                        </div>
                        <div class="stat-box-meta-icon">
                            <i class="fa fa-briefcase" aria-hidden="true"></i>
                        </div>
                    </div>
                    <p><a href="<?php echo get_the_permalink(); ?>?candidate-page=jobs-applied"><?php echo esc_html__( 'View All Applied Jobs', 'nokri' ); ?></a></p>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="stat-box blue">
                    <div class="stat-box-meta">
                        <div class="stat-box-meta-text">
                            <h4><?php echo esc_html__( 'Followed companies', 'nokri' ); ?></h4>
                            <h3><?php echo esc_html($follow_comp); ?></h3>
                        </div>
                        <div class="stat-box-meta-icon">
                            <i class="fa fa-users" aria-hidden="true"></i>
                        </div>
                    </div>
                    <p><a href="<?php echo get_the_permalink(); ?>?candidate-page=followed-companies"><?php echo esc_html__( 'View all followed companies', 'nokri' ); ?></a></p>
                </div>
            </div>
            <!--<div class="col-md-12 col-sm-12 col-xs-12">
                <div class="stat-box blue">
                    <div class="stat-box-meta">
                        <div class="stat-box-meta-text">
                            <h4><?php echo esc_html__( 'WELCOME TO YOUR “ONE SIZE FITS ALL” JOB APPLICATION!', 'nokri' ); ?></h4>
                            <h5><?php echo esc_html__( 'Be sure to update your profile! This will be submitted to employers when you apply to their jobs through VagaJobs. It is very important to update this before applying!', 'nokri' ); ?></h5>
                            <div style="margin:30px 0 20px 0;"><a class="outlinebluebutton n-btn-flat" href="/dashboard/?candidate-page=edit-profile">Update Profile</a></div>
                        </div>
                        <div class="stat-box-meta-icon">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>-->
        </div>
    </div>
</div>

<?php require "candidate-jobs-applied-dashboard.php"; ?>

<div class="main-body dashboard-title no-bottom-padding" style="padding-top: 0px !important">
    <h4><?php echo esc_html__( 'Profile', 'nokri' ); ?></h4>
</div>
<div id="applicant-profile" class="main-body">
    <section class="n-candidate-detail">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding profile-tab-bar">
            <aside class="resume-3-sidebar resume-column" style="margin:unset !important">
                
                    <h4 class="widget-heading"><?php echo esc_html__( 'Background', 'nokri' ); ?></h4>
                    <p style="color: #09283c;"><?php echo ($intro); ?></p>
                
            </aside>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding profile-tab-bar">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 no-padding full-height">
                <?php $cand_profession = get_user_meta($user_crnt_id, '_cand_profession', true); ?>
                <aside class="resume-3-sidebar resume-column" style="margin-left: 0px !important;">
                    <div class="n-candidate-info">
                        <h4 class="widget-heading"><?php echo nokri_feilds_label('cand_det', esc_html__('Work History', 'nokri')); ?></h4>
                        <div class="work-history">
                            <div class="resume-timeline">
                                <?php
                                foreach ($cand_profession as $profession) {
                                    $project_end = $profession['project_end'];
                                    if ($profession['project_end'] == '') {
                                        $project_end = esc_html__('Currently working', 'nokri');
                                    }
                                    $project_role = (isset($profession['project_role'])) ? '<h5 class="degree-name">' . esc_html($profession['project_role']) . '</h5>' : '';
                                    $project_org = (isset($profession['project_organization'])) ? '<span class="institute-name">' . $profession['project_organization'] . '</span>' : '';
                                    $project_strt = (isset($profession['project_start'])) ? esc_html($profession['project_start']) : '';
                                    $project_detail = (isset($profession['project_desc'])) ? '<p>' . wp_strip_all_tags($profession['project_desc']) . '</p>' : '';
                                    ?>
                                    <div class="resume-timeline-box">
                                        <?php echo "" . ($project_org) . ($project_role) . ($project_detail); ?>
                                        <span class="degree-duration"><?php
                                            echo esc_html($project_strt) . " ";
                                            if ($project_end != '') {
                                                echo '- ' . ($project_end);
                                            } ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                </aside>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 no-padding full-height">
                <aside class="resume-3-sidebar resume-column" style="margin-right:0px !important;margin-left:0px !important">
                    <div class="n-candidate-inf">
                            <div class="resume-3-box">
                                <div class="custom-field-box">
                                    <div class="n-can-custom-meta">
                                        <?php $cand_education = get_user_meta($user_crnt_id, '_cand_education', true); 
                                        if ( $cand_education  && $cand_education[0]['degree_name'] != '' ) {  ?>
                                        <div class="timeline-box">
                                            <h4 class="widget-heading"><?php echo esc_html__( 'Education', 'nokri' ); ?> </h4>
                                            <ul class="education">
                                                <?php
                                                foreach($cand_education as $edu) { 
                                                $degre_name		= (isset($edu['degree_name']))       ?  esc_html($edu['degree_name']) :   '';
                                                $degre_insti	= (isset($edu['degree_institute']))  ?  '<p style="text-transform: uppercase;font-weight: bold;margin-bottom: 0px;color: #09283c;">'.esc_html($edu['degree_institute']).'</p>'   :   '';
                                                ?>
                                                <li>
                                                    <p style="font-weight: bold;margin-bottom: 0px;color: #09283c;"><?php echo $degre_name; ?></p>
                                                    <?php echo $degre_insti; ?>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <?php } ?>

                                        <?php if(!empty($skill_tags)) { ?> 
                                        <div class="n-skills" style="padding-bottom:20px">
                                            <h4 class="widget-heading"><?php echo esc_html__( 'Industry Skills', 'nokri' ); ?></h4>
                                            <div class="n-skills-tags">
                                                <?php echo "".($skill_tags); ?>
                                            </div>
                                        </div>
                                        <?php 
                                        }
                                        ?>

                                        <h4 class="widget-heading">Availability</h4>
                                        <div class="resume-detail-meta">
                                            <p style="margin-bottom: 0px;color: #09283c;font-size: 16px;font-weight:bold"> <?php echo bt_get_month_year( get_user_meta($user_crnt_id, 'cand_availability', true) ); ?> </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
