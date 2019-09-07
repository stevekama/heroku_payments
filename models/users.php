<?php 
require_once('initialization.php');

class Users {
    private $conn;
    private $table_name = 'users';

    // Users properties 
    public $id;
    public $fullnames;
    public $phone;
    public $email;
    public $username;
    public $password;

    //db connect
    public function __construct(){
        global $database;
        $this->conn = $database->connect();
    }
    //get user by email 
    public function find_user_by_id()
    {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE id = ? LIMIT 0,1';
        
        //Prepare statement 
        $stmt = $this->conn->prepare($query);

        //Bind Email
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        return $user;
    }  

    public function authenticate_user($email = '', $password = '')
    {
        $query = "SELECT * FROM ".$this->table_name." WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(
            array('email'=>$email)
        );
        $count = $stmt->rowCount();
        if($count > 0){
            while($user = $stmt->fetch(PDO::FETCH_ASSOC)){
                if(password_verify($password, $user['password'])){
                    return $user;
                }
            }
        }
    }  

    //find user by email 
    public function find_user_by_email($email="")
    {
        $query = "SELECT * FROM ".$this->table_name." WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array('email'=>$email));

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    public function create(){
        $query = "INSERT INTO ".$this->table_name."(fullnames, phone, email, username, password)VALUES(:fullnames, :phone, :email, :username, :password)";

        //propare statement 
        $stmt = $this->conn->prepare($query);

        //clean data
        $this->fullnames = htmlentities($this->fullnames);
        $this->phone = htmlentities($this->phone);
        $this->email = htmlentities($this->email);
        $this->username = htmlentities($this->username);
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        //Bind Data
        $stmt->bindParam(':fullnames', $this->fullnames);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);

        //Execute Query 
        if($stmt->execute()){
            return true;
        } 
    }
}