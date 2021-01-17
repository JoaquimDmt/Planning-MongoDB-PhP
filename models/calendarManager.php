<?php
class CalendarManager{

    private $_managerDb;
    public function __construct($db)
    {
        $this->_managerDb = $db;
    }

    //return the full list of employees
    public function getListEmployees()
    {
        $filter = [];
        $option = [];
        $read = new MongoDB\Driver\Query($filter, $option);
        //Exécution de la requête
        $cursor =  $this->_managerDb->executeQuery('Planning.employees', $read);
        $employees = [];
        foreach($cursor as $emp)
        {
            array_push($employees, $emp);//each result of the cursor result is push to the response array
        }
        return $employees;
    }

    //add each week in the right year array
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
                    
                array_push($listeSemaine[$key],$sem);//each result of the cursor result is push to the response array
            }
        }
        return $listeSemaine; 
    }

    //pull of the user id from the the week($week) of the year($year)
    public function setEmployeeToNull($week, $year)
    {
        $week=new MongoDB\BSON\ObjectId($week);
        $filter=array('_id'=>$week);
        $maj = array('$set'=>['user'=>'']);
        $updates = new MongoDB\Driver\BulkWrite();
        $updates->update($filter,$maj);
        $result = $this->_managerDb->executeBulkWrite('Planning.year'.$year, $updates) ;
    }

    //set the user id($emp) working the selected week($week) of the year($year)
    public function setEmployeeOfWeek($emp, $week, $year)
    {
        
        $week=new MongoDB\BSON\ObjectId($week);
        $emp=new MongoDB\BSON\ObjectId($emp);
        $filter=['_id'=>$week];
        $maj = ['$set'=>['user'=>$emp]];
        $updates = new MongoDB\Driver\BulkWrite();
        $updates->update($filter,$maj);
        $result = $this->_managerDb->executeBulkWrite('Planning.year'.$year, $updates) ;

    }

    //get number of working week per year and per employee
    public function getStatistics()
    {
        $listeSemaine = ["2017"=>[],'2018'=>[],'2019'=>[],'2020'=>[]];

        //Exécution de la requête
        foreach($listeSemaine as $key=>$value)
        {
            $command = new MongoDB\Driver\Command([
                //to do so, we make an aggregation on the employees collection. Lookup allows  to make a join with the collection of the year, 
                //matching the user id with the user id of the week. The result will be an array (dayOn) with all working week of the user
                //Then we add (nbDayOfWork) a field to return the size of dayOn
                //Finally we return the result by a projection of the prenom field, couleur field and nbDayOfWork
                'aggregate' => 'employees',
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
                array_push($listeSemaine[$key],$res);//each result of the cursor result is push to the response array
            }
            
        }
       
        return $listeSemaine;
            
    }
}