$(function(){
  $('#loginFORM').attr('action','javascript:void()')
  $('#loginFORM').submit(function(e){
      console.log(e);
      console.log("submitted");
      $.post(
        "/login",
        $('#loginFORM').serializeArray().stringify,
        function(response) {
          console.log(response);
          $('html').html(response);
        },
        'json');
      $(this).hide()
      return false;
  });
});