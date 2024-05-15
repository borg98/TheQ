<?php
require_once ('vendor/autoload.php');
require_once ('Database.php');
require_once ('UserDatabase.php');
require_once ('Validator.php');

$dbContext = new Db();
$v = new Validator($_POST);
$email = "";
$username = "";
$message = "";
$password = "";


$registeredOk = false;

$auth = $dbContext->getUsersDatabase()->getAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = $dbContext->getUsersDatabase()->getAuth();
    $message = "Registrering misslyckades";

    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zip = $_POST['zip'];
    $phone = $_POST['phone'];


    $v->field('email')->required()->email();
    $v->field('username')->required();
    $v->field('password')->required()->min_len(8)->max_len(16)->must_contain('@#$&!')->must_contain('a-z')->must_contain('A-Z')->must_contain('0-9');




    if ($v->is_valid()) {

        echo "hej";

        $userId = $auth->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.ethereal.email';
            $mail->SMTPAuth = true;
            $mail->Username = 'wilburn.abernathy@ethereal.email';
            $mail->Password = 'BkNVQYtEMDk6CuhMd8';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->From = "stefans@superdupershop.com";
            $mail->FromName = "Hello"; //To address and name 
            $mail->addAddress($_POST['username']); //Address to which recipient will reply 
            $mail->addReplyTo("noreply@ysuperdupershop.com", "No-Reply"); //CC and BCC 
            $mail->isHTML(true);
            $mail->Subject = "Registrering";
            $url = 'http://localhost:8000/verify_email.php?selector=' . \urlencode($selector) . '&token=' . \urlencode($token);
            $mail->Body = "<i>Hej, klicka på <a href='$url'>$url</a></i> för att verifiera ditt konto";
            $mail->send();
        });

        $dbContext->getUsersDatabase()->insertUserDetails($userId, $firstname, $lastname, $address, $city, $zip, $phone);


        $registeredOk = true;


    } else {
        $message = "Ngt gick fel";
    }
}

?>

<body>
    <main>

        <?php if ($registeredOk) { ?>
            <div>Tack för din registering, kolla mailet och klicka</div>
        <?php } else { ?>
            <h1><?php echo $message; ?></h1>
            <form method="post" class="form">
                <input type="text" name="email" placeholder="Email" value="<?php echo $email ?>">

                <input type="text" name="username" placeholder="Username" value="<?php echo $username ?>">
                <input type="password" name="password" placeholder="Password">
                <input type="text" name="firstname" placeholder="First Name">
                <input type="text" name="lastname" placeholder="Last Name">
                <input type="text" name="address" placeholder="Address">
                <input type="text" name="city" placeholder="City">
                <input type="text" name="zip" placeholder="ZIP Code">
                <input type="text" name="phone" placeholder="Phone Number">
                <button type="submit" class="btn">Registrera</button>
            </form>
        <?php } ?>
    </main>
</body>