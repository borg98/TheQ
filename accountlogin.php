<?php
require 'vendor/autoload.php';
require_once ('Models/Database.php');

$dbContext = new DB();

$auth = $dbContext->getUsersDatabase()->getAuth();
$message = "";
$username = "";
$ip = $auth->getIpAddress();
$userId = $auth->getUserId();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = "Login failed";


    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];


        try {
            $auth = $dbContext->getUsersDatabase()->getAuth();
            $auth->login($username, $password);
            $dbContext->storeLoginSession($userId, $ip);

            header('Location: /');


            exit;
        } catch (Exception $e) {
            $message = "Could not login";
        }
    } else {
        $message = "Username or password is missing";
    }
}





?>

<body>

    <main>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Login Page Styling with Bootstrap</title>
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet"
                id="bootstrap-css">
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <style>
                .row-box {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background-color: #f8f9fa;
                }

                .col-boxes-1 {
                    background-color: #ffffff;
                    border-radius: 8px;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    width: 100%;
                    max-width: 600px;
                }

                .header-table {
                    text-align: center;
                    margin-bottom: 10px;
                }

                .header-table h2 {
                    font-size: 1.5rem;
                    margin: 0;
                    color: #343a40;
                }

                .error-message {
                    text-align: center;
                    color: red;
                    margin-bottom: 20px;
                }

                .form table {
                    width: 100%;
                }

                .form-control {
                    margin-bottom: 10px;
                }

                .btn-container {
                    text-align: center;
                }

                .btn {
                    background-color: #007bff;
                    color: #fff;
                }

                .btn:hover {
                    background-color: #0056b3;
                }

                .listbutton {
                    background-color: #007bff;
                    color: #fff;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    text-decoration: none;
                    display: inline-block;
                    margin: 5px 0;
                }

                .listbutton:hover {
                    background-color: #0056b3;
                    color: #fff;
                }

                .forgot-password {
                    font-size: 0.9rem;
                    color: #007bff;
                    text-decoration: none;
                }

                .forgot-password:hover {
                    color: #0056b3;
                }
            </style>
        </head>

        <body>
            <?php echo $dbContext->getUsersDatabase()->getAuth()->isLoggedIn(); ?>
            <main>
                <div class="top-header d-flex justify-content-between align-items-center p-3">

                    <div>
                        <label for="active" class="menu-btn">
                            <i class="fas fa-bars" id="menu"></i>
                        </label>
                    </div>
                </div>

                <div class="content">
                    <div class="row-box">
                        <div class="col-boxes-1">
                            <div class="col-table">
                                <div class="table-section">
                                    <div class="header-table">
                                        <h2>Ny kund</h2>
                                    </div>
                                    <div class="error-message">
                                        <?php echo $message; ?>
                                    </div>
                                    <form method="post" class="form">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <th><label for="username">Username</label></th>
                                                    <td>
                                                        <input class="form-control" type="text" name="username"
                                                            value="<?php echo $username; ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><label for="password">Password</label></th>
                                                    <td>
                                                        <input class="form-control" type="password" name="password">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="btn-container">
                                                        <input type="submit" class="btn listbutton" value="Login">
                                                        &nbsp;&nbsp;&nbsp;
                                                        <a href="/" class="btn listbutton">Cancel</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="btn-container">
                                                        <a href="/forgotpassword.php" class="forgot-password">Forgot
                                                            Password</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </body>

        </html>

    </main>



    <?php

    ?>

</body>

</html>