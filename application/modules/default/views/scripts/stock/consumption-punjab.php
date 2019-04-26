<table class="table table-striped table-bordered table-condensed"  >
    <thead  style="background-color: lightgray;">
        <tr>
            <th rowspan="4" class="center" >Product</th>
            <th rowspan="4" class="center" >Opening Balance (Doses)</th>
            <th rowspan="4" class="center" >Received (Doses)</th>
            <th rowspan="4" class="center" >#</th>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->rpt_date <= '2018-12-01' && $this->province_id == 2) { ?>
                <th colspan="8" class="center" style="background-color: #66FF66; ">Number of Children Vaccinated (0-11 Months)</th>
                <th colspan="8" class="center" style="background-color: #FFFF66; ">Number of Children Vaccinated (12-23 Months)</th>
            <?php } else if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <th colspan="12" class="center" style="background-color: #66FF66; ">Number of Children Vaccinated (0-11 Months)</th>
                <th colspan="12" class="center" style="background-color: #FFFF66; ">Number of Children Vaccinated (12-23 Months)</th>
                <th colspan="12" class="center" style="background-color: #66FFFF;">2 Years and Above</th>
            <?php } else if ($this->province_id == 1) { ?>
                <th colspan="8" class="center" style="background-color: #66FF66; ">Number of Children Vaccinated (0-11 Months)</th>
                <th colspan="8" class="center" style="background-color: #FFFF66; ">Number of Children Vaccinated (12-23 Months)</th>
                <th colspan="8" class="center" style="background-color: #66FFFF;">2 Years and Above</th>
            <?php } else { ?>
                <th colspan="6" class="center" style="background-color: #66FF66; ">Number of Children Vaccinated (0-11 Months)</th>
                <th colspan="6" class="center" style="background-color: #FFFF66; ">Number of Children Vaccinated (12-23 Months)</th>
                <th colspan="6" class="center" style="background-color: #66FFFF;">2 Years and Above</th>                  
            <?php } ?>
            <th rowspan="4" class="center" >Unusable (Doses)</th>
            <th rowspan="4" class="center" >Closing Balance (Doses)</th>
        </tr>
        <tr>

            <th colspan="4" class="center"  style="background-color: #66FF66; ">Fixed</th>
            <?php if ($this->province_id == 1) { ?>
                <th colspan="4"  class="center"  style="background-color: #66FF66; ">Outreach</th>
            <?php } else { ?>
                <th colspan="2"  class="center"  style="background-color: #66FF66; ">Outreach</th>

            <?php } ?>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <th colspan="2"  class="center"  style="background-color: #66FF66; ">Defaulter<br>Covered</th>
            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <th colspan="2"  class="center"  style="background-color: #66FF66; ">Mobile</th>
                <th colspan="2"  class="center"  style="background-color: #66FF66; ">Health House LHWs</th>
            <?php } ?>
            <th colspan="4" class="center" style="background-color: #FFFF66; ;">Fixed</th>
            <?php if ($this->province_id == 1) { ?>
                <th colspan="4"  class="center" style="background-color: #FFFF66; ">Outreach</th>
            <?php } else { ?>
                <th colspan="2"  class="center" style="background-color: #FFFF66; ">Outreach</th>

            <?php } ?>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <th colspan="2"  class="center"  style="background-color: #FFFF66; ">Defaulter<br>Covered</th>
            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <th colspan="2"  class="center"  style="background-color: #FFFF66; ">Mobile</th>
                <th colspan="2"  class="center"  style="background-color: #FFFF66; ">Health House LHWs</th>
            <?php } ?>
            <?php if (strtotime($this->rpt_date) < strtotime('2018-01-01') && strtotime($this->rpt_date) > strtotime('2018-12-01') && $this->province_id == 2) { ?>
                <th colspan="4" class="center" style="background-color: #66FFFF; ">Fixed</th>
                <th colspan="2"  class="center" style="background-color: #66FFFF; ">Outreach</th>


            <?php } else if (strtotime($this->rpt_date) >= strtotime('2018-01-01') && strtotime($this->rpt_date) <= strtotime('2018-12-01') && $this->province_id == 2) { ?>


            <?php } else { ?>
                <th colspan="4" class="center" style="background-color: #66FFFF; ">Fixed</th>
                <?php if ($this->province_id == 1) { ?>
                    <th colspan="4"  class="center" style="background-color: #66FFFF; ">Outreach</th>
                <?php } else { ?>
                    <th colspan="2"  class="center" style="background-color: #66FFFF; ">Outreach</th>
                <?php } ?>   
            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <th colspan="2"  class="center"  style="background-color: #66FFFF; ">Defaulter<br>Covered</th>
                <th colspan="2"  class="center"  style="background-color: #66FFFF; ">Mobile</th>
                <th colspan="2"  class="center"  style="background-color: #66FFFF; ">Health House LHWs</th>
            <?php } ?>
        </tr>
        <tr>
            <th colspan="2" class="center" style="background-color: #66FF66; ">Inside <br> UC</th>
            <th colspan="2" class="center" style="background-color: #66FF66; ">Outside <br> UC</th>
            <th colspan="2" class="center" style="background-color: #66FF66; ">Inside <br> UC</th>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <th colspan="2" class="center" style="background-color: #66FF66; ">Inside <br> UC</th>
            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <th colspan="2" class="center" style="background-color: #66FF66; ">Inside <br> UC</th>
                <th colspan="2" class="center" style="background-color: #66FF66; ">Inside <br> UC</th>
            <?php } ?>
            <?php if ($this->province_id == 1) { ?>
                <th colspan="2" class="center" style="background-color: #66FF66; ">Outside <br> UC</th>
            <?php } ?>
            <th colspan="2" class="center" style="background-color: #FFFF66; ">Inside <br> UC</th>
            <th colspan="2" class="center" style="background-color: #FFFF66; ">Outside <br> UC</th>
            <th colspan="2" class="center" style="background-color: #FFFF66; ">Inside <br> UC</th>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <th colspan="2" class="center" style="background-color: #FFFF66; ">Inside <br> UC</th>
            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <th colspan="2" class="center" style="background-color: #FFFF66; ">Inside <br> UC</th>
                <th colspan="2" class="center" style="background-color: #FFFF66; ">Inside <br> UC</th>
            <?php } ?>
            <?php if ($this->province_id == 1) { ?>
                <th colspan="2" class="center" style="background-color: #FFFF66; ">Outside <br> UC</th>
            <?php } ?>
            <?php if (strtotime($this->rpt_date) < strtotime('2018-01-01') && $this->province_id == 2) { ?>
                <th colspan="2" class="center" style="background-color: #66FFFF;  ">Inside <br> UC</th>
                <th colspan="2" class="center" style="background-color: #66FFFF; ">Outside <br> UC</th>
                <th colspan="2" class="center" style="background-color: #66FFFF; ">Inside <br> UC</th>
            <?php } else if (strtotime($this->rpt_date) >= strtotime('2018-01-01') && strtotime($this->rpt_date) <= strtotime('2018-12-01') && $this->province_id == 2) { ?>
            <?php } else { ?>
                <th colspan="2" class="center" style="background-color: #66FFFF;  ">Inside <br> UC</th>
                <th colspan="2" class="center" style="background-color: #66FFFF; ">Outside <br> UC</th>
                <th colspan="2" class="center" style="background-color: #66FFFF; ">Inside <br> UC</th>
                
                <?php if ($this->province_id == 1) { ?>
                    <th colspan="2" class="center" style="background-color: #66FFFF; ">Outside <br> UC</th>
                <?php } ?>
            <?php } ?>
        </tr>
        <tr>
            <th class="center" style="background-color: #66FF66; ">M</th>
            <th class="center" style="background-color: #66FF66; ">F</th>
            <th class="center" style="background-color: #66FF66; ">M</th>
            <th class="center" style="background-color: #66FF66; ">F</th>
            <th class="center" style="background-color: #66FF66; ">M</th>
            <th class="center" style="background-color: #66FF66; ">F</th>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <th class="center" style="background-color: #66FF66; ">M</th>
                <th class="center" style="background-color: #66FF66; ">F</th>
            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <th class="center" style="background-color: #66FF66; ">M</th>
                <th class="center" style="background-color: #66FF66; ">F</th>
                <th class="center" style="background-color: #66FF66; ">M</th>
                <th class="center" style="background-color: #66FF66; ">F</th>
            <?php } ?>
            <?php if ($this->province_id == 1) { ?>
                <th class="center" style="background-color: #66FF66; ">M</th>
                <th class="center" style="background-color: #66FF66; ">F</th>
            <?php } ?>
            <th class="center" style="background-color: #FFFF66; ">M</th>
            <th class="center" style="background-color: #FFFF66; ">F</th>
            <th class="center" style="background-color: #FFFF66; ">M</th>
            <th class="center" style="background-color: #FFFF66; ">F</th>
            <th class="center" style="background-color: #FFFF66; ">M</th>
            <th class="center" style="background-color: #FFFF66; ">F</th>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <th class="center" style="background-color: #FFFF66; ">M</th>
                <th class="center" style="background-color: #FFFF66; ">F</th>
            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <th class="center" style="background-color: #FFFF66; ">M</th>
                <th class="center" style="background-color: #FFFF66; ">F</th>
                <th class="center" style="background-color: #FFFF66; ">M</th>
                <th class="center" style="background-color: #FFFF66; ">F</th>
            <?php } ?>
            <?php if ($this->province_id == 1) { ?>
                <th class="center" style="background-color: #FFFF66; ">M</th>
                <th class="center" style="background-color: #FFFF66; ">F</th>
            <?php } ?>
            <?php if (strtotime($this->rpt_date) < strtotime('2018-01-01') && strtotime($this->rpt_date) > strtotime('2018-12-01') && $this->province_id == 2) { ?>
                <th class="center" style="background-color: #66FFFF; ">M</th>
                <th class="center" style="background-color: #66FFFF; ">F</th>

                <th class="center" style="background-color: #66FFFF; ">M</th>
                <th class="center" style="background-color: #66FFFF; ">F</th>
                <th class="center" style="background-color: #66FFFF; ">M</th>
                <th class="center" style="background-color: #66FFFF; ">F</th>
            <?php } else if (strtotime($this->rpt_date) >= strtotime('2018-01-01') && strtotime($this->rpt_date) <= strtotime('2018-12-01') && $this->province_id == 2) { ?>


            <?php } else { ?>
                <th class="center" style="background-color: #66FFFF; ">M</th>
                <th class="center" style="background-color: #66FFFF; ">F</th>

                <th class="center" style="background-color: #66FFFF; ">M</th>
                <th class="center" style="background-color: #66FFFF; ">F</th>
                <th class="center" style="background-color: #66FFFF; ">M</th>
                <th class="center" style="background-color: #66FFFF; ">F</th>
                
                <?php if ($this->province_id == 1) { ?>
                    <th class="center" style="background-color: #66FFFF; ">M</th>
                    <th class="center" style="background-color: #66FFFF; ">F</th>
                <?php } ?>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($this->items as $result):
            if ($result['pk_id'] == 26 && $this->mm > 4 && $this->year > 2015) {
                continue;
            }
            ?>
        <input type="hidden" name="item_category[]" id="<?php echo $result['pk_id']; ?>-item_category" value="1" />
        <?php
        //  App_Controller_Functions::pr($row);
        if ($this->is_new_report == 1) {
            $row2 = $this->monthlyConsumtion2()->monthlyConsumtion2Vaccines($this->wh_id, $this->prev_month_date, $result['pk_id'], Model_ListDetail::AGE_0_11);
        } else {
            $row2 = $this->monthlyConsumtion2()->monthlyConsumtion2Vaccines($this->wh_id, $this->rpt_date, $result['pk_id'], Model_ListDetail::AGE_0_11);
            $row3 = $this->monthlyConsumtion2()->monthlyConsumtion2Vaccines($this->wh_id, $this->rpt_date, $result['pk_id'], Model_ListDetail::AGE_12_23);
            $row4 = $this->monthlyConsumtion2()->monthlyConsumtion2Vaccines($this->wh_id, $this->rpt_date, $result['pk_id'], Model_ListDetail::AGE_24);
        }
        $style = "";
        $counter = 1;
        $nod = $result['no_of_doses'];
        $max_i;
        ?>
        <?php if ($this->is_new_report == 1) { ?>
            <?php
            for ($i = $result['start_no']; $i <= $result['no_of_doses']; $i++) {
                if ($i == 0) {
                    $nod += 1;
                }
                if ($result['pk_id'] == 6) {
                    $style = '';
                    $s = "";
                } else {
                    $style = '';
                    $s = "";
                }
                ?>
                <tr>
                    <?php if ($counter == 1) { ?>
                        <td rowspan="<?php echo $nod ?>" class="center"><?php echo $result['item_name'] ?></td>
                    <input type="hidden" name="item_name[]" id="<?php echo $result['pk_id']; ?>-item_name" value="<?php echo $result['item_name']; ?>" />
                    <input type="hidden" name="flitm_id[]" value="<?php echo $result['pk_id']; ?>" />
                    <input type="hidden" name="doses_per_unit[]" id="<?php echo $result['pk_id']; ?>-doses" value="<?php echo $result['description']; ?>" />
                    <input type="hidden" name="start_no[]" id="<?php echo $result['pk_id']; ?>-start_no" value="<?php echo $result['start_no']; ?>" />
                    <input type="hidden" name="no_of_doses[]" id="<?php echo $result['pk_id']; ?>-no_of_doses" value="<?php echo $result['no_of_doses']; ?>" />
                    <input type = "hidden" name = "dispensed[]" id="<?php echo $result['pk_id'] ?>-dispensed" class = "form-control col-md-1"  value="0">

                    <?php if ($this->prev_month_date == $this->first_month) { ?>
                        <td rowspan="<?php echo $nod ?>"><input type="text" name="opening_balance[]" id="<?php echo $result['pk_id']; ?>-opening_balance"  class="form-control col-md-1" <?php if (strtotime($this->year . "-" . $this->mm) > strtotime('2015-12') && $this->province_id != 1 && $this->province_id != 4 && $this->province_id != 8) { ?>readonly="readonly" <?php } ?>   value="<?php echo (!empty($row2[0]['closingBalance'])) ? $row2[0]['closingBalance'] : '0'; ?>"></td>

                    <?php } else { ?>
                        <td rowspan="<?php echo $nod ?>"><input type="text" name="opening_balance[]" id="<?php echo $result['pk_id']; ?>-opening_balance"  class="form-control col-md-1" <?php if (strtotime($this->year . "-" . $this->mm) > strtotime('2015-12') && $this->province_id != 1 && $this->province_id != 4 && $this->province_id != 8) { ?>readonly="readonly" <?php } ?>  value="<?php echo (!empty($row2[0]['closingBalance'])) ? $row2[0]['closingBalance'] : '0'; ?>"></td>

                    <?php } ?>
                    <td rowspan="<?php echo $nod ?>"><input type="text" name="received[]" id="<?php echo $result['pk_id']; ?>-received"  class="form-control col-md-1"  value="0"></td>

                <?php } ?>
                <input type="hidden" name="vaccine_schedule_id[]" value="<?php echo ($i == 1 && $i == $nod) ? '' : $i; ?>" />
                <td style="vertical-align: middle;"><?php
                    echo ($i == 1 && $i == $nod) ? '' : $i;
                    if ($result['pk_id'] == '40') {
                        echo '1';
                    }
                    ?> </td>
                <?php
                if ($result['pk_id'] == 26 && $i == 0) {
                    $style1 = 'readonly="readonly"';
                    $s1 = "STYLE='color: #F9F9F9;background-color: #F9F9F9;'";
                } else {
                    $style1 = '';
                    $s1 = '';
                }
                ?>
                <?php
                if ($result['pk_id'] == 45) {
                    $style_dt = 'readonly="readonly"';
                    $s_dt = "STYLE='color: #F9F9F9;background-color: #F9F9F9;'";
                } else {
                    $style_dt = '';
                    $s_dt = '';
                }
                ?>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="fix_inuc_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_m_11" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="fix_inuc_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_f_11" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="fix_outuc_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_m_11" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="fix_outuc_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_f_11" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_m_11" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_f_11" class="form-control col-md-2"  value="0"></td>
                <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_outuc_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_11" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_outuc_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_11" class="form-control col-md-2"  value="0"></td>

                <?php } ?>
                <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="mobile_inside_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_m_11" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="mobile_inside_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_f_11" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="lhw_inside_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_m_11" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="lhw_inside_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_f_11" class="form-control col-md-2"  value="0"></td>

                <?php } ?>
                <?php if ($this->province_id == 1) { ?>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_outuc_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_11" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_outuc_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_11" class="form-control col-md-2"  value="0"></td>
                <?php } ?>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="fix_inuc_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_m_23" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="fix_inuc_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_f_23" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>   name="fix_outuc_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_m_23" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_outuc_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_f_23" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_m_23" class="form-control col-md-2"  value="0"></td>
                <td ><input type="text"  <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_f_23" class="form-control col-md-2"  value="0"></td>
                <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                    <td ><input type="text"  <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_23" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_23" class="form-control col-md-2"  value="0"></td>

                <?php } ?>
                <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="mobile_inside_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_m_23" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="mobile_inside_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_f_23" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="lhw_inside_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_m_23" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="lhw_inside_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_f_23" class="form-control col-md-2"  value="0"></td>

                <?php } ?>
                <?php if ($this->province_id == 1) { ?>
                    <td ><input type="text"  <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_23" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_23" class="form-control col-md-2"  value="0"></td>

                <?php } ?>
                <?php if (strtotime($this->rpt_date) < strtotime('2018-01-01') && strtotime($this->rpt_date) > strtotime('2018-12-01') && $this->province_id == 2) { ?>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="fix_inuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_m_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="fix_inuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_f_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>   name="fix_outuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_m_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_outuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_f_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_m_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_f_24" class="form-control col-md-2"  value="0"></td>
                <?php } else if (strtotime($this->rpt_date) >= strtotime('2018-01-01') && strtotime($this->rpt_date) <= strtotime('2018-12-01') && $this->province_id == 2) { ?>


                <?php } else { ?> 

                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="fix_inuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_m_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="fix_inuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_f_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>   name="fix_outuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_m_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_outuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_f_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_m_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_f_24" class="form-control col-md-2"  value="0"></td>
                    <?php if ($this->province_id == 1) { ?>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_24" class="form-control col-md-2"  value="0"></td>
<?php } ?> 
<!--                    <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?> name="mobile_inside_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_m_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="mobile_inside_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_f_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="lhw_inside_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_m_24" class="form-control col-md-2"  value="0"></td>
                    <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="lhw_inside_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_f_24" class="form-control col-md-2"  value="0"></td>-->

                    


                <?php } ?>
                <?php if ($counter == 1) { ?>
                    <td rowspan="<?php echo $nod ?>"><input type="text" name="unusable_vials[]" id="<?php echo $result['pk_id'] ?>-unusable" class="form-control col-md-1"  value="0"></td>

                    <td  rowspan="<?php echo $nod ?>"><input type="text" name="closing_balance[]" id="<?php echo $result['pk_id'] ?>-closing_balance" class="form-control col-md-1"  value="0"></td>
                <?php } ?> </tr>

            <?php
            $counter++;
        }
        ?>
    <?php } else if ($this->is_new_report == 2) {
        ?>
        <?php
        $j = 0;
        for ($i = $result['start_no']; $i <= $result['no_of_doses']; $i++) {
            if ($i == 0) {
                $nod += 1;
            }
            if ($result['pk_id'] == 6) {
                $style = '';
                $s = "";
            } else {
                $style = '';
                $s = "";
            }
            ?>
            <tr>
                <?php if ($counter == 1) { ?>
                    <td rowspan="<?php echo $nod ?>" class="center"><?php echo $result['item_name'] ?></td>
                <input type="hidden" name="item_name[]" id="<?php echo $result['pk_id']; ?>-item_name" value="<?php echo $result['item_name']; ?>" />
                <input type="hidden" name="flitm_id[]" value="<?php echo $result['pk_id']; ?>" />
                <input type="hidden" name="doses_per_unit[]" id="<?php echo $result['pk_id']; ?>-doses" value="<?php echo $result['description']; ?>" />
                <input type="hidden" name="start_no[]" id="<?php echo $result['pk_id']; ?>-start_no" value="<?php echo $result['start_no']; ?>" />
                <input type="hidden" name="no_of_doses[]" id="<?php echo $result['pk_id']; ?>-no_of_doses" value="<?php echo $result['no_of_doses']; ?>" />

                <td rowspan="<?php echo $nod ?>">
                    <?php echo (!empty($row2[0]['openingBalance'])) ? number_format($row2[0]['openingBalance']) : '0'; ?>
                </td>


                <td rowspan="<?php echo $nod ?>">
                    <?php echo (!empty($row2[0]['receivedBalance'])) ? number_format($row2[0]['receivedBalance']) : '0'; ?>
                </td>

            <?php } ?>
            <input type="hidden" name="vaccine_schedule_id[]" value="<?php echo ($i == 1 && $i == $nod) ? '' : $i; ?>" />
            <td><?php
                echo ($i == 1 && $i == $nod) ? '' : $i;
                if ($result['pk_id'] == '40') {
                    echo '1';
                }
                ?></td>
            <?php
            if ($result['pk_id'] == 26 && $i == 0) {
                $style1 = 'readonly="readonly"';
                $s1 = "STYLE='color: #F9F9F9;background-color: #F9F9F9;'";
            } else {
                $style1 = '';
                $s1 = '';
            }
            ?>
            <td > <?php echo (!empty($row2[$j]['fixed_inside_uc_male']) ) ? number_format($row2[$j]['fixed_inside_uc_male']) : '0'; ?></td>
            <td > <?php echo (!empty($row2[$j]['fixed_inside_uc_female']) ) ? number_format($row2[$j]['fixed_inside_uc_female']) : '0'; ?> </td>
            <td> <?php echo (!empty($row2[$j]['fixed_outside_uc_male']) ) ? number_format($row2[$j]['fixed_outside_uc_male']) : '0'; ?> </td>
            <td > <?php echo (!empty($row2[$j]['fixed_outside_uc_female'])) ? number_format($row2[$j]['fixed_outside_uc_female']) : '0'; ?>  </td>
            <td > <?php echo (!empty($row2[$j]['outreach_male']) ) ? number_format($row2[$j]['outreach_male']) : '0'; ?>  </td>
            <td > <?php echo (!empty($row2[$j]['outreach_female']) ) ? number_format($row2[$j]['outreach_female']) : '0'; ?>  </td>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <td > <?php echo (!empty($row2[$j]['outreach_outside_male']) ) ? number_format($row2[$j]['outreach_outside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row2[$j]['outreach_outside_female']) ) ? number_format($row2[$j]['outreach_outside_female']) : '0'; ?>  </td>

            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <td > <?php echo (!empty($row2[$j]['mobile_inside_male']) ) ? number_format($row2[$j]['mobile_inside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row2[$j]['mobile_inside_female']) ) ? number_format($row2[$j]['mobile_inside_female']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row2[$j]['lhw_inside_male']) ) ? number_format($row2[$j]['lhw_inside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row2[$j]['lhw_inside_female']) ) ? number_format($row2[$j]['lhw_inside_female']) : '0'; ?>  </td>
            <?php } ?>
            <?php if ($this->province_id == 1) { ?>
                <td > <?php echo (!empty($row2[$j]['outreach_outside_male']) ) ? number_format($row2[$j]['outreach_outside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row2[$j]['outreach_outside_female']) ) ? number_format($row2[$j]['outreach_outside_female']) : '0'; ?>  </td>
            <?php } ?>
            <td > <?php echo (!empty($row3[$j]['fixed_inside_uc_male'])) ? number_format($row3[$j]['fixed_inside_uc_male']) : '0'; ?></td>
            <td > <?php echo (!empty($row3[$j]['fixed_inside_uc_female']) ) ? number_format($row3[$j]['fixed_inside_uc_female']) : '0'; ?> </td>
            <td > <?php echo (!empty($row3[$j]['fixed_outside_uc_male']) ) ? number_format($row3[$j]['fixed_outside_uc_male']) : '0'; ?> </td>
            <td > <?php echo (!empty($row3[$j]['fixed_outside_uc_female'])) ? number_format($row3[$j]['fixed_outside_uc_female']) : '0'; ?>  </td>
            <td > <?php echo (!empty($row3[$j]['outreach_male'])) ? number_format($row3[$j]['outreach_male']) : '0'; ?>  </td>
            <td > <?php echo (!empty($row3[$j]['outreach_female'])) ? number_format($row3[$j]['outreach_female']) : '0'; ?>  </td>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <td > <?php echo (!empty($row3[$j]['outreach_outside_male']) ) ? number_format($row3[$j]['outreach_outside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row3[$j]['outreach_outside_female'])) ? number_format($row3[$j]['outreach_outside_female']) : '0'; ?>  </td>

            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <td > <?php echo (!empty($row3[$j]['mobile_inside_male']) ) ? number_format($row3[$j]['mobile_inside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row3[$j]['mobile_inside_female']) ) ? number_format($row3[$j]['mobile_inside_female']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row3[$j]['lhw_inside_male']) ) ? number_format($row3[$j]['lhw_inside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row3[$j]['lhw_inside_female']) ) ? number_format($row3[$j]['lhw_inside_female']) : '0'; ?>  </td>

            <?php } ?>
            <?php if ($this->province_id == 1) { ?>
                <td > <?php echo (!empty($row3[$j]['outreach_outside_male']) ) ? number_format($row3[$j]['outreach_outside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row3[$j]['outreach_outside_female'])) ? number_format($row3[$j]['outreach_outside_female']) : '0'; ?>  </td>
            <?php } ?>
            <?php if (strtotime($this->rpt_date) < strtotime('2018-01-01') && strtotime($this->rpt_date) > strtotime('2018-12-01') && $this->province_id == 2) { ?>
                <td > <?php echo (!empty($row4[$j]['fixed_inside_uc_male'])) ? number_format($row4[$j]['fixed_inside_uc_male']) : '0'; ?></td>
                <td > <?php echo (!empty($row4[$j]['fixed_inside_uc_female']) ) ? number_format($row4[$j]['fixed_inside_uc_female']) : '0'; ?> </td>
                <td > <?php echo (!empty($row4[$j]['fixed_outside_uc_male']) ) ? number_format($row4[$j]['fixed_outside_uc_male']) : '0'; ?> </td>
                <td > <?php echo (!empty($row4[$j]['fixed_outside_uc_female'])) ? number_format($row4[$j]['fixed_outside_uc_female']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row4[$j]['outreach_male']) ) ? number_format($row4[$j]['outreach_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row4[$j]['outreach_female'])) ? number_format($row4[$j]['outreach_female']) : '0'; ?>  </td>
            <?php } else if (strtotime($this->rpt_date) >= strtotime('2018-01-01') && strtotime($this->rpt_date) <= strtotime('2018-12-01') && $this->province_id == 2) { ?>


            <?php } else { ?> 

                <td > <?php echo (!empty($row4[$j]['fixed_inside_uc_male'])) ? number_format($row4[$j]['fixed_inside_uc_male']) : '0'; ?></td>
                <td > <?php echo (!empty($row4[$j]['fixed_inside_uc_female']) ) ? number_format($row4[$j]['fixed_inside_uc_female']) : '0'; ?> </td>
                <td > <?php echo (!empty($row4[$j]['fixed_outside_uc_male']) ) ? number_format($row4[$j]['fixed_outside_uc_male']) : '0'; ?> </td>
                <td > <?php echo (!empty($row4[$j]['fixed_outside_uc_female'])) ? number_format($row4[$j]['fixed_outside_uc_female']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row4[$j]['outreach_male']) ) ? number_format($row4[$j]['outreach_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row4[$j]['outreach_female'])) ? number_format($row4[$j]['outreach_female']) : '0'; ?>  </td>
                <?php if ($this->province_id == 1) { ?>
                <td > <?php echo (!empty($row4[$j]['outreach_outside_male']) ) ? number_format($row4[$j]['outreach_outside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row4[$j]['outreach_outside_female'])) ? number_format($row4[$j]['outreach_outside_female']) : '0'; ?>  </td>
                <?php } ?>
<!--                <td > <?php echo (!empty($row4[$j]['mobile_inside_male']) ) ? number_format($row4[$j]['mobile_inside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row4[$j]['mobile_inside_female']) ) ? number_format($row4[$j]['mobile_inside_female']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row4[$j]['lhw_inside_male']) ) ? number_format($row4[$j]['lhw_inside_male']) : '0'; ?>  </td>
                <td > <?php echo (!empty($row4[$j]['lhw_inside_female']) ) ? number_format($row4[$j]['lhw_inside_female']) : '0'; ?>  </td>-->
            <?php } ?>
            <?php if ($counter == 1) { ?>
                <td> <?php echo (!empty($row2[0]['adjustments']) ? number_format($row2[0]['adjustments']) : '0' ); ?> </td>

                <td>
                    <?php echo (!empty($row2[0]['closingBalance'])) ? number_format($row2[0]['closingBalance']) : '0'; ?>
                </td>
            <?php } ?> </tr>

            <?php
            $counter++;
            $j++;
        }
        ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <!-- <input type="hidden" data-date="<?php //echo $date;                                                                                                                                                                                                                                                                                      ?>" data-item="<?php //echo $item;                                                                                                                                                                                                                                                                                      ?>" data-wh="<?php //echo $wh;                                                                                                                                                                                                                                                                                      ?>" id="updateamc" /> -->
        <?php //$is_view = 1;     ?>
    <?php } else { ?>

        <?php
        $j = 0;
        for ($i = $result['start_no']; $i <= $result['no_of_doses']; $i++) {
            if ($i == 0) {
                $nod += 1;
            }
            if ($result['pk_id'] == 6) {
                $style = '';
                $s = "";
            } else {
                $style = '';
                $s = "";
            }
            ?>
            <tr>
                <?php if ($counter == 1) { ?>

                    <td rowspan="<?php echo $nod ?>" class="center"><?php echo $result['item_name'] ?></td>
                <input type="hidden" name="item_name[]" id="<?php echo $result['pk_id']; ?>-item_name" value="<?php echo $result['item_name']; ?>" />
                <input type="hidden" name="flitm_id[]" value="<?php echo $result['pk_id']; ?>" />

                <input type="hidden" name="doses_per_unit[]" id="<?php echo $result['pk_id']; ?>-doses" value="<?php echo $result['description']; ?>" />
                <input type="hidden" name="start_no[]" id="<?php echo $result['pk_id']; ?>-start_no" value="<?php echo $result['start_no']; ?>" />
                <input type="hidden" name="no_of_doses[]" id="<?php echo $result['pk_id']; ?>-no_of_doses" value="<?php echo $result['no_of_doses']; ?>" />
                <input type = "hidden" name = "dispensed[]" id="<?php echo $result['pk_id'] ?>-dispensed" class = "form-control col-md-1"  value="0">

                <?php if ($this->prev_month_date == $this->first_month) { ?>
                    <td rowspan="<?php echo $nod ?>"><input type="text" name="opening_balance[]" id="<?php echo $result['pk_id']; ?>-opening_balance"  class="form-control col-md-1" <?php if (strtotime($this->year . "-" . $this->mm) > strtotime('2015-12') && $this->province_id != 1 && $this->province_id != 4 && $this->province_id != 8) { ?>readonly="readonly" <?php } ?> value="<?php echo (!empty($row2[0]['openingBalance'])) ? $row2[0]['openingBalance'] : '0'; ?>"></td>

                <?php } else { ?>
                    <td rowspan="<?php echo $nod ?>"><input type="text" name="opening_balance[]" id="<?php echo $result['pk_id']; ?>-opening_balance"  class="form-control col-md-1" <?php if (strtotime($this->year . "-" . $this->mm) > strtotime('2015-12') && $this->province_id != 1 && $this->province_id != 4 && $this->province_id != 8) { ?>readonly="readonly" <?php } ?> value="<?php echo (!empty($row2[0]['openingBalance'])) ? $row2[0]['openingBalance'] : '0'; ?>"></td>

                <?php } ?>
                <td rowspan="<?php echo $nod ?>"><input type="text" name="received[]" id="<?php echo $result['pk_id']; ?>-received"  class="form-control col-md-1"  value=" <?php echo (!empty($row2[0]['receivedBalance'])) ? ($row2[0]['receivedBalance']) : '0'; ?>"></td>

            <?php } ?>
            <input type="hidden" name="vaccine_schedule_id[]" value="<?php echo ($i == 1 && $i == $nod) ? '' : $i; ?>" />
            <td style="vertical-align: middle;"><?php
                echo ($i == 1 && $i == $nod) ? '' : $i;
                if ($result['pk_id'] == '40') {
                    echo '1';
                }
                ?></td>
            <?php
            if ($result['pk_id'] == 26 && $i == 0) {
                $style1 = 'readonly="readonly"';
                $s1 = "STYLE='color: #F9F9F9;background-color: #F9F9F9;'";
            } else {
                $style1 = '';
                $s1 = '';
            }
            ?>
            <?php
            if ($result['pk_id'] == 45) {
                $style_dt = 'readonly="readonly"';
                $s_dt = "STYLE='color: #F9F9F9;background-color: #F9F9F9;'";
            } else {
                $style_dt = '';
                $s_dt = '';
            }
            ?>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="fix_inuc_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_m_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['fixed_inside_uc_male']) ) ? $row2[$j]['fixed_inside_uc_male'] : '0'; ?>"></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="fix_inuc_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_f_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['fixed_inside_uc_female']) ) ? $row2[$j]['fixed_inside_uc_female'] : '0'; ?>"></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="fix_outuc_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_m_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['fixed_outside_uc_male']) ) ? $row2[$j]['fixed_outside_uc_male'] : '0'; ?>"></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="fix_outuc_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_f_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['fixed_outside_uc_female'])) ? $row2[$j]['fixed_outside_uc_female'] : '0'; ?> "></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_m_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['outreach_male']) ) ? $row2[$j]['outreach_male'] : '0'; ?>"></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_f_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['outreach_female']) ) ? $row2[$j]['outreach_female'] : '0'; ?> "></td>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_outuc_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['outreach_outside_male']) ) ? $row2[$j]['outreach_outside_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_outuc_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['outreach_outside_female']) ) ? $row2[$j]['outreach_outside_female'] : '0'; ?> "></td>

            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="mobile_inside_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_m_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['mobile_inside_male']) ) ? $row2[$j]['mobile_inside_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="mobile_inside_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_f_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['mobile_inside_female']) ) ? $row2[$j]['mobile_inside_female'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="lhw_inside_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_m_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['lhw_inside_male']) ) ? $row2[$j]['lhw_inside_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="lhw_inside_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_f_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['lhw_inside_female']) ) ? $row2[$j]['lhw_inside_female'] : '0'; ?>"></td>

            <?php } ?>
            <?php if ($this->province_id == 1) { ?>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_outuc_m_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['outreach_outside_male']) ) ? $row2[$j]['outreach_outside_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="outreach_outuc_f_11_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_11" class="form-control col-md-2"  value="<?php echo (!empty($row2[$j]['outreach_outside_female']) ) ? $row2[$j]['outreach_outside_female'] : '0'; ?> "></td>

            <?php } ?>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_inuc_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_m_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['fixed_inside_uc_male'])) ? $row3[$j]['fixed_inside_uc_male'] : '0'; ?>"></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_inuc_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_f_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['fixed_inside_uc_female']) ) ? $row3[$j]['fixed_inside_uc_female'] : '0'; ?>"></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_outuc_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_m_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['fixed_outside_uc_male']) ) ? $row3[$j]['fixed_outside_uc_male'] : '0'; ?>"></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_outuc_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_f_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['fixed_outside_uc_female'])) ? $row3[$j]['fixed_outside_uc_female'] : '0'; ?>"></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_m_23" class="form-control col-md-2"  value=" <?php echo (!empty($row3[$j]['outreach_male']) ) ? $row3[$j]['outreach_male'] : '0'; ?> "></td>
            <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_f_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['outreach_female'])) ? $row3[$j]['outreach_female'] : '0'; ?>"></td>
            <?php if ($this->rpt_date >= '2018-01-01' && $this->province_id == 2) { ?>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_23" class="form-control col-md-2"  value=" <?php echo (!empty($row3[$j]['outreach_outside_male']) ) ? $row3[$j]['outreach_outside_male'] : '0'; ?> "></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['outreach_outside_female'])) ? $row3[$j]['outreach_outside_female'] : '0'; ?>"></td>

            <?php } ?>
            <?php if ($this->rpt_date >= '2019-01-01' && $this->province_id == 2) { ?>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="mobile_inside_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_m_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['mobile_inside_male']) ) ? $row3[$j]['mobile_inside_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="mobile_inside_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_f_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['mobile_inside_female']) ) ? $row3[$j]['mobile_inside_female'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="lhw_inside_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_m_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['lhw_inside_male']) ) ? $row3[$j]['lhw_inside_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="lhw_inside_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_f_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['lhw_inside_female']) ) ? $row3[$j]['lhw_inside_female'] : '0'; ?>"></td>
            <?php } ?>
            <?php if ($this->province_id == 1) { ?>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_m_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_23" class="form-control col-md-2"  value=" <?php echo (!empty($row3[$j]['outreach_outside_male']) ) ? $row3[$j]['outreach_outside_male'] : '0'; ?> "></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_f_23_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_23" class="form-control col-md-2"  value="<?php echo (!empty($row3[$j]['outreach_outside_female'])) ? $row3[$j]['outreach_outside_female'] : '0'; ?>"></td>


            <?php } ?>
            <?php if (strtotime($this->rpt_date) < strtotime('2018-01-01') && strtotime($this->rpt_date) > strtotime('2018-12-01') && $this->province_id == 2) { ?>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_inuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_m_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['fixed_inside_uc_male'])) ? $row4[$j]['fixed_inside_uc_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_inuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_f_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['fixed_inside_uc_female']) ) ? $row4[$j]['fixed_inside_uc_female'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_outuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_m_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['fixed_outside_uc_male']) ) ? $row4[$j]['fixed_outside_uc_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_outuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_f_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['fixed_outside_uc_female'])) ? $row4[$j]['fixed_outside_uc_female'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_m_24" class="form-control col-md-2"  value=" <?php echo (!empty($row4[$j]['outreach_male']) ) ? $row4[$j]['outreach_male'] : '0'; ?> "></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_f_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['outreach_female'])) ? $row4[$j]['outreach_female'] : '0'; ?>"></td>
            <?php } else if (strtotime($this->rpt_date) >= strtotime('2018-01-01') && strtotime($this->rpt_date) <= strtotime('2018-12-01') && $this->province_id == 2) { ?>


            <?php } else { ?> 

                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_inuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_m_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['fixed_inside_uc_male'])) ? $row4[$j]['fixed_inside_uc_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_inuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_inuc_f_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['fixed_inside_uc_female']) ) ? $row4[$j]['fixed_inside_uc_female'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_outuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_m_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['fixed_outside_uc_male']) ) ? $row4[$j]['fixed_outside_uc_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="fix_outuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-fixed_outuc_f_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['fixed_outside_uc_female'])) ? $row4[$j]['fixed_outside_uc_female'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_m_24" class="form-control col-md-2"  value=" <?php echo (!empty($row4[$j]['outreach_male']) ) ? $row4[$j]['outreach_male'] : '0'; ?> "></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_f_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['outreach_female'])) ? $row4[$j]['outreach_female'] : '0'; ?>"></td>
                <?php if ($this->province_id == 1) { ?>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_m_24" class="form-control col-md-2"  value=" <?php echo (!empty($row4[$j]['outreach_outside_male']) ) ? $row4[$j]['outreach_outside_male'] : '0'; ?> "></td>
                <td ><input type="text" <?php echo $style; ?><?php echo $style1; ?><?php echo $s; ?><?php echo $s1; ?>  name="outreach_outuc_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-outreach_outuc_f_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['outreach_outside_female'])) ? $row4[$j]['outreach_outside_female'] : '0'; ?>"></td>

                <?php } ?> 
<!--                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="mobile_inside_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_m_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['mobile_inside_male']) ) ? $row4[$j]['mobile_inside_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="mobile_inside_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-mobile_inside_f_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['mobile_inside_female']) ) ? $row4[$j]['mobile_inside_female'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="lhw_inside_m_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_m_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['lhw_inside_male']) ) ? $row4[$j]['lhw_inside_male'] : '0'; ?>"></td>
                <td ><input type="text" <?php echo $style_dt; ?><?php echo $s_dt; ?> name="lhw_inside_f_24_<?php echo $result['pk_id']; ?>_<?php echo $i; ?>" id="<?php echo $i; ?>-<?php echo $result['pk_id'] ?>-lhw_inside_f_24" class="form-control col-md-2"  value="<?php echo (!empty($row4[$j]['lhw_inside_female']) ) ? $row4[$j]['lhw_inside_female'] : '0'; ?>"></td>-->

            <?php } ?>
            <?php if ($counter == 1) { ?>
                <td rowspan="<?php echo $nod ?>"><input type="text" name="unusable_vials[]" id="<?php echo $result['pk_id'] ?>-unusable" class="form-control col-md-1"  value="<?php echo (!empty($row2[0]['adjustments']) ? $row2[0]['adjustments'] : '0' ); ?>"></td>

                <td  rowspan="<?php echo $nod ?>"><input type="text" name="closing_balance[]" id="<?php echo $result['pk_id'] ?>-closing_balance" class="form-control col-md-1"  value=" <?php echo (!empty($row2[0]['closingBalance'])) ? $row2[0]['closingBalance'] : '0'; ?>"></td>
            <?php } ?> </tr>

            <?php
            $counter++;
            $j++;
        }
        ?>
    <?php } ?>
<?php endforeach; ?>


</tbody>
</table>