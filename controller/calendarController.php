<?php
class CalendarController{

    private $_calendarManager;

    public function __construct($collect)
    {
        
        require_once('../model/calendarManager.php');
        $this->_calendarManager = new CalendarManager($collect);
    }

    public function startCalendar(){
        
        
        echo(json_encode(array('sessionEmploye'=>$this->getAllEmploye(),'sessionWeek'=>$this->getAllWeek())));
    }

    public function getAllEmploye()
    {
        return $this->_calendarManager->getListEmploye();
    }

    public function getAllWeek()
    {
        return $this->_calendarManager->getListWeek();
      
    }

    public function setEmployeToNull($tabArgs)
    {
        
        $this->_calendarManager->setEmployeToNull( $tabArgs[0], $tabArgs[1]);
    }

    public function setEmployeOfWeek($tabArgs)
    {
       
        $this->_calendarManager->setEmployeOfWeek($tabArgs[0], $tabArgs[1], $tabArgs[2]);

    }

    public function getStatistics(){
         echo(json_encode($this->_calendarManager->getStatistics()));
    }
}