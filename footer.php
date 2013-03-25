    
      <script src="js/bootstrap.js"></script>
      <!-- // <script src="js/less.js"></script> -->
      <div>admin pass is 55artjo55</div>
    </div>
    <script type="text/javascript">
      $(function(){
        //ajaxificate ALL THE LINKS
        $(".main-container a").live("click", function() {
            $('.main-container').fadeTo('fast', 0.3);
            $.ajax({
                url: $(this).attr('href'),
                dataType: "html",
                context: this,
                success: function(html) {
                    window.history.pushState({"html": html }, $(this).text() ,$(this).attr('href'));
                    $(".main-container").html($(html).filter('.main-container')).fadeTo('slow', 1);
                }
            });
            return false;
        });
        //react to clicking back, cant go forward yet
        window.onpopstate = function(e){
            $(function() {
              console.log(e);
              if(e.state){
                $('.main-container').html($(e.state.html).filter('.main-container'));
              }
            });
        };

        $('.tracks-ellipsis').live('click touchstart', function(){
            $('.tracks-ellipsis').fadeOut('fast');
            $('.hidden-tracks').fadeToggle();
        });

//        $('.tracks-ellipsis').live('mouseenter touchstart click', function() {
//          if($(this).parentsUntil('.full-album-listing').next().children().is('tbody'))
//          { console.log($(this).parentsUntil('.full-album-listing').next().wrapAll('<div>').attr('class','')); }
//          $(this).parentsUntil('.full-album-listing').next('div').slideDown('normal');
        
//        });
//        $('.tracks-ellipsis').live('mouseleave click', function() {
//            $(this).parentsUntil('.full-album-listing').next('div').slideUp('slow');
//           showToggle = false;
//        });

          $('body').fadeTo('fast', 0.3, function()
          {
            $(this).css('background-image', 'url("img/bg/<?php 
                                              echo array_rand(
                                                  array_slice(
                                                      scandir(
                                                      dirname(__FILE__)."/img/bg"
                                                      )
                                                      ,2));
                                            ?>.jpg")');
          }).fadeTo('fast', 1);

          $('body').css({
            'background': 'url',
            'background-size': 'cover',
            'background-attachment': 'fixed'
          }, 300);
        });
    </script>
  </body>
</html>
