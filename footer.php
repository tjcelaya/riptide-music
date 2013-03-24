    
      <script src="js/bootstrap.js"></script>
      <!-- // <script src="js/less.js"></script> -->
      <div>admin pass is 55artjo55</div>
      <script type="text/javascript">
      $(function(){
        
        // $('form').submit(function(){

        // });
      });
      </script>
    </div>
    <script type="text/javascript">
      $(function(){

        $(".main-container a").live("click", function() {
            $('.main-container').fadeTo('fast', 0.3);
            $.ajax({
                url: $(this).attr('href'),
                dataType: "html",
                success: function(html) {
                  console.log($(html).filter('.main-container'));
                    window.history.pushState({"html": $(html).filter('.main-container') },"", $(this).attr('href'));
                    // $(".main-container").html($(html).filter('.main-container')).fadeTo('slow', 1);
                }
            });
            return false;
        });

        window.onpopstate = function(e){
            console.log(e);
              // if(e.state){

              // }
        };

        // $('a').click(function(){
        //   console.log('ajaxCLICK!');
        //   $('.main-container').load($(this).attr('href')+" .main-container", function(){ 
        //     $('a').click(function(){
        //       console.log('TWOCLICKS!');
        //       $('.main-container').load($(this).attr('href')+" .main-container", function(){ console.log('AJAX!'); });
        //       return false;
        //     }); 
        //   });
        //   return false;
        // });


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

        $('body').css({ 'background-color': 'rgba(0,183,255,1);' });
        $('body').css({
          'background': 'url',
          'background-size': 'cover',
          'background-attachment': 'fixed'
        }, 300);
      });
    </script>
  </body>
</html>
