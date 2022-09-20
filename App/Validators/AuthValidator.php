<?php

namespace App\Validators;

class AuthValidator extends Base\UserBaseValidator
{
    protected array $errors = [
        'email_error' => 'Email or password invalid',
        'password_error' => 'Email or password invalid'
    ];

    protected array $rules = [
        'email' => '/^[a-zA-Z0-9.!#$%&\'*+\/\?^_`{|}~-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i',
        'password' => '/[a-zA-Z0-9.!#$%&\'*+\/\?^_`{|}~-]{8,}/'
    ];

    public function verifyPass(string $formPass, string $userPass) {

        if (!password_verify($formPass, $userPass)) {
            return false;
        }
        return true;
    }
}