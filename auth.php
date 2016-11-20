<?php
session_start();

class auth
{
    function __construct()
    {

    }

    function connectDB()
    {
        $servername = "basdat.southeastasia.cloudapp.azure.com";
        $port = "1999";
        $username = "affan";
        $password = "affan1234";
        $dbname = "postgres";

        // Create connection
        $conn = new PDO('pgsql:host=' . $servername . ';port=' . $port . ';dbname=' . $dbname, $username, $password);
        $conn = new PDO('pgsql:host=' . $servername . ';port=' . $port . ';dbname=' . $dbname, $username, $password);
        // Check connection
        if (!$conn) {
            die("Connection failed: " + mysqli_connect_error());
        }

        $conn->query("set search_path to sisidang");
        return $conn;
    }

    function login()
    {
        $conn = $this->connectDB();
        $error = "";
        if (isset($_POST['submit'])) {
            if (empty($_POST['username']) || empty($_POST['password'])) {
                $error = "Enter your username and password, please.";
                $_SESSION['errorMsg'] = $error;
                require_once 'navigation.php';
                navigation::go_to_url('index.php');
            } else {
                try {
                    $username = stripslashes($_POST['username']);
                    $password = stripslashes($_POST['password']);

                    $query = "SELECT * FROM sisidang.mahasiswa WHERE username=:username OR password=:password LIMIT 1";

                    $stmt = $conn->prepare($query);
                    $stmt->execute(array(':username' => $username, ':password' => $password));
                    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

                    echo $userRow['nama'];

                    if ($stmt->rowCount() > 0) {
                        $_SESSION["isLogin"] = true;
                        $_SESSION['username'] = $username;
                        require_once 'navigation.php';
                        navigation::go_to_url('index.php');
                    } else {
                        $error = "Either username or password are wrong.";
                        $_SESSION['errorMsg'] = $error;
                        require_once 'navigation.php'; navigation::go_to_url('login.php');
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    function logout()
    {
        session_unset();
        session_destroy();
        require_once 'navigation.php';
        navigation::go_to_url('index.php');
    }
}

$auth = new auth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['submit'] === 'Login') {
        echo "login";
        $auth->login();
    } elseif ($_POST['submit'] === 'Logout') {
        echo "logout";
        $auth->logout();
    }
}