<?

// index page - navigated to by users, 
$app->get('/', function() use ($sqlConnection) {

  // get the contents of the api/getAllPosts url
  $postsResult = 
    json_decode(
      file_get_contents(
        "http://ww2.cs.fsu.edu/~celaya/index.php/api/posts"
        )
      );

  // get the status of the db connection
  if (mysqli_connect_errno($sqlConnection))
    $sqlConnectionStatus = mysqli_connection_error();
  else
    $sqlConnectionStatus = 'connection ok';

  // ouput buffering is used here, it means 
  // instead of echo/print writing to the page 
  // its caught with ob_get_clean and appended to the 
  ob_start();
  var_dump($postsResult);
  $contentBody = $sqlConnectionStatus. "<br>" . ob_get_clean();
  
  // insert some newlines to space out the results
  str_replace( ')', '<br>)<br>', $contentBody);

  //get the template into a variable, 
  // this could have been done with file_get_contents
  // but it is more secure to import the file directly
  // instead of making an http request for it (its not meant to be user-facing)
  ob_start();
  require_once('homepage.thtml');
  $homePage = ob_get_clean();

  // finally, return the result of inserting the content into 
  // the homePage string represenatation of the homepage
  echo str_replace ( '!{posts}', $contentBody, $homePage);
})->name('index');

