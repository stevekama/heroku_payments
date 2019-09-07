<?php 
require_once('initialization.php');

class Transactions{
    //Decalare table name 
    private $conn;
    private $table_name = 'transactions';
    //Declare class properties 
    public $id;
    public $app_token;
    public $transaction_id;
    public $transaction_time;
    public $product;
    public $transaction_amount;
    public $transaction_currency;
    public $transaction_method;
    public $transaction_status;

    //connect to db 
    public function __construct()
    {
        global $database;
        $this->conn = $database->connect();
    }

    public function create()
    {
       $query = 'INSERT INTO '.$this->table_name.'(transaction_id, transaction_time, product, transaction_amount, transaction_currency, transaction_method, transaction_status)VALUES(:transaction_id, :transaction_time, :product, :transaction_amount, :transaction_currency, :transaction_method, :transaction_status)';

       //Prepare statement
       $stmt = $this->conn->prepare($query);

       //clean data 
       $this->transaction_id = htmlentities($this->transaction_id);
       $this->transaction_time = htmlentities($this->transaction_time);
       $this->product = htmlentities($this->product);
       $this->transaction_amount = htmlentities($this->transaction_amount);
       $this->transaction_currency = htmlentities($this->transaction_currency);
       $this->transaction_method = htmlentities($this->transaction_method);
       $this->transaction_status = htmlentities($this->transaction_status);

       //Bind Data
       $stmt->bindParam(':transaction_id', $this->transaction_id);
       $stmt->bindParam(':transaction_time', $this->transaction_time);
       $stmt->bindParam(':product', $this->product);
       $stmt->bindParam(':transaction_amount', $this->transaction_amount);
       $stmt->bindParam(':transaction_currency', $this->transaction_currency);
       $stmt->bindParam(':transaction_method', $this->transaction_method);
       $stmt->bindParam(':transaction_status', $this->transaction_status);
       
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

    public function find_all(Type $var = null)
    {
        $query = "SELECT * FROM ".$this->table_name." ORDER BY id DESC";
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }

    public function update()
    {
        $query = "UPDATE ".$this->table_name." SET app_token = :app_token, transaction_id = :transaction_id, transaction_time = :transaction_time, product = :product, transaction_amount = :transaction_amount, transaction_currency = :transaction_currency, transaction_method = :transaction_method, transaction_status = :transaction_status WHERE id = :id";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        //clean data 
        $this->transaction_id = htmlentities($this->transaction_id);
        $this->transaction_time = htmlentities($this->transaction_time);
        $this->product = htmlentities($this->product);
        $this->transaction_amount = htmlentities($this->transaction_amount);
        $this->transaction_currency = htmlentities($this->transaction_currency);
        $this->transaction_method = htmlentities($this->transaction_method);
        $this->transaction_status = htmlentities($this->transaction_status);

        //Bind Data
        $stmt->bindParam(':transaction_id', $this->transaction_id);
        $stmt->bindParam(':transaction_time', $this->transaction_time);
        $stmt->bindParam(':product', $this->product);
        $stmt->bindParam(':transaction_amount', $this->transaction_amount);
        $stmt->bindParam(':transaction_currency', $this->transaction_currency);
        $stmt->bindParam(':transaction_method', $this->transaction_method);
        $stmt->bindParam(':transaction_status', $this->transaction_status);

         // Execute query
        if($stmt->execute()) {
            return true;
        }

        //print error 
        $error = new ErrorLogs();
        $error->errors = 'Error';
        $error->description = $stmt->error;
        if($error->create()){
            return false;
        }
    } 

    // Delete
    public function delete() 
    {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }

        //print error 
        $error = new ErrorLogs();
        $error->errors = 'Error';
        $error->description = $stmt->error;
        if($error->create()){
            return false;
        }
    }
}