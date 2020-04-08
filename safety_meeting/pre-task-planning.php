<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';

if(isset($_POST['save'])){
	
    foreach($_POST as $key=>$val){
		$_POST[$key] = mysql_real_escape_string($val);
		$data[$key] = $val;        
    }
	
    if(isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != ''){
		
		$sql1 = 'SELECT * FROM pre_task_plan WHERE id='.$_POST['id'];
        $re = mysql_query($sql1);

        if(mysql_num_rows($re)>0){
            $sql2 = "UPDATE pre_task_plan SET data = '".mysql_real_escape_string(serialize($data))."', project = '".$_POST['project']."', division = '".$_POST['division']."', foreman = '".$_POST['foreman']."', cell = '".$_POST['cell']."', specific_location = '".$_POST['specific_location']."', date = '".date("Y-m-d",strtotime($_POST['date']))."', updated = '".date('Y-m-d h:i:s')."' WHERE `id` = '".$_POST['id']."'";			
            $query_stat = mysql_query($sql2);
			
			$row_id = $_POST['id'];			
        }
        else{
            $_SESSION['error_msg'] = "Record not found!";
        }
    }else{
		
		$sql = "INSERT INTO pre_task_plan (project, division, foreman,cell, specific_location, date, data, created) VALUES ('".$_POST['project']."','".$_POST['division']."','".$_POST['foreman']."','".$_POST['cell']."','".$_POST['specific_location']."','".date("Y-m-d",strtotime($_POST['date']))."','".mysql_real_escape_string(serialize($data))."', '".date('Y-m-d h:i:s')."')";
        $query_stat = mysql_query($sql);
		
        $row_id = mysql_insert_id();
    }
	
	if($query_stat){
		
		require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');
		$mail = new NextCodeMailer();
		
		$mail->From = 'noreply@nextcode.info';
		$mail->FromName = 'NextCode.Info';
		
		if ($_POST['division'] == '1') {
			$mail->addAddress('norcalsafety@southlandind.com');
		} else if ($_POST['division'] == '2') {
			$mail->addAddress('socalimt@southlandind.com');	
		} else if ($_POST['division'] == '3') {
			$mail->addAddress('MWSafety@Southlandind.com');
		} else if ($_POST['division'] == '4') {	
            $mail->addAddress('MADSafety@southlandind.com');
			/*$mail->addAddress('JDevan@southlandind.com');
            $mail->addAddress('Mlaplace@southlandind.com');
            $mail->addAddress('Gstewart@southlandind.com');*/
        }
		
		if(isset($_POST['foreman_email']) && !empty($_POST['foreman_email'])){
			$mail->addAddress($_POST['foreman_email']);
		}
		
		$mail->AddBCC('si-notifications@nextcode.info');
		$mail->AddBCC('pankaj1983samal@gmail.com');
		
		$mail->isHTML(true);# Set email format to HTML
		$mail->Subject = 'Southland - Pre-Task Planning';
		$mail->Body = '<html>
							<head>
								<meta http-equiv="content-type" content="text/html; charset=utf-8">
							</head>
							<body text="#000000" bgcolor="#FFFFFF">
								<font size="-1">Hi,<br>
									<br>
									Please click <a href="'.$base_url.'/html2pdf_v4.03/examples/pre_task_plan_doc.php?id='.$row_id .'" >here</a> to download Pre-Task Plan PDF.
									<br>
									<br>
									You can also copy the below link to download Pre-Task Plan PDF.<br><br>
									'.$base_url.'/html2pdf_v4.03/examples/pre_task_plan_doc.php?id='.$row_id.'
									<br><br>
								</font>
								<br>										
								<p style="font-size:9.5pt;margin:0;font-family:Arial;">Thanks,</p>
								<p style="font-size:9.5pt;margin:5px 0;font-family:Arial;">Team Nextcode</p>
								<p style="font-size:9.0pt;font-family:Arial;color:#000033;margin:0;">
									<a href="https://nextcode.info/">NextCode.Info, LLC.</a>
								</p>
								<p style="margin:0;">
									<a href="https://nextcode.info/" target="_blank" style="font-size:9.0pt;font-family:Arial;color:#000033">
										https://nextcode.info/
									</a>
								</p>
								<p><br></p>    
							</body>
						</html>';
	
		
		# $mail must have been created		
		if($mail->send()) {
			$_SESSION['success_msg'] = "Pre-Task Planning has been sent to user email.";

			header('Location:/portal/weekly_tailgate/pre-task-planning.php');
			exit;
		}
		else{
			$_SESSION['error_msg'] = "Sorry, mail couldn't be send. Contact Admin!";
		}		
	}else{
		$_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";
	}
}


if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql1 = 'SELECT * FROM pre_task_plan WHERE id='.$id;
    $re = mysql_query($sql1);

    if(mysql_num_rows($re)>0){
        $info  = mysql_fetch_array($re);	
		foreach(unserialize($info['data']) as $key=>$val){
			$info[$key] = stripslashes($val);
		}
    }
}

$query_div = "SELECT * FROM divisions WHERE client = $client AND active = '1'";
$result_div = mysql_query($query_div);
while ($ob_div = mysql_fetch_object($result_div)) {
    $divisions[$ob_div->id] = $ob_div;
}
?>

<?php include_once dirname(dirname(dirname(__FILE__))).'/_head.php';  ?>
<style>
.form-horizontal .checkbox-inline{ padding-top:0px;}
</style>
<hr>
<div id="frame" >
    <form class="form-horizontal" id="pre-task" method="POST" action="" name="pre-task" enctype="multipart/form-data">

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
            <h3 class="ttext" style="margin-bottom: 10px;">PRE-TASK PLANNING WORKSHEET</h3>
            <span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
            <div class="clr"><br></div>

            <div id="personal_edit">
                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-5">
                                <span class="en">Project:</span>
                                <span class="sp" style="display: none;"> </span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="project" id="project" class="form-control" value="<?php echo $info['project'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">Foreman:</span>
                                <span class="sp" style="display: none;"> </span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="foreman" id="foreman" class="form-control" value="<?php echo $info['foreman'];?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-5">
                                <span class="en">Cell:</span>
                                <span class="sp" style="display: none;"> </span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="cell" id="cell" class="form-control" value="<?php echo $info['cell'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">Specific Location:</span>
                                <span class="sp" style="display: none;"> </span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="specific_location" id="specific_location" class="form-control" value="<?php echo $info['specific_location'];?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-5">
                                <span class="en">Date:</span>
                                <span class="sp" style="display: none;"> </span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="date" id="date" class="form-control" value="<?php echo $info['date'];?>" placeholder="MM/DD/YYYY">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">JHA:</span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="jha" id="jha" class="form-control" value="<?php echo $info['jha'];?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-md-5">
                                <span class="en">Division :</span>
                                <span class="error">*</span>
                            </div>
                            <div class="col-md-7">
                                <select class="form-control" name="division" id="division">
                                    <option value="">Select Division</option>
                                    <?php
                                    foreach($divisions as $div) {
                                    ?>
                                    <option value="<?php echo $div->id; ?>" <?php echo $info['division']== $div->id?" selected":""; ?>>
                                        <?php echo $div->nickname; ?>
                                    </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">Foreman Email:</span>                               
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="foreman_email" id="foreman_email" class="form-control" value="<?php echo $info['foreman_email'];?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!--Strats: "Trade / Crew Size" Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12" >
                            <span><strong>Trade / Crew Size</strong></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-5">
                                <span class="en">Sheet Metal:</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="sheet_metal" id="sheet_metal" class="form-control" value="<?php echo $info['sheet_metal'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">Plumbers:</span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="plumber" id="plumber" class="form-control" value="<?php echo $info['plumber'];?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-5">
                                <span class="en">Fitters:</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="fitter" id="fitter" class="form-control" value="<?php echo $info['fitter'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-6">
                                <span class="en">Controls:</span>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="control" id="control" class="form-control" value="<?php echo $info['control'];?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="col-sm-5">
                                <span class="en">Service:</span>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="service" id="service" class="form-control" value="<?php echo $info['service'];?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6"></div>
                </div>

                <div class="col-sm-12 nopad">
                    <div class="form-group" style="margin:10px 0">
                        <div class="col-sm-12">
                            <span class="en">Description of Work:</span>
                        </div>
                        <div class="col-sm-12">
                            <textarea cols="30" name="desc_work" id="desc_work" class="form-control"><?php echo $info['desc_work'];?></textarea>
                        </div>
                    </div>
                </div>
                <!---End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "Do not start work" Div-->
                <div class="col-sm-12" >
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12" >
                        <span><strong>WALK WORK AREA</strong>&nbsp;&nbsp;
                        Do not start work before all hazards are mitigated</span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12" >
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="open_holes" value="open_holes" <?php if(stripslashes($info['open_holes']) == "open_holes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Open Holes
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="checkbox" name="mitigation1" value="mitigation1" <?php if(stripslashes($info['mitigation1']) == "mitigation1"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Mitigation
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="miti_1" id="miti_1" class="form-control" value="<?php echo $info['miti_1'];?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="leading" value="leading" <?php if(stripslashes($info['leading']) == "leading"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Leading Edge
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="checkbox" name="mitigation2" value="mitigation2" <?php if(stripslashes($info['mitigation2']) == "mitigation2"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Mitigation
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="miti_2" id="miti_2" class="form-control" value="<?php echo $info['miti_2'];?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="slip_trip" <?php if(stripslashes($info['slip_trip']) == "slip_trip"){ echo "checked='true'"; } ?> value="slip_trip" style="margin-top: 1px; margin-right: 2px;">
                                Slip/Trip Hazards
                            </label>&nbsp;&nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="checkbox" name="mitigation3" value="mitigation3" <?php if(stripslashes($info['mitigation3']) == "mitigation3"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Mitigation
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="miti_3" id="miti_3" class="form-control" value="<?php echo $info['miti_3'];?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="congested" value="congested" <?php if(stripslashes($info['congested']) == "congested"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Congested with other trades
                            </label>&nbsp;&nbsp;&nbsp;
                            <span>If yes, coordinate with other trades</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">
                            <span>Will weather conditions affect the safe completion of this work? </span>
                        </div>
                        <div class="col-sm-6 nopad">
                            <label class="checkbox-inline">
                                <input type="radio" name="weather_conditions" <?php if(stripslashes($info['weather_conditions']) == "yes"){ echo "checked='true'"; } ?> value="yes" style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            &nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="weather_conditions" <?php if(stripslashes($info['weather_conditions']) == "no"){ echo "checked='true'"; } ?>  value="no" style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <span>If yes, what is being done to minimize or mitigate safety concerns?</span><br>
                            <textarea  name="safety" id="safety" class="form-control"><?php echo $info['safety'];?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <span>Nearest Evacuation route: </span><br>
                            <textarea  name="route" id="route" class="form-control"><?php echo $info['route'];?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <span>Nearest Fire Extinguisher:</span><br>
                            <textarea  name="fire" id="fire" class="form-control"><?php echo $info['fire'];?></textarea>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "MATERIAL HANDLING" Div-->
                <div class="col-sm-12" >
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12"><strong>MATERIAL HANDLING</strong>&nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="material_handling" value="yes" <?php if(stripslashes($info['material_handling']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="material_handling" value="no" <?php if(stripslashes($info['material_handling']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12" >
                    <div class="form-group">
                        <div class="col-sm-5">
                            Inspection performed and documented
                        </div>
                        <div class="col-sm-7 nopad">
                            <label class="checkbox-inline">
                                <input type="radio" name="inspection" value="yes" <?php if(stripslashes($info['inspection']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="inspection" value="no" <?php if(stripslashes($info['inspection']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="forklift" value="forklift" <?php if(stripslashes($info['forklift']) == "forklift"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" > Forklift *
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="cha" value="cha" <?php if(stripslashes($info['cha']) == "cha"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" > Chain Fall
                            </label>
                        </div>
                        <div class="col-sm-1">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="other1" value="other1" <?php if(stripslashes($info['other1']) == "other1"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Other
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="input_1" id="input_1" class="col-sm-3 form-control" value="<?php echo $info['input_1']; ?>" >
                        </div>
                        <div class="col-sm-3">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="equipment" value="equipment" <?php if(stripslashes($info['equipment']) == "equipment"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" >
                                Equipment Capacities
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="pallet" value="pallet" <?php if(stripslashes($info['pallet']) == "pallet"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" > Pallet Jack
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="come_a_long" value="come_a_long" <?php if(stripslashes($info['come_a_long']) == "come_a_long"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" > Come a Long
                            </label>
                        </div>
                        <div class="col-sm-1">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="other2" value="other2" <?php if(stripslashes($info['other2']) == "other2"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Other
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="input_2" id="input_2" class="col-sm-3 form-control" value="<?php echo $info['input_2']; ?>" >
                        </div>
                        <div class="col-sm-3">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="load_weight" value="load_weight" <?php if(stripslashes($info['load_weight']) == "load_weight"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" >
                                Load Weights Known
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="duc" value="duc" <?php if(stripslashes($info['duc']) == "duc"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" > Duct Jack
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="electric_hoist" value="electric_hoist" <?php if(stripslashes($info['electric_hoist']) == "electric_hoist"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" > Electric Hoist
                            </label>
                        </div>
                        <div class="col-sm-1">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="other_3" value="other_3" <?php if(stripslashes($info['other_3']) == "other_3"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Other
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="input_3" id="input_3" class="col-sm-3 form-control" value="<?php echo $info['input_3']; ?>" >
                        </div>
                        <div class="col-sm-3">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="spotter" value="spotter" <?php if(stripslashes($info['spotter']) == "spotter"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" >
                                Spotter
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12"  >* Documented model specific training required for forklift operation</div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12"  >Check with Safety dept. if training is required.</div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-8" >Foreman or supervisor notified of any failed items noted during inspection: </div>
                        <div class="col-sm-4">
                            <label class="checkbox-inline">
                                <input type="radio" name="supervisor_notified" value="yes" <?php if(stripslashes($info['supervisor_notified']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" >
                                Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="supervisor_notified" value="no" <?php if(stripslashes($info['supervisor_notified']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" >
                                No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12" >Manual material handling of spiral duct or sharp edged materials requires the use of cut resistant sleeves and gloves.
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "ELECTRIC POWER" Div-->
                <div class="col-sm-12" >
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12"><strong>ELECTRIC POWER TOOLS</strong>&nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="electrical_power_tools" value="yes"  <?php if(stripslashes($info['electrical_power_tools']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="electrical_power_tools" value="no"  <?php if(stripslashes($info['electrical_power_tools']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12" >
                    <div class="form-group">
                        <div class="col-sm-3" >Inspection Performed: </div>
                        <div class="col-sm-9">
                            <label class="checkbox-inline">
                                <input type="radio" name="inspection_performed" value="yes"  <?php if(stripslashes($info['inspection_performed']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" >
                                Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="inspection_performed" value="no"  <?php if(stripslashes($info['inspection_performed']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" >
                                No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3" >GFCI  Available </div>
                        <div class="col-sm-9">
                            <label class="checkbox-inline">
                                <input type="radio" name="gfci" value="yes"  <?php if(stripslashes($info['gfci']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" >
                                Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="gfci" value="no"  <?php if(stripslashes($info['gfci']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;" >
                                No
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="portal_band" value="portal_band"  <?php if(stripslashes($info['portal_band']) == "portal_band"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Portal Band Saw
                            </label><br>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="reciprocate" value="reciprocate"  <?php if(stripslashes($info['reciprocate']) == "reciprocate"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Reciprocating Saw(Saw All)
                            </label><br>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="grinder" value="grinder"  <?php if(stripslashes($info['grinder']) == "grinder"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Grinder
                            </label><br>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="jig" value="jig"  <?php if(stripslashes($info['jig']) == "jig"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                    Jig Saw
                            </label><br>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="circular" value="circular"  <?php if(stripslashes($info['circular']) == "circular"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Circular Saw
                            </label><br>
                            <label class="checkbox-inline">
                                <input type="checkbox" name="other_4" value="other_4"  <?php if(stripslashes($info['other_4']) == "other_4"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Other
                            </label><br>
                        </div>
                        <div class="col-sm-6">
                            <label >
                                All work is to be secured before cutting or grinding. Exceptions to this rule may be granted by safety department on a case by case basis.
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <span>Remove damaged extension cords from service.Do not attempt to repair.</span>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "PERSONAL FALL" Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12"><strong>PERSONAL FALL ARREST</strong>&nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="fall_arrest" value="yes" <?php if(stripslashes($info['fall_arrest']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="fall_arrest" value="no" <?php if(stripslashes($info['fall_arrest']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="inspection_perfo" value="inspection_perfo" <?php if(stripslashes($info['inspection_perfo']) == "inspection_perfo"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Inspection Performed
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="approved" value="approved" <?php if(stripslashes($info['approved']) == "approved"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Approved anchorage points identified
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="correct_equip" value="correct_equip" <?php if(stripslashes($info['correct_equip']) == "correct_equip"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Correct Equipment Available
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="fall_distance" value="fall_distance" <?php if(stripslashes($info['fall_distance']) == "fall_distance"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Fall distance calculation performed
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="trained" value="trained" <?php if(stripslashes($info['trained']) == "trained"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Trained in Equipment Use
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <!--label class="checkbox-inline">
                                <input type="checkbox" name="rescue_retrieval" value="rescue_retrieval" <?php if(stripslashes($info['rescue_retrieval']) == "rescue_retrieval"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Rescue/Retrieval plan.
                            </label-->
                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <span>Explain Below</span><br>
                            <textarea  name="resue" id="resue" class="form-control"><?php echo $info['resue'];?></textarea>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "BARRICADESL" Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12" style="">
                            <strong>BARRICADES</strong>
                            &nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="barricades" value="yes" <?php if(stripslashes($info['barricades']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="barricades" value="no" <?php if(stripslashes($info['barricades']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-12" style="">
                            <span>Control zones must be established if hazards exist to other trade workers or the public.</span>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "WORK AT HEIGHT" Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12">
                            <strong>WORK AT HEIGHT</strong>
                            &nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="work_at_night" value="yes" <?php if(stripslashes($info['work_at_night']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="work_at_night" value="no" <?php if(stripslashes($info['work_at_night']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label class="col-sm-3 checkbox-inline">
                                <input type="checkbox" name="lad" value="lad" <?php if(stripslashes($info['lad']) == "lad"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Ladder
                            </label>
                            <label class="col-sm-3 checkbox-inline">
                                <input type="checkbox" name="scissor" value="scissor" <?php if(stripslashes($info['scissor']) == "scissor"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">  Scissor
                            </label>
                            <label class="col-sm-3 checkbox-inline">
                                <input type="checkbox" name="boom" value="boom" <?php if(stripslashes($info['boom']) == "boom"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Boom
                            </label>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-sm-7 nopad">
                                Are documented training records available for each model of equipment <br>
                            </div>
                            <div class="col-sm-5 nopad">
                                <label class="checkbox-inline">
                                    <input type="radio" name="documented_training_1" value="yes" <?php if(stripslashes($info['documented_training_1']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="documented_training_1" value="no" <?php if(stripslashes($info['documented_training_1']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "SCAFFOLDS" Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12">
                            <strong>SCAFFOLDS</strong>
                            &nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="scaffolds" value="yes" <?php if(stripslashes($info['scaffolds']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="scaffolds" value="no" <?php if(stripslashes($info['scaffolds']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="inspection" value="inspection" <?php if(stripslashes($info['inspection']) == "inspection"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Inspection performed by onsite safety or other designated  competent person
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="capacity" value="capacity" <?php if(stripslashes($info['capacity']) == "capacity"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Capacities Known
                            </label>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "CRANE WORK" Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12" style="">
                            <strong>CRANE WORK</strong> &nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="crane_work" value="yes" <?php if(stripslashes($info['crane_work']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="crane_work" value="no" <?php if(stripslashes($info['crane_work']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="crane_pick" value="crane_pick" <?php if(stripslashes($info['crane_pick']) == "crane_pick"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Approved crane pick plan
                            </label>
                        </div>&nbsp;
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="barricade_erect" value="barricade_erect" <?php if(stripslashes($info['barricade_erect']) == "barricade_erect"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Barricade erected to include entire travel path of load
                            </label>
                        </div>&nbsp;
                        <div class="col-sm-2">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="traffic" value="traffic" <?php if(stripslashes($info['traffic']) == "traffic"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Traffic control
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <i>All employees within the fly zone must have documented training in rigging and signaling</i>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "LO/TO" Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12">
                            <strong>LO/TO</strong>&nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="lo_to" value="yes" <?php if(stripslashes($info['lo_to']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="lo_to" value="no" <?php if(stripslashes($info['lo_to']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span class="col-sm-4 nopad">Are documented training records available</span>
                            <div class="col-sm-3">
                                <label class="checkbox-inline">
                                    <input type="radio" name="records_available" <?php if(stripslashes($info['records_available']) == "yes"){ echo "checked='true'"; } ?> value="yes" style="margin-top: 1px; margin-right: 2px;"> Yes
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="records_available" <?php if(stripslashes($info['records_available']) == "no"){ echo "checked='true'"; } ?> value="no" style="margin-top: 1px; margin-right: 2px;"> No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "HOTWORK" Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12">
                            <strong>HOTWORK</strong>
                            &nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="hotwork" value="yes" <?php if(stripslashes($info['hotwork']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="hotwork" value="no" <?php if(stripslashes($info['hotwork']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                     <div class="form-group">
                        <div class="col-sm-12">
                            <div class="col-sm-3 nopad">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="permit_required" value="permit_required" <?php if(stripslashes($info['permit_required']) == "permit_required"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Permit Required
                                </label>
                            </div>
                            <div class="col-sm-3">
                                <label class="checkbox-inline">
                                    <input type="radio" name="permit_check" value="yes" <?php if(stripslashes($info['permit_check']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                                </label>
                            </div>
                            <div class="col-sm-3">
                                <label class="checkbox-inline">
                                    <input type="radio" name="permit_check" value="N/A" <?php if(stripslashes($info['permit_check']) == "N/A"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> N/A
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <i>Follow instructions on permit and all policies and procedures. </i>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: "CONFINED SPACES" Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12">
                            <strong>CONFINED SPACES</strong>
                            &nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="confined_space" value="yes" <?php if(stripslashes($info['confined_space']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="confined_space" value="no" <?php if(stripslashes($info['confined_space']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <span class="col-sm-4">Are documented training records available</span>
                        <div class="col-sm-8">
                            <label class="checkbox-inline">
                                <input type="radio" name="documented_training" value="yes" <?php if(stripslashes($info['documented_training']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="documented_training" value="no" <?php if(stripslashes($info['documented_training']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>

                    <div class="form-group" >
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="permit_completed" value="permit_completed" <?php if(stripslashes($info['permit_completed']) == "permit_completed"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Permit Completed(Required)
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="multi_gas" value="multi_gas" <?php if(stripslashes($info['multi_gas']) == "multi_gas"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Multi gas detector(Required)
                            </label>
                        </div>
                    </div>

                    <div class="form-group" >
                        <div class="col-sm-12 text-center">
                            <i>Permit must be returned to safety dept. upon completion of work</i>
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <!--Strats: " PPE " Div-->
                <div class="col-sm-12">
                    <div class="form-group" style="text-align:left;background: #D6D6D6;padding:5px 0px;">
                        <div class="col-sm-12">
                            <strong> PPE </strong>
                            &nbsp;&nbsp;
                            <label class="checkbox-inline">
                                <input type="radio" name="ppe" value="yes" <?php if(stripslashes($info['ppe']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                            <label class="checkbox-inline">
                                <input type="radio" name="ppe" value="no" <?php if(stripslashes($info['ppe']) == "no"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <span>Standard PPE includes hard hat, safety glasses, gloves, safety toe boots</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="standard_ppe" value="standard_ppe" <?php if(stripslashes($info['standard_ppe']) == "standard_ppe"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;">
                                Standard PPE
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="ppe_yes" value="yes" <?php if(stripslashes($info['ppe_yes']) == "yes"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Yes
                            </label>
                        </div>
                    </div>

                    <div class="form-group" >
                        <div class="col-sm-2">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="special" value="special" <?php if(stripslashes($info['special']) == "special"){ echo "checked='true'"; } ?> style="margin-top: 1px; margin-right: 2px;"> Special
                            </label>
                        </div>
                        <span class="col-sm-2">Please List</span>
                        <div class="col-sm-8">
                            <input type="text" name="please_list" id="please_list" class="form-control" value="<?php echo $info['please_list'];?>" >
                        </div>
                    </div>
                </div>
                <!--End: Div-->

                <div class="clr"><br></div>
                <div class="col-sm-12 nopad" style="margin:15px; margin-top:0px;">
                    <div class="form-group">
                        <div style="margin-left:15px">Additional Comments:</div><br>
                        <div class="col-sm-12" style="margin-right:15px">
                            <textarea name="additional_comm" id="additional_comm" class="form-control" rows="5" cols="10" style="width:95%;"><?php echo $info['additional_comm']; ?></textarea>
                        </div>
                    </div>
                </div>

                <div><h3 class="ttext" style="margin-bottom: 10px;">PRE-TASK PLANNING</h3></div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-4" style="text-align:center">
                        <div class="form-group" style="margin:10px 0;">
                            <label>
                                <span class="en">Sequence of Basic Jobs Step</span>
                            </label>
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_1" id="seq_1" class="form-control" value="<?php echo $info['seq_1'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_2" id="seq_2" class="form-control" value="<?php echo $info['seq_2'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_3" id="seq_3" class="form-control" value="<?php echo $info['seq_3'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_4" id="seq_4" class="form-control" value="<?php echo $info['seq_4'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_5" id="seq_5" class="form-control" value="<?php echo $info['seq_5'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_6" id="seq_6" class="form-control" value="<?php echo $info['seq_6'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_7" id="seq_7" class="form-control" value="<?php echo $info['seq_7'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_8" id="seq_8" class="form-control" value="<?php echo $info['seq_8'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_9" id="seq_9" class="form-control" value="<?php echo $info['seq_9'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_10" id="seq_10" class="form-control" value="<?php echo $info['seq_10'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_11" id="seq_11" class="form-control" value="<?php echo $info['seq_11'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_12" id="seq_12" class="form-control" value="<?php echo $info['seq_12'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_13" id="seq_13" class="form-control" value="<?php echo $info['seq_13'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_14" id="seq_14" class="form-control" value="<?php echo $info['seq_14'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_15" id="seq_15" class="form-control" value="<?php echo $info['seq_15'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_16" id="seq_16" class="form-control" value="<?php echo $info['seq_16'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_17" id="seq_17" class="form-control" value="<?php echo $info['seq_17'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="seq_18" id="seq_18" class="form-control" value="<?php echo $info['seq_18'];?>">
                        </div>
                    </div>
                    <div class="col-sm-4" style="text-align:center">
                        <div class="form-group" style="margin:10px 0;">
                            <label>
                                <span class="en">Hazards Involved</span>
                                <span class="sp" style="display: none;"> </span>

                            </label>
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_1" id="hazard_1" class="form-control" value="<?php echo $info['hazard_1'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_2" id="hazard_2" class="form-control" value="<?php echo $info['hazard_2'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_3" id="hazard_3" class="form-control" value="<?php echo $info['hazard_3'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_4" id="hazard_4" class="form-control" value="<?php echo $info['hazard_4'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_5" id="hazard_5" class="form-control" value="<?php echo $info['hazard_5'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_6" id="hazard_6" class="form-control" value="<?php echo $info['hazard_6'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_7" id="hazard_7" class="form-control" value="<?php echo $info['hazard_7'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_8" id="hazard_8" class="form-control" value="<?php echo $info['hazard_8'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_9" id="hazard_9" class="form-control" value="<?php echo $info['hazard_9'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_10" id="hazard_10" class="form-control" value="<?php echo $info['hazard_10'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_11" id="hazard_11" class="form-control" value="<?php echo $info['hazard_11'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_12" id="hazard_12" class="form-control" value="<?php echo $info['hazard_12'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_13" id="hazard_13" class="form-control" value="<?php echo $info['hazard_13'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_14" id="hazard_14" class="form-control" value="<?php echo $info['hazard_14'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_15" id="hazard_15" class="form-control" value="<?php echo $info['hazard_15'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_16" id="hazard_16" class="form-control" value="<?php echo $info['hazard_16'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_17" id="hazard_17" class="form-control" value="<?php echo $info['hazard_17'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="hazard_18" id="hazard_18" class="form-control" value="<?php echo $info['hazard_18'];?>">
                        </div>
                    </div>
                    <div class="col-sm-4" style="text-align:center">
                        <div class="form-group" style="margin:10px 0;">
                            <label>
                                <span class="en">Methods to Eliminate/Control Hazards</span>
                                <span class="sp" style="display: none;"> </span>

                            </label>
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_1" id="method_1" class="form-control" value="<?php echo $info['method_1'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_2" id="method_2" class="form-control" value="<?php echo $info['method_2'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_3" id="method_3" class="form-control" value="<?php echo $info['method_3'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_4" id="method_4" class="form-control" value="<?php echo $info['method_4'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_5" id="method_5" class="form-control" value="<?php echo $info['method_5'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_6" id="method_6" class="form-control" value="<?php echo $info['method_6'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_7" id="method_7" class="form-control" value="<?php echo $info['method_7'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_8" id="method_8" class="form-control" value="<?php echo $info['method_8'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_9" id="method_9" class="form-control" value="<?php echo $info['method_9'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_10" id="method_10" class="form-control" value="<?php echo $info['method_10'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_11" id="method_11" class="form-control" value="<?php echo $info['method_11'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_12" id="method_12" class="form-control" value="<?php echo $info['method_12'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_13" id="method_13" class="form-control" value="<?php echo $info['method_13'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_14" id="method_14" class="form-control" value="<?php echo $info['method_14'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_15" id="method_15" class="form-control" value="<?php echo $info['method_15'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_16" id="method_16" class="form-control" value="<?php echo $info['method_16'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_17" id="method_17" class="form-control" value="<?php echo $info['method_17'];?>">
                        </div>
                        <div class="form-group" style="margin:10px 0;">
                            <input type="text" name="method_18" id="method_18" class="form-control" value="<?php echo $info['method_18'];?>">
                        </div>
                    </div>

                </div>

                <div class="col-sm-12 nopad" style="border: 2px solid black;margin-top:30px">
                    <div class="form-group" style="margin-bottom:0px">
                        <div class="col-sm-4" style="border-right:1px solid black ">
                            <label>
                                <span class="en">Crew Members Name:</span>
                                <span class="sp" style="display: none;"> </span>

                            </label>
                        </div>
                        <div class="col-sm-4" style="">
                            <div class="col-sm-4" style="border-right:1px solid black">
                                <label>
                                    <span class="en">Mon</span>
                                    <span class="sp" style="display: none;"> </span>

                                </label>
                            </div>
                            <div class="col-sm-4" style="border-right:1px solid black">
                                <label>
                                    <span class="en">Tue</span>
                                    <span class="sp" style="display: none;"> </span>

                                </label>
                            </div>
                            <div class="col-sm-4" style="border-right:1px solid black">
                                <label>
                                    <span class="en">Wed</span>
                                    <span class="sp" style="display: none;"> </span>

                                </label>
                            </div>
                        </div>
                        <div class="col-sm-4" style="">
                            <div class="col-sm-3" style="border-right:1px solid black">
                                <label>
                                    <span class="en">Thu</span>
                                    <span class="sp" style="display: none;"> </span>

                                </label>
                            </div>
                            <div class="col-sm-3" style="border-right:1px solid black">
                                <label>
                                    <span class="en">Fri</span>
                                    <span class="sp" style="display: none;"> </span>

                                </label>
                            </div>
                            <div class="col-sm-3" style="border-right:1px solid black">
                                <label>
                                    <span class="en">Sat</span>
                                    <span class="sp" style="display: none;"> </span>

                                </label>
                            </div>
                            <div class="col-sm-3" style="">
                                <label>
                                    <span class="en">Sun</span>
                                    <span class="sp" style="display: none;"> </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div  class="col-sm-12 nopad" style="border: 2px solid black;">
                    <div class="form-group" style="margin-bottom:0px">
                        <div class="col-sm-12">
                            <label>
                                <p>"I acknowledge that I will will comply with the safety procedures outlined above and understand that failure to do so could result in injury and is grounds for disciplinary action.
                                 I further acknowledge that I reported to work Fit For Duty today and that I am leaving work injury free.  If I was involved in an incident, I will report it to my Foreman immediately."
                                 </p>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="clr"><br></div>

                
                <?php
                $loop = isset($info['counter'])?$info['counter']:1;
                for($i=1; $i <= $loop; $i++){
                ?>
                <div class="col-sm-12 nopad">
                    <div class="form-group" style="margin:10px 0;">
                        <div class="col-sm-4">
                            <label>Print Crew Members Name</label>
                            <input type="text" name="crew_<?php echo $i; ?>" id="crew_<?php echo $i; ?>" class="form-control" value="<?php echo $info['crew_'.$i];?>">
                        </div>
                        <div class="col-sm-8">
                            <label>&nbsp;</label>
                            <div id="crew_sign_<?php echo $i; ?>">
                                <div  class="sig sigWrapper current" style="cursor:crosshair;width:585px;height: 130px; overflow: hidden;">
                                    <div style="display: none;" class="typed"></div>
                                    <canvas class="pad" width="585" height="130"></canvas>
                                    <input name="crew_sign_<?php echo $i; ?>" id="crew_sign_<?php echo $i; ?>" value="" class="output" type="hidden">
                                </div>
                                <a href="#clear" class="clearButton">Clear signature</a>
                            </div>
                        </div>
                    </div>

                </div>
                <?php
                }
                ?>
                <div id="items">
                </div>
				
                
                <div class="col-sm-12">
                <button id="add" class="add btn btn-md btn-success">
                        <span class="glyphicon glyphicon-plus"></span> Add More
                    </button>
                    <input type="hidden" id="counter" name="counter" value="<?php echo !empty($info['counter'])?$info['counter']:'1'; ?>">
                </div>

                <div class="clr"><br></div>
                <div class="col-sm-12 nopad" style="margin-top:10px">
                    <div class="col-sm-4">
                        <label class="control-label">
                            <span class="en">Foreman Signature:</span>
                            <span class="sp" style="display: none;"></span>
                            <span class="error">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <div id="sign_ipad1">
                            <div  class="sig sigWrapper current" style="cursor:crosshair;width:585px;height: 130px; overflow: hidden;">
                                <div style="display: none;" class="typed"></div>
                                <canvas class="pad" width="585" height="130"></canvas>
                                <input name="foreman_sign" id="foreman_sign" value="" class="output" type="hidden">
                            </div>
                            <a href="#clear" class="clearButton">Clear signature</a>
                        </div>
                    </div>
                </div>
                <div class="clr"><br></div>

                <div class="col-sm-12 nopad">
                    <div class="col-sm-4">
                        <label class="control-label">
                            <span class="en">Safety Signature:</span>
                            <span class="sp" style="display: none;"></span>
                            <span class="error">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <div id="sign_ipad2">
                            <div  class="sig sigWrapper current" style="cursor:crosshair;width:585px;height: 130px; overflow: hidden;">
                                <div style="display: none;" class="typed"></div>
                                <canvas class="pad" width="585" height="130"></canvas>
                                <input name="safety_sign" id="safety_sign" value="" class="output" type="hidden">
                            </div>
                            <a href="#clear" class="clearButton">Clear signature</a>
                        </div>
                    </div>
                </div>
                <div class="clr"><br></div>
            </div>

            <div class="col-sm-12 ">
                <div class="col-sm-3 row">                  
                    <?php if(isset($info)){ ?>
					<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
					<?php } ?>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='/portal/'">Cancel</button>
                    &nbsp;
                    <input type="submit" name="save" class="btn btn-primary" value="<?php echo (isset($_GET['id']) && !empty($_GET['id']))?'Update':'Submit';?>">
                </div>
            </div>

            <div class="clr"><br></div>
        </fieldset>
    </form>
</div>

<script src="/js/jquery.signaturepad.js"></script>
<script>
$(document).ready(function () {
    $("#pre-task").validate({
        rules: {
            project: "required",
            foreman: "required",
            cell: "required",
            specific_location: "required",
            date: "required",
            division: "required",
        },
    });

    <?php
    $loop = isset($info['counter'])?$info['counter']:1;
    for($i=1; $i <= $loop; $i++){
        if(isset($info['crew_sign_'.$i]) && trim($info['crew_sign_'.$i])!=''){
    ?>
    $('#crew_sign_<?=$i?>').signaturePad({drawOnly:true,validateFields:false, lineWidth :0}).regenerate('<?php echo $info['crew_sign_'.$i] ?>');
    <?php
        }else{
    ?>
    $('#crew_sign_<?=$i?>').signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
    <?php
        }
    }   
    ?>  
    
    var counter = $('#counter').val();
    $('#add').on('click',function(e){

        e.preventDefault();
        counter++;

         $("#items").append(
            '<div class="col-sm-12 nopad" id="trow_'+counter+'"><div class="form-group" style="margin:10px 0;"><div class="col-sm-4"><label>Print Crew Members Name</label><input type="text" name="crew_'+counter+'" id="crew_'+counter+'" class="form-control" value=""></div><div class="col-sm-8"><label>&nbsp;</label><div id="crew_sign_'+counter+'"><div  class="sig sigWrapper current" style="cursor:crosshair;width:585px;height: 130px; overflow: hidden;"><div style="display: none;" class="typed"></div><canvas class="pad" width="585" height="130"></canvas><input name="crew_sign_'+counter+'" id="crew_sign_'+counter+'" value="" class="output" type="hidden"></div><a href="#clear" class="clearButton">Clear signature</a></div></div></div><div class="col-sm-12"><button type="button" id="remove'+counter+'" class="remove btn btn-md btn-danger glyphicon glyphicon-minus" onclick="removeSign('+counter+');"></button></div><div class="clear"></div></br></div>'
        )
        
        $('#crew_sign_'+counter).signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
        
        console.log("#remove"+counter);
        /*$("#remove"+counter).on('click',function(e){
            console.log("#remove"+counter);
            e.preventDefault();
            $("#trow_"+counter).remove();
            counter--;
        });*/
        $('#counter').val(counter);
    });
    
    <?php if(isset($info['foreman_sign']) && trim($info['foreman_sign'])!=''){ ?>
        $('#sign_ipad1').signaturePad({drawOnly:true,validateFields:false, lineWidth :0}).regenerate('<?php echo $info['foreman_sign'] ?>');
    <?php }else{ ?>
        $('#sign_ipad1').signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
    <?php } ?>
    <?php if(isset($info['safety_sign']) && trim($info['safety_sign'])!=''){ ?>
        $('#sign_ipad2').signaturePad({drawOnly:true,validateFields:false, lineWidth :0}).regenerate('<?php echo $info['safety_sign'] ?>');
    <?php }else{ ?>
        $('#sign_ipad2').signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
    <?php } ?>

    $( "#date" ).datetimepicker({
        lang:'en',
        timepicker:false,
        format:'m/d/Y',
        closeOnDateSelect: true,
        scrollInput: false,
    });
});

removeSign = function(objID){
    $("#trow_"+objID).remove();
}
</script>

<?php include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>