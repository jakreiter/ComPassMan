<?php
use App\Kernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

if (isset($_SERVER['HTTP_CLIENT_IP']) || isset($_SERVER['HTTP_X_FORWARDED_FOR']) || ! (in_array(@$_SERVER['REMOTE_ADDR'], [
	'127.0.0.1',
	'fe80::1',
	'::1',
	'10.14.10.98',
	'212.14.57.138',
    '10.14.10.93'
]) || php_sapi_name() === 'cli-server')) {
	header('HTTP/1.0 403 Forbidden');
	exit('<span style="color: red; font-size: 8em;">⚠</span><br> You are not allowed to access this file. ');
}

require __DIR__ . '/../vendor/autoload.php';

// The check is to ensure we don't use .env in production
if (! isset($_SERVER['APP_ENV'])) {
	if (! class_exists(Dotenv::class)) {
		throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
	}
	(new Dotenv())->load(__DIR__ . '/../dev.env');
}

$env = $_SERVER['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));

if ($debug) {
	umask(0000);
	
	Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
	Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
	Request::setTrustedHosts(explode(',', $trustedHosts));
}

$kernel = new Kernel($env, $debug);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
