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
    $profile = tideways_disable();
    $timeout = 3;

    $requestTimeFloat = explode('.', $_SERVER['REQUEST_TIME_FLOAT']);
    if (!isset($requestTimeFloat[1])) {
        $requestTimeFloat[1] = 0;
    }

    $allowedServerKeys = array(
        'DOCUMENT_ROOT',
        'HTTPS',
        'HTTP_HOST',
        'HTTP_USER_AGENT',
        'PATH_INFO',
        'PHP_AUTH_USER',
        'PHP_SELF',
        'QUERY_STRING',
        'REMOTE_ADDR',
        'REMOTE_USER',
        'REQUEST_METHOD',
        'REQUEST_TIME',
        'REQUEST_TIME_FLOAT',
        'SERVER_ADDR',
        'SERVER_NAME',
        'UNIQUE_ID',
    );

    $serverMeta = array_intersect_key($_SERVER, array_flip($allowedServerKeys));

    $meta = array(
        //'url' => $url,
        'get' => $_GET,
        //'env' => $this->getEnvironment($_ENV),
        'SERVER' => $serverMeta,
        //'simple_url' => $this->getSimpleUrl($url),
        'request_ts_micro' => array(
            'sec' => $requestTimeFloat[0],
            'usec' => $requestTimeFloat[1]),
    );

    $data = array(
        'profile' => $profile,
        'meta' => $meta,
    );

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
            case 'https':
                $ch = curl_init($out);
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
