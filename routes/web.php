<?php

$router -> add("admin/posts/{id:\d+}/edit", ["controller" => \App\Controllers\HomeController::class, "action" => "index", "method" => "GET"]);
