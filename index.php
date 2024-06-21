<?php
require_once "config.php";
include_once "libs/general.php";
include_once "libs/dbapi.php";
include_once "./model/model.php";

if (DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(FALSE);
}

ob_start();
session_start();
$m = new Model();
$post = json_decode(file_get_contents('php://input') ?? $_POST);
// pred($post->data);
if (isset($post->data) && $post->data !== null) {
    $req_data = $post->data;
} else {
    $req_data = '';
}
if (isset($post->aid) && strlen($post->aid) > 0) {
    $aid = $post->aid;
} else {
    $aid = 0;
}
if ($aid == 1) {
    $m->add_todo($req_data);
} else if ($aid == 2) {
    $m->update_todo($req_data);
}
$rs = $m->get_all_todos();
$m->return_result($rs, '');

