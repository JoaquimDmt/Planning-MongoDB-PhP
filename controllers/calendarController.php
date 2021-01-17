<?php
class CalendarController{

    private $_calendarManager;

    public function __construct($collect)
    {
        
        require_once('../models/calendarManager.php');
        $this->_calendarManager = new CalendarManager($collect);
    }

    public function startCalendar(){//when the user comes to the page calendar for the first time. This return a tab to json extension because data are pull by an axios request in calendrier.php
        
        
        echo(json_encode(array('sessionEmployee'=>$this->getAllEmployees(),'sessionWeek'=>$this->getAllWeek())));
    }

    public function getAllEmployees()//so the calendar manager return the full list of employees
    {
        return $this->_calendarManager->getListEmployees();
    }

    public function getAllWeek()//so the calendar manager return the full list of week for each year
    {
        return $this->_calendarManager->getListWeek();
      
    }

    public function setEmployeeToNull($tabArgs)//so the calendar manager pull of the user from the matching week ($tabArgs[0]) and year ($tabArgs[1])
    {
        
        $this->_calendarManager->setEmployeeToNull( $tabArgs[0], $tabArgs[1]);
    }

    public function setEmployeeOfWeek($tabArgs)//so the calendar manager set the user ($tabArgs[0]) on the matching week ($tabArgs[1]) and year ($tabArgs[2])
    {
       
        $this->_calendarManager->setEmployeeOfWeek($tabArgs[0], $tabArgs[1], $tabArgs[2]);

    }

    public function getStatistics(){//so the calendar manager return statistics datas
         echo(json_encode($this->_calendarManager->getStatistics()));
    }
}