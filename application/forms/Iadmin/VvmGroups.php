<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VvmGroups
 *
 * @author Nabila
 */
class Form_Iadmin_VvmGroups extends Form_Base {

    //put your code here
    /**
     * $_fields
     * 
     * Form Fields
     * @vvm_group_id: VVM Group Id
     * @vvm_group_id_update: VVM Group Id Update
     * @status: Status
     * 
     * @var type 
     */
    private $_fields = array(
        "vvm_group_id" => "Vvm Group Id",
        "vvm_group_id_update" => "Vvm Group Id Update",
        "stages" => "Stages"
    );
    
    /**
     * $_hidden
     * 
     * Hidden
     * @vvm_group_id_hidden: VVm Group Id Hidden
     * 
     * @var type 
     */
    private $_hidden = array(
        "vvm_group_id_hidden" => "VVM Group Id Hidden"
    );

    /**
     * Initializes Form Fields
     */
    public function init() {
        
        foreach ($this->_hidden as $col => $name) {
            if ($col == "vvm_group_id_hidden") {
                parent::createHidden($col);
            }
        }

        foreach ($this->_fields as $col => $name) {
            if ($col == "vvm_group_id_update"){
                parent::createReadOnlyText($col);
            }
            
            if ($col == "vvm_group_id") {
                parent::createText($col);
            }

            if ($col == "stages") {
                $stages = array(
                    "0" => "0",
                    "1" => "1",
                    "2" => "2",
                    "3" => "3",
                    "4" => "4"
                );
                parent::createMultiCheckbox($col, $stages);
            }

        }
    }
    
     /**
     * Add Hidden Fields
     */
    public function addHidden() {
        parent::createHiddenWithValidator("id");
    }
}
