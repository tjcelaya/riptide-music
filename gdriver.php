<?php require "header.php"; ?>
// [test driver] author: Rick
// posts a new genre into table NewGenre
// test post(/api/newgenre)

<div class='inner-row-div row-fluid'>
    <div class='row-fluid'>
        <div class='main-body span5'>

	    <form method="post" action="./api/newgenre">
	    <input type='hidden' name='key' value='1,kqed4017g' />
        <table>
        <tr>
        <td colspan="2" style="padding-left:105px;padding-bottom:8px;">
        <strong>Type a new genre into form below:</strong>
        </td>
        </tr>
        <tr>
        <td>
			<div style="padding-bottom:25px"><strong>Genre Name: </strong>
	          </div>
              </td>
        <td>
    	<input type="text" name="genreName" size="30" /><br />
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <br />
        </td>
        </tr>
        <tr>
        <td valign="top"><div align="right">
	    <strong>Description: </strong><br /></div>
        </td>
        <td>
		<textarea name="description" cols="30" rows="5" wrap="virtual"></textarea>
        </td>
        </tr>
        <tr>
        <td> 
        <td>
 <input type="submit" name="submit" value="Send" />
        </td>
        </tr>        </table>
        </form>
            
    

        </div>
       </div>
    </div>
<?php require "footer.php"; ?>
