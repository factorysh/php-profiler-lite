<?php

function _phpprofiler_init() {
    //phpinfo();
    //var_dump($_SERVER);
    $token = getenv('PHP_PROFILER_TOKEN');
    if ($token) {
        if ($token == $_SERVER['HTTP_PHP_PROFILER_TOKEN']) {
            register_shutdown_function('_phpprofiler_close');
            tideways_enable();
        }
    }
}

function _phpprofiler_close() {
    $data = tideways_disable();
    var_dump($data);
}

_phpprofiler_init();
?>
