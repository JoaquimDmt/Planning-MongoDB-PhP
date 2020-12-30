<?php
class Connexion{

    private $_collection;
    private $_identifiant;
    private $_password;
    private $_manager;

    public function __construct(){//constructor : the password and the id are set in env.json for privacy.
        $dotenv = json_decode(file_get_contents('../Config/env.json',true));
        $this->_identifiant = $dotenv->{"IDENTIFIANT"};
        $this->_password = $dotenv->{'MOT_DE_PASSE'};
   }

   public function doConnect(){//Connect to the DB and set the managerDb
        try{

            $this->_manager = new MongoDB\Driver\Manager("mongodb+srv://{$this->_identifiant}:{$this->_password}@cluster0.hychf.mongodb.net/Planning?retryWrites=true&w=majority");

        }catch(MongoDB\Driver\Exception\InvalidArgumentException $e )
        {
                $_SESSION['userStateLogIn'] = ['res'=>'Une erreur a eu lieu lors de la connexion à la base de donnée. Veuillez réessayer plus tard.','couleur' => 'red'];//Session var to explain where the error came from
                
                header("Location : ../views/form.php");
        }
    }

    public function getManagerDB(){return $this->_manager;}//return the managerDb
    

}
?>