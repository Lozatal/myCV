<?php

  namespace mycv\utils;

  class Writer{
    public static $conteneur=null;
    public function __construct($contain){
      self::$conteneur=$contain;
    }

    public static function jsonFormatCollection($ressource, array $tab, $total=null, $size=null, $page=null){
      $tabRendu["type"]="collection";
      if($total!=null){
        $tabRendu["meta"]["count"]=$total;
      }
      if($size!=null){
        $tabRendu["meta"]["items"]=$size;
      }
      if($page!=null){
        $tabRendu["meta"]["page"]=$page;
      }
      $tabRendu[$ressource]=$tab;
      return json_encode($tabRendu);
    }

    public static function jsonFormatRessource($ressource,$tabRessource,$link=null){
      $tabRendu["type"]="ressource";
      $tabRendu[$ressource]=$tabRessource;
      if($link!=null){
        $tabRendu["links"]=$link;
      }
      return json_encode($tabRendu);
    }

    public static function addLink($tabObjet, $nameObject, $pathFor){
      for($i=0;$i<sizeof($tabObjet);$i++){
        $tabRendu[$i][$nameObject]=$tabObjet[$i];
        $href["href"]=self::$conteneur->get('router')->pathFor($pathFor, ['id'=>$tabObjet[$i]['id']]);
        $tab["self"]=$href;
        $tabRendu[$i]["links"]=$tab;
      }
      if (isset($tabRendu)) {
        return $tabRendu;
      }

    }

    public static function addLinks($pathfor,$id){
      $href["href"]=self::$conteneur->get('router')->pathFor($pathfor, ['id'=>$id]);
      $tab["self"]=$href;
      return $tab;
    }
  }
