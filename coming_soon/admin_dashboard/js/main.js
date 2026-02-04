!(function($) {

  "use strict";

  $(document).on('click', '.mobile-nav-toggle', function(e) {
    $('body').toggleClass('mobile-nav-active');
    $('.mobile-nav-toggle i').toggleClass('icofont-navigation-menu icofont-close');
  });

  $(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
      $('.back-to-top').fadeIn('slow');
    } else {
      $('.back-to-top').fadeOut('slow');
    }
  });

  $('.back-to-top').click(function() {
    $('html, body').animate({
      scrollTop: 0
    }, 678);
  });

  $('#modal_open').click(function(){
    $('.modal_container').fadeIn();
    $('.dropdown_modal').fadeIn();
    $('.dropdown_modal').css("display", "inline-block");
    $('.dropdown_modal').animate({
      top: '35px'
    }, 1);
  });

  $('#modal_close').click(function(){
    $('#txt_img_upload').text("");
    $('#img_upl_field').val('');
    $('.dropdown_modal').animate({
      top: '-250px'
    }, 1);
    setTimeout(function(){
      $('.modal_container').fadeOut();
      $('.dropdown_modal').fadeOut();
    }, 200);
  });

})(jQuery);