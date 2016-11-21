<?php
session_start();
require_once "database.php";

class auth
{

    private $conn;

    public function __construct()
    {
        $db = new database();
        $this->conn = $db->connectDB();
    }

    public function __toString()
    {
        echo "Using the toString method: ";
        return "";
    }

    public function login()
    {
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

                    $stmt = $this->conn->prepare($query);
                    $stmt->execute(array(':username' => $username, ':password' => $password));
                    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

                    echo $userRow['nama'];

                    if ($stmt->rowCount() > 0) {
                        $_SESSION['username'] = $username;
                        $_SESSION['userdata'] = array();
                        $userdata = &$_SESSION['userdata'];
                        foreach ($userRow as $item => $value) {
                            $userdata[$item] = $value;
                        }
//                        print_r($userdata);
                        require_once 'navigation.php';
                        navigation::go_to_url('index.php');
                    } else {
                        $error = "Either username or password are wrong.";
                        $_SESSION['errorMsg'] = $error;
                        require_once 'navigation.php';
                        navigation::go_to_url('login.php');
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        require_once 'navigation.php';
        navigation::go_to_url('index.php');
    }

    /**
     * @return PDO
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * @param PDO $conn
     */
    public function setConn($conn)
    {
        $this->conn = $conn;
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