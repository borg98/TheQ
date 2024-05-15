<?php
require_once ('vendor/autoload.php');
require_once ('Models/Database.php');


$dbContext = new DB();

$dbContext->getUsersDatabase()->getAuth()->logOut();
header('Location: /');
exit;