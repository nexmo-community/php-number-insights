<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\App(['config' => $config]);
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    return new \Slim\Views\PhpRenderer('../templates/');
};

// handle errors ourselves
unset($app->getContainer()['errorHandler']);
unset($app->getContainer()['phpErrorHandler']);

$app->get('/', function (Request $request, Response $response) {
    return $this->view->render($response, "main.php", []);
});

$app->post('/insight', function (Request $request, Response $response) {
    $params = $request->getParsedBody();

    $basic = new \Nexmo\Client\Credentials\Basic(
        $this->config['api_key'],
        $this->config['api_secret']
    );
    $client = new \Nexmo\Client($basic);

    // choose the correct insight type
    switch($params['insight']) {
        case "standard":
            $insight = $client->insights()->standard($params['number']);
            break;
        case "advanced":
            $insight = $client->insights()->advanced($params['number']);
            break;
        default:
            $insight = $client->insights()->basic($params['number']);
            break;
    }

    if($insight && $insight['status'] == 0) {
        return $this->view->render($response, "main.php", ["insight" => $insight]);
    }

    // if we get to here, something bad happened
    $error = true;
    if($insight && $insight['status']) {
        $error_message = "Status " . $insight['status'] 
            . ": " . $insight['status_message'];
    }

    return $this->view->render($response, "main.php", ["error" => $error, "error_message" => $error_message]);

});

$app->run();
