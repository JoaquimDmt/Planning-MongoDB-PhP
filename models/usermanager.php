<?php
class userManager{

    private $_managerDb;

    public function __construct($db)
    {
        require_once('user.php');
        $this->_managerDb = $db;
    }

    public function getUserById($id){//return the user by his ID

        $filter = ['id' => $id];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        //Exécution de la requête
        $cursor =  $this->_managerDb->executeQuery('Planning.users', $read);
        foreach($cursor as $user)
        {
                return $user;
        }
    }

    public function getUserByPassAndEmail($tabFilter){//Return user by his password and email

        return $user = $this->testExistUser($tabFilter);//Test if he exists  and return the user or null then
    }

    public function addUser($tabUser)//add user to the DB
    {
        $userTabFilter=['email' =>$tabUser['email']];
        //On insère le nouvel uilisateur
        if($this->testExistUser($userTabFilter) == null)//Test if he has been added before and return the user(we will set an error then) or null (we can add it  then)
        {
            $single_insert = new MongoDB\Driver\BulkWrite();
            $newAddId = $single_insert->insert($tabUser);//return the id of the new add
            // Création d'une nouvel objet de la collection "users"
            $this->_managerDb->executeBulkWrite('Planning.users', $single_insert) ;
            
        }else{
            $newAddId = null;
        }
        
        return $newAddId;//return null of the id
    }

    public function createUser($userTab){//create the user as a PHP object

       
        $user = new User($userTab['id']);
        $user->hydrate($userTab);
        return $user;
    }

    public function testExistUser($tabFilter)//test if the user already exists
    {
            
            $option = [];
            $read = new MongoDB\Driver\Query($tabFilter, $option);
            //Exécution de la requête
            $cursor =   $this->_managerDb->executeQuery('Planning.users', $read);
            //On vérifie si le resultat de la requete existe
            foreach($cursor as $user)
            {
                $userExist = $user ? $user : null;//set to the user value if exists, null insted
            }
            //var_dump($userExist);
            return $userExist;
    }

}
?>
