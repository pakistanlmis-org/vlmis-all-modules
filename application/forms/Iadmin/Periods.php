<?php

class Form_Iadmin_Periods extends Form_Base
{
    private $_fields = array(
        "period_name_update" => "period_name_update",
        "period_code_update" => "period_code_update",
        "period_name" => "period_name",
        "period_code" => "period_code",
        "begin_month" => "begin_month",
        "end_month" => "end_month"
    );
    
    private $_hidden = array(
        "pk_id" => "pk_id"
    );
    
    private $_list = array(
        'begin_month' => array(
        ),
        'end_month' => array(
        )
    );
    
    private $_multioptions = array(
        "1" => "January",
        "2" => "February",
        "3" => "March",
        "4" => "April",
        "5" => "May",
        "6" => "June",
        "7" => "July",
        "8" => "August",
        "9" => "September",
        "10" => "October",
        "11" => "November",
        "12" => "December"
    );
            
    public function init()
    {
        
        foreach($this->_list as $col =>$name)
        {
            switch($col)
            {
                case "begin_month":
                case "end_month":
                    parent::createSelect($col, $this->_multioptions);
                    break;
                default:
                    break;
            }
        }
        
        foreach($this->_fields as $col => $name)
        {
            switch($col)
            {
                case "period_name_update":
                case "period_code_update":
                    parent::createReadOnlyText($col);
                    break;
                case "period_name":
                case "period_code":
                    parent::createText($col);
                    break;
                default:
                    break;
            }
        }
        
        foreach ($this->_hidden as $col => $name) 
            {
            switch ($col) 
            {
                case "pk_id":
                    parent::createHidden($col);
                    break;
                default:
                    break;
            }
        }
    }
    
     public function addHidden() {
        parent::createHiddenWithValidator("id");
    }
    
  
}


