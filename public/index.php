<?php

const BASE_PATH = __DIR__ . '/../';
require BASE_PATH . '/vendor/autoload.php';

use Classes\Database;

$config = [
    'host' => 'localhost',
    'port' => '3306',
    'dbname' => 'rest_test',
    'charset' => 'utf8mb4',
];

$db = new Classes\Database($config);

$posts = [
    "id" => 1,
    "body" => "Cuerpo de el post"
];

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($uri === '/posts' && $method === 'GET') {
    $responseData = get($db);

    header("HTTP/1.1 200 OK");
    echo $responseData;
    exit;
} else if ($uri === '/posts' && $method === 'POST') {
    $result = post($db);

    header("HTTP/1.1 200 OK");
    echo $result; 
} else if ($uri === '/posts' && $method === 'PUT') {
    $result = put($db);

    // echo "hola desde put"; 
} else if ($uri === '/posts' && $method === 'PATCH') {
    echo "hola desde patch"; 
} else if ($uri === '/posts' && $method === 'DELETE') {
    echo "hola desde delete"; 
}

function get($db) {
    $result = $db->query("SELECT * FROM `posts`")->get();

    return json_encode($result); 
}

function post($db) {
    $body = $_POST['body'];

    $id = $db->query("INSERT INTO `posts` (body) VALUES (:body)",
            [':body' => $_POST['body']])
            ->connection
            ->lastInsertId();

    return json_encode(compact('id', 'body'));
}

function put($db) {
    $data = formatInput();

    $db->query("UPDATE `posts` SET body = :body WHERE id = :id", $data);

    return json_encode($data);
}

function formatInput()
{
    return (array) json_decode(file_get_contents("php://input"), true);
}

function dd($arg) {
    echo"<pre>";
    var_dump($arg);
    echo "</pre>";
    exit;
}