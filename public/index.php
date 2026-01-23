<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';
$config = include __DIR__ . '/../config.php';

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);
$app->addRoutingMiddleware();

$view = new \Slim\Views\PhpRenderer(__DIR__ . '/../templates/');

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

        $isAsyncRequest = false;

        // choose the correct insight type
        switch ($params['insight']) {
            case 'standard':
                $insight = $client->insights()->standard($params['number']);
                break;
            case 'advanced':
                $isAsyncRequest = true;
                $insight = $client->insights()->advancedAsync(
                    $params['number'],
                    $config['callback_url'] . '/webhook/number-insight'
                );
                break;
            default:
                $insight = $client->insights()->basic($params['number']);
                break;
        }

        return $view->render(
            $response,
            'main.php',
            [
                'insight' => $insight,
                'isAsyncRequest' => $isAsyncRequest,
                'error' => false
            ]
        );
    } catch (\Vonage\Client\Exception\Request $requestError) {
        return $view->render(
            $response, 
            'main.php', 
            [
                'error' => true, 
                'error_message' => $requestError->getMessage(),
            ]
        );
    }
});

$app->post('/webhook/number-insight', function (Request $request, Response $response) {
    $outputFile = fopen('advanced-insight-response.txt', 'w');

    fwrite($outputFile, $request->getBody());
    fclose($outputFile);

    return $response;
});


$app->run();