var inFifteenMinutes = new Date(new Date().getTime() + 15 * 60 * 1000);
    
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

    if (Cookies.get('mainMenu') === 'about') {
        $("#tab1").removeClass('inactive');
        $("#tab1C").css('display', 'block');
        $("#tab2C").css('display', 'none');
        $("#tab3C").css('display', 'none');
        $("#tab2").addClass('inactive');
        $("#tab3").addClass('inactive');

     } else if (Cookies.get('mainMenu') === 'jobs') {
        $("#tab2").removeClass('inactive');
        $("#tab2C").css('display', 'block');
        $("#tab1C").css('display', 'none');
        $("#tab3C").css('display', 'none');
        $("#tab1").addClass('inactive');
        $("#tab3").addClass('inactive');

     } else if (Cookies.get('mainMenu') === 'location') {
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