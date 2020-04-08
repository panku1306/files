<?php
    include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';

    if(isset($_POST['save'])){

    }

    include_once dirname(dirname(dirname(__FILE__))).'/_head.php';
?>
<style>
.form-horizontal .checkbox-inline{ padding-top:0px;}
#dat-error{position: absolute;top: 34px;}
.hed{text-align: center;height: 55px;background-color: #0E76BC;color: #fff;padding-top: 11px;border: 2px solid #0E76BC;border-radius: 7px 7px 0 0;}
.heading{text-align: center;padding: 8px 0;color: #fff;background-color: #000;}
.heading2{text-align: left;padding: 8px 15px;color: #fff;background-color: #000;}
@media only screen and (max-width: 766px) {
    .coustum-box{ overflow-y: scroll;width:95%; }
}
.table > tbody > tr > td{padding:2px 5px;}
</style>
<hr>
<div id="frame" >
    <form class="form-horizontal" id="envise-pre-task" method="POST" action="" name="pre-task" >
        <?php if (isset($_SESSION['success_msg'])){ ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php
            echo $_SESSION['success_msg'];
            unset($_SESSION['success_msg']);
            ?>
        </div>
        <?php } elseif ($_SESSION['error_msg']) { ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php
            echo $_SESSION['error_msg'];
            unset($_SESSION['error_msg']);
            ?>
        </div>
        <?php } ?>

        <fieldset class="col-sm-12 nopad">
            <h3 class="ttext" style="margin-bottom: 10px;text-decoration: none;">ENVISE <br> EMPLOYEE PRE-TASK PLANNING <br> & SAFETY REPORT</h3>
            <span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
            <br>
            <div id="personal_edit">
                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">EMPLOYEE:</span>
                                <span class="sp" style="display: none;"> </span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="employee" id="employee" class="form-control" value="<?php //echo $info['employee'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">FOREMAN/SUPERVISOR:</span>
                                <span class="sp" style="display: none;"> </span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="foreman" id="foreman" class="form-control" value="<?php //echo $info['foreman'];?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">PROJECT(S):</span>
                                <span class="sp" style="display: none;"> </span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="project" id="project" class="form-control" value="<?php //echo $info['project'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">MONTH:</span>
                                <span class="sp" style="display: none;"> </span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-sm-4 ">
                                <select name="month" id="month" class="form-control nopad" >
                                    <option value="" disabled selected>Month</option>
                                    <?php
                                    $i = 1;
                                    $month = strtotime('2011-01-01');
                                    while($i <= 12){
                                        $value = str_pad($i,2,"0",STR_PAD_LEFT);
                                        $month_name = date('F', $month);
                                        echo '<option value="'. $month_name. '">'.$month_name.'</option>';
                                        $month = strtotime('+1 month', $month);
                                        $i++;
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2 nopad">
                                <span style="position: absolute;top: 8px;left: -2px;">,20</span>
                                <input type="text" name="dat" id="dat" maxlength="2" onkeypress="return isNumberKey(event)" class="form-control" style="width: 51%;position: absolute;top: 0px;left: 22px;padding: 2px 5px;" value="<?php //echo $info['dat'];?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 nopad" style="margin-top:46px">
                <h3 class="ttext" style="margin-bottom: 10px;text-decoration: none;"><span style="font-weight: 600;text-decoration: underline;">TAKE TWO</span> MINUTES AND PLAN FOR SAFETY</h3>
                <br>
                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">Project:</span>
                                <span class="sp" style="display: none;"> </span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="proj" id="proj" class="form-control" value="<?php //echo $info['proj'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">Trade:</span>
                                <span class="sp" style="display: none;"> </span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="trad" id="trad" class="form-control" value="<?php //echo $info['trad'];?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">Date:</span>
                                <span class="sp" style="display: none;"> </span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="date" id="date" class="form-control" value="<?php //echo $info['date'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">Foreman:</span>
                                <span class="sp" style="display: none;"> </span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="foreman2" id="foreman2" class="form-control" value="<?php //echo $info['foreman2'];?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 nopad hed" >Plan out your work before you start and throughout your day.</div>
            <div class="col-sm-12 nopad heading" >SAFETY PROCEDURES(Check all that apply)</div>
            <div class="col-sm-12 nopad" style="margin-top: 7px;">
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="lay" value="lay" <?php //if(stripslashes($info['lay']) == "lay"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Layout/ Tremble
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="ina" value="ina" <?php //if(stripslashes($info['ina']) == "ina"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Install Anchors
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="isg" value="isg" <?php //if(stripslashes($info['isg']) == "isg"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Install Straps/Hangers
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="ipe" value="ipe" <?php //if(stripslashes($info['ipe']) == "ipe"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Install Pipe
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="ida" value="ida" <?php //if(stripslashes($info['ida']) == "ida"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Install Duct
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="wld" value="wld" <?php //if(stripslashes($info['wld']) == "wld"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Welding/Brazing/Soldering
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="cut" value="cut" <?php //if(stripslashes($info['cut']) == "cut"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Cutting
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="drl" value="drl" <?php //if(stripslashes($info['drl']) == "drl"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Drilling/Coring
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="prf" value="prf" <?php //if(stripslashes($info['prf']) == "prf"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Process Fittings
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="mal" value="mal" <?php //if(stripslashes($info['mal']) == "mal"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Material Handling
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="fab" value="fab" <?php //if(stripslashes($info['fab']) == "fab"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Fabrication/Assembly
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="insl" value="insl" <?php //if(stripslashes($info['insl']) == "insl"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Installing Seismic
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="crap" value="crap" <?php //if(stripslashes($info['crap']) == "crap"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Crane Pick
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="hous" value="hous" <?php //if(stripslashes($info['hous']) == "hous"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Housekeeping
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="demo" value="demo" <?php //if(stripslashes($info['demo']) == "demo"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Demolition
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="insmeq" value="insmeq" <?php //if(stripslashes($info['insmeq']) == "insmeq"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Installing mechanical equipment
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="inf" value="inf" <?php //if(stripslashes($info['inf']) == "inf"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Installing fixtures
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="othe" value="othe" <?php //if(stripslashes($info['othe']) == "othe"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Other
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="mnt" value="mnt" <?php //if(stripslashes($info['mnt']) == "mnt"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Maintance Inspection
                    </label>
                </div>
            </div>

            <div class="col-sm-5 nopad heading2" style="margin-top: 15px;">What additional tasks are you performing?</div>
            <div class="col-sm-7" >&nbsp;</div>
            <div class="col-sm-12 nopad">
                <textarea  name="additional_task"  class="form-control" placeholder="What additional tasks are you performing?"><?php //echo $info['additional_task'];?></textarea>
            </div>

            <div class="col-sm-12 nopad heading" style="margin-top: 20px;">SAFETY PROCEDURES(Check all that apply</div>
            <div class="col-sm-12 nopad" style="margin-top: 7px;">
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="falh" value="falh" <?php //if(stripslashes($info['falh']) == "falh"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Unprotected Fall Hazards
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="scaff" value="scaff" <?php //if(stripslashes($info['scaff']) == "scaff"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Scaffolds
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="powder" value="powder" <?php //if(stripslashes($info['powder']) == "powder"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Powder actuated/HILTI Tools
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="rooftop" value="rooftop" <?php //if(stripslashes($info['rooftop']) == "rooftop"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Rooftop
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="boom_left" value="boom_left" <?php //if(stripslashes($info['boom_left']) == "boom_left"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Scissor/Boom lift
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="fork" value="fork" <?php //if(stripslashes($info['fork']) == "fork"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Forklift
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="chem" value="chem" <?php //if(stripslashes($info['chem']) == "chem"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Chemicals
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="wel" value="wel" <?php //if(stripslashes($info['wel']) == "wel"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Welding/cutting
                    </label>
                </div>
                <div class="col-sm-1 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="oth2" value="oth2" <?php //if(stripslashes($info['oth2']) == "oth2"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Other
                    </label>
                </div>
                <div class="col-sm-3 nopad">
                    <input type="text" name="oth2_txt" id="oth2_txt" style="margin-top: -6px;" class="form-control" value="<?php //echo $info['oth2_txt'];?>">
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="rig_rop" value="rig_rop" <?php //if(stripslashes($info['rig_rop']) == "rig_rop"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Rigging/Rope
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="rig_rop" value="rig_rop" <?php //if(stripslashes($info['rig_rop']) == "rig_rop"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Ladders A-Frame/Extension
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    &nbsp;
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="pw_tool" value="pw_tool" <?php //if(stripslashes($info['pw_tool']) == "pw_tool"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Power Tools
                    </label>
                </div>
            </div>

            <div class="col-sm-5 nopad heading2" style="margin-top: 15px;">What additional hazards are present?</div>
            <div class="col-sm-7" >&nbsp;</div>
            <div class="col-sm-12 nopad">
                <textarea  name="additional_haz"  class="form-control" placeholder="What additional hazards are present?"><?php //echo $info['additional_haz'];?></textarea>
            </div>

            <div class="col-sm-12 nopad heading" style="margin-top: 20px;">SAFETY PRECAUTIONS(Check all that apply)</div>
            <div class="col-sm-12 nopad" style="margin-top: 7px;">
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="gogg" value="gogg" <?php //if(stripslashes($info['gogg']) == "gogg"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Goggles/Fasesheild
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="harness" value="harness" <?php //if(stripslashes($info['harness']) == "harness"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Harness/Lanyard
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="dispo" value="dispo" <?php //if(stripslashes($info['dispo']) == "dispo"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Disposable Respirator
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="fir_ex" value="fir_ex" <?php //if(stripslashes($info['fir_ex']) == "fir_ex"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Fire Extinguisher
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="taskl" value="taskl" <?php //if(stripslashes($info['taskl']) == "taskl"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Task Lighting
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="shde" value="shde" <?php //if(stripslashes($info['shde']) == "shde"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Shade/Water/Sunscreen(Heat Illness)
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="ventl" value="ventl" <?php //if(stripslashes($info['ventl']) == "ventl"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Ventilation
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="fir" value="fir" <?php //if(stripslashes($info['fir']) == "fir"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Fire-Resistant & Arc flash Clothes/Gloves
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="loc" value="loc" <?php //if(stripslashes($info['loc']) == "loc"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Lockout/Tagout
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="h_cov" value="h_cov" <?php //if(stripslashes($info['h_cov']) == "h_cov"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Hole Covers
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="mat_hl" value="mat_hl" <?php //if(stripslashes($info['mat_hl']) == "mat_hl"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Material Handling Aids
                    </label>
                </div>
                <div class="col-sm-1 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="oth3" value="oth3" <?php //if(stripslashes($info['oth3']) == "oth3"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Other
                    </label>
                </div>
                <div class="col-sm-3 nopad">
                    <input type="text" name="oth3_txt" id="oth3_txt"  class="form-control" value="<?php //echo $info['oth3_txt'];?>">
                </div>
            </div>

            <div class="col-sm-5 nopad heading2" style="margin-top: 15px;">What additional precautions must be taken?</div>
            <div class="col-sm-7" >&nbsp;</div>
            <div class="col-sm-12 nopad">
                <textarea  name="precautions"  class="form-control" placeholder="What additional precautions must be taken?"><?php //echo $info['precautions'];?></textarea>
            </div>

            <div class="col-sm-12 nopad " style="margin-top: 15px;">
                <div class="coustum-box">
                    <table class="table table-responsive" width="100%">
                        <tr>
                            <td class="heading" colspan="2">
                                SAFETY CHECKLIST<br>(all boxes must be checked. If the answer is YES to any questions, contact your Foreman/Supervisor before proceeding)
                            </td>
                            <td class="heading">YES</td>
                            <td class="heading">NO</td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="dl_wr" value="dl_wr" <?php //if(stripslashes($info['dl_wr']) == "dl_wr"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Attend daily two minute start up meeting
                                </label>
                            </td>
                            <td>
                                Do todays tasks require special training/certification?
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="tas_requi" value="yes" <?php //if(stripslashes($info['tas_requi']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="tas_requi" value="no" <?php //if(stripslashes($info['tas_requi']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="for_imm" value="for_imm" <?php //if(stripslashes($info['for_imm']) == "for_imm"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Bring any Safety Issues to your Foreman immediately
                                </label>
                            </td>
                            <td>
                                Do todays tasks require special tools or equipment?
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="tools" value="yes" <?php //if(stripslashes($info['tools']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="tools" value="no" <?php //if(stripslashes($info['tools']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="tk_chn" value="tk_chn" <?php //if(stripslashes($info['tk_chn']) == "tk_chn"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Don't take chances if in doubt ask your Forman
                                </label>
                            </td>
                            <td>
                                Do todays tasks require review of Safety Data Sheets?
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="tools" value="yes" <?php //if(stripslashes($info['tools']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="tools" value="no" <?php //if(stripslashes($info['tools']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="ins_work" value="ins_work" <?php //if(stripslashes($info['ins_work']) == "ins_work"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Insure that your work are is free of hazards
                                </label>
                            </td>
                            <td>
                                Will weather be a safety concern today?
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="saf_con" value="yes" <?php //if(stripslashes($info['saf_con']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="saf_con" value="no" <?php //if(stripslashes($info['saf_con']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="pro_equip" value="pro_equip" <?php //if(stripslashes($info['pro_equip']) == "pro_equip"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Wear all required Personal Protective Equipment
                                </label>
                            </td>
                            <td>
                                Are barricades warning tape or safety signs required?
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="bar_war" value="yes" <?php //if(stripslashes($info['bar_war']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="bar_war" value="no" <?php //if(stripslashes($info['bar_war']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="tool_equ" value="tool_equ" <?php //if(stripslashes($info['tool_equ']) == "tool_equ"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Inspect all tools and equipment before use.
                                </label>
                            </td>
                            <td>
                                Is the weight of the materials your handling more than 50lbs?
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="mat_hal" value="yes" <?php //if(stripslashes($info['mat_hal']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="mat_hal" value="no" <?php //if(stripslashes($info['mat_hal']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="bef_use" value="bef_use" <?php //if(stripslashes($info['bef_use']) == "bef_use"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Be trained with all tools before use.
                                </label>
                            </td>
                            <td>
                                Are there any new crew members that require support?
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="cr_mem" value="yes" <?php //if(stripslashes($info['cr_mem']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="cr_mem" value="no" <?php //if(stripslashes($info['cr_mem']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="eptc" value="eptc" <?php //if(stripslashes($info['eptc']) == "eptc"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    There are enough personal to complete the task
                                </label>
                            </td>
                            <td>
                                Are you working in trenches or confined spaces?
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="wtcs" value="yes" <?php //if(stripslashes($info['wtcs']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="wtcs" value="no" <?php //if(stripslashes($info['wtcs']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="tpbc" value="tpbc" <?php //if(stripslashes($info['tpbc']) == "tpbc"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Has a pre task plan been completed for high risk work
                                </label>
                            </td>
                            <td>
                                Are ladders visually inspected prior to use?
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="vis_pr" value="yes" <?php //if(stripslashes($info['vis_pr']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                            <td align="center">
                                <label class="checkbox-inline" style="padding: 0;">
                                    <input type="radio" name="vis_pr" value="no" <?php //if(stripslashes($info['vis_pr']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="emerg" value="emerg" <?php //if(stripslashes($info['emerg']) == "emerg"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Do you know who to contact in an emergency
                                </label>
                            </td>
                            <td></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                        <tr>
                            <td>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="vis" value="vis" <?php //if(stripslashes($info['vis']) == "vis"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Are ladders visually inspected prior to use
                                </label>
                            </td>
                            <td></td>
                            <td align="center"></td>
                            <td align="center"></td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="col-sm-12 nopad heading" style="margin-top: 20px;">SAFE WORK PRACTICES(CHECK ALL THAT APPLY)</div>
            <div class="col-sm-12 nopad" style="margin-top: 7px;">
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="llce" value="llce" <?php //if(stripslashes($info['llce']) == "llce"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Know the location of the closest emergency exit, fire extinguisher, and first aid kit to your work area.
                    </label>
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="msyuv" value="msyuv" <?php //if(stripslashes($info['msyuv']) == "msyuv"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Make sure you have the correct size and type of ladder and that it is setup and being used properly
                    </label>
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="etapc" value="etapc" <?php //if(stripslashes($info['etapc']) == "etapc"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Ensure that all power cords are inspected and free of damage, and laid out to minimize trip hazards.
                    </label>
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="utrt" value="utrt" <?php //if(stripslashes($info['utrt']) == "utrt"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Use the right tool for the job. Use the tool the way the manufacture designed it to be used.
                    </label>
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="facgm" value="facgm" <?php //if(stripslashes($info['facgm']) == "facgm"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Flammable and compressed gasses must be properly used, secured, transported and stored properly when not in use.
                    </label>
                </div>
            </div>

            <div class="col-sm-12 nopad heading" style="margin-top: 20px;">SAFEWORK PRACTICES (Check all that apply)</div>
            <div class="col-sm-12 nopad" style="margin-top: 7px;">
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="fpemi" value="fpemi" <?php //if(stripslashes($info['fpemi']) == "fpemi"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Fall protection equipment must be inspected before each use and stored properly when not in use.
                    </label>
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="eywah" value="eywah" <?php //if(stripslashes($info['eywah']) == "eywah"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Ensure your work area has proper lighting and ventilation. Stay clear of work causing dust, mists, or vapors.
                    </label>
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="dnrnj" value="dnrnj" <?php //if(stripslashes($info['dnrnj']) == "dnrnj"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Do not run on the jobsite. Do not listen to music or use headphones. Cell phones may only be used on breaks.
                    </label>
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="situe" value="situe" <?php //if(stripslashes($info['situe']) == "situe"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Smoking, including the use of e-cigarettes, is only permitted in approved areas.
                    </label>
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="cfmm" value="cfmm" <?php //if(stripslashes($info['cfmm']) == "cfmm"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        Clean up after you make a mess. Housekeeping should be conducted on an on-going basis.
                    </label>
                </div>
            </div>

            <div class="col-sm-12 nopad heading" style="margin-top: 20px;">EMPLOYEE SAFETY REPORT (Check all that apply)</div>
            <div class="col-sm-12 nopad" style="margin-top: 7px;">
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="irsh" value="irsh" <?php //if(stripslashes($info['irsh']) == "irsh"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        I reported a Safety Hazard
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="irsi" value="irsi" <?php //if(stripslashes($info['irsi']) == "irsi"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        I resolved a Safety Issue
                    </label>
                </div>
                <div class="col-sm-4 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="ihss" value="ihss" <?php //if(stripslashes($info['ihss']) == "ihss"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        I had a Safety Suggestion
                    </label>
                </div>
            </div>

            <div class="col-sm-12 nopad" style="padding-top: 13px;">
                <div class="col-sm-12 nopad">
                    <div class="form-group">
                        <div class="col-sm-1">
                            <span class="en">Description:</span>
                        </div>
                        <div class="col-sm-11">
                            <textarea  name="description"  class="form-control" placeholder="Description"><?php //echo $info['description'];?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 nopad">
                    <div class="form-group">
                        <div class="col-sm-2">
                            <span class="en">Reported to:</span>
                        </div>
                        <div class="col-sm-10">
                            <div class="col-sm-2 nopad">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="suprv" value="suprv" <?php //if(stripslashes($info['suprv']) == "suprv"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Supervisor
                                </label>
                            </div>
                            <div class="col-sm-2 nopad">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="ensaf" value="ensaf" <?php //if(stripslashes($info['ensaf']) == "ensaf"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Envise Safety
                                </label>
                            </div>
                            <div class="col-sm-2 nopad">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="general" value="general" <?php //if(stripslashes($info['general']) == "general"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    General
                                </label>
                            </div>
                            <div class="col-sm-1 nopad">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="oth4" value="oth4" <?php //if(stripslashes($info['oth4']) == "oth4"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Other
                                </label>
                            </div>
                            <div class="col-sm-3 nopad">
                                <input type="text" name="oth4_txt" id="oth4_txt"  class="form-control" value="<?php //echo $info['oth4_txt'];?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 nopad">
                    <div class="col-sm-6 ">
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="iwiai" value="iwiai" <?php //if(stripslashes($info['iwiai']) == "iwiai"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                I was involved in an incident
                            </label>
                        </div>
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="acci" value="acci" <?php //if(stripslashes($info['acci']) == "acci"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Accident
                            </label>
                        </div>
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="inmi" value="inmi" <?php //if(stripslashes($info['inmi']) == "inmi"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                INear-Miss
                            </label>
                        </div>
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="inju" value="inju" <?php //if(stripslashes($info['inju']) == "inju"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Injury
                            </label>
                        </div>
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="oth5" value="oth5" <?php //if(stripslashes($info['oth5']) == "oth5"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Other
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="ihav" value="ihav" <?php //if(stripslashes($info['ihav']) == "ihav"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                I had a violation
                            </label>
                        </div>
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="unac" value="unac" <?php //if(stripslashes($info['unac']) == "unac"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Unsafe act
                            </label>
                        </div>
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="nsco" value="nsco" <?php //if(stripslashes($info['nsco']) == "nsco"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                nsafe condition
                            </label>
                        </div>
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="unpo" value="unpo" <?php //if(stripslashes($info['unpo']) == "unpo"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Unsafe Procedure
                            </label>
                        </div>
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="imppe" value="imppe" <?php //if(stripslashes($info['imppe']) == "imppe"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Improper PPE
                            </label>
                        </div>
                        <div class="col-sm-12 nopad">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="oth6" value="oth6" <?php //if(stripslashes($info['oth6']) == "oth6"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Other
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 nopad">
                    <div class="form-group">
                        <div class="col-sm-1">
                            <span class="en">Description:</span>
                        </div>
                        <div class="col-sm-11">
                            <textarea  name="description2"  class="form-control" placeholder="Description"><?php //echo $info['description2'];?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 nopad">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="irffd" value="irffd" <?php //if(stripslashes($info['irffd']) == "irffd"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                        <b><u>I reported to work today fit for duty</u> (free of injury/illness, not under the influence of drugs or alcohol)</b>
                    </label>
                </div>
                <div class="col-sm-12 nopad" style="padding-top: 13px;">
                    <div class="col-sm-6 nopad">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="idng" value="idng" <?php //if(stripslashes($info['idng']) == "idng"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                            <b><u>I did not get injured or become ill on the job today.</u></b>
                        </label>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="form-group">
                            <div class="col-sm-1">
                                <span class="en">Intial:</span>
                            </div>
                            <div class="col-sm-11">
                                <input type="text" name="intial" id="intial" class="form-control" value="<?php //echo $info['intial'];?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 nopad">
                    <div class="col-sm-6 nopad">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <span class="en">Employee:</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="employee2" id="employee2" class="form-control" value="<?php //echo $info['employee2'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 nopad">
                        <div class="form-group">
                            <div class="col-sm-2">
                                <span class="en">Signature:</span>
                            </div>
                            <div class="col-sm-11">
                                <div id="sign_ipad1">
                            <div  class="sig sigWrapper current" style="cursor:crosshair;width:462px;height: 130px; overflow: hidden;">
                                <div style="display: none;" class="typed"></div>
                                <canvas class="pad" width="462" height="130"></canvas>
                                <input name="signature" id="signature" value="" class="output" type="hidden">
                            </div>
                            <a href="#clear" class="clearButton">Clear signature</a>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="col-sm-12 " style="margin-top: 10px;">
                <div class="col-sm-3 row">
                    <?php if(isset($info)){ ?>
                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                    <?php } ?>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='/portal/'">Cancel</button>
                    &nbsp;
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo (isset($_GET['id']) && !empty($_GET['id']))?'Update':'Submit';?>">
                </div>
            </div>

            <div style="clear:both;"><br></div>
        </fieldset>
    </form>
</div>

<script src="/js/jquery.signaturepad.js"></script>
<script>
$(document).ready(function () {
    $("#envise-pre-task").validate({
        rules: {
            employee: "required",
            foreman: "required",
            project: "required",
            month: "required",
            dat: "required",
        },
    });

    <?php if(isset($info['signature']) && trim($info['signature'])!=''){ ?>
            $('#sign_ipad1').signaturePad({drawOnly:true,validateFields:false, lineWidth :0}).regenerate('<?php echo $info['signature'] ?>');
    <?php }else{ ?>
        $('#sign_ipad1').signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
    <?php } ?>
});
    var specialKeys = new Array();
    specialKeys.push(8); //Backspace
    specialKeys.push(9); //Tab
    specialKeys.push(46); //Delete
    specialKeys.push(36); //Home
    specialKeys.push(35); //End
    specialKeys.push(37); //Left
    specialKeys.push(39); //Right

    function isNumberKey(e){
        var keyCode = e.keyCode == 0 ? e.charCode : e.keyCode;
        var ret = ((keyCode >= 48 && keyCode <= 57) || (specialKeys.indexOf(e.keyCode) != -1 && e.charCode != e.keyCode));
        return ret;
    }

    $( "#date" ).datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        closeOnDateSelect: true,
        scrollInput: false,
    });
</script>

<?php include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>
