<?php
class Database
{
    private $host = "localhost";
    private $username = "root";
    private $db_name = "project_restaurant";
    private $password = "";
    public $connect;
 
    public function getConnection(){
 
        $connect = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
 
        if(!$connect->connect_errno) {
            mysqli_select_db($connect, $this->db_name);
        } else {
            http_response_code(400);
        }
 
        return $connect;
    }
}
?>