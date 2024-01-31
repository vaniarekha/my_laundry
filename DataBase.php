<?php
require "DataBaseConfig.php";

class DataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $username;
    protected $password;
    protected $databasename;

    public function __construct()
    {
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
        $dbc = new DataBaseConfig();
        $this->servername = $dbc->servername;
        $this->username = $dbc->username;
        $this->password = $dbc->password;
        $this->databasename = $dbc->databasename;
    }

    function dbConnect()
    {
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data)
    {
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function logIn($table, $username, $password)
    {
        $username = $this->prepareData($username);
        $password = $this->prepareData($password);
        $this->sql = "select * from " . $table . " where role = 'user' and username = '" . $username . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
            if (mysqli_num_rows($result) != 0) {
            $dbusername = $row['username'];
            $dbpassword = $row['password'];
            if ($dbusername == $username && password_verify($password, $dbpassword)) {
                $login = true;
            } else $login = false;
        } else $login = false;

        return $login;
    }

    function signUp($table, $name, $email, $username, $password)
    {
        $name = $this->prepareData($name);
        $email = $this->prepareData($email);
        $username = $this->prepareData($username);
        $password = $this->prepareData($password);

        // Check if username already exists
        $checkUsernameQuery = "SELECT * FROM " . $table . " WHERE username = '" . $username . "'";
        $result = mysqli_query($this->connect, $checkUsernameQuery);

        if (mysqli_num_rows($result) > 0) {
            // Username already exists, return false or handle accordingly
            return false;
        }

        // Continue with the registration process
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = "INSERT INTO " . $table . " (name, email, username, password) VALUES ('" . $name . "','" . $email . "','" . $username . "','" . $hashedPassword . "')";

        if (mysqli_query($this->connect, $insertQuery)) {
            return true;
        } else {
            return false;
        }
    }
}

?>
