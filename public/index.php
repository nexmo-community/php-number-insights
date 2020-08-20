<?php
use DI\Container;
use Nexmo\Client\Exception\Request as NexmoRequestException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require '../vendor/autoload.php';
require '../config.php';

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();
$container->set('config', $config);

// Register component on container
$container->set('view', function () {
    return new \Slim\Views\PhpRenderer('../templates/');
});

$app->get('/', function (Request $request, Response $response) {
    return $this->get('view')->render($response, 'main.php', ['error' => false, 'insight' => null]);
});

$app->post('/insight', function (Request $request, Response $response) {
    try {
        $params = $request->getParsedBody();

        $basic = new \Nexmo\Client\Credentials\Basic(
            $this->get('config')['api_key'],
            $this->get('config')['api_secret']
        );
        $client = new \Nexmo\Client($basic);

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

        if ($insight && $insight['status'] == 0) {
            return $this->get('view')->render($response, 'main.php', ['insight' => $insight]);
        }
    } catch (NexmoRequestException $requestError) {
        return $this->get('view')->render(
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
