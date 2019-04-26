<?php

class Form_Iadmin_GeoColors extends Form_Base
{
    private $_fields = array(
        "color_code_name" => "color_code_name"
    );
    
    private $_hidden = array(
        "pk_id" => "pk_id"
    );
    
    public function init()
    {
        foreach($this->_fields as $col => $name)
        {
            switch($col)
            {
                case "color_code_name":
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


