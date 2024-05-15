<?php
require_once ('Database.php');
require_once ('UserDatabase.php');
require_once ('vendor/autoload.php');


class DB
{

    public $pdo;
    public $userDatabase;

    public function getUsersDatabase()
    {
        return $this->userDatabase;

    }


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
        $this->userDatabase = new UserDatabase($this->pdo);
        $this->initIfNotInitialized();

    }

    function storeLoginSession($userId, $ip)
    {
        $sql = "insert into loginsessions (user_id, timestamp, ip) values (:user_id, :timestamp, :ip)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'timestamp' => time(), 'ip' => $ip]);

    }
    function getUserClassrooms($id)
    {
        $stmt = $this->pdo->prepare("SELECT c.classroom_name, cu.classroom_id FROM classrooms_users cu
        inner join users u ON cu.user_id = u.id
        inner join classrooms c ON cu.classroom_id =  c.id
        where cu.user_id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }





    function loggedinUser()
    {
        return $this->userDatabase->getAuth()->getUsername();
    }

    function initIfNotInitialized()
    {
        static $initialized = false;
        if ($initialized)
            return;

        $this->userDatabase->setupUsers();
        $this->userDatabase->seedUsers();

        $sql = $this->pdo;

        $sql->query("CREATE TABLE IF NOT EXISTS `usersdetails` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(10) unsigned NOT NULL,
            `firstname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `lastname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `zip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `phone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");



        $initialized = true;

        $sql = "CREATE TABLE IF NOT EXISTS `loginsessions` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(10) unsigned NOT NULL,
            `timestamp` int(10) unsigned NOT NULL,
            `ip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $sql = "CREATE TABLE IF NOT EXISTS Classrooms (
            id int(11) AUTO_INCREMENT,
            classroom_name varchar(255) NOT NULL,
            description text NOT NULL,
            created_at varchar(255) NOT NULL,
            updated_at varchar(255) NOT NULL,
            PRIMARY KEY (id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $sql = "CREATE TABLE IF NOT EXISTS Classrooms_Queue (
            id int(11) AUTO_INCREMENT,
            classroom_id int(11) ,
            user_id int(11) ,
            question text NOT NULL,
            status int(11) ,
            created_at varchar(255) NOT NULL,
            updated_at varchar(255) NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (classroom_id) REFERENCES Classrooms(id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $sql = "CREATE TABLE IF NOT EXISTS Classrooms_Users (
            id int(11) AUTO_INCREMENT,
            classroom_id int(11) ,
            user_id int(11) ,

            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (classroom_id) REFERENCES Classrooms(id)
            )ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

    }



}


?>