<?php
set_include_path('..'.PATH_SEPARATOR);

require_once('lib/common_service.php');
require_once('lib/session_start.php');


if ( ! isset($_SESSION['ident'])) {
  $args = new RequestParameters();
  $args->defineNonEmptyString('login');
  $args->defineNonEmptyString('password');

  if (! $args->isValid()){
   produceError('argument(s) invalide(s) --> '.implode(', ',$args->getErrorMessages()));
   return;
  }

  try{
    $data = new DataLayer();
    $ident = $data->authentifier($args->login, $args->password);
    if ($ident != NULL){
      $_SESSION['ident']=$ident;
      produceResult($ident);
    }else {
      produceError('login ou mot de passe incorrecte');
    }
  } catch (PDOException $e){
    produceError($e->getMessage());
  }

} else {
   produceError("deja authentifie");
   return;
}
?>