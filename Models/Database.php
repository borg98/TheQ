<?php
require_once "vendor/autoload.php";
require_once "Init.php";

class DB
{
    private $pdo;

    private $init;

    function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
        $dotenv->load();
        $host = $_ENV['host'];
        $db = $_ENV['db'];
        $user = $_ENV['user'];
        $pass = $_ENV['pass'];
        $port = $_ENV['port'];

        $dsn = "mysql:host=$host;dbname=$db;port=$port";
        $this->pdo = new PDO($dsn, $user, $pass);
        $init = new Init($this->pdo);
        $init->init();
    }


    function getUserClassrooms($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Classrooms_Users WHERE user_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }

    function getClassroomQueueById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM Classrooms_Queue WHERE classroom_id = :id SORT BY created_at DESC");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }

    function addUserToQueue($classroom_id, $user_id, $question)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Classrooms_Queue (classroom_id, user_id, question, status, created_at, updated_at) VALUES (:classroom_id, :user_id, :question, 0, NOW(), NOW())");
        $stmt->execute(['classroom_id' => $classroom_id, 'user_id' => $user_id, 'question' => $question]);
    }
}

