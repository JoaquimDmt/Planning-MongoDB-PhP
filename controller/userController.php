<?php
class userController{
    private $_userManager;
    private $_user;
    private $_redirectTab;

    public function __construct($collect)
    {
        require('../model/user.php');
        require_once('../model/usermanager.php');
        $this->_userManager = new Usermanager($collect);
        $this->_redirectTab = array(
            'form' => "vue/form.php",
            'calendrier' => "vue/calendrier.php"
        );

    }

    public function doLogin(){
        $redirect ="";
        if(isset($_POST['staticEmail']) && isset($_POST['inputPassword']) && $_POST['staticEmail'] != "" && $_POST['inputPassword'] !="")
        {
            $userTabFilter=array(
                '$and' =>array(
                        ['email' => $_POST['staticEmail']],
                          ['password' => $_POST['inputPassword']])
            );
<<<<<<< Updated upstream
            $this->_user = $this->_userManager->createUser($result['_id'],$user);
            if($_SESSION['userStateLogIn'] = $this->_user == 'null'){
                ['res'=>'Echec à la ','couleur' => 'red'];
                header('Location : ../vue/form.php');
=======

            if($result = $this->_userManager->getUserByPassAndEmail($userTabFilter) != null)
            {
                $user= array(
                    'id' => $result['_id'],
                    'email' => $result['email'],
                    'password' => $result['password'],
                    'firstname' => $result['firstname'],
                    'lastname' => $result['lastname'],
                    'pseudo' => $result['pseudo']
                );
                $this->_user = $this->_userManager->createUser($user);
                if($this->_user == 'null'){

                    $_SESSION['userStateLogIn'] = ['res'=>'Une erreur a lieu lors de la connexion, veuillez reessayer plus tard.','couleur' => 'red'];
                    $redirect = "form";

                }else{
                    ['res'=>'Connexion réussie','couleur' => 'green'];
                    $redirect = "calendrier";
                    $_SESSION['user'] =$this->_user;
                }
            
>>>>>>> Stashed changes
            }else{
                $_SESSION['userStateLogIn'] = ['res'=>'Aucun compte avec votre identifiant et mot de passe existe.','couleur' => 'red'];
                $redirect = "form";
            }

        }else{
            $_SESSION['userStateLogIn'] = ['res'=>'Veuillez remplir le formulaire correctement.','couleur' => 'red'];
            $redirect = "form";
        }
        header("Location : ../{$this->_redirectTab[$redirect]}");
        

        
    }

    public function doLogup()
    {
        $redirect="";
        $user=array(
            'email' => $_POST['staticEmail'],
            'firstname' => $_POST['inputFirstName'],
            'lastname' => $_POST['inputLastName'],
            'password' => $_POST['inputPassword'],
            'pseudo' => $_POST['inputPseudo'],
        );
        if(isset($user['email']) && ($user['email']!="") && isset($user['firstname']) && ($user['firstname']!="") && isset($user['lastname']) && ($user['lastname']!="") && isset($user['password']) && ($user['password']!="") && isset($user['pseudo']) && ($user['pseudo']!=""))
        {
            $idNewAdd = $this->_userManager->addUser($user);
            
            if($_SESSION['userStateLogUp'] = $idNewAdd == 'null')
            {
                $_SESSION['userStateLogUp'] =['res'=>'Un compte avec votre identifiant et mot de passe existe déjà','couleur' => 'red'];
                $redirect = "form";
            }else{
                $this->_user = $this->_userManager->createUser($user);
                ['res'=>'Inscription réussie','couleur' => 'green'];
                $redirect = "calendrier";
                $_SESSION['user'] = $this->_user;

            }
            

        }else{
            $_SESSION['userStateLogUp'] = ['res'=>'Veuillez remplir le formulaire correctement','couleur' => 'red'];
            $redirect = "form";
        }
        header("Location : ../{$this->_redirectTab[$redirect]}");
    }


}
?>
