<?php

namespace App\Services;

use App\Helpers\SessionHelper;
use App\Validators\AuthValidator;
use App\Validators\Base\BaseValidator;

class AuthService
{
    public static function call(array $fields): bool {

        $validator = new AuthValidator();

        if ($validator -> validate($fields) && $user = $validator -> checkEmailOnExists($fields['email'])) {
            if ($validator -> verifyPass($fields['password'], $user -> password)) {
                SessionHelper::setUserData($user -> id, ['is_admin' => $user -> is_admin]);
                return true;
            }
        }

        $_SESSION['auth']['login']['error'] = 'Email or password invalid';

        return false;
    }
}