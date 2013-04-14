<?php

$app->get('/userStats/:id', function($id) use ($sqlConnection) {
  $sqlQueryResult = array();

  $rateSuccess = 
    get_sql_results(
      $sqlQueryResult,
      $sqlConnection,
      "select count(*) as rateCount from Rates where userID= $id; "
    );
    
  $reviewsSuccess = 
    get_sql_results(
      $sqlQueryResult,
      $sqlConnection,
      "select count(*) as reviewCount from Reviews where userID= $id;"
    );

  $compactedResult = array();

  foreach( $sqlQueryResult as $k => $pieceOfData ) {
    if (is_array($pieceOfData)) {
      $compactedResult[array_shift(array_keys($pieceOfData))] = array_shift(array_values($pieceOfData));

    }
  }

  echo json_encode($compactedResult);
});


