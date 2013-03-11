<?php require "header.php"; ?>
// [test driver] author: Rick
// posts a new tag into table Tags
// test post(/api/savetag)
// 

<div class='inner-row-div row-fluid'>
    <div class='row-fluid'>
        <div class='main-body span5'>
     
        <tr>
        <td colspan = 2>
        Find a tag:
            <form class="form-search" action="/~celaya/riptideMusic/tdriver.php" method='GET'>
              <div class="input-append"> 
                    <input 
                        tabindex='1' 
                        value='' 
                        name='q' 
                        type="text"
                        class="search-query"/>
                    <button tabindex='2' value='!' type="submit" class='btn'>
                      <i class="icon-search"></i>
                    </button>
              </div>
            </form>
        </td></tr>

        <tr>
        <td>
        <?php
           if (isset($_GET["q"]))
            {
              $_GET['q'] = urldecode($_GET['q']);

              $APIrequestURL = 
                str_replace(
                  "%2F",
                  "/",
                  str_replace(
                    "tdriver.php?q=", 
                    "api/findtag/",
                    "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
                    )
                  );

              echo "<pre><code>";
               echo "<BR>";
                $APIresponse = 
                            json_decode(
                              file_get_contents(
                                $APIrequestURL
                                  )
                              );
				echo "Results: ";
//    			$tags[] = $APIresponse;
//    			foreach ($APIresponse as $tags){
//       				echo $tags['tagName'];
				var_dump($APIresponse);
               echo "<BR>";
              echo "</pre></code>" ;
            }
        ?>
        </td>
        </tr>
        
        
	    <form method="post" action="./api/savetag">
	    <input type='hidden' name='key' value='1' />
        <table>
        <tr>
        <td colspan="2" style="padding-left:105px;padding-bottom:8px;">
        <strong>Type a new tag into form below:</strong>
        </td>
        </tr>
        <tr>
        <td>
			<div style="padding-bottom:25px"><strong>Tag Name: </strong>
	          </div>
              </td>
        <td>
    	<input type="text" name="tagName" size="30" /><br />
        </td>
        </tr>
        <tr>
        <td> 
        <td>
 <input type="submit" name="submit" value="Make Tag" />
        </td>
        </tr>        </table>
        </form>

        
        <form method="post" action="./api/tagalbum">
	    <input type='hidden' name='key' value='1' />
                <table>
        <tr>
        <td colspan="2" style="padding-left:105px;padding-bottom:8px;">
        <strong>Tag an Album by tagname and weight, and albumID:</strong>
        </td>
        </tr>
        <tr>
        <td>
			<div style="padding-bottom:25px"><strong>Tag Name: </strong>
	          </div>
              </td>
        <td>
    	<input type="text" name="tagName" size="30" /><br />
        </td>
        </tr>
        <tr>
        <td>
			<div style="padding-bottom:25px"><strong>Weight: </strong>
	          </div>
              </td>
        <td>
    	<input type="text" name="weight" size="30" /><br />
        </td>
        </tr>
                <tr>
        <td>
			<div style="padding-bottom:25px"><strong>Album ID: </strong>
	          </div>
              </td>
        <td>
    	<input type="text" name="albumID" size="30" /><br />
        </td>
        </tr>
        
        <tr>
        <td colspan="2">
        <br />
        </td>
        </tr>
        <tr>
        <td> 
        <td>
 <input type="submit" name="submit" value="Tag Album" />
        </td>
        </tr>        </table>
        </form>
        
    

        </div>
       </div>
    </div>
<?php require "footer.php"; ?>
