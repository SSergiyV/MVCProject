<?php

namespace Core;

use Exception;

class View
{
    /**
     * @throws Exception
     */
    public static function render($view, $args = []): void {

        extract($args, EXTR_SKIP);
        $file = VIEW_DIR . $view . ".php";

        if (!is_readable($file)) {
            throw new Exception("[{$file}] not found", 404);
        }
        require $file;

    }
}