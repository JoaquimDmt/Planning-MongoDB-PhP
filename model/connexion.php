<?php
class Connexion{

    private $_collection;
    private $_identifiant;
    private $_password;
    private $_manager;

    public function __construct(){
        $this->_identifiant = "";
        $this->_password = "";
   }

   public function doConnect(){
        try{
            $this->_manager = new MongoDB\Driver\Manager('mongodb+srv://'.$this->_identifiant.':'.$this->_password.'@planning.hychf.mongodb.net/test');
        }catch(MongoDB\Driver\Exception\InvalidArgumentException $e )
        {
                $e->getMessage();
        }
    }

    public function getManagerDB(){return $this->_manager;}
    

}
?>