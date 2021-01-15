<?php

    if(!isset($_SESSION))
	{
		session_start();
    }//start session if is not.

    require_once('../models/connexion.php');
    $dbInstance = new Connexion();
    $dbInstance->doConnect();
    $collection = $dbInstance->getManagerDB();//Do a connection to the DB
    
    $fc=$_GET['fc'];//Fonction to execute
    $src = $_GET['ctrl'];//Which controllers the link will leads on
    $empToAct = [
                    'emp'=>$_GET['emp']==null ?"":$_GET['emp'],
                    'week'=>$_GET['week']==null ?"":$_GET['week'], 
                    'year' =>$_GET['year']==null ?"":$_GET['year']
    ];//Array includes Employe that will be add or remove from a week, the week and the year

    $map = array(
           'user' => array(
                            'login'=>array('method'=>'doLogin','args'=>""),
                            'logup'=>array('method'=>'doLogup','args'=>""),
                            'disconnect'=>array('method'=>'disconnect','args'=>"")
            ),
            'calendar' => array(
                            'start' => array('method'=>'startCalendar','args'=>""),
                            'setToNull' => array('method'=>'setEmployeToNull','args'=>array($empToAct['week'],$empToAct['year'])),
                            'setEmpOfWeek' => array('method'=>'setEmployeOfWeek','args'=>array($empToAct['emp'],$empToAct['week'],$empToAct['year'])),
                            'statistics' => array("method"=>'getStatistics', 'args'=>"")
                            )

    );//This array is like a map matching with the right controllers and the right method then.
    

    
   
    require_once('./'.$src.'Controller.php');//Call thecontrollersrs that will be needed
    $curControler = $src.'Controller';
    $curControler = new $curControler($collection);//Create new controllers
    
    $method = $map[$src][$fc]['method'];//assembling the method
    $curControler->$method($map[$src][$fc]['args']);//executes the method


?>