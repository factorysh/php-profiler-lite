<?php

function _phpprofiler_init() {
    $token = getenv('PHP_PROFILER_TOKEN');
    if ($token) {
        if ($token == $_SERVER['HTTP_PHP_PROFILER_TOKEN']) {
            register_shutdown_function('_phpprofiler_close');
            $flags = 0;
            $f = getenv('PHP_PROFILER_FLAGS');
            if ($f) {
                $names = array(
                    'CPU' => TIDEWAYS_FLAGS_CPU,
                    'MEMORY' => TIDEWAYS_FLAGS_MEMORY,
                    'NO_BUILTINS' => TIDEWAYS_FLAGS_NO_BUILTINS,
                    'NO_SPANS' => TIDEWAYS_FLAGS_NO_SPANS,
                );
                foreach (explode(',', $f) as $flag) {
                    $flags |= $names[$flag];
                }
            }
            tideways_enable($flags);
        }
    }
}

function _phpprofiler_close() {
    $data = tideways_disable();
    var_dump($data);
}

_phpprofiler_init();
?>
