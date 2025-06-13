<?php

require_once '../config/constants.php';
include PROJECT_ROOT . '/modele/user/checkCredentials.php';

session_start();

include_once PROJECT_ROOT . '/views/components/header.html';
include_once PROJECT_ROOT . '/views/main.html';
include_once PROJECT_ROOT . '/views/components/footer.html';
