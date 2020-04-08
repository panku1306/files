<?php
include_once dirname(dirname(dirname(__FILE__))). '/_inc.php';

if(isset($_GET['id'])){
	$id = $_GET['id'];
}

if ($_POST) {
	
	$err = 0;
	while (list($index, $ob) = each($_POST)) {
		$info[$index] = ms($ob);
	}

    # File path
	$dir = dirname(dirname(dirname(__FILE__)))."/attachments/vehicle_claim_doc/";
	
	if (!$info['date_incident']) $err+=1;
	if (!$info['division']) $err+=2;
	if(!$info['job_number']) $err+=4;
	if(!$info['vehicle_no']) $err+=8;		
	if(!$info['vehicle_year']) $err+=16;
	if (!$info['veh_model']) $err+=32;
	if(!$info['veh_color']) $err+=64;
	if(!$info['license_pn']) $err+=128;		
	if(!$info['vin_no']) $err+=256;
	if(!$info['ed_name']) $err+=512;
	if(!$info['ed_home_ph']) $err+=1024;
	if(!$info['ed_cell_no']) $err+=2048;		
	if(!$info['ed_license_no']) $err+=4096;
	if(!$info['ed_dob']) $err+=8192;
		
	if (!$err) {
        
        # Upload Vehicle claim images 
        if(isset($_FILES['vclaim_images']['name'])){

            $files = $_FILES['vclaim_images']['name'];
            $images = array();
            $ext = array('jpeg','jpg','gif','png');

            #Loop through uploaded files
            foreach($files as $key=>$value){		  
                $temp = $_FILES['vclaim_images']['tmp_name'][$key];
                $file = pathinfo($files[$key]);
            
                #Check if file contains valid extension or not
                if(in_array($file['extension'],$ext)){
                    $file_name = $file['filename'].'_'.time().'.'.$file['extension'];
                    
                    #Check if file uploaded or not
                    if(move_uploaded_file($temp,$dir.$file_name)){
                        $images[].= $file_name; 
                    }
                }		  
            }
            $vclaim_images = implode(',',$images);		
        }
		
        # Fetch existing vehicle claim record
		$check_data = mysql_query("SELECT * FROM `vehicle_claim` WHERE `id`='" . $id . "'");
		if(!empty($vclaim_images)){
            $vclaim_images = ", `vclaim_images`='" . $vclaim_images . "' ";
        }else{
            $vclaim_images = " ";
        }
		if (mysql_num_rows($check_data) > 0) {
			$row_id = mysql_fetch_array($check_data);
			$row_id_val = $row_id['id'];

			$insrt_det = "UPDATE `vehicle_claim` SET
                `date_incident`='" .date('Y-m-d',strtotime($info['date_incident']))."',
                `division`='" . $info['division']. "',
                `job_number`='" . $info['job_number']. "',
                `vehicle_no`='" . $info['vehicle_no'] ."',
                `vehicle_year`='" . $info['vehicle_year'] . "',
                `veh_model`='" . $info['veh_model'] . "',
                `veh_color`='" . $info['veh_color'] . "',
                `license_pn`='" . $info['license_pn'] . "',
                `vin_no`='" . $info['vin_no'] . "',
                `project`='" . $info['project'] . "',
                `foreman_email`='" . $info['foreman_email'] . "',
                `ed_name`='" . $info['ed_name'] . "',
                `ed_address`='" . $info['ed_address'] . "',
                `ed_home_ph`='" . $info['ed_home_ph'] . "',
                `ed_cell_no`='" . $info['ed_cell_no'] . "',
                `ed_license_no`='" . $info['ed_license_no'] . "',
                `ed_dob`='" . $info['ed_dob'] . "',
                `ed_passenger`='" . $info['ed_passenger'] . "',
                `ed_passenger_rel`='" . $info['ed_passenger_rel'] . "',
                `oth_driver_name`='" . $info['oth_driver_name'] . "',
                `oth_dri_addr`='" . $info['oth_dri_addr'] . "',
                `oth_dri_ph`='" . $info['oth_dri_ph'] . "',
                `veh_owner_name`='" . $info['veh_owner_name'] . "',
                `owner_add`='" . $info['owner_add'] . "',
                `owner_ph`='" . $info['owner_ph'] . "',
                `oth_veh_year`='" . $info['oth_veh_year'] . "',
                `oth_veh_model`='" . $info['oth_veh_model'] . "',
                `oth_veh_color`='" . $info['oth_veh_color'] . "',
                `oth_veh_license`='" . $info['oth_veh_license'] . "',
                `oth_veh_vin`='" . $info['oth_veh_vin'] . "',
                `oth_driver_insco`='" . $info['oth_driver_insco'] . "',
                `oth_insco_ph`='" . $info['oth_insco_ph'] . "',
                `oth_ins_pollicy`='" . $info['oth_ins_pollicy'] . "',
                `oth_passenger`='" . $info['oth_passenger'] . "',
                `oth_passenger_rel`='" . $info['oth_passenger_rel'] . "',
                `3_driver_name`='" . $info['3_driver_name'] . "',
                `3_driver_add`='" . $info['3_driver_add'] . "',
                `3_driver_ph`='" . $info['3_driver_ph'] . "',
                `3_veh_owner`='" . $info['3_veh_owner'] . "',
                `3_owner_add`='" . $info['3_owner_add'] . "',
                `3_owner_ph`='" . $info['3_owner_ph'] . "',
                `3_veh_year`='" . $info['3_veh_year'] . "',
                `3_veh_model`='" . $info['3_veh_model'] . "',
                `3_veh_color`='" . $info['3_veh_color'] . "',
                `3_veh_license`='" . $info['3_veh_license'] . "',
                `3_veh_vinno`='" . $info['3_veh_vinno'] . "',
                `3_driver_insco`='" . $info['3_driver_insco'] . "',
                `3_insco_ph`='" . $info['3_insco_ph'] . "',
                `3_ins_policy`='" . $info['3_ins_policy'] . "',
                `3_passenger`='" . $info['3_passenger'] . "',
                `3_passenger_rel`='" . $info['3_passenger_rel'] . "',
                `officer_name`='" . $info['officer_name'] . "',
                `headquarter`='" . $info['headquarter'] . "',
                `badge_no`='" . $info['badge_no'] . "',
                `prn`='" . $info['prn'] . "',
                `time_incident`='" .date('Y-m-d H:i:s', strtotime($info['time_incident']))."',
                `weather`='" . $info['weather'] . "',
                `location`='" . $info['location'] . "',
                `kc_pavement`='" . $info['kc_pavement'] . "',
                `dir_si_travel`='" . $info['dir_si_travel'] . "',
                `si_dri_speed`='" . $info['si_dri_speed'] . "',
                `dir_oth_travel`='" . $info['dir_oth_travel'] . "',
                `oth_veh_speed`='" . $info['oth_veh_speed'] . "',
                `dir_3veh_travel`='" . $info['dir_3veh_travel'] . "',
                `3_veh_speed`='" . $info['3_veh_speed'] . "',
                `desc_incident`='" . $info['desc_incident'] . "',
                `desc_damage_veh`='" . $info['desc_damage_veh'] . "',
                `safy_drivable`='" . $info['safy_drivable'] . "',
                `towed`='" . $info['towed'] . "',
                `veh_towed_by`='" . $info['veh_towed_by'] . "',
                `veh_towed_to`='" . $info['veh_towed_to'] . "',
                `desc_damage_othveh`='" . $info['desc_damage_othveh'] . "',
                `desc_damage_3veh`='" . $info['desc_damage_3veh'] . "',
                `pi_name`='" . $info['pi_name'] . "',
                `pi_add`='" . $info['pi_add'] . "',
                `pi_ph`='" . $info['pi_ph'] . "',
                `injury`='" . $info['injury'] . "',
                `pi_2_name`='" . $info['pi_2_name'] . "',
                `pi_2_add`='" . $info['pi_2_add'] . "',
                `pi_2_ph`='" . $info['pi_2_ph'] . "',
                `injury_2`='" . $info['injury_2'] . "',
                `pi_3_name`='" . $info['pi_3_name'] . "',
                `pi_3_add`='" . $info['pi_3_add'] . "',
                `pi_3_ph`='" . $info['pi_3_ph'] . "',
                `injury_3`='" . $info['injury_3'] . "',
                `wit_name`='" . $info['wit_name'] . "',
                `wit_address`='" . $info['wit_address'] . "',
                `wit_phone`='" . $info['wit_phone'] . "',
                `wit_2_name`='" . $info['wit_2_name'] . "',
                `wit_2_address`='" . $info['wit_2_address'] . "',
                `wit_2_phone`='" . $info['wit_2_phone'] . "',
                `wit_3_name`='" . $info['wit_3_name'] . "',
                `wit_3_address`='" . $info['wit_3_address'] . "',
                `wit_3_phone`='" . $info['wit_3_phone'] . "', 
                `signature`='" . $info['signature'] . "' 
                $vclaim_images 	
                WHERE `id`='" . $id . "'";
			
			$stat = mysql_query($insrt_det);
		} 
		else {
			$insrt_det = "INSERT INTO `vehicle_claim` SET
                `date_incident`='" .date('Y-m-d',strtotime($info['date_incident']))."',
                `division`='" . $info['division']. "',
                `job_number`='" . $info['job_number']. "',
                `vehicle_no`='" . $info['vehicle_no'] ."',
                `vehicle_year`='" . $info['vehicle_year'] . "',
                `veh_model`='" . $info['veh_model'] . "',
                `veh_color`='" . $info['veh_color'] . "',
                `license_pn`='" . $info['license_pn'] . "',
                `vin_no`='" . $info['vin_no'] . "',
                `project`='" . $info['project'] . "',
                `foreman_email`='" . $info['foreman_email'] . "',
                `ed_name`='" . $info['ed_name'] . "',
                `ed_address`='" . $info['ed_address'] . "',
                `ed_home_ph`='" . $info['ed_home_ph'] . "',
                `ed_cell_no`='" . $info['ed_cell_no'] . "',
                `ed_license_no`='" . $info['ed_license_no'] . "',
                `ed_dob`='" . $info['ed_dob'] . "',
                `ed_passenger`='" . $info['ed_passenger'] . "',
                `ed_passenger_rel`='" . $info['ed_passenger_rel'] . "',
                `oth_driver_name`='" . $info['oth_driver_name'] . "',
                `oth_dri_addr`='" . $info['oth_dri_addr'] . "',
                `oth_dri_ph`='" . $info['oth_dri_ph'] . "',
                `veh_owner_name`='" . $info['veh_owner_name'] . "',
                `owner_add`='" . $info['owner_add'] . "',
                `owner_ph`='" . $info['owner_ph'] . "',
                `oth_veh_year`='" . $info['oth_veh_year'] . "',
                `oth_veh_model`='" . $info['oth_veh_model'] . "',
                `oth_veh_color`='" . $info['oth_veh_color'] . "',
                `oth_veh_license`='" . $info['oth_veh_license'] . "',
                `oth_veh_vin`='" . $info['oth_veh_vin'] . "',
                `oth_driver_insco`='" . $info['oth_driver_insco'] . "',
                `oth_insco_ph`='" . $info['oth_insco_ph'] . "',
                `oth_ins_pollicy`='" . $info['oth_ins_pollicy'] . "',
                `oth_passenger`='" . $info['oth_passenger'] . "',
                `oth_passenger_rel`='" . $info['oth_passenger_rel'] . "',
                `3_driver_name`='" . $info['3_driver_name'] . "',
                `3_driver_add`='" . $info['3_driver_add'] . "',
                `3_driver_ph`='" . $info['3_driver_ph'] . "',
                `3_veh_owner`='" . $info['3_veh_owner'] . "',
                `3_owner_add`='" . $info['3_owner_add'] . "',
                `3_owner_ph`='" . $info['3_owner_ph'] . "',
                `3_veh_year`='" . $info['3_veh_year'] . "',
                `3_veh_model`='" . $info['3_veh_model'] . "',
                `3_veh_color`='" . $info['3_veh_color'] . "',
                `3_veh_license`='" . $info['3_veh_license'] . "',
                `3_veh_vinno`='" . $info['3_veh_vinno'] . "',
                `3_driver_insco`='" . $info['3_driver_insco'] . "',
                `3_insco_ph`='" . $info['3_insco_ph'] . "',
                `3_ins_policy`='" . $info['3_ins_policy'] . "',
                `3_passenger`='" . $info['3_passenger'] . "',
                `3_passenger_rel`='" . $info['3_passenger_rel'] . "',
                `officer_name`='" . $info['officer_name'] . "',
                `headquarter`='" . $info['headquarter'] . "',
                `badge_no`='" . $info['badge_no'] . "',
                `prn`='" . $info['prn'] . "',
                `time_incident`='" .date('Y-m-d H:i:s', strtotime($info['time_incident']))."',
                `weather`='" . $info['weather'] . "',
                `location`='" . $info['location'] . "',
                `kc_pavement`='" . $info['kc_pavement'] . "',
                `dir_si_travel`='" . $info['dir_si_travel'] . "',
                `si_dri_speed`='" . $info['si_dri_speed'] . "',
                `dir_oth_travel`='" . $info['dir_oth_travel'] . "',
                `oth_veh_speed`='" . $info['oth_veh_speed'] . "',
                `dir_3veh_travel`='" . $info['dir_3veh_travel'] . "',
                `3_veh_speed`='" . $info['3_veh_speed'] . "',
                `desc_incident`='" . $info['desc_incident'] . "',
                `desc_damage_veh`='" . $info['desc_damage_veh'] . "',
                `safy_drivable`='" . $info['safy_drivable'] . "',
                `towed`='" . $info['towed'] . "',
                `veh_towed_by`='" . $info['veh_towed_by'] . "',
                `veh_towed_to`='" . $info['veh_towed_to'] . "',
                `desc_damage_othveh`='" . $info['desc_damage_othveh'] . "',
                `desc_damage_3veh`='" . $info['desc_damage_3veh'] . "',
                `pi_name`='" . $info['pi_name'] . "',
                `pi_add`='" . $info['pi_add'] . "',
                `pi_ph`='" . $info['pi_ph'] . "',
                `injury`='" . $info['injury'] . "',
                `pi_2_name`='" . $info['pi_2_name'] . "',
                `pi_2_add`='" . $info['pi_2_add'] . "',
                `pi_2_ph`='" . $info['pi_2_ph'] . "',
                `injury_2`='" . $info['injury_2'] . "',
                `pi_3_name`='" . $info['pi_3_name'] . "',
                `pi_3_add`='" . $info['pi_3_add'] . "',
                `pi_3_ph`='" . $info['pi_3_ph'] . "',
                `injury_3`='" . $info['injury_3'] . "',
                `wit_name`='" . $info['wit_name'] . "',
                `wit_address`='" . $info['wit_address'] . "',
                `wit_phone`='" . $info['wit_phone'] . "',
                `wit_2_name`='" . $info['wit_2_name'] . "',
                `wit_2_address`='" . $info['wit_2_address'] . "',
                `wit_2_phone`='" . $info['wit_2_phone'] . "',
                `wit_3_name`='" . $info['wit_3_name'] . "',
                `wit_3_address`='" . $info['wit_3_address'] . "',
                `wit_3_phone`='" . $info['wit_3_phone'] . "',
                `signature`='" . $info['signature'] . "' "
                . $vclaim_images ;
			
			$stat =  mysql_query($insrt_det);
			$row_id_val = mysql_insert_id();
		}
		
		if($stat){
			# Send email
			require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');				
			$mail = new NextCodeMailer();
								
			$url = $base_url.'/html2pdf_v4.03/examples/vehicleclaims_doc.php?id=' . $row_id_val;
			$binary_content = file_get_contents($url);
							
			$mail->From = 'noreply@nextcode.info';
			$mail->FromName = 'NextCode.Info';			
			
			
            
            if(isset($_POST['foreman_email']) && !empty($_POST['foreman_email'])){
                $mail->addAddress($_POST['foreman_email']);
            }
			
			$mail->AddBCC('si-notifications@nextcode.info');
			# $mail->AddBCC('pankaj1983samal@gmail.com');

			$mail->isHTML(true);# Set email format to HTML
			$mail->Subject = 'Southland - Vehicle Claims';
			$mail->Body = 'There should be a PDF attached to this message with your info for vehicle claims. Check it out!';
			$mail->AltBody = 'There should be a PDF attached to this message with your info for vehicle claims. Check it out!';
			$mail->AddStringAttachment($binary_content, "vehicleclaims_doc.pdf", 'base64', 'application/pdf');
							
			# $mail must have been created		
			if($mail->send()) {
				$_SESSION['success_msg'] = "Vehicle Claims details has been sent to user email.";		
			}
			else{
				$_SESSION['error_msg'] = "Sorry, mail couldn't be send. Contact Admin!";
			}

            header('Location:/portal/safety_meeting/vehicleclaims.php');
            exit;	
		}else{
			$_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";
		}
	}
}

$query = "SELECT * FROM vehicle_claim WHERE id = '" . $id . "'";
$result = mysql_query($query);

while ($ob = mysql_fetch_array($result)) {
	$info= $ob;	
}

/* Division info */
$query_div = "SELECT * FROM divisions WHERE client = $client AND active = '1'";
$result_div = mysql_query($query_div);
while ($ob_div = mysql_fetch_object($result_div)) {
    $divisions[$ob_div->id] = $ob_div;
}
?>
<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div id="frame">
	<form class="form-horizontal" id="form_val" method="post" action="" name="form_val" enctype="multipart/form-data">
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
		
		<div class="col-sm-12 row"  style="padding:0 30px;">					
			<div class="pull-left" style="margin-right:5px;width: 100%;">
				<h3 class="ttext">VEHICLE CLAIM</h3>
				<p>If you are involved in a vehicle collision, contact your <b>division safety representative <i>immediately</i></b>.  Please complete this form <br>at the scene of the collision. Send the completed form to your division safety representative and to Pat Parra in the Corporate<br> Safety Department.  If Pat Parra is not available, please contact Reyann Contreras.  </p>
				<p><b>Primary Contact:  Division Safety Representative</b><br>
				<b>Secondary Contact:  Pat Parra,</b> Corporate Safety Coordinator</p>
			</div>
			<div style="float: left;margin-left:16%;margin-top: -9px;">
				<a><u>pparra@southlandind.com</u></a><br>	
				Phone:&nbsp;714) 901-5800, Ext 7154<br>						
				Cell:&nbsp;949) 892-9027<br>						
				Fax:&nbsp;714) 908-3314<br>				
			</div>
		</div>
	
		<div class="col-sm-12 row"  style="padding:0 30px;">
			<div class="pull-left" style="margin-right:5px;width: 100%;">
				<label style="font-size: 15px;font-weight: bold;">
					<span class="en"></span> <span class="en"><u>Instructions</u></span>
					<span class="sp" style="display: none;"> </span>
					<span class="error"></span>
				</label>
			</div>
			<div style="float: left;width:100%">
				<div style="float: left;width:100%">
					<span class="en" style="float: left;margin-right: 12px;"> 1. Stop at once!  Check for personal injuries and call or send for emergency personnel (Police, Fire Department, Ambulance). </span>
					<span class="sp" style="float: left;margin-right: 12px;display: none;"> </span>
				</div>
				<div style="float: left;width:100%">
					<span class="en"  style="float: left;margin-right: 12px;"> 2. Tactfully get the name and addresses of witnesses, using the space provided on this form. </span>
					<span class="sp" style="float: left;margin-right: 12px;display: none;"> </span>
				</div>
				<div style="float: left;width:100%">
					<span class="en"  style="float: left;margin-right: 12px;"> 3. Do not argue.  <b>Make no statement except to the proper authorities.  Sign nothing except the official police reports.</b> </span>
					<span class="sp" style="float: left;margin-right: 12px;display: none;"> </span>
				</div>
				<div style="float: left;width:100%">
					<span class="en"  style="float: left;margin-right: 12px;">4. Note all details and complete this report.  <b>Do not plead guilty to any charge with out consulting with your employer.</b> </span>
					<span class="sp" style="float: left;margin-right: 12px;display: none;"> </span>
				</div>
				<div style="float: left;width:100%">
					<span class="en"  style="float: left;margin-right: 12px;">5. When police arrive, get the name and badge number of the officers and note them on this report.  </span>
					<span class="sp" style="float: left;margin-right: 12px;display: none;"> </span>
				</div>
			</div>
		</div>
		
		<span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
		
		<legend class="col-sm-12 " style="font-size:16px;font-weight:bold;line-height:25px;margin-top:10px;">
			IN THE EVENT OF A SERIOUS INJURY, CONTACT YOUR DIVISION SAFETY REPRESENTATIVE OR THE CORPORATE SAFETY DEPARTMENT IMMEDIATELY!!!
		</legend><br/>
		<fieldset class="col-sm-12 row">	
			<div id="personal_edit" >
					
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Date of Incident</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error">*</span>
							  </label>
							  <div class="col-sm-6">
								<input type="text" name="date_incident" id="date_incident" class="form-control<?=$err&1?" error":""?>" value="<? if($info['date_incident'] != '') echo date('m/d/Y', strtotime($info['date_incident'])); ?>">
							  </div>
						 </div>
					 </div>					 
					 <div class="col-sm-7 nopad">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">Division/Job No. </span>
								<span class="sp" style="display: none;">Número de trabajo</span>
							</label>
							<div class="col-sm-5">
								<select name="division" class="form-control <?=$err&2?" error":""?>">
									<option <?php if ($info['division'] == '') { echo 'selected'; } ?> value="">All Division</option>
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
							 <div class="col-sm-3">
								<input type="text" name="job_number" id="job_number" class="form-control <?=$err&4?" error":""?>" value="<? if($info['job_number'] != '') echo $info['job_number']; ?>">
							 </div>
						  </div>
					  </div>	
				 </div>		
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Vehicle No</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-6">
							 <input type="text" name="vehicle_no" id="vehicle_no" class="form-control <?=$err&8?" error":""?>" value="<? if($info['vehicle_no'] != '') echo $info['vehicle_no']; ?>">
							</div>
						 </div>
					 </div>	 
					 <div class="col-sm-7 nopad">
						 <div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">Vehicle Year</span>
								<span class="sp" style="display: none;">Fecha</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-5">
							  <input type="text" name="vehicle_year" id="vehicle_year" class="form-control <?=$err&16?" error":""?>" value="<? if($info['vehicle_year'] != '') echo $info['vehicle_year']; ?>">
							</div>
						</div>
					</div>			
				</div>	
				
				<div class="col-sm-12 ">
				   <div class="col-sm-5 nopad">
					  <div class="form-group">
						 <label class="col-sm-5 control-label">
							<span class="en">Make/Model</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error">*</span>
						 </label>
						 <div class="col-sm-6">
						  <input type="text" name="veh_model" id="veh_model" class="form-control <?=$err&32?" error":""?>" value="<? if($info['veh_model'] != '') echo $info['veh_model']; ?>">
						</div>
					  </div>
					</div>
					<div class="col-sm-7 nopad">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">Vehicle Color </span>
								<span class="sp" style="display: none;">Fecha</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-5">
								<input type="text" name="veh_color" id="veh_color" class="form-control <?=$err&64?" error":""?>" value="<? if($info['veh_color'] != '') echo $info['veh_color']; ?>">
							</div>
					   </div>
				   </div>
				</div>  
					
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">License Plate No. </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-6">
								<input type="text" name="license_pn" id="license_pn" class="form-control <?=$err&128?" error":""?>" value="<? if($info['license_pn'] != '') echo $info['license_pn']; ?>">
							</div>
						</div>
					</div> 
					<div class="col-sm-7 nopad">
						<div class="form-group">  
							<label class="col-sm-4 control-label">
								<span class="en">Vin No</span>
								<span class="sp" style="display: none;">Fecha</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-5">
								<input type="text" name="vin_no" id="vin_no" class="form-control <?=$err&256?" error":""?>" value="<? if($info['vin_no'] != '') echo $info['vin_no']; ?>">
							</div>
						</div>
					</div>
				</div>

                <div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Jobsite. </span>
							</label>
							<div class="col-sm-6">
								<input type="text" name="project" id="project" class="form-control" value="<? if($info['project'] != '') echo $info['project']; ?>">
							</div>
						</div>
					</div> 
					<div class="clr"></div>
				</div>

                <div class="col-sm-12 ">
                    <div class="col-sm-5 nopad">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">
                                <span class="en">Foreman Email:</span>                               
                            </label>
                            <div class="col-sm-6">
                                <input type="email" name="foreman_email" id="foreman_email" class="form-control" value="<?php echo $info['foreman_email'];?>">
                            </div>
                        </div>
                    </div>
				</div>

				<!--start of Southland Employee Driver Information -->
				<div class="col-sm-12 ">
					<label><b><u>Southland Employee Driver Information </u></b></label>
				</div>
				
				<div class="col-sm-12 ">	
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Name</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-6">
								<input type="text" name="ed_name" id="ed_name" class="form-control <?=$err&512?" error":""?>" value="<? if($info['ed_name'] != '') echo $info['ed_name']; ?>">
							</div>
						</div> 
					</div>
				</div> 
				  
				<div class="col-sm-12 ">
					<div class="col-sm-9 nopad">
						<div class="form-group">
							<label class="col-sm-12 control-label">
								<span class="en">Address</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-11">
								<textarea name="ed_address" id="ed_address" class="form-control" rows="3" cols="20" style="width:100%"><?php if ($info['ed_address'] != '') echo $info['ed_address']; ?></textarea>
							</div>
						</div>
					</div>
				</div>  
			 
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
						   <label class="col-sm-5 control-label">
								<span class="en">Home Phone No </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error">*</span>
						   </label>
						   <div class="col-sm-6">
							  <input type="text" name="ed_home_ph" class="form-control <?=$err&1024?" error":""?>" id="ed_home_ph" class="form-control" value="<? if($info['ed_home_ph'] != '') echo $info['ed_home_ph']; ?>">
						   </div>
						</div>
					 </div>	
					<div class="col-sm-7 nopad">
						<div class="form-group">	
							<label class="col-sm-4 control-label">
								<span class="en">Work Cell No</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-5">
							   <input type="text" name="ed_cell_no" id="ed_cell_no" class="form-control <?=$err&2048?" error":""?>" value="<? if($info['ed_cell_no'] != '') echo $info['ed_cell_no']; ?>">
							</div>
						</div>
					</div>
				</div>  
		   
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Drivers License Number</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error">*</span>
						  </label>
						  <div class="col-sm-6">
							<input type="text" name="ed_license_no" id="ed_license_no" class="form-control <?=$err&4096?" error":""?>" value="<? if($info['ed_license_no'] != '') echo $info['ed_license_no']; ?>">
						  </div>
						</div>
					 </div>	
					 <div class="col-sm-7 nopad">
					   <div class="form-group">	
						<label class="col-sm-4 control-label">
							<span class="en">Date of Birth </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error">*</span>
						</label>
						<div class="col-sm-5">
						  <input type="text" name="ed_dob" class="form-control <?=$err&8192?" error":""?>" id="ed_dob" value="<? if($info['ed_dob'] != '') echo $info['ed_dob']; ?>">
					   </div>
					 </div>
				   </div> 
				</div> 
			 
				<div class="col-sm-12 ">
					<div class="col-sm-6 nopad">
					   <span style="float: left;margin-right: 12px;" class="en">Passenger(s)?  </span>
					   <span style="float: left;margin-right: 12px;display: none;" class="sp"> </span>
					   <label style="float: left;margin-right: 12px;">
						   <input type="radio" <?php if ($info['ed_passenger'] == '' || $info['ed_passenger'] == 'yes') { ?>checked="true"<?php } ?> style="float: left;" value="yes" id="ed_passenger1" name="ed_passenger"><span style="float: right;">&nbsp; Yes</span>
					   </label>
					   <label style="float: left;margin-right: 12px;">
						   <input type="radio" <?php if ($info['ed_passenger'] == '' || $info['ed_passenger'] == 'no') { ?>checked="true"<?php } ?>style="float: left;" value="no" id="ed_passenger2" name="ed_passenger">
						   <span style="float: right;">&nbsp; No</span>
						</label>
					</div>					
				</div>
						
				<div class="col-sm-12 ">	
					<div class="col-sm-9 nopad">
					   <div class="form-group">
						  <label class="col-sm-12 control-label">
								<span class="en">If yes, please list passenger(s) and relationship.</span>
								<span class="sp" style="display: none;"> </span>
						  </label>
						  <div class="col-sm-11">
							 <textarea name="ed_passenger_rel" class="form-control" id="ed_passenger_rel" rows="2" cols="20" style="width:100%"><?php if ($info['ed_passenger_rel'] != '') echo $info['ed_passenger_rel']; ?></textarea>
						  </div>
					   </div> 
					 </div>
				  </div>	   
					
					<!--end of Southland Employee Driver Information -->
				<!--start of Other Driver’s Information  -->
				
				<div class="col-sm-12 ">
					<label>
						<b><u>Other Driver’s Information </u></b>
					</label>
				</div>		
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
				          <label class="col-sm-5 control-label">
								<span class="en">Driver’s Name </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						  </label>
						   <div class="col-sm-6">
							   <input type="text" name="oth_driver_name" class="form-control" id="oth_driver_name" class="" value="<? if($info['oth_driver_name'] != '') echo $info['oth_driver_name']; ?>">
					       </div>
				      </div>
				    </div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-9 nopad">
					    <div class="form-group">
						   <label class="col-sm-12 control-label">
								<span class="en">Driver’s Address (include city and state)</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						   </label>
					       <div class="col-sm-11">
						      <textarea name="oth_dri_addr" class="form-control" id="oth_dri_addr" rows="3" cols="20" style="width:100%"><?php if ($info['oth_dri_addr'] != '') echo $info['oth_dri_addr']; ?></textarea>
					       </div>
					    </div>
					 </div>
				 </div>
				 
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					  <div class="form-group">
						<label class="col-sm-5 control-label">
							<span class="en">Driver’s Phone No.   </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						</label>
						<div class="col-sm-6">
						    <input type="text" name="oth_dri_ph" class="form-control" id="oth_dri_ph" class="" value="<? if($info['oth_dri_ph'] != '') echo $info['oth_dri_ph']; ?>">
					    </div>
				     </div>
				   </div>
			    </div>  
			    
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Vehicle Owner’s Name   </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
						    <input type="text" name="veh_owner_name" class="form-control" id="veh_owner_name" class="" value="<? if($info['veh_owner_name'] != '') echo $info['veh_owner_name']; ?>">
					      </div>
				        </div>	   
				      </div>
			      </div>
			        
			    <div class="col-sm-12 ">
					<div class="col-sm-9 nopad">
					   <div class="form-group">
							  <label class="col-sm-12 control-label">
								<span class="en">Owner’s Address (include city and state)  </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							  </label>
							 <div class="col-sm-11">
								  <textarea name="owner_add" class="form-control" id="owner_add" rows="3" cols="20" style="width:100%"><?php if ($info['owner_add'] != '') echo $info['owner_add']; ?></textarea>
							 </div>
				        </div>
				    </div>
				 </div>
				 
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
						  <label class="col-sm-5 control-label">
								<span class="en">Owner’s Phone No.    </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
						    <input type="text" name="owner_ph" class="form-control"  id="owner_ph" class="" value="<? if($info['owner_ph'] != '') echo $info['owner_ph']; ?>">
					      </div>
				       </div>
				    </div>
				</div> 
				 
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							  <label class="col-sm-5 control-label">
								<span class="en">Vehicle Year</span>
								<span style="display: none;" class="sp"> </span>
								<span class="error"></span>
							  </label>
						  <div class="col-sm-6">
						   <input type="text" class="form-control" value="<? if($info['oth_veh_year'] != '') echo $info['oth_veh_year']; ?>" class=""  id="oth_veh_year" name="oth_veh_year">				   
						  </div>
					   </div>
					 </div>
				     <div class="col-sm-7 nopad">
					   <div class="form-group">	
							 <label class="col-sm-4 control-label">
								<span class="en">Make/Model</span>
								<span style="display: none;" class="sp"> Make/Model </span>
								<span class="error"></span>
							 </label>
							 <div class="col-sm-5">
								<input type="text" class="form-control" value="<? if($info['oth_veh_model'] != '') echo $info['oth_veh_model']; ?>" class="" id="oth_veh_model" name="oth_veh_model">
							 </div>
					   </div>
				    </div>
			    </div>
			    
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							  <label class="col-sm-5 control-label">
								<span class="en">Vehicle Color</span>
								<span style="display: none;" class="sp"> </span>
								<span class="error"></span>
							  </label>
						<div class="col-sm-6">
						  <input type="text"  class="form-control"  value="<? if($info['oth_veh_color'] != '') echo $info['oth_veh_color']; ?>" class="" id="oth_veh_color" name="oth_veh_color">
					    </div>
					  </div>
				   </div>
				   <div class="col-sm-7 nopad">
					   <div class="form-group">	
						 <label class="col-sm-4 control-label">
							<span class="en">License Plate No.  </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						</label>
					    <div class="col-sm-5">
						  <input type="text"  class="form-control"  name="oth_veh_license" id="oth_veh_license" class="" value="<? if($info['oth_veh_license'] != '') echo $info['oth_veh_license']; ?>">
					    </div>
					 </div>
				  </div>
			   </div>
			   
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Vin No</span>
							<span class="sp" style="display: none;">Fecha</span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
						    <input type="text" class="form-control" name="oth_veh_vin" id="oth_veh_vin" value="<? if($info['oth_veh_vin'] != '') echo $info['oth_veh_vin']; ?>">
					      </div>
					  </div>
				   </div>
				   <div class="col-sm-7 nopad">
					   <div class="form-group">	
						 <label class="col-sm-4 control-label">
							<span class="en">Other Driver’s Insurance Co    </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						</label>
						<div class="col-sm-5">
						  <input type="text" class="form-control" name="oth_driver_insco" id="oth_driver_insco" class="" value="<? if($info['oth_driver_insco'] != '') echo $info['oth_driver_insco']; ?>">
					     </div>
					 </div>
				  </div>
			   </div>
			   	
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Ins. Co. Phone No </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						</label>
						<div class="col-sm-6">
						   <input type="text" class="form-control" name="oth_insco_ph" id="oth_insco_ph" class="" value="<? if($info['oth_insco_ph'] != '') echo $info['oth_insco_ph']; ?>">
					    </div>
					  </div>
				   </div>
				   <div class="col-sm-7 nopad">
					   <div class="form-group">	
						 <label class="col-sm-4 control-label">
							<span class="en">Ins. Policy No</span>
							<span class="sp" style="display: none;">Fecha</span>
							<span class="error"></span>
						 </label>
						 <div class="col-sm-5">
						   <input type="text" class="form-control" name="oth_ins_pollicy" id="oth_ins_pollicy" value="<? if($info['oth_ins_pollicy'] != '') echo $info['oth_ins_pollicy']; ?>">
					      </div>
					 </div>
				  </div>
			   </div>
			   	
				<div class="col-sm-12 ">
				   <div class="col-sm-6 nopad">
						<span style="float: left;margin-right: 12px;" class="en">Passenger(s)?  </span>
						<span style="float: left;margin-right: 12px;display: none;" class="sp"> </span>
						
						<label style="float: left;margin-right: 12px;">
						   <input type="radio" <?php if ($info['oth_passenger'] == '' || $info['oth_passenger'] == 'yes') { ?>checked="true"<?php } ?> style="float: left;" value="yes" id="oth_passenger1" name="oth_passenger"><span style="float: right;">Yes</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						   <input type="radio"<?php if ($info['oth_passenger'] == '' || $info['oth_passenger'] == 'no') { ?>checked="true"<?php } ?> style="float: left;" value="no" id="oth_passenger2" name="oth_passenger">
						<span style="float: right;">No</span>
						</label>
				   </div>				    
			    </div>	
			   
				
				<div class="col-sm-12 ">	
				   <div class="col-sm-9 nopad">
					  <div class="form-group">
					    <label class="col-sm-12 control-label">
							<span class="en">If yes, please list passenger(s) and relationship.</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						 </label>
						 <div class="col-sm-11">
						    <textarea name="oth_passenger_rel" class="form-control" id="oth_passenger_rel" rows="2" cols="20" style="width:100%"><?php if ($info['oth_passenger_rel'] != '') echo $info['oth_passenger_rel']; ?></textarea>
					    </div>
				     </div>
				   </div>
			    </div>
				
				<!--end of Other Driver’s Information  -->
				<!--start of Third Driver’s Information (If Applicable) -->
				
				<div class="col-sm-12 ">	
				  <label>
				      <b><u>Third Driver’s Information (If Applicable) </u></b>
				  </label>
				</div>	
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Driver’s Name </span>
							<span class="sp" style="display: none;"> </span>
						  </label>
						  <div class="col-sm-6">
						     <input type="text" class="form-control" name="3_driver_name" id="3_driver_name" class="" value="<? if($info['3_driver_name'] != '') echo $info['3_driver_name']; ?>">
					      </div>
				      </div>
				   </div>
				</div>	
				
				<div class="col-sm-12 ">	
				   <div class="col-sm-9 nopad">
					  <div class="form-group">
					   <label class="col-sm-12 control-label">
							<span class="en">Driver’s Address (include city and state)</span>
							<span class="sp" style="display: none;"> </span>
						</label>
						<div class="col-sm-11">
						   <textarea name="3_driver_add" class="form-control" id="3_driver_add" rows="3" cols="20" style="width:100%"><?php if ($info['3_driver_add'] != '') echo $info['3_driver_add']; ?></textarea>
					    </div>
				     </div>
				   </div>
				</div>		
				
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Driver’s Phone No.   </span>
							<span class="sp" style="display: none;"> </span>
						  </label>
						 <div class="col-sm-6">
						    <input type="text" name="3_driver_ph" class="form-control" id="3_driver_ph" class="" value="<? if($info['3_driver_ph'] != '') echo $info['3_driver_ph']; ?>">
					     </div>
				     </div>
				   </div>
				</div>		
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Vehicle Owner’s Name   </span>
							<span class="sp" style="display: none;"> </span>
						  </label>
						  <div class="col-sm-6">
						    <input type="text" name="3_veh_owner" class="form-control" id="3_veh_owner" class="" value="<? if($info['3_veh_owner'] != '') echo $info['3_veh_owner']; ?>">
					      </div>
				     </div>
				   </div>
				</div>	
						
			    <div class="col-sm-12 ">	
				   <div class="col-sm-9 nopad">
					  <div class="form-group">
					   <label class="col-sm-12 control-label">
							<span class="en">Owner’s Address (include city and state)  </span>
							<span class="sp" style="display: none;"> </span>
						</label>
						<div class="col-sm-11">
						   <textarea name="3_owner_add" class="form-control" id="3_owner_add" rows="3" cols="20" style="width:100%"><?php if ($info['3_owner_add'] != '') echo $info['3_owner_add']; ?></textarea>
					    </div>
				     </div>
				   </div>
				</div>	
					
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Owner’s Phone No.    </span>
							<span class="sp" style="display: none;"> </span>
						  </label>
						  <div class="col-sm-6">
						   <input type="text" name="3_owner_ph" class="form-control" id="3_owner_ph" class="" value="<? if($info['3_owner_ph'] != '') echo $info['3_owner_ph']; ?>">
					      </div>
				     </div>
				   </div>
				</div>	
					
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Vehicle Year</span>
							<span style="display: none;" class="sp"> </span>
						  </label>
						  <div class="col-sm-6">
						    <input type="text" class="form-control"  value="<? if($info['3_veh_year'] != '') echo $info['3_veh_year']; ?>" class=""id="3_veh_year" name="3_veh_year">				   
					      </div>
				        </div>
				     </div>
				     <div class="col-sm-7 nopad">
					     <div class="form-group">	
						    <label class="col-sm-4 control-label">
								<span class="en">Make/Model</span>
								<span style="display: none;" class="sp"> Make/Model </span>
						   </label>
						   <div class="col-sm-5">
						       <input type="text" class="form-control"   value="<? if($info['3_veh_model'] != '') echo $info['3_veh_model']; ?>" class="" id="3_veh_model" name="3_veh_model">
					       </div>
					    </div>
				     </div>
			     </div>
			     
			    <div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Vehicle Color</span>
							<span style="display: none;" class="sp"> </span>
						</label>
						<div class="col-sm-6">
						   <input type="text" class="form-control"  value="<? if($info['3_veh_color'] != '') echo $info['3_veh_color']; ?>" class="" id="3_veh_color" name="3_veh_color">
						</div>
					 </div>
				   </div>
				   <div class="col-sm-7 nopad">
					  <div class="form-group">	
						 <label class="col-sm-4 control-label">
							<span class="en">License Plate No.  </span>
							<span class="sp" style="display: none;"> </span>
						</label>
						<div class="col-sm-5">
						   <input type="text" class="form-control" name="3_veh_license" id="3_veh_license" class="" value="<? if($info['3_veh_license'] != '') echo $info['3_veh_license']; ?>">
						</div>
					  </div>
				  </div>
				</div>
				  
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Vin No</span>
							<span class="sp" style="display: none;"> </span>
						</label>
						<div class="col-sm-6">
						   <input type="text" class="form-control" name="3_veh_vinno" id="3_veh_vinno" value="<? if($info['3_veh_vinno'] != '') echo $info['3_veh_vinno']; ?>">
						</div>
					 </div>
				   </div>
				   <div class="col-sm-7 nopad">
					  <div class="form-group">	
						 <label class="col-sm-4 control-label">
							<span class="en">Other Driver’s Insurance Co    </span>
							<span class="sp" style="display: none;"> </span>
						</label>
						<div class="col-sm-5">
						  <input type="text" class="form-control" name="3_driver_insco" id="3_driver_insco" class="" value="<? if($info['3_driver_insco'] != '') echo $info['3_driver_insco']; ?>">
						</div>
					  </div>
				  </div>
				</div>
				  
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Ins. Co. Phone No </span>
							<span class="sp" style="display: none;"> </span>
						</label>
						<div class="col-sm-6">
						  <input type="text" name="3_insco_ph" class="form-control" id="3_insco_ph" class="" value="<? if($info['3_insco_ph'] != '') echo $info['3_insco_ph']; ?>">
					   </div>
					  </div>
					</div>
					<div class="col-sm-7 nopad">
					  <div class="form-group">	
						 <label class="col-sm-4 control-label">
							<span class="en">Ins. Policy No</span>
							<span class="sp" style="display: none;">Fecha</span>
						</label>
						<div class="col-sm-5">
						   <input type="text"  class="form-control" name="3_ins_policy" id="3_ins_policy" value="<? if($info['3_ins_policy'] != '') echo $info['3_ins_policy']; ?>">
						</div>
					  </div>
				  </div>
				</div>	
				  
				<div class="col-sm-12 ">
					<div class="col-sm-6 nopad">
						<span style="float: left;margin-right: 12px;" class="en">Passenger(s)?  </span>
						<span style="float: left;margin-right: 12px;display: none;" class="sp"> </span>
						
						<label style="float: left;margin-right: 12px;">
							<input type="radio" <?php if ($info['3_passenger'] == '' || $info['3_passenger'] == 'yes') { ?>checked="true"<?php } ?> style="float: left;" value="yes" id="3_passenger1" name="3_passenger"><span style="float: right;">Yes</span>
						</label>
						<label style="float: left;margin-right: 12px;">
							<input type="radio" <?php if ($info['3_passenger'] == '' || $info['3_passenger'] == 'no') { ?>checked="true"<?php } ?> style="float: left;" value="no" id="3_passenger2" name="3_passenger">
							<span style="float: right;">No</span>
						</label>
					</div>						
				</div>
				  
				<div class="col-sm-12 ">	
				   <div class="col-sm-9 nopad">
					  <div class="form-group">
					   <label class="col-sm-12 control-label">
							<span class="en">If yes, please list passenger(s) and relationship.</span>
							<span class="sp" style="display: none;"> </span>
						</label>
						<div class="col-sm-11">
							<textarea name="3_passenger_rel" class="form-control" id="3_passenger_rel" rows="2" cols="20" style="width:100%"><?php if ($info['3_passenger_rel'] != '') echo $info['3_passenger_rel']; ?></textarea>
						</div>
					 </div>
				  </div>
				</div>
				  
				<!--end of Third Driver’s Information (If Applicable)  -->
				<!--start of Police Information  -->	
						
				<div class="col-sm-12 ">
				    <label>
					  <b><u>Police Information </u></b>
					</label>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Officer’s Name     </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
						     <input type="text" class="form-control" name="officer_name" id="officer_name" class="" value="<? if($info['officer_name'] != '') echo $info['officer_name']; ?>">
					      </div>
				       </div>
				     </div>
				     <div class="col-sm-7 nopad">
					    <div class="form-group">	
						   <label class="col-sm-4 control-label">
								<span class="en">Headquarters </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						   </label>
						   <div class="col-sm-5">
						      <input type="text" class="form-control" name="headquarter" id="headquarter" class="" value="<? if($info['headquarter'] != '') echo $info['headquarter']; ?>">
				    	   </div>
				         </div>
				     </div>
			     </div>	
			   	
			    <div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Badge No</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" name="badge_no" class="form-control" id="badge_no" value="<? if($info['badge_no'] != '') echo $info['badge_no']; ?>">
						  </div>
					   </div>
				     </div>
				     <div class="col-sm-7 nopad">
					    <div class="form-group">	
						   <label class="col-sm-4 control-label">
								<span class="en">Police Report No   </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						   </label>
						   <div class="col-sm-5">
						      <input type="text" class="form-control" name="prn" id="prn" class="" value="<? if($info['prn'] != '') echo $info['prn']; ?>">
						   </div>
					    </div>
				     </div>
			      </div>
			      		
				<!--end of Police Information  -->
				<!--start of Incident Information  -->
				
				<div class="col-sm-12 ">
				  <label>
					<b><u>Incident Information </u></b>
				  </label>		
				</div>
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Time of Incident </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error">*</span>
						  </label>
						  <div class="col-sm-6">
						     <input type="text" class="form-control"  name="time_incident" id="time_incident" class="" value="<? if($info['time_incident'] != '') echo date('m/d/Y h:i:s', strtotime($info['time_incident'])); ?>">
					      </div>
					    </div>
				     </div>
				     <div class="col-sm-7 nopad">
					    <div class="form-group">	
							 <label class="col-sm-4 control-label">
								<span class="en">Weather</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							  </label>
							  <div class="col-sm-5">
								<input type="text" name="weather" class="form-control"  id="weather" value="<? if($info['weather'] != '') echo $info['weather']; ?>">
							  </div>
				          </div>
				      </div>
			      </div>
			      
				<div class="col-sm-12 ">
				  <div class="col-sm-9 nopad">
					  <div class="form-group">
						   <label class="col-sm-12">
								<span class="en">Location (include city and state) </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error">*</span>
						   </label>
							<div class="col-sm-11">
								<textarea name="location" class="form-control" id="location" rows="2" cols="20" style="width:100%"><?php if ($info['location'] != '') echo $info['location']; ?></textarea>
							</div>
						</div>
				   </div>
				</div>
			        
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Kind and Condition of Pavement   </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" class="form-control" name="kc_pavement" id="kc_pavement" class="" value="<? if($info['kc_pavement'] != '') echo $info['kc_pavement']; ?>">
						  </div>
					   </div>
					</div>
				</div>	
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Direction of SI Vehicle’s Travel </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							  <input type="text" class="form-control"  name="dir_si_travel" id="dir_si_travel" class="" value="<? if($info['dir_si_travel'] != '') echo $info['dir_si_travel']; ?>">
						  </div>
						</div>
					 </div>	
					 <div class="col-sm-7 nopad">
					   <div class="form-group">	
						  <label class="col-sm-4 control-label">
								<span class="en">SI Driver’s Speed </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						  </label>
						  <div class="col-sm-5">
							  <input type="text" class="form-control"  name="si_dri_speed" id="si_dri_speed" value="<? if($info['si_dri_speed'] != '') echo $info['si_dri_speed']; ?>">
						  </div>
						</div>
					 </div>
				 </div>
				 
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
								<span class="en">Direction of Other Vehicle’s Travel   </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" class="form-control" name="dir_oth_travel" id="dir_oth_travel" class="" value="<? if($info['dir_oth_travel'] != '') echo $info['dir_oth_travel']; ?>">
						  </div>
						</div>
					  </div>	
					  <div class="col-sm-7 nopad">
						<div class="form-group">	
							 <label class="col-sm-4 control-label">
								<span class="en">Other Vehicle’s Speed </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							  </label>
							  <div class="col-sm-5">
								 <input type="text"  class="form-control" name="oth_veh_speed" id="oth_veh_speed" value="<? if($info['oth_veh_speed'] != '') echo $info['oth_veh_speed']; ?>">
							  </div>
						  </div>
					  </div>
				  </div>
				   
				<div class="col-sm-12 ">
					 <div class="col-sm-5 nopad">
						<div class="form-group">
							  <label class="col-sm-5 control-label">
								<span class="en">Direction of Third Vehicle’s Travel   </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							  </label>
							  <div class="col-sm-6">
								 <input type="text" class="form-control" name="dir_3veh_travel" id="dir_3veh_travel" class="" value="<? if($info['dir_3veh_travel'] != '') echo $info['dir_3veh_travel']; ?>">
							  </div>
						 </div>
					  </div>	
					  <div class="col-sm-7 nopad">
						 <div class="form-group">	
							 <label class="col-sm-4 control-label">
								<span class="en">Third Vehicle’s Speed</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							</label>
							<div class="col-sm-5">
							   <input type="text"  class="form-control" name="3_veh_speed" id="3_veh_speed" value="<? if($info['3_veh_speed'] != '') echo $info['3_veh_speed']; ?>">
							</div>
						 </div>
					 </div>
				  </div>
				  
				<div class="col-sm-12 ">
					<div class="col-sm-9 nopad">
						<div class="form-group">	
							<label class="col-sm-12">
								<span class="en">Description of Incident (Include Length and Position of Skid Marks)  </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							</label>
							<div class="col-sm-11">
							  <textarea name="desc_incident" class="form-control" id="desc_incident" rows="5" cols="50" style="width:100%"><?php if ($info['desc_incident'] != '') echo $info['desc_incident']; ?></textarea>
						   </div>
						</div>
					</div>
				</div>  
				  
				<div class="col-sm-12 ">
					<div class="col-sm-9 nopad">
						<div class="form-group">	
							<label class="col-sm-12">
								<span class="en">Description of Damage to SI Vehicle</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							</label>
							<div class="col-sm-11">
							   <textarea name="desc_damage_veh" class="form-control" id="desc_damage_veh" rows="2" cols="20" style="width:100%"><?php if ($info['desc_damage_veh'] != '') echo $info['desc_damage_veh']; ?></textarea>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-12 ">
					<div class="col-sm-7 nopad">
						<span style="float: left;margin-right: 12px;" class="en">Is S.I. vehicle safely drivable?    </span>
						<span style="float: left;margin-right: 12px;display: none;" class="sp"> </span>
						
						<label style="float: left;margin-right: 12px;">
						   <input type="radio" <?php if ($info['safy_drivable'] == '' || $info['safy_drivable'] == 'yes') { ?>checked="true"<?php } ?> style="float: left;" value="yes" id="safy_drivable1" name="safy_drivable"><span style="float: right;">Yes</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						   <input type="radio" <?php if ($info['safy_drivable'] == '' || $info['safy_drivable'] == 'no') { ?>checked="true"<?php } ?> style="float: left;" value="no" id="safy_drivable2" name="safy_drivable">
						   <span style="float: right;">No</span>
						</label>
					</div>					
				</div>

				<div class="col-sm-12 ">
					<div class="col-sm-7 nopad">
						<span style="float: left;margin-right: 12px;" class="en">If no, was it towed?   </span>
						<span style="float: left;margin-right: 12px;display: none;" class="sp"> </span>
						
						<label style="float: left;margin-right: 12px;">
						   <input type="radio" <?php if ($info['towed'] == '' || $info['towed'] == 'yes') { ?>checked="true"<?php } ?>style="float: left;" value="yes" id="towed1" name="towed"><span style="float: right;">Yes</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						   <input type="radio" <?php if ($info['towed'] == '' || $info['towed'] == 'no') { ?>checked="true"<?php } ?>style="float: left;" value="no" id="towed2" name="towed">
						   <span style="float: right;">No</span>
						</label>
					</div>
				</div>	

				<div class="col-sm-12 ">
				   <div class="col-sm-9 nopad">
						 <div class="form-group">
							<label class="col-sm-12">
								<span class="en">Vehicle was towed by:(include name and address):    </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							</label>
							<div class="col-sm-11">
							  <textarea name="veh_towed_by"  class="form-control" id="veh_towed_by" rows="2" cols="20" style="width:100%"><?php if ($info['veh_towed_by'] != '') echo $info['veh_towed_by']; ?></textarea>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-12 ">
				   <div class="col-sm-9 nopad">
					  <div class="form-group">
						<label class="col-sm-12">
								<span class="en">Vehicle was towed to:(include name and address):</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						</label>
						<div class="col-sm-11">
							<textarea name="veh_towed_to" class="form-control" id="veh_towed_to" rows="2" cols="20" style="width:100%"><?php if ($info['veh_towed_to'] != '') echo $info['veh_towed_to']; ?></textarea>
						</div>
					 </div> 
				  </div>
				</div>	

				<div class="col-sm-12 ">
				  <div class="col-sm-9 nopad">
					<div class="form-group">
						<label class="col-sm-12">
							<span class="en">Description of Damage to Other Vehicle </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						</label>
						<div class="col-sm-11">
						   <textarea name="desc_damage_othveh" class="form-control" id="desc_damage_othveh" rows="2" cols="20" style="width:100%"><?php if ($info['desc_damage_othveh'] != '') echo $info['desc_damage_othveh']; ?></textarea>
						</div>
					 </div>
				   </div>
				</div>

				<div class="col-sm-12 ">
				   <div class="col-sm-9 nopad">
					  <div class="form-group">
						 <label class="col-sm-12">
							<span class="en">Description of Damage to Third Vehicle </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						 </label>
						 <div class="col-sm-11">
							<textarea name="desc_damage_3veh" class="form-control" id="desc_damage_3veh" rows="2" cols="20" style="width:100%"><?php if ($info['desc_damage_3veh'] != '') echo $info['desc_damage_3veh']; ?></textarea>
						 </div>
					  </div>
					</div>
				</div>

				<!--end of Incident Information  -->
				<!--start of Personal Injuries  -->

				<div class="col-sm-12 ">					
					<label>
						<b><u>Personal Injuries </u></b>
					</label>					
				</div>

				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Name</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" name="pi_name" class="form-control" id="pi_name"  value="<? if($info['pi_name'] != '') echo $info['pi_name']; ?>">
						  </div>
						</div>
					 </div>
				 </div>	
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Address </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							</label>
							<div class="col-sm-6">
							   <input type="text" name="pi_add"  class="form-control" id="pi_add"  value="<? if($info['pi_add'] != '') echo $info['pi_add']; ?>">
							</div>
						 </div>
					  </div>
					  <div class="col-sm-7 nopad">
						 <div class="form-group">	
							 <label class="col-sm-4 control-label">
								<span class="en">Phone No.  </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							 </label>
							 <div class="col-sm-5">
								<input type="text" class="form-control" name="pi_ph" id="pi_ph" value="<? if($info['pi_ph'] != '') echo $info['pi_ph']; ?>">
							 </div>
						  </div>
					  </div>
				  </div>
					 
				 <div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Injury</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							</label>
							<div class="col-sm-6">
							  <input type="text" name="injury" id="injury" class="form-control" value="<? if($info['injury'] != '') echo $info['injury']; ?>">
							</div>
						</div>
					</div>
				 </div>	
				 
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Name</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" name="pi_2_name" id="pi_2_name" class="form-control" value="<? if($info['pi_2_name'] != '') echo $info['pi_2_name']; ?>">
						  </div>
					   </div>
					</div>
				</div>	

				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
								<span class="en">Address </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							   <input type="text" name="pi_2_add" id="pi_2_add" class="form-control" value="<? if($info['pi_2_add'] != '') echo $info['pi_2_add']; ?>">
						  </div>
					   </div>
					 </div>
					 <div class="col-sm-7 nopad">
						<div class="form-group">	
							<label class="col-sm-4 control-label">
								<span class="en">Phone No.  </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							</label>
							<div class="col-sm-5">
								<input type="text" name="pi_2_ph"  class="form-control" id="pi_2_ph" value="<? if($info['pi_2_ph'] != '') echo $info['pi_2_ph']; ?>">
							</div>
						 </div>
					 </div>
				 </div>
				 
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Injury</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" name="injury_2" id="injury_2" class="form-control" value="<? if($info['injury_2'] != '') echo $info['injury_2']; ?>">
						  </div>
					   </div>
					</div>
				</div>
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Name</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						 </label>
						 <div class="col-sm-6">
							<input type="text" name="pi_3_name" id="pi_3_name" class="form-control" value="<? if($info['pi_3_name'] != '') echo $info['pi_3_name']; ?>">
						 </div>
					  </div>
				   </div>
				</div>

				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Address </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" name="pi_3_add" id="pi_3_add" class="form-control" value="<? if($info['pi_3_add'] != '') echo $info['pi_3_add']; ?>">
						  </div>
						</div>
					 </div>
					 <div class="col-sm-7 nopad">
						<div class="form-group">	
						   <label class="col-sm-4 control-label">
								<span class="en">Phone No.  </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						   </label>
						   <div class="col-sm-5">
							  <input type="text" name="pi_3_ph" id="pi_3_ph"  class="form-control" value="<? if($info['pi_3_ph'] != '') echo $info['pi_3_ph']; ?>">
						   </div>
						</div>
					 </div>
				 </div>
				 
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Injury</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						 <div class="col-sm-6">
							<input type="text" name="injury_3" id="injury_3" class="form-control" value="<? if($info['injury_3'] != '') echo $info['injury_3']; ?>">
						 </div>
					   </div>
					 </div>
				 </div>   
				  
				<!--end of Personal Injuries -->
				<!--start of Witness(es) -->

				<div class="col-sm-12 ">					
					<label>
						<b><u>Witness(es) </u></b>
					</label>					
				</div>

				<div class="col-sm-12 ">
				  <div class="col-sm-5 nopad">
					  <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Name</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" name="wit_name" id="wit_name" class="form-control" value="<? if($info['wit_name'] != '') echo $info['wit_name']; ?>">
						  </div>
					   </div>
					</div>
				</div>

				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
						   <label class="col-sm-5 control-label">
								<span class="en">Address </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						   </label>
						  <div class="col-sm-6">
							 <input type="text" name="wit_address" id="wit_address" class="form-control" value="<? if($info['wit_address'] != '') echo $info['wit_address']; ?>">
						  </div>
						</div>
					 </div>
					 <div class="col-sm-7 nopad">
						<div class="form-group">	
							<label class="col-sm-4 control-label">
								<span class="en">Phone No.  </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						   </label>
						   <div class="col-sm-5">
							  <input type="text" name="wit_phone" class="form-control" id="wit_phone" value="<? if($info['wit_phone'] != '') echo $info['wit_phone']; ?>">
						   </div>
						</div>
					 </div>
				</div>

				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Name</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						</label>
						<div class="col-sm-6">
						   <input type="text" name="wit_2_name" id="wit_2_name" class="form-control" value="<? if($info['wit_2_name'] != '') echo $info['wit_2_name']; ?>">
						</div>
					 </div>
				  </div>
				</div>

				<div class="col-sm-12 ">
				   <div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Address </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" name="wit_2_address" id="wit_2_address" class="form-control" value="<? if($info['wit_2_address'] != '') echo $info['wit_2_address']; ?>">
						  </div>
					   </div>
					</div>
					<div class="col-sm-7 nopad">
						<div class="form-group">	
							 <label class="col-sm-4 control-label">
								<span class="en">Phone No.  </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
							 </label>
							 <div class="col-sm-5">
								<input type="text" name="wit_2_phone" id="wit_2_phone" class="form-control" value="<? if($info['wit_2_phone'] != '') echo $info['wit_2_phone']; ?>">
							 </div>
						</div>
					 </div>
				</div>	 

				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Name</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" name="wit_3_name" id="wit_3_name" class="form-control" value="<? if($info['wit_3_name'] != '') echo $info['wit_3_name']; ?>">
						  </div>
					   </div>
					</div>
				</div>	

				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
					   <div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Address </span>
							<span class="sp" style="display: none;"> </span>
							<span class="error"></span>
						  </label>
						  <div class="col-sm-6">
							 <input type="text" name="wit_3_address" id="wit_3_address" class="form-control" value="<? if($info['wit_3_address'] != '') echo $info['wit_3_address']; ?>">
						  </div>
					   </div>
					</div>	
					<div class="col-sm-7 nopad">
						<div class="form-group">	
						    <label class="col-sm-4 control-label">
								<span class="en">Phone No.  </span>
								<span class="sp" style="display: none;"> </span>
								<span class="error"></span>
						    </label>
						    <div class="col-sm-5">
							    <input type="text" name="wit_3_phone" id="wit_3_phone" class="form-control" value="<? if($info['wit_3_phone'] != '') echo $info['wit_3_phone']; ?>">
						    </div>
						</div>
					 </div>
				 </div>	   

                <div class="col-sm-12 row">				 
					<div class="col-sm-5 nopad">
						<div class="form-group">	
							<label class="col-sm-5 control-label" style="margin-left:12px">
								Upload Image(s)
							</label>
							<div class="col-sm-6">
								<input type="file" name="vclaim_images[]" id="vclaim_images" class="form-control" style="border:none;padding:0px;" accept="image/*" onchange="validateImage(this.value)" multiple>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-12 ">
                    <span class='label_h' >Diagram of Collision:</span>
                    <div id="signature-pad" class="signature-pad">
                        <div class="signature-pad--body" style="background-color: #efefef; width:740px;height: 400px;">
                          <canvas class="pad" width="740" height="400" id="sign"></canvas>
                          <input name="signature" id="signature" value="" class="output" type="hidden">
                        </div>
                        <div class="signature-pad--footer">
                          <div class="signature-pad--actions">
                            <div>
                              <button type="button" class="button clear" data-action="clear" style="margin: 12px 0px;">Clear</button>
                            </div>
                          </div>
                        </div>
                    </div>  
                </div>
				<p class="col-sm-12 "><b>IF POSSIBLE, TAKE PHOTOGRAPHS OF THE VEHICLE(S) AND COLLISION SITE.  FORWARD THE DISPOSABLE CAMERA, DEVELOPED FILM OR DIGITAL IMAGES TO PAT PARRA AT THE CORPORATE SAFETY DEPARTMENT.</b></p>
			</div>
			  
			<div class="clear">&nbsp;</div>
			<div class="col-sm-12 ">
				<div class="col-sm-3 row">					
					<button type="button" class="btn btn-danger" onclick="window.location.href='/portal/';">Cancel</button>
					&nbsp;
					<input type="submit" name="save" class="btn btn-primary" value="Submit">			
				</div>		
			</div>			
			<div class="clear">&nbsp;</div>
		</fieldset>	
		
	</form>
</div>

<script src="/js/signature_pad.umd.js"></script>
<script src="/js/app.js"></script>
<script>
function validateImage(img){
	var img_ext = img.split('.').pop();
	var valid_ext = ["jpeg","jpg","gif","png"];
	var ext = valid_ext.indexOf(img_ext);
	if(ext == -1){
	    alert("Only jpeg,jpg,gif,png files are allowed");
	    document.getElementById('vclaim_images').value=" ";
	    return false;
	}else{
	    return true;
	}
}
$(document).ready(function() {		
	$("#form_val").validate({
		rules: {
			date_incident: "required",
			job_number: "required",
			vehicle_no: "required",
			vehicle_year: "required",
			veh_model: "required",
			veh_color: "required",
			license_pn: "required",
			vin_no: "required",
			ed_name: "required",
			ed_address: "required",
			ed_home_ph: "required",
			ed_cell_no: "required",
			ed_license_no: "required",
			ed_dob: "required",
			time_incident: "required",
			location: "required",
		},
	});
	
	$("#date_incident, #ed_dob").datetimepicker({
		lang:'en',
		timepicker:false,
		format:'m/d/Y',
		closeOnDateSelect: true,
		scrollInput: false,
	});
	
	$("#time_incident").datetimepicker({
		lang:'en',			
		closeOnDateSelect: true,
		scrollInput: false,
	});	
	
	<?php if(isset($info['signature']) && trim($info['signature'])!=''){ ?>  
            signaturePad = new SignaturePad(document.getElementById("sign"), {
              onEnd: function () {
                // assign to hidden input
                  $('#signature').val(signaturePad.toDataURL());
              }
            });
            var signature = '<?php echo $info['signature'] ?>';
                if (signature) {
                    signaturePad.fromDataURL(signature);
                }  

   <?php } else{ ?>
            signaturePad = new SignaturePad(document.getElementById("sign"), {
              onEnd: function () {
                // assign to hidden input
                  $('#signature').val(signaturePad.toDataURL());
              }
            });
    <?php } ?>
	
});	
</script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>