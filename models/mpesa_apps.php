<?php 
require_once('initialization.php');

class MPESA_Apps{
    //Decalare table name 
    private $conn;
    private $table_name = 'mpesa_apps';
    //Declare class properties 
    public $id;
    public $app_name;
    public $app_key;
    public $app_secret;
    public $app_token;
    public $shortcode;
    public $confirmation;
    public $validation;
    
    //connect to db 
    public function __construct()
    {
        global $database;
        $this->conn = $database->connect();
    }

    public function create()
    {
       $query = 'INSERT INTO '.$this->table_name.'(app_name, app_key, app_secret, app_token, shortcode, confirmation, validation)VALUES(:app_name, :app_key, :app_secret, :app_token, :shortcode, :confirmation, :validation)';  

       //Prepare statement
       $stmt = $this->conn->prepare($query);

       //clean data 
       $this->app_name = htmlentities($this->app_name);
       $this->app_key = htmlentities($this->app_key);
       $this->app_secret = htmlentities($this->app_secret);
       $this->app_token = htmlentities($this->app_token);
       $this->shortcode = htmlentities($this->shortcode);
       $this->confirmation = htmlentities($this->confirmation);
       $this->validation = htmlentities($this->validation);
       

       //Bind Data
       $stmt->bindParam(':app_name', $this->app_name);
       $stmt->bindParam(':app_key', $this->app_key);
       $stmt->bindParam(':app_secret', $this->app_secret);
       $stmt->bindParam(':app_token', $this->app_token);
       $stmt->bindParam(':shortcode', $this->shortcode);
       $stmt->bindParam(':confirmation', $this->confirmation);
       $stmt->bindParam(':validation', $this->validation);

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

}