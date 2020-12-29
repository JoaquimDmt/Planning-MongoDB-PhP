<?php
class Connexion{

    private $_collection;
    private $_identifiant;
    private $_password;
    private $_manager;

    public function __construct(){
        $this->_identifiant = "valereAdmin";
        $this->_password = "bm58ot05";
   }

   public function doConnect(){
        try{

            $this->_manager = new MongoDB\Driver\Manager("mongodb+srv://{$this->_identifiant}:{$this->_password}@cluster0.hychf.mongodb.net/Planning?retryWrites=true&w=majority");

        }catch(MongoDB\Driver\Exception\InvalidArgumentException $e )
        {
               echo $e->getMessage();
        }
    }

    public function getManagerDB(){return $this->_manager;}
    

}
?>