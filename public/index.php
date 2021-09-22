<?php

use App\Http\Action;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Pipeline\MiddlewareResolver;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Aura\Router\RouterContainer;
use Framework\Http\Router\AuraRouterAdapter;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Application;
use App\Http\Middleware;

//ini_set('display_errors', 'off');

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialization

$params = [
    'debug' => true,
    'users' => ['admin' => 'password'],
];

$aura = new RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', Action\HelloAction::class);
$routes->get('about', '/about', Action\AboutAction::class);
$routes->get('cabinet', '/cabinet', Action\CabinetAction::class);
$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);

$resolver = new MiddlewareResolver();
$app = new Application($resolver, new Middleware\NotFoundHandler(), new Response());

$app->pipe(new Middleware\ErrorHandlerMiddleware($params['debug']));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\ProfilerMiddleware::class);
$app->pipe(new Framework\Http\Middleware\RouteMiddleware($router));
$app->pipe('cabinet', new Middleware\BasicAuthMiddleware($params['users']));
$app->pipe(new Framework\Http\Middleware\DispatchMiddleware($resolver));



### Running

$request = ServerRequestFactory::fromGlobals();
$response = $app->run($request, new Response());

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);

//preg_match('/blog/', '/blog', $matches);
//var_dump($matches);

//echo "<br> ";
//phpinfo();
//var_dump(php_ini_loaded_file(), php_ini_scanned_files());