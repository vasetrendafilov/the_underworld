if($(window).width() < 800 ){
  $('body').css('background-image', 'url("http://localhost/Drzava.mk/resources/img/backgroundTall.jpg")');
}
$(document).ready(function(){
  var pageWidth = $( window ).width();
  if ($('#update_link').width()){
    var updateProf = $('#update_link').width();
  }else{
    var updateProf = 0;
  }
  if ($('#signout_link').width()){
    var signout = $('#signout_link').width();
  }else{
    var signout = 0;
  }
  if ($('#signup_link').width()){
    var signup = $('#signup_link').width();
  }else{
    var signup = 0;
  }
  if ( $('#signin_link').width()){
    var signin =  $('#signin_link').width();
  }else{
    var signin = 0;
  }
  var padding = (pageWidth - ($('#home_link').width() + $('#payments_link').width() + updateProf + signout + signup + signin )) / 10;
  $('#home_link').css('padding-left',2*padding).css('padding-right',2*padding);
  $('#payments_link').css('padding-left',padding).css('padding-right',padding);
  $('#update_link').css('padding-left',padding).css('padding-right',padding);
  $('#signout_link').css('padding-left',padding).css('padding-right',padding);
  $('#signup_link').css('padding-left',padding).css('padding-right',padding);
  $('#signin_link').css('padding-left',padding).css('padding-right',padding);
  $('nav').delay(1000).animate({
    opacity:1
  },1000);

  $('#update_link').hover(function () {
    $("#profile_svg").css('fill','#000000');
    $('#profile_text').css('color','#000000');
    },function () {
    $("#profile_svg").css('fill','#ffffff');
    $('#profile_text').css('color','#ffffff');
    });
});
