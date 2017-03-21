<?php
//Connexion à la base de donnée
$pdo = new PDO('mysql:host=localhost;dbname=lokisalle', 'root', '', array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
));

//Session 
session_start();


//Variables

$msg = '';
$page = '';
$contenu = "";

//Chemin 
define('RACINE_SERVEUR', $_SERVER['DOCUMENT_ROOT']);
define('RACINE_SITE', '/php/projet/');


//Inclusion de fonctions.php

require_once('fonctions.inc.php');

