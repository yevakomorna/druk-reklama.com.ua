<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

require __dir__ . '/../vendor/autoload.php';

$debugEnable = false;
if (true || $_SERVER['SERVER_ADDR'] == '127.0.0.1') {
    $environment = 'dev';
    $debugEnable = true;
} else {
    $environment = 'prod';
}

if ($debugEnable) {
    Debug::enable();
}

$kernel = new AppKernel($environment, $debugEnable);
if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
