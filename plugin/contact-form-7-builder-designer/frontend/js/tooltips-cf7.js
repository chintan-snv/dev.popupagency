jQuery(document).ready(function($){
  var cf7_tooltips;
  $(".cf7-tooltips-icon").hover(function(){
      clearTimeout(cf7_tooltips);
      $(".cf7-container-tooltips").removeClass('hidden');
      var text = $(this).data("value");
      $(".cf7-tooltips-content").html(text);
      var offset = $(this).offset();
      var top = offset.top;
      var left = offset.left+27;
      var height = $(".cf7-tooltips-content").height() / 2 + 10;
      top = top - height;
      $(".cf7-container-tooltips").css('top', top+'px');
      $(".cf7-container-tooltips").css('left', left+'px');
  },function(){
    cf7_tooltips = setTimeout(function(){
      $(".cf7-container-tooltips").addClass('hidden');
    }, 300);
    
  })
  $(".cf7-container-tooltips").hover(function() {
    clearTimeout(cf7_tooltips);
    $(this).removeClass('hidden');
  }, function() {
    $(".cf7-container-tooltips").addClass('hidden');
  });
})