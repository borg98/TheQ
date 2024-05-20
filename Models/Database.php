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



    function getClassroomQueueById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM classrooms_queue 
        WHERE classroom_id = :id AND status = 0
        ORDER BY created_at DESC ");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();

    }
    function addUserToQueue($classroom_id, $question, $user_id, $location)
    {
        // Format the current date and time
        $date = date('Y-m-d H:i:s');

        // Check if a duplicate entry already exists
        $checkStmt = $this->pdo->prepare("
        SELECT COUNT(*) 
        FROM classrooms_queue 
        WHERE classroom_id = :classroom_id 
        AND user_id = :user_id 
        AND status = 0;
        
    ");
        $checkStmt->execute([
            'classroom_id' => $classroom_id,
            'user_id' => $user_id,

        ]);

        // Fetch the count of existing entries
        $count = $checkStmt->fetchColumn();

        // If no duplicates are found, insert the new entry
        if ($count == 0) {
            $stmt = $this->pdo->prepare("
            INSERT INTO classrooms_queue 
            (classroom_id, user_id, question, status, created_at, updated_at, studentlocation)  
            VALUES (:classroom_id, :user_id, :question, 0, :date_created, :date_updated, :studentlocation)
        ");
            $stmt->execute([
                'classroom_id' => $classroom_id,
                'user_id' => $user_id,
                'question' => $question,
                'date_created' => $date,
                'date_updated' => $date,
                'studentlocation' => $location
            ]);
        }
    }

    function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    function markQueueAsDone($id)
    {
        $stmt = $this->pdo->prepare("UPDATE classrooms_queue SET status = 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    function checkIfAlreadyInQueue($classroom_id, $user_id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM classrooms_queue WHERE classroom_id = :classroom_id AND user_id = :user_id AND status = 0");
        $stmt->execute(['classroom_id' => $classroom_id, 'user_id' => $user_id]);
        return $stmt->fetchColumn();
    }
    function getAllClassrooms()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM classrooms");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function getUserByClassroomId($classroom_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM classrooms_users LEFT JOIN users u ON user_id = u.id  WHERE classroom_id = :classroom_id");
        $stmt->execute(['classroom_id' => $classroom_id]);
        return $stmt->fetchAll();

    }
    function removeStudent($id, $classroom_id)
    {

        $stmt = $this->pdo->prepare("DELETE FROM classrooms_queue WHERE user_id = :id AND classroom_id = :classroom_id AND status = 0");
        $stmt->execute(['id' => $id, 'classroom_id' => $classroom_id]);
    }
    function setStatusToBanned($id)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET status = 2 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    function updateUserStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        $stmt->execute(['id' => $id, 'status' => $status]);
    }
    function getallUsers()
    {
        $stmt = $this->pdo->prepare("SELECT id, username, status FROM users");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    function addUserToClassroom($user_id, $classroom_id)
    {
        try {
            // Check if the user already exists in the classroom
            $checkStmt = $this->pdo->prepare("SELECT COUNT(*) FROM classrooms_users WHERE classroom_id = :classroom_id AND user_id = :user_id");
            $checkStmt->execute(['classroom_id' => $classroom_id, 'user_id' => $user_id]);
            $exists = $checkStmt->fetchColumn();

            if ($exists) {
                echo "User already exists in the classroom.";
                return; // Exit the function if the user already exists
            }

            // Insert the user into the classroom if they don't already exist
            $stmt = $this->pdo->prepare("INSERT INTO classrooms_users (classroom_id, roles_mask, user_id) VALUES (:classroom_id, 16, :user_id)");
            $stmt->execute(['classroom_id' => $classroom_id, 'user_id' => $user_id]);

            echo "User added to classroom successfully.";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    function checkUserStatus($id)
    {
        $stmt = $this->pdo->prepare("SELECT status FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn();
    }
    function removeStudentFromClassroom($id, $classroom_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM classrooms_users WHERE user_id = :id AND classroom_id = :classroom_id");
        $stmt->execute(['id' => $id, 'classroom_id' => $classroom_id]);
    }
    function addClassroom($classroom_name, $description)
    {
        $date = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("INSERT INTO classrooms (classroom_name, description, created_at, updated_at) VALUES (:classroom_name, :description, :date_created, :date_updated)");
        $stmt->execute(['classroom_name' => $classroom_name, 'description' => $description, 'date_created' => $date, 'date_updated' => $date]);
    }
    function SetUserRole($id, $role, $classroom_id)
    {
        $stmt = $this->pdo->prepare("UPDATE classrooms_users SET roles_mask = :roles_mask WHERE user_id = :id AND classroom_id = :classroom_id");
        $stmt->execute(['id' => $id, 'roles_mask' => $role, 'classroom_id' => $classroom_id]);
    }
    function checkUserRole($id, $classroom_id)
    {
        $stmt = $this->pdo->prepare("SELECT roles_mask FROM classrooms_users WHERE user_id= :id AND classroom_id = :classroom_id");
        $stmt->execute(['id' => $id, 'classroom_id' => $classroom_id]);
        return $stmt->fetchColumn();
    }
    function TotalUsersInAllQueues()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM classrooms_queue WHERE status = 0");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function getNumberOfClassrooms()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM classrooms");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function questionsRecent24Hours()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM classrooms_queue WHERE created_at >= NOW() - INTERVAL 1 DAY");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function countAllUsers()
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    function getOnlineUsers()
    {
        $stmt = $this->pdo->prepare("
        SELECT username, last_login
        FROM users
        WHERE last_login >= UNIX_TIMESTAMP(NOW() - INTERVAL 30 MINUTE)
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getRecentQuestions()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM classrooms_queue WHERE created_at >= NOW() - INTERVAL 1 DAY");
        $stmt->execute();
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
            studentlocation varchar(255) NOT NULL,
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
            roles_mask int(11) ,
            PRIMARY KEY (id),
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (classroom_id) REFERENCES Classrooms(id)
            )ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

    }



}


?>