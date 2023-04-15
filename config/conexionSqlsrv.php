<?php

require_once 'config.php';

class conexionSqlsrv {

    public $conn = null;

    public function __construct()
    {}

    public function conectar(){

        try {

            $this->conn = new PDO(
                DB_SERVER,
                DB_USER,
                DB_PASS,
                array(
                    //PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );

            return $this->conn; 
            echo 'bien';
        
        }
        catch(PDOException $e) {

            die("Error connecting to SQL Server: " . $e->getMessage());

            return $this->conn;
echo 'mal';

        }

    }


}


?>