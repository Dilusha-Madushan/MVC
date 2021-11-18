<?php
class Database {

    private $host = DB_HOST;
    private $user = DB_USER;
    private $pwd = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;

    public function __construct(){

        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try{
            $this->dbh = new PDO($dsn, $this->user , $this->pwd, $options);

        } catch(PDOException $e){
            echo $e->getMessage();
            die();
        }
    }

    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($stmt , $param , $value , $type = null){
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                $type = PDO::PARAM_STR;    
            }
        }

        $this->stmt->bindValue($param , $value , $type);
    }

    public function execute(){
        return $this->stmt->execute();
    }

    public function resultSet(){
        if(!is_null($this->stmt)){
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return null;
    }

    public function singleResult(){
        if(!is_null($this->stmt)){
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }
        return null;
    }

    public function rowCount($stmt){
        if(!is_null($this->stmt)){
            return $this->stmt->rowCount();
        }
        return -1;
        
    }

    public function resetStmt(){
        $this->stmt = null;
    }
}