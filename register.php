<?php
require_once 'Models/Database.php';
require_once 'vendor/autoload.php';



$dbContext = new DB();

$message = "";
$username = "";

$registeredOk = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = "Registrering misslyckades";


    $username = $_POST['username'];
    $password = $_POST['password']; // Hejsan123#

    try {
        $userId = $dbContext->getUsersDatabase()->getAuth()->register($username, $password, $username, function ($selector, $token) {

        });
        $registeredOk = true;
    } catch (\Delight\Auth\InvalidEmailException $e) {
        $message = "Ej korrekt email";
    } catch (\Delight\Auth\InvalidPasswordException $e) {
        $message = "Invalid password";
    } catch (\Delight\Auth\UserAlreadyExistsException $e) {
        $message = "Finns redan";
    } catch (\Exception $e) {
        $message = "Ngt gick fel";
    }

}


?>

<body>
    <main>
        <div class="top-header">
            <div class="logo">
                <a href="index.html"> <img src="/images/rocket.png"></a>
            </div>
            <div>
                <label for="active" class="menu-btn">
                    <i class="fas fa-bars" id="menu"></i>
                </label>
            </div>
        </div>

        <div class="content">
            <?php if ($registeredOk) {

                ?>
                <div>Tack för din registering, kolla mailet och klicka </div>

                <?php
            } else {

                ?>

                <!DOCTYPE html>
                <html lang="en">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Form Styling with Bootstrap</title>
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
                    </style>
                </head>

                <body>
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
                                                        <button type="submit" class="btn">Registrera</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </body>

                </html>


                <?php
            }
            ?>

        </div>


    </main>



    <?php

    ?>

</body>

</html>




</html>