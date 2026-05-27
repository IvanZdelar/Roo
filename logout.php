<?php
session_start();
require_once 'bootstrap.php';
$pdo = require 'db.php';
require_once 'auth_helpers.php';

clear_remember_me($pdo);

session_unset();
session_destroy();

redirect('index.php');