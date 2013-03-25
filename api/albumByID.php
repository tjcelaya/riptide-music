<?php

$app->get('/albumByID/:id', function($id) use ($sqlConnection) {

  $sqlQueryResult = array();
  
  $idRetrieve = 
    getAlbumByID(
      $sqlQueryResult,
      $sqlConnection,
      $id
    );

  if(!$idRetrieve) {
    echo json_encode(array('err' => 'notfound'));
    return;
  }

  echo json_encode($sqlQueryResult);
});