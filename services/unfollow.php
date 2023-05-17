<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require('lib/watchdog_service.php');

$args = new RequestParameters();
$args->defineString('target');

if(! $args->isValid()){
    produceError('argument(s) invalide(s) --> '.implode(', ', $args->getErrorMessages()));
    return;
}

try{
    $data = new DataLayer();
    $bool = true;
    foreach($data->getSubscriptions() as $value){
        if($value['userId'] == $args->target){
            produceResult($data->unfollow($args->target));
            $bool = false;
        }
    }
    if(! $data->getUser($args->target))
        produceError('cet utilisateur n\'existe pas.');
    else if($bool)
        produceError('cet abonnement n\'existe pas.');
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>