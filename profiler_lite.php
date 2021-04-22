<?php

function _phpprofiler_init() {
    $token = getenv('PHP_PROFILER_TOKEN');
    if ($token && $token == $_SERVER['HTTP_PHP_PROFILER_TOKEN']) {
        register_shutdown_function('_phpprofiler_close');
        $flagsCombined = 0;
        $flags = getenv('PHP_PROFILER_FLAGS');
        if ($flags) {
            $flagNames = array(
                'CPU' => TIDEWAYS_FLAGS_CPU,
                'MEMORY' => TIDEWAYS_FLAGS_MEMORY,
                'NO_BUILTINS' => TIDEWAYS_FLAGS_NO_BUILTINS,
                'NO_SPANS' => TIDEWAYS_FLAGS_NO_SPANS,
            );
            foreach (explode(',', $flags) as $flag) {
                $flagsCombined |= $flagNames[$flag];
            }
        }
        tideways_enable($flagsCombined);
    }
}

function _phpprofiler_close() {
    $data = tideways_disable();
    $timeout = 3;

    $out = getenv('PHP_PROFILER_URL');
    if ($out) {
        $url = parse_url($out);
        switch ($url['scheme']) {
            case 'file':
                file_put_contents(
                    $url['path'] . '/' .uniqid() . '.json',
                    json_encode($data) . PHP_EOL);
                break;
            case 'http':
                $ch = curl_init($url);
                if (!$ch) {
                    throw new Exception('Failed to create cURL resource');
                }

                $headers = array(
                    // Prefer to receive JSON back
                    'Accept: application/json',
                    // The sent data is JSON
                    'Content-Type: application/json',
                );

                $res = curl_setopt_array($ch, array(
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => json_encode($data),
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_TIMEOUT => $timeout,
                ));
                if (!$res) {
                    throw new Exception('Failed to set cURL options');
                }

                $result = curl_exec($ch);
                if ($result === false) {
                    throw new Exception('Failed to submit data');
                }
                curl_close($ch);
                break;
        }
    } else {
    var_dump($data);
    }
}

_phpprofiler_init();
