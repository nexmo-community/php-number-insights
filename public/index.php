<?php

use Vonage\Client\Exception\Request as VonageRequestException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require '../vendor/autoload.php';
require '../config.php';

$app = AppFactory::create();

$view = new \Slim\Views\PhpRenderer('../templates/');

$app->get('/', function (Request $request, Response $response) use ($view) {
    return $view->render($response, 'main.php', ['error' => false, 'insight' => null]);
});

$app->post('/insight', function (Request $request, Response $response) use ($view, $config) {
    try {
        $params = $request->getParsedBody();

        $basic = new \Vonage\Client\Credentials\Basic(
            $config['api_key'],
            $config['api_secret']
        );
        $client = new \Vonage\Client($basic);

        // choose the correct insight type
        switch ($params['insight']) {
            case 'standard':
                $insight = $client->insights()->standard($params['number']);
                break;
            case 'advanced':
                $insight = $client->insights()->advanced($params['number']);
                break;
            default:
                $insight = $client->insights()->basic($params['number']);
                break;
        }

        return $view->render($response, 'main.php', ['insight' => $insight]);
    } catch (VonageRequestException $requestError) {
        return $view->render(
            $response, 
            'main.php', 
            [
                'error' => true, 
                'error_message' => $requestError->getMessage(),
                'insight' => $insight
            ]
        );
    }
});

$app->run();
