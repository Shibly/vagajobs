<?php
global $nokri;
$dashboard_page = $footer_style = '';


/* Search page lay out */
$search_page_layout = ( isset($nokri['search_page_layout']) && $nokri['search_page_layout'] != "" ) ? $nokri['search_page_layout'] : "";
/* dashboard page */
$dashboard_page = ( isset($nokri['sb_dashboard_page']) && $nokri['sb_dashboard_page'] != "" ) ? $nokri['sb_dashboard_page'] : "";
if ((isset($nokri['select_footer_layout'])) && $nokri['select_footer_layout'] != '') {
    $footer_style = ($nokri['select_footer_layout']);
}
/* No footer in map search */
if ($search_page_layout == '3' && basename(get_page_template()) == 'page-search.php') {
    
} else {
    if (basename(get_page_template()) != 'page-dashboard.php') {
        get_template_part('template-parts/footers/footer', $footer_style);
    }
}
/* Hidden Inputs */
get_template_part('template-parts/hidden', 'inputs');
/* Linkedin access */
include( 'template-parts/linkedin-access.php' );
/* Linkedin messages */
get_template_part('template-parts/linkedin', 'messages');
/* Email verification and reset password */
get_template_part('template-parts/verification', 'logic');
?>
</div>
<div id="toast-container" class="toast-top-right"  >
    <div class="toast toast-success" aria-live="polite"  id="progress_loader">
        <div class="toast-title"><?php  echo esc_html__('Uploading','nokri')?></div>
        <div class="toast-message" id="progress_counter"></div>
    </div>
</div>
<div id="popup-data"></div>
<div id="app-data"></div>
<div id="short-desc-data"></div>
<div id="status_action_data"></div>
<div id="job-alert-dataaaaa"></div> 
<?php
if (isset($nokri['banners_code_footer']) && $nokri['banners_code_footer'] != '') {
    echo ($nokri['banners_code_footer']);
}
?> 
<?php if ((isset($nokri['scroll_to_top'])) && $nokri['scroll_to_top'] == '1') { ?>
    <a href="#" class="scrollup"><i class="fa fa-chevron-up"></i></a>
        <?php
    } echo nokri_authorization();
    wp_footer();
    /* Email job alerts */
    get_template_part('template-parts/job', 'alerts');
    ?>
</body>
</html>