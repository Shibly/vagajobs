<div class="profile-tab-bar">
    <ul id="tabs" class="profile-tabs">
        <li><a class="tab-switcher" id="tab1" data-id="about">About</a></li>
        <li><a class="tab-switcher" id="tab2" data-id="jobs">Jobs</a></li>
        <li><a class="tab-switcher" id="tab3" data-id="location">location</a></li>
    </ul>

    <div class="container-desktop" id="tab1C">
         <?php include 'employer/employer-about.php';?>
    </div>

    <div class="container-desktop" id="tab2C">
          <?php include 'employer/employer-jobs.php';?>
    </div>

    <div class="container-desktop" id="tab3C">
          <?php include 'employer/employer-location.php';?>
    </div>

</div>

<script type="text/javascript">



                        $(document).ready(function () {


                            var inFifteenMinutes = new Date(new Date().getTime() + 15 * 60 * 1000);


                            /**
                             * Desktop Function
                             */

                            $('#tabs li a:not(:first)').addClass('inactive');
                            $('.container-desktop').hide();
                            $('.container-desktop:first').show();

                            $('#tabs li a').click(function () {
                                var t = $(this).attr('id');
                                if ($(this).hasClass('inactive')) { //this is the start of our condition
                                    $('#tabs li a').addClass('inactive');
                                    $(this).removeClass('inactive');

                                    $('.container-desktop').hide();
                                    $('#' + t + 'C').fadeIn('slow');
                                }
                            });


                            $('#tab1').addClass('inactive');
                            $("#tab1C").css('display', 'none');

                            if (Cookies.get('mainMenu') === 'men') {
                                $("#tab1").removeClass('inactive');
                                $("#tab1C").css('display', 'block');
                                $("#tab2C").css('display', 'none');
                                $("#tab3C").css('display', 'none');
                                $("#tab2").addClass('inactive');
                                $("#tab3").addClass('inactive');


                            } else if (Cookies.get('mainMenu') === 'women') {
                                $("#tab2").removeClass('inactive');
                                $("#tab2C").css('display', 'block');
                                $("#tab1C").css('display', 'none');
                                $("#tab3C").css('display', 'none');
                                $("#tab1").addClass('inactive');
                                $("#tab3").addClass('inactive');


                            } else if (Cookies.get('mainMenu') === 'objects') {
                                $("#tab3").removeClass('inactive');
                                $("#tab3C").css('display', 'block');
                                $("#tab1C").css('display', 'none');
                                $("#tab2C").css('display', 'none');
                                $("#tab1").addClass('inactive');
                                $("#tab2").addClass('inactive');
                            }

                            $("#tab1").on("click", function () {
                                Cookies.set('mainMenu', $(this).data('id'), {expires: inFifteenMinutes});
                            });
                            $("#tab2").on("click", function () {
                                Cookies.set('mainMenu', $(this).data('id'), {expires: inFifteenMinutes});
                            });
                            $("#tab3").on("click", function () {
                                Cookies.set('mainMenu', $(this).data('id'), {expires: inFifteenMinutes});
                            });
</script>