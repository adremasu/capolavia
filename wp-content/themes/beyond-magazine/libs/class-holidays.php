<?php

class holidaysCheck {

    public function __construct(){
        $this->today = strtotime('today');
        $this->nextYear = intval(date('Y')) + 1;
        $this->currentYear = date('Y');
        $this->holidays = array();
        // Capodanno

        $currentYearNYE = strtotime("$this->currentYear-01-01");
        $nextYearNYE = strtotime("$this->nextYear-01-01");
        if ($this->today > $currentYearNYE){
            $holidays[] = $nextYearNYE;
        } else {
            $holidays[] = $currentYearNYE;
        }


// Epifania
        $currentYearEpiphany = strtotime("$this->currentYear-01-06");
        $nextYearEpiphany = strtotime("$this->nextYear-01-06");
        if ($this->today > $currentYearEpiphany){
            $holidays[] = $nextYearEpiphany;
        } else {
            $holidays[] = $currentYearEpiphany;
        }

// Pasquetta

        if ($this->today > (easter_date($this->currentYear) + 86400)){

            $holidays[] = easter_date($this->nextYear) + 86400;
        } else {
            $holidays[] = easter_date($this->currentYear) + 86400;

        }

// 25 Aprile

        $currentYearLD = strtotime("$this->currentYear-04-25");
        $nextYearLD = strtotime("$this->nextYear-04-25");
        if ($this->today > $currentYearLD){
            $holidays[] = $nextYearLD;
        } else {
            $holidays[] = $currentYearLD;
        }

// 1° maggio

        $currentYearMayDay = strtotime("$this->currentYear-05-01");
        $nextYearMayDay = strtotime("$this->nextYear-05-01");
        if ($this->today > $currentYearMayDay){
            $holidays[] = $nextYearMayDay;
        } else {
            $holidays[] = $currentYearMayDay;
        }

// 2 Giugno

        $currentYearRD = strtotime("$this->currentYear-06-02");
        $nextYearRD = strtotime("$this->nextYear-06-02");
        if ($this->today > $currentYearRD){
            $holidays[] = $nextYearRD;
        } else {
            $holidays[] = $currentYearRD;
        }
// 15 Agosto

        $currentYearSD = strtotime("$this->currentYear-08-15");
        $nextYearSD = strtotime("$this->nextYear-08-15");
        if ($this->today > $currentYearSD){
            $holidays[] = $nextYearSD;
        } else {
            $holidays[] = $currentYearSD;
        }
// Santi

        $currentYearHalloween = strtotime("$this->currentYear-11-01");
        $nextYearHalloween = strtotime("$this->nextYear-11-01");
        if ($this->today > $currentYearHalloween){
            $holidays[] = $nextYearHalloween;
        } else {
            $holidays[] = $currentYearHalloween;
        }
// Natale e s.stefano
        $currentYearXmas = strtotime("$this->currentYear-12-25");
        $nextYearXmas = strtotime("$this->nextYear-12-25");
        if ($this->today > $currentYearXmas){
            $holidays[] = $nextYearXmas;
        } else {
            $holidays[] = $currentYearXmas;
        }

        $currentYearSS = strtotime("$this->currentYear-12-26");
        $nextYearSS = strtotime("$this->nextYear-12-26");
        if ($this->today > $currentYearSS){
            $holidays[] = $nextYearSS;
        } else {
            $holidays[] = $currentYearSS;
        }
    }

    public function is_holiday($day){
        if (!is_int($day)){
            $day = $this->today;
        }

        if (in_array($day, $this->holidays)){
            return true;
        } else {
            return false;
        }

    }

}



