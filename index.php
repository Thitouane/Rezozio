<?php
include("lib/DataLayer.class.php");
include("lib/Identite.class.php");

session_name('rezozio');
session_start();
if (isset($_SESSION['ident'])){
    $personne = $_SESSION['ident'];
}

date_default_timezone_set ('Europe/Paris');
try{
    $data = new DataLayer();
    $messages = $data->getMessages(15);
    $users = $data->getUsers();
    require ('views/pageIndex.php');
} catch (PDOException $e){
    $errorMessage = $e->getMessage();
    require("views/pageErreur.php");
}
?>