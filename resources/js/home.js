$(document).ready(function(){
  $('#toolbox').hover(function() {
    hovered = true;
       setTimeout(function() {
           if(hovered) {
              $('#toolbox').children('#box').slideDown();
           }
       }, 250);//delay hover
  },function () {
     hovered = false;
      $(this).children('#box').slideUp();
  });
});
