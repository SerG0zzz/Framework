<?php

use App\Http\Action;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\ActionResolver;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Aura\Router\RouterContainer;
use Framework\Http\Router\AuraRouterAdapter;
use Psr\Http\Message\ServerRequestInterface;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

### Initialization

$params = [
    'users' => ['admin' => 'password'],
];

$aura = new RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', Action\HelloAction::class);
$routes->get('about', '/about', Action\AboutAction::class);

$routes->get('cabinet', '/cabinet', function (ServerRequestInterface $request) use ($params) {
    $auth = new Action\Middleware\BasicAuthMiddleware($params['users']);
    $cabinet = new Action\CabinetAction();

    return $auth($request, function (ServerRequestInterface $request) use ($cabinet) {
       return $cabinet($request);
    });
});

$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class, ['id' => '\d+']);

$router = new AuraRouterAdapter($aura);
$resolver = new ActionResolver();

### Running

$request = ServerRequestFactory::fromGlobals();
try {
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $resolver->resolve($result->getHandler());
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new HtmlResponse('Undefined page', 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'SerG0');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);

//preg_match('/blog/', '/blog', $matches);
//var_dump($matches);

//echo "<br> ";
//phpinfo();
//var_dump(php_ini_loaded_file(), php_ini_scanned_files());