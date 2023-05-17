<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');

$args = new RequestParameters();
$args->defineNonEmptyString('userId');
$args->defineNonEmptyString('password');
$args->defineNonEmptyString('pseudo');

if(! $args->isValid()){
    produceError('argument(s) invalide(s) --> '.implode(', ', $args->getErrorMessages()));
    return;
}

try{
    $data = new DataLayer();
    if($data->getUser($args->userId))
        produceError("L'utilisateur {$args->userId} existe déjà.");
    else{
        $usr = $data->createUser($args->userId, $args->password, $args->pseudo);
        produceResult($data->getUser($args->userId));
    }  
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>