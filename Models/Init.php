<?php

class Init
{
    private $pdo;

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function init()
    {

        $this->pdo->query("CREATE TABLE IF NOT EXISTS `Users` (
            `id` int(11) AUTO_INCREMENT,
            `username` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `created_at` varchar(255) NOT NULL,
            `updated_at` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            )");

        $this->pdo->query("CREATE TABLE IF NOT EXISTS `Classrooms` (
            `id` int(11) AUTO_INCREMENT,
            `classroom_name` varchar(255) NOT NULL,
            `description` text NOT NULL,
            `created_at` varchar(255) NOT NULL,
            `updated_at` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            )");

        $this->pdo->query("CREATE TABLE IF NOT EXISTS `Classrooms_Queue` (
            `id` int(11) AUTO_INCREMENT,
            `classroom_id` int(11) ,
            `user_id` int(11) ,
            `question` text NOT NULL,
            `status` int(11) ,
            `created_at` varchar(255) NOT NULL,
            `updated_at` varchar(255) NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`),
            FOREIGN KEY (`classroom_id`) REFERENCES `Classrooms`(`id`)
            )");

        $this->pdo->query("CREATE TABLE IF NOT EXISTS `Users_Classrooms` (
        `id` int(11) AUTO_INCREMENT,    
        `classroom_id` int(11) ,
        `user_id` int(11) ,
        PRIMARY KEY (`id`)
        FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`),
        FOREIGN KEY (`classroom_id`) REFERENCES `Classrooms`(`id`)
        )");


    }

}