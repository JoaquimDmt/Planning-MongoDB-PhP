<?php
class Connexion{

    private $_collection;
    private $_identifiant;
    private $_password;
    private $_manager;

    public function __construct(){
        $dotenv = json_decode(file_get_contents('env.json',true));
        $this->_identifiant = $dotenv->{"IDENTIFIANT"};
        $this->_password = $dotenv->{'MOT_DE_PASSE'};
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