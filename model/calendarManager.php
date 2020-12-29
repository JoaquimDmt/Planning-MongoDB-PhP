<?php
class CalendarManager{

    private $_managerDb;
    public function __construct($db)
    {
        require_once('connexion.php');
        $this->_managerDb = $db;
    }

    public function getListEmploye()
    {
        $filter = [];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        //Exécution de la requête
        $cursor =  $this->_managerDb->executeQuery('Planning.employes', $read);
        $employe = [];
        foreach($cursor as $emp)
        {
                
        
                array_push($employe,$emp);
        }
        return $employe;
    }

    public function getListWeek()
    {
        $listeSemaine = ["2017"=>[],'2018'=>[],'2019'=>[],'2020'=>[]];
        $filter = [];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        
        //Exécution de la requête
        foreach($listeSemaine as $key=>$value)
        {
            $cursor =  $this->_managerDb->executeQuery('Planning.year'.$key, $read);
            foreach($cursor as $sem)
            {
                    
                     array_push($listeSemaine[$key],$sem);
            }
            
            
        }
      
        return $listeSemaine; 
    }

    public function setEmployeToNull($week, $year)
    {
        $week=new MongoDB\BSON\ObjectId($week);
        $filter=array('_id'=>$week);
        $maj = array('$set'=>['user'=>'']);
        $updates = new MongoDB\Driver\BulkWrite();
        $updates->update($filter,$maj);
        $result = $this->_managerDb->executeBulkWrite('Planning.year'.$year, $updates) ;
        
    }

    public function setEmployeOfWeek($emp, $week, $year)
    {
        $week=new MongoDB\BSON\ObjectId($week);
        $emp=new MongoDB\BSON\ObjectId($emp);
        $filter=['_id'=>$week];
        $maj = ['$set'=>['user'=>$emp]];
        $updates = new MongoDB\Driver\BulkWrite();
        $updates->update($filter,$maj);
        $result = $this->_managerDb->executeBulkWrite('Planning.year'.$year, $updates) ;

    }

    public function getStatistics()
    {
        $listeSemaine = ["2017"=>[],'2018'=>[],'2019'=>[],'2020'=>[]];

        //Exécution de la requête
        foreach($listeSemaine as $key=>$value)
        {
           
            
           
            $command = new MongoDB\Driver\Command([
                'aggregate' => 'employes',
                'pipeline' => [
                            [
                                '$lookup' => [
                                    'from' => 'year'.$key,
                                    'localField'=> '_id', 
                                    'foreignField'=> 'user', 
                                    'as'=> 'dayOn' 
                                ]
                            ],
                            [
                                '$addFields' => [
                                    'nbDayOfWork'=> [
                                        '$size' => '$dayOn'
                                    ]
                                ]
                            ],
                            [
                                '$project' =>[
                                    'prenom'=> 1,
                                    'couleur'=>1, 
                                    'nbDayOfWork'=> 1 
                                ]
                            ]
                ],
                'cursor' => new stdClass,
            ]);
            $cursor =  $this->_managerDb->executeCommand('Planning', $command);
           
        
            foreach($cursor as $res)
            {
                
                     array_push($listeSemaine[$key],$res);
            }
            
            
          
        }
       
        return $listeSemaine;
            
    }
}