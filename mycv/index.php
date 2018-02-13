<?php
  //Démarrage de la session utilisateur
  session_start();

  require_once __DIR__ . '/../src/vendor/autoload.php';

  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;
  use \DavidePastore\Slim\Validation\Validation as Validation;
  use \Respect\Validation\Validator as Validator;
  use illuminate\database\Eloquent\ModelNotFoundException as ModelNotFoundException;

  /* Appel des contrôleurs */



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
      'tmpl_dir' => __DIR__ . '/../src/view/template'
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

  function checkLogin(Request $req, Response $resp, callable $next){
    if(isset($_SESSION['user_login'])){
      return $next($req, $resp);
    }else{
      //$redirect=$this->get('router')->pathFor('loginPost');
      $redirect='/';
      $resp=$resp->withStatus(301)->withHeader('Location', $redirect);
      return $next($req, $resp);
    }
  }
  //======================================================
  //======================================================
  //======================================================
  //                    Application
  //======================================================
  //======================================================
  //======================================================

  // Page de création de compte
  /*

  $app->get('/creerCompte',
    function(Request $req, Response $resp, $args){
      $ctrl=new Comptes($this);
      return $ctrl->getComptesCreation($req,$resp,$args);
    }
  )->setName("comptesCreationGet");

  $validators = [
      'nom' => Validator::stringType()->alnum()->setname("Nom"),
      'email' => Validator::email()->setname("Email"),
      'password' => Validator::stringType()->alnum()->setname("Password"),
      'password_rep' => Validator::stringType()->alnum()->setname("Password_verify")
  ];

  $app->post('/creerCompte',
    function(Request $req, Response $resp, $args){
      if($req->getAttribute('has_errors')){
        $args['exception'] = $req->getAttribute('errors');
      }
      $ctrl=new Comptes($this);
      return $ctrl->postCompte($req,$resp,$args);
    }
  )->setName("comptesPost")->add(new Validation($validators));

  */

  $app->run();
?>
