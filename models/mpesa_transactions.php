<?php 
require_once('initialization.php');

class MPESATransactions{
    //Decalare table name 
    private $conn;
    private $table_name = 'mpesa_transactions';
    //Declare class properties 
    public $id;
    public $app_token;
    public $transaction_type;
    public $transaction_id;
    public $transaction_time;
    public $transaction_amount;
    public $business_shortcode;
    public $bill_refnumber;
    public $invoice_number;
    public $original_balance;
    public $third_party_transaction_id;
    public $msisdn;
    public $first_name;
    public $middle_name;
    public $last_name;
    
    //connect to db 
    public function __construct()
    {
        global $database;
        $this->conn = $database->connect();
    }

    public function create()
    {
       $query = 'INSERT INTO '.$this->table_name.'(transaction_type, transaction_id, transaction_time, transaction_amount, business_shortcode, bill_refnumber, invoice_number, original_balance, third_party_transaction_id, msisdn, first_name, middle_name, last_name)VALUES(:transaction_type, :transaction_id, :transaction_time, :transaction_amount, :business_shortcode, :bill_refnumber, :invoice_number, :original_balance, :third_party_transaction_id, :msisdn, :first_name, :middle_name, :last_name )';  

       //Prepare statement
       $stmt = $this->conn->prepare($query);

       //clean data 
       $this->transaction_type = htmlentities($this->transaction_type);
       $this->transaction_id = htmlentities($this->transaction_id);
       $this->transaction_time = htmlentities($this->transaction_time);
       $this->transaction_amount = htmlentities($this->transaction_amount);
       $this->business_shortcode = htmlentities($this->business_shortcode);
       $this->bill_refnumber = htmlentities($this->bill_refnumber);
       $this->invoice_number = htmlentities($this->invoice_number);
       $this->original_balance = htmlentities($this->original_balance);
       $this->third_party_transaction_id = htmlentities($this->third_party_transaction_id);
       $this->msisdn = htmlentities($this->msisdn);
       $this->first_name = htmlentities($this->first_name);
       $this->middle_name = htmlentities($this->middle_name);
       $this->last_name = htmlentities($this->last_name);

       //Bind Data
       $stmt->bindParam(':transaction_type', $this->transaction_type);
       $stmt->bindParam(':transaction_id', $this->transaction_id);
       $stmt->bindParam(':transaction_time', $this->transaction_time);
       $stmt->bindParam(':transaction_amount', $this->transaction_amount);
       $stmt->bindParam(':business_shortcode', $this->business_shortcode);
       $stmt->bindParam(':bill_refnumber', $this->bill_refnumber);
       $stmt->bindParam(':invoice_number', $this->invoice_number);
       $stmt->bindParam(':original_balance', $this->original_balance);
       $stmt->bindParam(':third_party_transaction_id', $this->third_party_transaction_id);
       $stmt->bindParam(':msisdn', $this->msisdn);
       $stmt->bindParam(':first_name', $this->first_name);
       $stmt->bindParam(':middle_name', $this->middle_name);
       $stmt->bindParam(':last_name', $this->last_name);

       //Execute query 
       if($stmt->execute()){
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

    public function update()
    {
        $query = "UPDATE ".$this->table_name." SET app_token = :app_token, transaction_type = :transaction_type, transaction_id = :transaction_id, transaction_time = :transaction_time, transaction_amount = :transaction_amount, business_shortcode = :business_shortcode, bill_refnumber = :bill_refnumber, invoice_number = :invoice_number, original_balance = :original_balance, third_party_transaction_id = :third_party_transaction_id, msisdn = :msisdn, first_name = :first_name, last_name = :last_name WHERE id = :id";

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        //clean data
        $this->app_token = htmlentities($this->app_token);
        $this->transaction_type = htmlentities($this->transaction_type);
        $this->transaction_id = htmlentities($this->transaction_id);
        $this->transaction_time = htmlentities($this->transaction_time);
        $this->transaction_amount = htmlentities($this->transaction_amount);
        $this->business_shortcode = htmlentities($this->business_shortcode);
        $this->bill_refnumber = htmlentities($this->bill_refnumber);
        $this->invoice_number = htmlentities($this->invoice_number);
        $this->original_balance = htmlentities($this->original_balance);
        $this->third_party_transaction_id = htmlentities($this->third_party_transaction_id);
        $this->msisdn = htmlentities($this->msisdn);
        $this->first_name = htmlentities($this->first_name);
        $this->middle_name = htmlentities($this->middle_name);
        $this->last_name = htmlentities($this->last_name);

        //Bind Data
        $stmt->bindParam(':app_token', $this->app_token);
        $stmt->bindParam(':transaction_type', $this->transaction_type);
        $stmt->bindParam(':transaction_id', $this->transaction_id);
        $stmt->bindParam(':transaction_time', $this->transaction_time);
        $stmt->bindParam(':transaction_amount', $this->transaction_amount);
        $stmt->bindParam(':business_shortcode', $this->business_shortcode);
        $stmt->bindParam(':bill_refnumber', $this->bill_refnumber);
        $stmt->bindParam(':invoice_number', $this->invoice_number);
        $stmt->bindParam(':original_balance', $this->original_balance);
        $stmt->bindParam(':third_party_transaction_id', $this->third_party_transaction_id);
        $stmt->bindParam(':msisdn', $this->msisdn);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':middle_name', $this->middle_name);
        $stmt->bindParam(':last_name', $this->last_name);

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
    public function delete() {
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