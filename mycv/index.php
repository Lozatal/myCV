<?php
  require_once __DIR__ . '/../src/vendor/autoload.php';

  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;
  use \DavidePastore\Slim\Validation\Validation as Validation;
  use \Respect\Validation\Validator as Validator;
  use illuminate\database\Eloquent\ModelNotFoundException as ModelNotFoundException;

  /* Appel des contrôleurs */

  use \mycv\control\cvController as cv;

  /* Appel des utilitaires */

  use \mycv\utils\Writer as writer;

  /* Configuration de la BDD */

  $config=parse_ini_file("../src/config/mycv.db.conf.ini");
  $db = new Illuminate\Database\Capsule\Manager();
  $db->addConnection($config);
  $db->setAsGlobal();
  $db->bootEloquent();

  /* Appel et configuration de twig */
  $loader = new Twig_Loader_Filesystem('../src/view');
  $twig = new Twig_Environment($loader, array(
      'cache' => false
  ));

  //Création et configuration du container
  $configuration=[
    'settings'=>[
      'displayErrorDetails'=>true,
      'production' => false,
      'tmpl_dir' => __DIR__ . '/../src/view'
    ],
    'view'=>function($c){
      return new \Slim\Views\Twig(
        $c['settings']['tmpl_dir'],
        ['debug'=>true]
      );
    }
  ];

  $errors = require_once __DIR__ . '/../src/config/api_errors.php';

  $c=new \Slim\Container(array_merge( $configuration, $errors) );
  $app=new \Slim\App($c);
  $c = $app->getContainer();

  //Initialisation du conteneur pour le writer
  new writer($c);

  //Middleware

  function afficheError(Response $resp, $location, $errors){
  	$resp=$resp->withHeader('Content-Type','application/json')
  	->withStatus(400)
  	->withHeader('Location', $location);
  	$resp->getBody()->write(json_encode($errors));
  	return $resp;
  }

  //======================================================
  //======================================================
  //======================================================
  //                    Application
  //======================================================
  //======================================================
  //======================================================

  //======================================================
  //                  Versio Anglaise
  //======================================================

  $app->get('/',
    function(Request $req, Response $resp, $args){
      $args["lang"]="en";
      $ctrl=new cv($this);
      return $ctrl->index($req,$resp,$args);
    }
  )->setName("index2");

  $app->get('/{lang}/index',
    function(Request $req, Response $resp, $args){
      $ctrl=new cv($this);
      return $ctrl->index($req,$resp,$args);
    }
  )->setName("index");

  $app->run();
?>
