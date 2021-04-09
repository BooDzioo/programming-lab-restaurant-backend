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

        if(!$this->connect->connect_errno) {
            mysqli_select_db($this->connect, $this->db_name);
        } else {
            http_response_code(400);
        }
    }

    public function getConnection() {
        return $this->connect;
    }
}

$database = new Database();

?>