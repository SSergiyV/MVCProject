<?php

function redirect(string $path = '') {

    header('Location: ' . SITE_URL . '/' . $path);
    exit;
}

function redirectBack() {

    $redirect = $_SERVER['HTTP_REFERER'] ?? '/';
    header('Location: ' . $redirect);
    exit;
}

function url(string $link = ''):string {

    return SITE_URL . '/' . $link;
}

function isAdminRoute(): bool {

    $parts = explode('/', $_SERVER['REQUEST_URI']);
    return in_array('admin', $parts);
//    preg_match('(admin){1,}', $_SERVER['REQUEST_URI']);
}