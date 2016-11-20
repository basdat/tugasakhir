<?php
session_start();

class auth
{
    function __construct() {

    }

    function connectDB()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "lab8solution";

        // Create connection

        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Check connection

        if (!$conn) {
            die("Connection failed: " + mysqli_connect_error());
        }

        return $conn;
    }

    function login()
    {
        $conn = connectDB();
        $error = "";
        if (isset($_POST['submit'])) {
            if (empty($_POST['username']) || empty($_POST['password'])) {
                $error = "Enter your username and password, please.";
                $_SESSION['errorMsg'] = $error;
                header("Location: index.php");
            } else {
                $username = stripslashes($_POST['username']);
                $password = stripslashes($_POST['password']);
                $query = "SELECT * FROM user WHERE username ='$username' AND password = '$password' AND role = 'admin'";
                $result = $conn->query($query);
                if ($result->num_rows == 1) {
                    $_SESSION["isLogin"] = true;
                    $_SESSION['username'] = $username;
                    /*header("Location: admin.php");*/
                } else {
                    $error = "Either username or password are wrong.";
                    $_SESSION['errorMsg'] = $error;
                    header("Location: index.php");
                }
            }
        }
    }

    function logout()
    {
        session_unset();
        session_destroy();
        header("Location: index.php");
    }
}

$auth = new auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['submit'] === 'Login') {
        echo "kocak login";
        $auth->login();
    } elseif ($_POST['submit'] === 'Logout') {
        echo "kocak logout";
        $auth->logout();
    }
}