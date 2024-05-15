<?php

require_once "database.php";
require_once "userdatabase.php";


class Init
{
    private $pdo;


    public $userDatabase;

    public function getUsersDatabase()
    {
        return $this->userDatabase;

    }

    function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->userDatabase = new UserDatabase($pdo);
        $this->userDatabase->init();

    }

    public function init()
    {

        $this->userDatabase->setupUsers();
        $this->userDatabase->seedUsers();



        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            user_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            content TEXT NOT NULL,
            user_id INT NOT NULL,
            post_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (post_id) REFERENCES posts(id)
        )");
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS loginsessions (
           `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(10) unsigned NOT NULL,
            `timestamp` int(10) unsigned NOT NULL,
            `ip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
        );
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users_details (
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



    }

}