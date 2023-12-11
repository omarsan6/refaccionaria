<?php

    class Query extends Conexion{

        private $pdo, $con, $sql;

        private $datos;

        public function __construct(){
            $this->pdo = new Conexion();
            $this->con = $this->pdo->connect(); 
        }

        public function select($sql)
        {
            $result = $this->con->prepare($sql);
            $result->execute();
            $data = $result->fetch(PDO::FETCH_ASSOC);
            return $data;
        }

        public function selectAll($sql)
        {
            $result = $this->con->prepare($sql);
            $result->execute();
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }

        public function save($sql,$datos){
            $this->sql = $sql;
            $this->datos = $datos;

            $insert = $this->con->prepare($this->sql);
            $data = $insert->execute($this->datos);

            if($data){
                $res = 1;
            } else{
                $res = 0;
            }

            return $res;
        }
    }


?>