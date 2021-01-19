<?php
class UserController{

    private $_userManager;//User manager from the Model directory
    private $_user;//User from the Model directory
    private $_redirectTab;//Array to lead the controllers to the correct page. Prevent from repeating header('Location...')

    public function __construct($collect)
    {
        require('../models/user.php');
        require_once('../models/usermanager.php');
        $this->_userManager = new Usermanager($collect);
        $this->_redirectTab = array(
            'form' => "views/form.php",
            'calendrier' => "views/calendrier.php"
        );

    }

    //Log the user if he logged up before
    public function doLogin(){
        $redirect ="";
        if(isset($_POST['staticEmail']) && isset($_POST['inputPassword']) && $_POST['staticEmail'] != "" && $_POST['inputPassword'] !="")//check if the form is well fill
        {
            $userTabFilter=array(
                '$and' =>array(
                        ['email' =>$_POST['staticEmail']],
                          ['password' => sha1($_POST['inputPassword'])])
            );//For the filter used on UserManager

            $result = $this->_userManager->getUserByPassAndEmail($userTabFilter);
            if($result  != null)//return null if the user doesn't exist in DB
            {
                
                $user= array(
                    'id' => $result->_id,
                    'email' => $result->email,
                    'password' => $result->password,
                    'firstname' => $result->firstname,
                    'lastname' => $result->lastname,
                    'pseudo' => $result->pseudo
                );//Filter used on UserManager
                
                $this->_user = $this->_userManager->createUser($user);//Create user, not in DB but, for the session var, as an object
                if($this->_user == 'null'){//In case the creation didn't work

                    $_SESSION['userStateLogIn'] = ['res'=>'Une erreur a lieu lors de la connexion, veuillez reessayer plus tard.','couleur' => 'red'];//Session var to explain where the error came from
                    $redirect = "form";//redirect to the form connection

                }else{//In case the connexion worked
                    
                    $_SESSION['userStateLogIn'] = ['res'=>'Connexion réussie','couleur' => 'green'];
                    $redirect = "calendrier";//redirect to the calendar
                    $_SESSION['user'] =$this->_user;//Init the session var user with the user created before
                }
            
            }else{
                $_SESSION['userStateLogIn'] = ['res'=>'Aucun compte avec votre identifiant et mot de passe existe.','couleur' => 'red'];//Session var to explain where the error came from
                $redirect = "form";//redirect to the form connection
            }

        }else{
            $_SESSION['userStateLogIn'] = ['res'=>'Veuillez remplir le formulaire correctement.','couleur' => 'red'];//Session var to explain where the error came from
            $redirect = "form";//redirect to the form connection
        }
        header("Location : ../{$this->_redirectTab[$redirect]}");//proceed to redirection
        

        
    }

    //Subscribing part
    public function doLogup()
    {
        $redirect="";
        $user=array(
            'email' => $_POST['staticEmail'],
            'firstname' => $_POST['inputFirstName'],
            'lastname' => $_POST['inputLastName'],
            'password' => sha1($_POST['inputPassword']),
            'pseudo' => $_POST['inputPseudo'],
        );//For the filter used on UserManager
        if(isset($user['email']) && ($user['email']!="") && isset($user['firstname']) && ($user['firstname']!="") && isset($user['lastname']) && ($user['lastname']!="") && isset($user['password']) && ($user['password']!="") && isset($user['pseudo']) && ($user['pseudo']!=""))//check if the form is well fill
        {
            $idNewAdd = $this->_userManager->addUser($user);//So the user manager can add the new user. Return the id if the add worked, null if he already exists
            
            if( $idNewAdd == null)//if null
            {
                $_SESSION['userStateLogUp'] =['res'=>'Un compte existe déjà avec cet email','couleur' => 'red'];//Session var to explain where the error came from
                $redirect = "form";//redirect to the form connection
            }else{
                $this->_user = $this->_userManager->createUser($user);//Create user, not in DB but, for the session var, as an object
                $_SESSION['userStateLogUp']=['res'=>'Inscription réussie','couleur' => 'green'];
                $redirect = "calendrier";//redirect to the calendar
                $_SESSION['user'] = $this->_user;//Init the session var user with the user created before

            }
            

        }else{
            $_SESSION['userStateLogUp'] = ['res'=>'Veuillez remplir le formulaire correctement','couleur' => 'red'];//Session var to explain where the error came from
            $redirect = "form";//redirect to the form connection
        }
        header("Location : ../{$this->_redirectTab[$redirect]}");//proceed to redirection
    }

    public function disconnect(){
        $redirect="";
        session_destroy();
        header("Location :../index.php");

    }


}
?>
