<?php

require_once 'config/constants.php';
include __DIR__ . '/modele/user/checkCredentials.php';

$_SESSION = [];
session_start();

var_dump($_SESSION);

include_once 'views/components/header.html';
include_once 'main.html';