<?php
    class Database {
       private $con;
    
       public function connect() {
          $this->con = new Mysqli("localhost", "root", "", "ecommerceapp", 3307);
          return $this->con;
       }
    }
?>