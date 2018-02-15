<?php

  namespace mycv\control;

  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;

  use mycv\model\cv as cv;
  // use mycv\model\experience as experience;
  // use mycv\model\formation as formation;
  use mycv\model\profil as profil;

  class cvController{

    public $conteneur=null;

    public function __construct($conteneur){
      $this->conteneur=$conteneur;
    }

    public function index(Request $req,Response $resp,array $args){
      if(isset($args["lang"])){
        if($args["lang"]=="en"){
          $lang=$args["lang"];
        }else{
          $lang="fr";
        }
      }else{
        $lang="en";
      }
      $cv = cv::where("lang","=",$lang)->first();
      $profil = profil::where("id_cv","=",$cv->id)->first();
      if($cv){
        $style='http://'.$_SERVER['HTTP_HOST']."/style";
        $index=$this->conteneur->get('router')->pathFor('index',["lang"=>$lang]);
        return $this->conteneur->view->render($resp,'index.twig',['style'=>$style,
                                                                  'index'=>$index,
                                                                  'profil'=>$profil,
                                                                  'cv'=>$cv]);
      }else{
        $redirect=$this->conteneur->get('router')->pathFor('index');
        $resp=$resp->withStatus(301)->withHeader('Location', $redirect);
        return $resp;
      }
    }
  }
