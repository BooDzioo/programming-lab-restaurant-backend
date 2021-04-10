<?php
class Database
{
    public $connect;
    private $host;
    private $username;
    private $db_name;
    private $password;

    function __construct() {
        $this->host = "localhost";
        $this->username = "root";
        $this->db_name = "project_restaurant";
        $this->password = "";

        $this->connect = new mysqli($this->host, $this->username, $this->password, $this->db_name);
    }

    public function getConnection() {
        if(!$this->connect->connect_errno) {
            mysqli_select_db($this->connect, $this->db_name);
            return $this->connect;
        } else {
            http_response_code(500);
        }
    }
}

$database = new Database();

?>