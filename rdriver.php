<?php require "header.php"; ?>
// [test driver] author: Rick
// posts a review
// test post(/api/review)

<div class='inner-row-div row-fluid'>
    <div class='row-fluid'>
        <div class='main-body span5'>

	    <form method="post" action="./api/reviewp">
	    <input type='hidden' name='key' value='1,kqed4017g' />
        <table>
        <tr>
        <td colspan="2" style="padding-left:105px;padding-bottom:8px;">
        <strong>Type your review into the form below:</strong>
        </td>
        </tr>
        <tr>
        <td>
			<div style="padding-bottom:25px"><strong>User ID: </strong><br /><br><br>
			    <strong>AlbumID: </strong>
	          </div>
              </td>
        <td>
    	<input type="text" name="userID" size="30" /><br />
    	<input type="number" name="albumID" size="30" />
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <br />
        </td>
        </tr>
        <tr>
        <td valign="top"><div align="right">
	    <strong>Message: </strong><br /></div>
        </td>
        <td>
		<textarea name="review" cols="30" rows="5" wrap="virtual"></textarea>
        </td>
        </tr>
        <tr>
        <td> 
        <td>
 <input type="submit" name="submit" value="Submit Review" />
        </td>
        </tr>        </table>
        </form>
            
    
	    <form method="post" action="./api/rate">
	    <input type='hidden' name='key' value='1,kqed4017g' />
        <table>
        <tr>
        <td colspan="2" style="padding-left:105px;padding-bottom:8px;">
        <strong>Give a rating:</strong>
        </td>
        </tr>
        <tr>
        <td>
			<div style="padding-bottom:25px"><strong>User ID: </strong><br /><br><br>
			    <strong>AlbumID: </strong>
	          </div>
              </td>
        <td>
    	<input type="text" name="userID" size="30" /><br />
    	<input type="number" name="albumID" size="30" />
        </td>
        </tr>
        <tr>
        <td colspan="2">
        <br />
        </td>
        </tr>
        <tr>
        <td valign="top"><div align="right">
	    <strong>Rating (0 - 5): </strong><br /></div>
        </td>
        <td>
    	<input type="text" name="rating" size="10" /><br />
        </td>
        </tr>
        <tr>
        <td> 
        <td>
 <input type="submit" name="submit" value="Rate" />
        </td>
        </tr>        </table>
        </form>
            
        </div>
    </div>
</div>
<?php require "footer.php"; ?>
