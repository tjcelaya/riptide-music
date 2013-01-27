<?php
// backend api call to retrieve posts
$app->get('/GETposts', function() use ($sqlConnection) {

  //perform a mysqli query
  $postResult = mysqli_query(
        $sqlConnection,
        "SELECT name, body, created FROM microposts;"
    );
  
  //prepare a hashmap/dictionary/whatever to put the results into
  $postsObject = array();

  //get them record by record into the result object
  if($postResult->num_rows > 0) {
      while($row = $postResult->fetch_assoc()) {
          array_unshift($postsObject, $row);
      }
  }
  else {
      echo json_encode(array('err' =>'NO RESULTS'));
  }

  echo json_encode($postsObject);
});

//backend post method to create a new post
$app->post('/POSTnew', function() use ($app, $sqlConnection) {
    //turn the post string into a hash
    // parse_str($app->request()->getBody(), $requestBody);

    // //create insert query using values from hash
    // $insertQuery = 'INSERT INTO microposts (body, name, created) ' . // append the next line
    //   "VALUES ( '{$requestBody['comment']}', '{$requestBody['name']}', NOW() );";
      
    // //attempt query
    // $sqlSuccess = mysqli_query(
    //   $sqlConnection,
    //   $insertQuery
    //   );

    // had to remove the final slash(thats what substr is for) 
    // for the redirect to be captured by slim
    // and rendered properly
    // The urlFor method simply returns the url of 
    // the named route supplied to it
    $app->response()->redirect(
      "http://ww2.cs.fsu.edu/~celaya/",
      // substr($app->urlFor('index'), 0, -1),
      302);
    // var_dump($insertQ . '///////' . $sqlSuccess . mysqli_error($sqlConnection));
});
