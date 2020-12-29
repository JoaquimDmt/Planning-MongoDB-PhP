<?php
    session_start();
    require_once('../model/connexion.php');
    $dbInstance = new Connexion();
    $dbInstance->doConnect();
    $collection = $dbInstance->getManagerDB();
    
    $fc=$_GET['fc'];
    $src = $_GET['ctrl'];
    $empToAct = ['emp'=>$_GET['emp']==null ?"":$_GET['emp'],'week'=>$_GET['week']==null ?"":$_GET['week'], 'year' =>$_GET['year']==null ?"":$_GET['year']];
    $map = array(
           'user' => array(
                            'login'=>array('method'=>'doLogin','args'=>""),
                            'logup'=>array('method'=>'doLogup','args'=>"")
            ),
            'calendar' => array(
                            'start' => array('method'=>'startCalendar','args'=>""),
                            'setToNull' => array('method'=>'setEmployeToNull','args'=>array($empToAct['week'],$empToAct['year'])),
                            'setEmpOfWeek' => array('method'=>'setEmployeOfWeek','args'=>array($empToAct['emp'],$empToAct['week'],$empToAct['year'])),
                            'statistics' => array("method"=>'getStatistics', 'args'=>"")
                            )

           );
    

    
   
    require_once('./'.$src.'Controller.php');
    $curControler = $src.'Controller';
    $curControler = new $curControler($collection);
    
    $method = $map[$src][$fc]['method'];
    $curControler->$method($map[$src][$fc]['args']);


?>