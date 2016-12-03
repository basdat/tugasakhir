<?php

class database
{
    private $servername;
    private $port;
    private $username;
    private $password;
    private $dbname;

    public function __construct()
    {
        $this->servername = "basdat.southeastasia.cloudapp.azure.com";
        $this->port = "1999";
        $this->username = "affan";
        $this->password = "affan1234";
        $this->dbname = "postgres";
    }

    public function connectDB()
    {
        // Create connection
        $conn = new PDO('pgsql:host=' . $this->servername . ';port=' . $this->port . ';dbname=' . $this->dbname, $this->username, $this->password);
        // Check connection
        if (!$conn) {
            die("Connection failed: " + mysqli_connect_error());
        }

        $conn->query("set search_path to test");
        return $conn;
    }

    /**
     * @return string
     */
    public function getServername()
    {
        return $this->servername;
    }

    /**
     * @param string $servername
     */
    public function setServername($servername)
    {
        $this->servername = $servername;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getDbname()
    {
        return $this->dbname;
    }

    /**
     * @param string $dbname
     */
    public function setDbname($dbname)
    {
        $this->dbname = $dbname;
    }

}