<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');


$args = new RequestParameters();
$args->defineNonEmptyString('userId');
$args->defineNonEmptyString('size');

if (! $args->isValid()){
  produceError('argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
  return;
}

try{
  $data = new DataLayer();
  $descFile = $data->getAvatar($args->userId);
  if ($descFile){ // l'utilisateur existe
    // si l'avatar est NULL, renvoyer l'avatar par défaut :
    $flux = is_null($descFile['avatar_type']) ? fopen('../images/avatar_def.png','r') : $descFile['avatar_type'];
    $mimeType = is_null($descFile['avatar_type']) ? 'image/png' : $descFile['mimetype'];
    // a voir dans la base de donnee
    header("Content-type: $mimeType");
    fpassthru($flux);
    exit();
  }
  else
    produceError('Utilisateur inexistant');
}
catch (PDOException $e){
  produceError($e->getMessage());
}

 //flemme on verra tarplu


?>