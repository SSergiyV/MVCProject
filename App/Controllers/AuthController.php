<?php

namespace App\Controllers;

use App\Helpers\SessionHelper;
use Core\Controller;
use Core\View;

class AuthController extends Controller {

    public function login() {

        View::render('auth/login');
    }

    public function logout() {

    }

    public function registration() {

        View::render('auth/register');
    }

    public function verify() {

    }

    public function before(string $action): bool {

        if (in_array($action, ['login', 'registration']) && SessionHelper::authCheck()) {
            return false;
        }
        return parent::before($action);
    }
}