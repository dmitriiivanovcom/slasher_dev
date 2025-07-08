<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log('POST-запрос: ' . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI']);
        error_log('POST-данные: ' . json_encode($_POST));
    }
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
