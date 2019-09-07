<?php 
require_once('initialization.php');

class Apps{
    //Decalare table name 
    private $conn;
    private $table_name = 'apps';
    //Declare class properties 
    public $id;
    public $app_name;
    public $app_method;
    public $app_key;
    public $app_secret;
    public $app_token;
    
    //connect to db 
    public function __construct()
    {
        global $database;
        $this->conn = $database->connect();
    }

    public function create()
    {
       $query = 'INSERT INTO '.$this->table_name.'(app_name, app_method, app_key, app_secret, app_token)VALUES(:app_name, :app_method, :app_key, :app_secret, :app_token)';  

       //Prepare statement
       $stmt = $this->conn->prepare($query);

       //clean data 
       $this->app_name = htmlentities($this->app_name);
       $this->app_method = htmlentities($this->app_method);
       $this->app_key = htmlentities($this->app_key);
       $this->app_secret = htmlentities($this->app_secret);
       $this->app_token = bin2hex(openssl_random_pseudo_bytes(10));
       
       //Bind Data
       $stmt->bindParam(':app_name', $this->app_name);
       $stmt->bindParam(':app_method', $this->app_method);
       $stmt->bindParam(':app_key', $this->app_key);
       $stmt->bindParam(':app_secret', $this->app_secret);
       $stmt->bindParam(':app_token', $this->app_token);
       
       //Execute query 
       if($stmt->execute()){
           return true;
       }
       //print error 
       $error = new ErrorLogs();
       $error->errors = $stmt->error;
       $error->description = $stmt->error;
       if($error->create()){
            return false;
       }
    }

    public function find_by_token($token = '')
    {
        $query = "SELECT * FROM ".$this->table_name." WHERE app_token = :app_token LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(array('app_token'=>$token));
        $count = $stmt->rowCount();
        if($count > 0){
            $app = $stmt->fetch(PDO::FETCH_ASSOC);
            return $app;
        }else{
            return false;
        }
    }

}