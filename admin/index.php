<?php
session_start();
$nonavbar = '';
$pageTitle = 'Login';
if (isset($_SESSION['logedin'])) {
    header('Location: dashboard.php'); //redirecat to dashboard page
}
include 'init.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedpass = sha1($password);
    //check if user is in database
    $stmt = $conn->prepare("SELECT UserID , Username,Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1 ");
    $stmt->execute(array($username, $hashedpass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    //if count > 0 this mean database has record
    if ($count > 0) {
        $_SESSION['logedin'] = $username;  // session register in username
        $_SESSION['ID'] = $row['UserID']; // register session id
        header('Location: dashboard.php'); //redirecat to dashboard page
        exit();
    }
}
?>

    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control" type="text" name="user" placeholder=" User Name " autocomplete="off"/>
        <input class="form-control" type="password" name="pass" placeholder=" Password  " autocomplete="new-password"/>
        <input class="btn btn-primary btn-block" type="submit" value="Login"/>

    </form>


<?php
include $tpl . 'footer.php';
