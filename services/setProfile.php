<?php
set_include_path('..'.PATH_SEPARATOR);
require_once('lib/common_service.php');
require('lib/watchdog_service.php');

$args = new RequestParameters();
$args->defineString('password');
$args->defineString('pseudo');
$args->defineString('description');

if(! $args->isValid()){
    produceError('argument(s) invalide(s) --> '.implode(', ', $args->getErrorMessages()));
    return;
}

try{
    $data = new DataLayer();
    if($args->password!='')
        $data->setPassword($args->password);
    if($args->pseudo!='')
        $data->setPseudo($args->pseudo);
    if($args->description!='')
        $data->setDesc($args->description);
    produceResult($data->getUser($_SESSION['ident']->login));
}
catch (PDOException $e){
    produceError($e->getMessage());
}
?>