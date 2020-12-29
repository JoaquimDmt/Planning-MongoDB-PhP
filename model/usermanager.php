<?php
class userManager{

    private $_managerDb;

    public function __construct($db)
    {
        require_once('connexion.php');
        require_once('user.php');
        $this->_managerDb = $db;
    }

    public function getUserById($id){

        $filter = ['id' => $id];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        //Exécution de la requête
        $cursor =  $this->_managerDb->executeQuery('CollectPlanning.users', $read);
        foreach($cursor as $user)
        {
                return $user;
        }
    }

    public function getUserByPassAndEmail($tabFilter){

        return $user = $this->testExistUser($tabFilter);
    }

    public function addUser($tabUser)
    {
        //On insère le nouvel uilisateur
        if($this->testExistUser($tabUser) == null)
        {
            $single_insert = new MongoDB\Driver\BulkWrite();
            $newAddId = $single_insert->insert($tabUser);
            // Création d'une nouvel objet de la collection "users"
            $this->_managerDb->executeBulkWrite('CollectPlanning.users', $single_insert) ;
            
        }else{
            $newAddId = 'null';
        }
        
        return $newAddId;
    }

    public function createUser($id, $userTab){

        $user = new User($id);
        $user->hydrate($userTab);
        var_dump($user);
        return $user;
    }

    public function testExistUser($tabFilter)
    {
        
            $option = [];
            $read = new MongoDB\Driver\Query($tabFilter, $option);
            //Exécution de la requête
            $cursor =   $this->_managerDb->executeQuery('CollectPlanning.users', $read);
            //On vérifie si le resultat de la requete existe
            foreach($cursor as $user)
            {
                
                $userExist = $user ? $user : null;
            }
            var_dump($userExist);
            return $userExist;
    }

}
?>
