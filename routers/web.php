<?php

$url = $_GET['url'] ?? '/';

if ($url == '/users') {
    (new UserController())->index();
} elseif ($url == '/users/create') {
    (new UserController())->create();
} elseif ($url == '/users/store') {
    (new UserController())->store();
} 
// Xử lý dynamic ID cho edit/delete tuỳ theo cách bạn viết Router

?>