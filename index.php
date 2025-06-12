<?php

require_once 'config/constants.php';
include __DIR__ . '/modele/user/checkCredentials.php';

session_start();

include_once 'views/components/header.html';
include_once 'views/main.html';
include_once 'views/components/footer.html';