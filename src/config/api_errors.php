<?php

return [
    'notFoundHandler'=>function($c){
      return function ($rq, $rs) {
        $resp=$rs->withStatus( 400 );
        $resp = $resp->withHeader('Content-Type', 'application/json;charset=utf8');
        $resp->getBody()->write(json_encode('URI non traitée')) ;
        return $resp;
      };
    },
  'notAllowHandler'=>function($c){
    return function($req,$resp,$methods){
      return $resp->withStatus(405)
                  ->withHeader('Allow',implode(',',$methods))
                  ->withHeader('Content-Type',"application/json")
                  ->getBody()
                  ->write(json_encode('Méthode permises :'.implode(',',$methods)));
    };
  },
  'phpErrorHandler'=>function($c){
    return function($req,$resp, $error){
      return $resp->withStatus(500)
                  ->withHeader('Content-Type',"application/json")
                  ->getBody()
                  ->write(json_encode('Erreur PHP'));
    };
  },
];
