<?php
session_start();

if (isset($_POST['userid']) && isset($_POST['password'])) {
    // if the user has just tried to log in
    $userid = $_POST['userid'];
    $password = $_POST['password'];

    $db_conn = new mysqli('localhost', 'auth', 'gromulax1214!', 'auth');
//  $db_conn = mysqli_connect('localhost', 'auth', 'gromulax1214!', 'auth');

    if (mysqli_connect_errno()) {
        echo 'Connection to database failed:' . mysqli_connect_error();
        exit();
    }
    $addQuery = "insert into authorized_users values'" . $userid . "' sha1('" . $password . "')";
    $chkQuery = "select * from authorized_users where 
            name='" . $userid . "' and 
            password=sha1('" . $password . "')";
    $addQuery = $db_conn->query($addQuery);
    $result = $db_conn->query($chkQuery);
    if ($result->num_rows) {
// if they are in the database register the user id
        $_SESSION['valid_user'] = $userid;
    }
    $db_conn->close();
//    mysqli_close($db_conn);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
</head>
<body>
<?php
if (isset($_SESSION['valid_user'])) {
    echo '<p>You are logged in as: ' . $_SESSION['valid_user'] . ' <br />';
    echo '<a href="logout.php">Log out</a></p>';
} else {
    if (isset($userid)) {
        // if they've tried and failed to log in
        echo '<p>Could not log you in.</p>';
    } else {
        // they have not tried to log in yet or have logged out
        echo '<p>You are not logged in.</p>';
    }

    // provide form to log in
    echo '<form action="registration.php" method="post">';
    echo '<fieldset>';
    echo '<legend>Login Now!</legend>';
    echo '<p><label for="userid">UserID:</label>';
    echo '<input type="text" name="userid" id="userid" size="30"/></p>';
    echo '<p><label for="password">Password:</label>';
    echo '<input type="password" name="password" id="password" size="30"/></p>';
    echo '</fieldset>';
    echo '<button type="submit" name="register">Register</button>';
    echo '</form>';

}
?>
<p><a href="authmain.php.php">Go to login</a></p>
</body>
</html>