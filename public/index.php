<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use reunionou\actions\AddCommentAction;
use reunionou\actions\CreateEventAction;
use reunionou\actions\DeleteEventAction;
use reunionou\actions\GetCommentsByEventIdAction;
use reunionou\actions\GetCreatedEventsByUserIdAction;
use reunionou\actions\GetEventAction;
use reunionou\actions\GetEventParticipantsAction;
use reunionou\actions\GetEventsAction;
use reunionou\actions\GetEventsUserIdAction;
use reunionou\actions\GetUserAction;
use reunionou\actions\InviteParticipantAction;
use reunionou\actions\LoginUserAction;
use reunionou\actions\RegisterUserAction;
use reunionou\actions\UpdateParticipantStatusAction;
use Slim\Exception\NotFoundException;
use Slim\Factory\AppFactory;


$db = new DB();
$db->addConnection([
    'driver' => 'mysql',
    'host' => 'mysql',
    'database' => 'atelier2',
    'username' => 'atelier2',
    'password' => 'atelier2',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
]);


$db->setAsGlobal();
$db->bootEloquent();

$app = AppFactory::create();


// $app->add(function ($request, $handler) {
//     $response = $handler->handle($request);

//     if ($request->getMethod() === "OPTIONS") {
//         $response = $response
//         ->withHeader("Access-Control-Allow-Origin: *")
//         ->withHeader("Access-Control-Allow-Methods: *")
//         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//         ->withHeader('Access-Control-Max-Age', 3600)
//         ->withHeader('Access-Control-Allow-Credentials', 'true');
//     } else {
//         $response = $response
//         ->withHeader('Access-Control-Allow-Origin', "*")
//         ->withHeader('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,PATCH,OPTIONS')
//         ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
//         ->withHeader('Access-Control-Max-Age', 3600)
//         ->withHeader('Access-Control-Allow-Credentials', 'true');
//     }

//     return $response;
// });

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);


$app->post('/register', RegisterUserAction::class);
$app->post('/login', LoginUserAction::class);
$app->get('/user/{id}', GetUserAction::class);
$app->post('/event', CreateEventAction::class);
$app->get('/event/{id}', GetEventAction::class);
$app->get('/events', GetEventsAction::class);
$app->delete('/event/{id}', DeleteEventAction::class);
$app->post('/event/{id}/invite', InviteParticipantAction::class);
$app->get('/event/{id}/participants', GetEventParticipantsAction::class);
$app->put('/participant/{id}/status', UpdateParticipantStatusAction::class);
$app->post('/event/{id}/comment', AddCommentAction::class);
$app->get('/event/{id}/comments', GetCommentsByEventIdAction::class);
$app->get('/user/{id}/events', GetEventsUserIdAction::class);
$app->get('/user/{id}/events/created', GetCreatedEventsByUserIdAction::class);


$app->run();