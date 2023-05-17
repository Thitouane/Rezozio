<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters();
$args->defineNonEmptyString('userId');

if(! $args->isValid()){
    produceError('argument(s) invalide(s) --> '.implode(', ', $args->getErrorMessages()));
    return;
}

try{
    $data = new DataLayer();
    $profil = $data->getProfile($args->userId);
    if($profil)
        produceResult($profil);
    else    
        produceError("{$args->userId} n'est pas un login correcte.");
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>