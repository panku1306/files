<?php 
include_once dirname(dirname(dirname(__FILE__))). '/_inc.php';

if(isset($_GET['id']) || isset($_GET['i_id'])){
	$i_id = $_GET['i_id'];
	$id = $_GET['id'];	
}else{
	$qs = split('/',$_SERVER['QUERY_STRING']);
	$i_id = $qs[0];
	$id = $qs[1];
}

if ($_POST) {
	
	$err = 0;
	
	while (list($index, $ob) = each($_POST)) {
		$info[$index] = $ob;
	}
	
	if ($err == '') {
		
		$check_data = mysql_query("select * from `defensive_driving` where `id`='" . $id . "'");
		
		if (mysql_num_rows($check_data) > 0) {
			$row_id = mysql_fetch_array($check_data);
			$row_id_val = $row_id['id'];
			
			$insrt_det = "update `defensive_driving` set
					 `d_date`='" .date('Y-m-d',strtotime($info['d_date']))."',
					 `vehicle_no`='" . $info['vehicle_no']. "',
					 `division`='" . $info['division']. "',
					 `topic`='" . $info['topic'] ."',
					 `month_of`='" . $info['month_of'] . "',
					 `signature`='" . $info['signature'] . "',
					 `print_name`='" . $info['print_name'] . "',
					 `comments_dd`='" . $info['comments_dd'] . "',
					 `sdivision`='" . $info['sdivision'] . "',
					 `department`='" . $info['department'] . "',
					 `s_date`='" .date('Y-m-d',strtotime($info['s_date'])) . "',
					 `vehicle_year`='" . $info['vehicle_year'] . "',
					 `make_model`='" . $info['make_model'] . "',
					 `odometer_reading`='" . $info['odometer_reading'] . "',
					 `driver_name`='" . $info['driver_name'] . "',
					 `veh_number`='" . $info['veh_number'] . "',
					 `date_maintenance`='" . date('Y-m-d',strtotime($info['date_maintenance'])) . "',
					 `odometer_time`='" .$info['odometer_time']."',
					 `gi_ok`='" . $info['gi_ok'] . "',
					 `gi_nr`='" . $info['gi_nr'] . "',
					 `horn_ok`='" . $info['horn_ok'] . "',
					 `horn_nr`='" . $info['horn_nr'] . "',
					 `ww_ok`='" . $info['ww_ok'] . "',
					 `ww_nr`='" . $info['ww_nr'] . "',
					 `wwindow_ok`='" . $info['wwindow_ok'] . "',
					 `wwindow_nr`='" . $info['wwindow_nr'] . "',
					 `sb_ok`='" . $info['sb_ok'] . "',
					 `sb_nr`='" . $info['sb_nr'] . "',
					 `rvm_ok`='" . $info['rvm_ok'] . "',
					 `rvm_nr`='" . $info['rvm_nr'] . "',
					 `brakes_ok`='" . $info['brakes_ok'] . "',
					 `brakes_nr`='" . $info['brakes_nr'] . "',
					 `gc_ok`='" . $info['gc_ok'] . "',
					 `gc_nr`='" . $info['gc_nr'] . "',
					 `lights_ok`='" . $info['lights_ok'] . "',
					 `lights_nr`='" . $info['lights_nr'] . "',
					 `tires_ok`='" . $info['tires_ok'] . "',
					 `tires_nr`='" . $info['tires_nr'] . "',
					 `gcon_ok`='" . $info['gcon_ok'] . "',
					 `gcon_nr`='" . $info['gcon_nr'] . "',
					 `bl_ok`='" . $info['bl_ok'] . "',
					 `bl_nr`='" . $info['bl_nr'] . "',
					 `ol_ok`='" . $info['ol_ok'] . "',
					 `ol_nr`='" . $info['ol_nr'] . "',
					 `gt_ok`='" . $info['gt_ok'] . "',
					 `gt_nr`='" . $info['gt_nr'] . "',
					 `wl_ok`='" . $info['wl_ok'] . "',
					 `wl_nr`='" . $info['wl_nr'] . "',
					 `es_ok`='" . $info['es_ok'] . "',
					 `es_nr`='" . $info['es_nr'] . "',
					 `crt_ok`='" . $info['crt_ok'] . "',
					 `crt_nr`='" . $info['crt_nr'] . "',
					 `fe_ok`='" . $info['fe_ok'] . "',
					 `fe_nr`='" . $info['fe_nr'] . "',
					 `fak_ok`='" . $info['fak_ok'] . "',
					 `fak_nr`='" . $info['fak_nr'] . "',
					 `ark_ok`='" . $info['ark_ok'] . "',
					 `ark_nr`='" . $info['ark_nr'] . "',
					 `sf_ok`='" . $info['sf_ok'] . "',
					 `sf_nr`='" . $info['sf_nr'] . "',
					 `td_ok`='" . $info['td_ok'] . "',
					 `td_nr`='" . $info['td_nr'] . "',
					 `rs_ok`='" . $info['rs_ok'] . "',
					 `rs_nr`='" . $info['rs_nr'] . "',
					 `comments`='" . $info['comments'] . "'
                      where `id`='" . $id . "'";
					
			$query_stat = mysql_query($insrt_det);
			
		} 
		else {
			$insrt_det = "insert into `defensive_driving` set
				 `d_date`='" .date('Y-m-d',strtotime($info['d_date']))."',
				 `vehicle_no`='" . $info['vehicle_no']. "',
				 `division`='" . $info['division']. "',
				 `topic`='" . $info['topic'] ."',
				 `month_of`='" . $info['month_of'] . "',
				 `signature`='" . $info['signature'] . "',
				 `print_name`='" . $info['print_name'] . "',
				 `comments_dd`='" . $info['comments_dd'] . "',
				 `sdivision`='" . $info['sdivision'] . "',
				 `department`='" . $info['department'] . "',
				 `s_date`='" .date('Y-m-d',strtotime($info['s_date'])) . "',
				 `vehicle_year`='" . $info['vehicle_year'] . "',
				 `make_model`='" . $info['make_model'] . "',
				 `odometer_reading`='" . $info['odometer_reading'] . "',
				 `driver_name`='" . $info['driver_name'] . "',
				 `veh_number`='" . $info['veh_number'] . "',
				 `date_maintenance`='" .date('Y-m-d',strtotime( $info['date_maintenance'])) . "',
				 `odometer_time`='" .$info['odometer_time']."',
				 `gi_ok`='" . $info['gi_ok'] . "',
				 `gi_nr`='" . $info['gi_nr'] . "',
				 `horn_ok`='" . $info['horn_ok'] . "',
				 `horn_nr`='" . $info['horn_nr'] . "',
				 `ww_ok`='" . $info['ww_ok'] . "',
				 `ww_nr`='" . $info['ww_nr'] . "',
				 `wwindow_ok`='" . $info['wwindow_ok'] . "',
				 `wwindow_nr`='" . $info['wwindow_nr'] . "',
				 `sb_ok`='" . $info['sb_ok'] . "',
				 `sb_nr`='" . $info['sb_nr'] . "',
				 `rvm_ok`='" . $info['rvm_ok'] . "',
				 `rvm_nr`='" . $info['rvm_nr'] . "',
				 `brakes_ok`='" . $info['brakes_ok'] . "',
				 `brakes_nr`='" . $info['brakes_nr'] . "',
				 `gc_ok`='" . $info['gc_ok'] . "',
				 `gc_nr`='" . $info['gc_nr'] . "',
				 `lights_ok`='" . $info['lights_ok'] . "',
				 `lights_nr`='" . $info['lights_nr'] . "',
				 `tires_ok`='" . $info['tires_ok'] . "',
				 `tires_nr`='" . $info['tires_nr'] . "',
				 `gcon_ok`='" . $info['gcon_ok'] . "',
				 `gcon_nr`='" . $info['gcon_nr'] . "',
				 `bl_ok`='" . $info['bl_ok'] . "',
				 `bl_nr`='" . $info['bl_nr'] . "',
				 `ol_ok`='" . $info['ol_ok'] . "',
				 `ol_nr`='" . $info['ol_nr'] . "',
				 `gt_ok`='" . $info['gt_ok'] . "',
				 `gt_nr`='" . $info['gt_nr'] . "',
				 `wl_ok`='" . $info['wl_ok'] . "',
				 `wl_nr`='" . $info['wl_nr'] . "',
				 `es_ok`='" . $info['es_ok'] . "',
				 `es_nr`='" . $info['es_nr'] . "',
				 `crt_ok`='" . $info['crt_ok'] . "',
				 `crt_nr`='" . $info['crt_nr'] . "',
				 `fe_ok`='" . $info['fe_ok'] . "',
				 `fe_nr`='" . $info['fe_nr'] . "',
				 `fak_ok`='" . $info['fak_ok'] . "',
				 `fak_nr`='" . $info['fak_nr'] . "',
				 `ark_ok`='" . $info['ark_ok'] . "',
				 `ark_nr`='" . $info['ark_nr'] . "',
				 `sf_ok`='" . $info['sf_ok'] . "',
				 `sf_nr`='" . $info['sf_nr'] . "',
				 `td_ok`='" . $info['td_ok'] . "',
				 `td_nr`='" . $info['td_nr'] . "',
				 `rs_ok`='" . $info['rs_ok'] . "',
				 `rs_nr`='" . $info['rs_nr'] . "',
				 `comments`='" . $info['comments'] . "'";
				 
			$query_stat = mysql_query( $insrt_det);
			$row_id_val = mysql_insert_id();
		}
		
		# If success
		if($query_stat){
			# Send email
			require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');				
			$mail = new NextCodeMailer();
			
			$url = $base_url.'/html2pdf_v4.03/examples/defensive_driving_doc.php?id=' . $id;
			$binary_content = file_get_contents($url);
			
			$mail->From = 'noreply@nextcode.info';
			$mail->FromName = 'NextCode.Info';
			
			/*
			if ($info['division'] == '1') {
				$mail->addAddress('zgill@southlandind.com');
				$mail->addAddress('norcalsafety@southlandind.com');
			} else if ($info['division'] == '2') {
				$mail->addAddress('slimpus@southlandind.com');						
			} else if ($info['division'] == '3') {
				$mail->addAddress('kdunn@southlandind.com');
			} else if ($info['division'] == '4') {				
				$mail->addAddress('JDevan@southlandind.com');
			} else if ($info['division'] == '5') {
				$mail->addAddress('kdunn@southlandind.com');				
			} else if ($info['division'] == '7' || $info['division'] == '8') {
				$mail->addAddress('mgadient@southlandind.com');
			} else {
				$mail->addAddress('slimpus@southlandind.com');
			}
			$mail->addAddress('ppara@southlandind.com');*/
			
			$mail->AddBCC('si-notifications@nextcode.info');
			$mail->addAddress('pankaj1983samal@gmail.com');		
			
			$mail->isHTML(true);# Set email format to HTML
			$mail->Subject = 'Defensive Driving';
			$mail->Body = 'There should be a PDF attached to this message with your info for defensive driving. Check it out!';
			$mail->AltBody = 'There should be a PDF attached to this message with your info for defensive driving. Check it out!';
			$mail->AddStringAttachment($binary_content, "defensive_driving.pdf", 'base64', 'application/pdf');
							
			# $mail must have been created
			if($mail->send()) {			
				$_SESSION['success_msg'] = "Defensive Driving report has been sent to user email.";				
			}
			else{				
				$_SESSION['error_msg'] = "Sorry, mail couldn't be send. Contact Admin!";
			}						
		}else{				
			$_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";
		}
	}
}

$query = "SELECT * FROM defensive_driving WHERE id = '" . $id . "'";
$result = mysql_query($query);
while ($ob = mysql_fetch_array($result)) {
	$info= $ob;	
}

$query = "SELECT * FROM divisions WHERE client = $client AND active = '1'";
$result = mysql_query($query);
while ($ob = mysql_fetch_object($result)) {
	$divisions[$ob->id] = $ob;
}
?>
<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div id="frame">
	<form class="form-horizontal" id="defensive_driving" method="post" action="" name="safety_checklist" enctype="multipart/form-data">
		
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
		
		<fieldset>
			<h3 class="ttext">DEFENSIVE DRIVING</h3>
			<span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
			<!--span style="display:block;float:right;font-size:12px;font-weight:bold;">
				<a tile="Saved Driving Details" href="saved_dd.php">
				<img style="height:20px; padding: 0px; margin: -3px 0px 0px;" src="folder.png">
				Saved Driving Details
				</a>
			</span-->
			
			<?php
			$check_doc_data = mysql_query("select * from `defensive_diving_doc` where `user_id`='".$i_id."' and DATE(NOW()) between `week_start_date` and `week_end_date` order by id desc LIMIT 1");
			if(mysql_num_rows($check_doc_data) > 0){
				$fet_details = mysql_fetch_array($check_doc_data);
				
				if($fet_details['first_aid_image'] != ''){
					$file_ext = pathinfo($fet_details['first_aid_image']);
					if($file_ext['extension'] == 'pdf'){
			?>
			<div class="text-center">			
				<iframe id="fred" style="border:none;height:600px; width:90%;" src="https://docs.google.com/gview?url=<?php echo $base_url; ?>/uploaded_content/<?php echo $fet_details['first_aid_image']; ?>&embedded=true"></iframe>
			</div>
			<?php
					}else{
			?>
			<div class="text-center" style="border:none; height:600px; overflow:auto;padding: 10px 0px; width:90%;margin: 20px auto;">			
				<img id="fred" style="width:100%;" src="/uploaded_content/<?php echo $fet_details['first_aid_image']; ?>">
			</div>
			<?php						
					}
				}
			}
			?>	
			
			<br>
			<div id="personal_edit" >				
				<div class="col-sm-12 ">
					<p style="clear:both;"><b>I have read and reviewed this month's  defensive driving topic.</b></p><br><br>
					
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Date :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-8">
								<input type="text" name="d_date" id="d_date" class="form-control" value="<? if($info['d_date'] != '') echo date('m-d-Y', strtotime($info['d_date'])); ?>" placeholder="MM/DD/YYYY">
							</div>							
						</div>
					</div>					
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Vehicle No :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-8">
								<input type="text" name="vehicle_no" id="vehicle_no" class="form-control" value="<? if($info['vehicle_no'] != '') echo $info['vehicle_no']; ?>">
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Division :</span>								
								<span class="error">*</span>
							</label>
							<div class="col-md-8">
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
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Topic :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-8">
								<input type="text" name="topic" id="topic" class="form-control" value="<? if($info['topic'] != '') echo $info['topic']; ?>">
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Month of :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-8">
								<input type="text" name="month_of" id="month_of" class="form-control" value="<? if($info['month_of'] != '') echo $info['month_of']; ?>">
							</div>							
						</div>
					</div>
				</div>	
				
				<div class="col-sm-12 ">
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Print Name :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-8">
								<input type="text" name="print_name" id="print_name" class="form-control" value="<? if($info['print_name'] != '') echo $info['print_name']; ?>">
							</div>							
						</div>
					</div>
					<div class="col-sm-12 ">
						<div class="form-group">							
							<div id="sign_ipad">
								<div  class="sig sigWrapper current" style="cursor:crosshair;width:100%;height: 120px; overflow: hidden;">
									<div style="display: none;" class="typed"></div>
									<canvas class="pad" height="120" style="width:100%"></canvas>
									<input name="signature" id="signature" value="" class="output" type="hidden">								
								</div>
								<a href="#clear" class="clearButton">Clear signature</a>	
							</div>					
						</div>
					</div>
				</div>
								
				<div class="col-sm-12 ">
					<div class="col-sm-12" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-3 control-label">
								<span class="en">Comments/Suggestions :</span>								
								<span class="error">*</span>
							</label>
							<div class="col-md-12">
								<textarea class="form-control" name="comments_dd" id="comments_dd" rows="5" cols="50" style="width:100%"><?php if ($info['comments_dd'] != '') echo $info['comments_dd']; ?></textarea>
							</div>							
						</div>
					</div>
				</div>	
				
				<div class="col-sm-12 ">
					<h1 style="text-align:center;">Vehicle Safety Checklist</h1><br>				
					<p><b>Fax or email your completed sign sheet and vehicle safety checklist to your division safety manager.</b></p><br>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-6 control-label">
								<span class="en">Division/Department :</span>								
								<span class="error">*</span>
							</label>
							<div class="col-md-3">
								<select class="form-control" name="sdivision" id="sdivision">
									<option value="">Select Division</option>								
									<?php 
									foreach($divisions as $div) { 			
									?>
									<option value="<?php echo $div->id; ?>" <?php echo $info['sdivision']== $div->id?" selected":""; ?>>
										<?php echo $div->nickname; ?>
									</option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="col-md-3">
								<input style="" type="text" name="department" id="department" class="form-control" value="<? if($info['department'] != '') echo $info['department']; ?>">
							</div>
						</div>
					</div>					
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-6 control-label">
								<span class="en">Date :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-6">
								<input type="text" name="s_date" id="s_date" class="form-control" value="<? if($info['s_date'] != '') echo date('m-d-Y', strtotime( $info['s_date'])); ?>">
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-6 control-label">
								<span class="en">Vehicle Year/<br>Make/Model :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-3">
								<input style="" type="text" name="vehicle_year" id="vehicle_year" class="form-control" value="<? if($info['vehicle_year'] != '') echo $info['vehicle_year']; ?>">
							</div>
							<div class="col-md-3">
								<input style="" type="text" name="make_model" id="make_model" class="form-control" value="<? if($info['make_model'] != '') echo $info['make_model']; ?>">
							</div>
						</div>
					</div>					
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-6 control-label">
								<span class="en">Odometer Reading :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-6">
								<input type="text" name="odometer_reading" id="odometer_reading" class="form-control" value="<? if($info['odometer_reading'] != '') echo $info['odometer_reading']; ?>">
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-sm-6 control-label">
								<span class="en">Driver's Name :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-sm-6">
								<input  type="text" name="driver_name" id="driver_name" class="form-control" value="<? if($info['driver_name'] != '') echo $info['driver_name']; ?>">
							</div>							
						</div>
					</div>					
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-6 control-label">
								<span class="en">Vehicle Number :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-6">
								<input type="text" name="veh_number" id="veh_number" class="form-control" value="<? if($info['veh_number'] != '') echo $info['veh_number']; ?>">
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-6 control-label">
								<span class="en">Date of Last<br> Maintenance Service :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-6">
								<input  type="text" name="date_maintenance" id="date_maintenance" class="form-control" value="<? if($info['date_maintenance'] != '') echo date('m-d-Y', strtotime($info['date_maintenance'])); ?>">
							</div>							
						</div>
					</div>					
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-6 control-label">
								<span class="en">Odometer Reading <br>@ Time of service :</span>								 
								<span class="error">*</span>
							</label>
							<div class="col-md-6">
								<input type="text" name="odometer_time" id="odometer_time" class="form-control" value="<? if($info['odometer_time'] != '') echo $info['odometer_time']; ?>">
							</div>							
						</div>
					</div>
				</div>
				
				
				<div class="col-sm-12 "><!--1. Cab Inspection --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">1. Cab Inspection</span>
								<span class="sp" style="display: none;">Mantenimiento de Registros</span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">OK </label>
									<label class="col-sm-3 control-label">Needs Repair </label>
									<label class="col-sm-8 control-label">&nbsp;</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">a. Gauges and Instrument: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="gi_ok" id="gi_ok" value="OK" style="display:inline-block;" <?php if (($info['gi_ok'] == '' && $info['gi_nr'] == '')|| $info['gi_ok'] == 'OK') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="gi_nr" id="gi_nr" value="NR" <?php if ($info['gi_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										All gauges and lights are working properly.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">b. Horn: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="horn_ok" value="OK" id="horn_ok" style="display:inline-block;" <?php if (($info['horn_ok'] == '' && $info['horn_nr'] == '') || $info['horn_ok'] == 'OK') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="horn_nr" value="NR" id="horn_nr" <?php if ($info['horn_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Gives adequate and reliable warning signal.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">c. Windshield Wipers: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="ww_ok" id="ww_ok" value="OK" <?php if (($info['ww_ok'] == '' && $info['ww_nr'] == '')|| $info['ww_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="ww_nr" id="ww_nr" value="NR" <?php if ($info['ww_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Worn out wipers should be replaced before<br> the beginning of the rainy season.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">d. Windshield and Windows: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="wwindow_ok" id="wwindow_ok" value="OK" <?php if (($info['wwindow_ok'] == '' && $info['wwindow_nr'] == '')|| $info['wwindow_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="wwindow_nr" id="wwindow_nr" value="NR" <?php if ($info['wwindow_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Cracked and broken glass should be reported <br>and replaced; Defroster should
										be working <br>properly; Glass should be clean inside and out;<br> No objects or
										stickers on windshield or windows <br>which impair vision.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">e.Seat Belts: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="sb_ok" id="sb_ok" value="OK" <?php if (($info['sb_ok'] == '' && $info['sb_nr'] == '')|| $info['sb_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="sb_nr" id="sb_nr" value="NR" <?php if ($info['sb_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										All safety belt buckles should work easily.<br> Driver note: USE THEM!
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">f.Rear View Mirrors: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="rvm_ok" id="rvm_ok" value="OK" <?php if (($info['rvm_ok'] == '' && $info['rvm_nr'] == '') || $info['rvm_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="rvm_nr" id="rvm_nr" value="NR" <?php if ($info['rvm_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Firmly attached and reflects view behind vehicle.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">g. Brakes: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="brakes_ok" id="brakes_ok" value="OK" <?php if (($info['brakes_ok'] == '' && $info['brakes_nr'] == '')|| $info['brakes_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="brakes_nr" id="brakes_nr" value="NR" <?php if ($info['brakes_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										If pedal goes down more than ½ way to the floor,<br> brakes need adjustment(For vehicles with power <br>brakes, engine must be running for this test.)<br>Parking brake must work securely when set.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">h. General Condition: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="gc_ok" id="gc_ok" value="OK" <?php if (($info['gc_ok'] == '' && $info['gc_nr'] == '')|| $info['gc_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="gc_nr" id="gc_nr" value="NR" <?php if ($info['gc_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Remove objects from dash, visors or seat<br> which could fly around the cab and
										injure the <br>driver if vehicle stops suddenly or <br>an accident occurs.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--1. Cab Inspection --->
				
				<div class="col-sm-12 "><!--2. Outside of Vehicle Inspection: --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">2. Outside of Vehicle Inspection:</span>								 
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">OK </label>
									<label class="col-sm-3 control-label">Needs Repair </label>
									<label class="col-sm-8 control-label">&nbsp;</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">a. Lights: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="lights_ok" id="lights_ok" value="OK" style="display:inline-block;" <?php if (($info['lights_ok'] == '' && $info['lights_nr'] == '') || $info['lights_ok'] == 'OK') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="lights_nr" id="lights_nr" value="NR" <?php if ($info['lights_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Check high and low beam, emergency <br>flashers, side markers, parking lights,<br>
										license plate lights, tail lights,<br> brake lights, back-up lights, turn <br>signals (front and back).
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">b. Tires: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="tires_ok" value="OK" id="tires_ok" style="display:inline-block;" <?php if (($info['tires_ok'] == '' && $info['tires_nr'] == '')|| $info['tires_ok'] == 'OK') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="tires_nr" value="NR" id="tires_nr" <?php if ($info['tires_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Check tire pressure and tread wear;<br> Check both sides of tires for bulges <br>or
										large cracks on sidewall.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">c.General Condition: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="gcon_ok" id="gcon_ok" value="OK" <?php if (($info['gcon_ok'] == '' && $info['gcon_nr'] == '')|| $info['gcon_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="gcon_nr" id="gcon_nr" value="NR" <?php if ($info['gcon_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Dents, scrapes and any other damage<br> should be reported to your supervisor.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--2. Outside of Vehicle Inspection: --->				
				
				<div class="col-sm-12 "><!--3. Under Vehicle Inspection: --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">3. Under Vehicle Inspection:</span>								 
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">OK </label>
									<label class="col-sm-3 control-label">Needs Repair </label>
									<label class="col-sm-8 control-label">&nbsp;</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">a. Brake Lines: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="bl_ok" id="bl_ok" value="OK" style="display:inline-block;" <?php if (($info['bl_ok'] == '' && $info['bl_nr'] == '')|| $info['bl_ok'] == 'OK') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="bl_nr" id="bl_nr" value="NR" <?php if ($info['bl_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Check for apparent fluid leaks.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">b. Oil Leaks: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="ol_ok" value="OK" id="ol_ok" style="display:inline-block;" <?php if (($info['ol_ok'] == '' && $info['ol_nr'] == '')|| $info['ol_ok'] == 'OK') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="ol_nr" value="NR" id="ol_nr" <?php if ($info['ol_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Check for leaks under transmission and engine.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">c.Gas Tank: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="gt_ok" id="gt_ok" value="OK" <?php if (($info['gt_ok'] == '' && $info['gt_nr'] == '')|| $info['gt_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="gt_nr" id="gt_nr" value="NR" <?php if ($info['gt_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Make sure gas cap is securely attached.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">d.Water Leaks: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="wl_ok" id="wl_ok" value="OK" <?php if (($info['wl_ok'] == '' && $info['wl_nr'] == '')|| $info['wl_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="wl_nr" id="wl_nr" value="NR" <?php if ($info['wl_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Check for water leaks under radiator.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">e.Exhaust System: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="es_ok" id="es_ok" value="OK" <?php if (($info['es_ok'] == '' && $info['es_nr'] == '')|| $info['es_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="es_nr" id="es_nr" value="NR" <?php if ($info['es_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Check for exhaust leaks in exhaust <br>system, muffler, tail pipe and exhaust pipe.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--3. Under Vehicle Inspection: --->
				
				<div class="col-sm-12 "><!--4. Safety Equipment --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">4. Safety Equipment</span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">OK </label>
									<label class="col-sm-3 control-label">Needs Repair </label>
									<label class="col-sm-8 control-label">&nbsp;</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">a. Cone/Reflective Triangle: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="crt_ok" id="crt_ok" value="OK" style="display:inline-block;" <?php if (($info['crt_ok'] == '' && $info['crt_nr'] == '')|| $info['crt_ok'] == 'OK') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="crt_nr" id="crt_nr" value="NR" <?php if ($info['crt_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Use in the event of a vehicle breakdown or <br>accident; Use when parking vehicle
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">b.Fire Extinguisher: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="fe_ok" value="OK" id="fe_ok" style="display:inline-block;" <?php if (($info['fe_ok'] == '' && $info['fe_nr'] == '')|| $info['fe_ok'] == 'OK') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="fe_nr" value="NR" id="fe_nr" <?php if ($info['fe_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Check for full charge with current inspection tag.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">c. First Aid Kit: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="fak_ok" id="fak_ok" value="OK" <?php if (($info['fak_ok'] == '' &&  $info['fak_nr'] == '')|| $info['fak_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="fak_nr" id="fak_nr" value="NR" <?php if ($info['fak_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Must be kept fully stocked.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">d.Accident Reporting Kit (should include a camera): </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="ark_ok" id="ark_ok" value="OK" <?php if (($info['ark_ok'] == '' && $info['ark_nr'] == '')|| $info['ark_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="ark_nr" id="ark_nr" value="NR" <?php if ($info['ark_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Must be kept in the glove compartment.<br> Use in the event of an accident to record<br> information and take photos of the <br>accident scene.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">e.Spare Fuses: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="sf_ok" id="sf_ok" value="OK" <?php if (($info['sf_ok'] == '' && $info['sf_nr'] == '')|| $info['sf_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="sf_nr" id="sf_nr" value="NR" <?php if ($info['sf_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Must be kept in the glove compartment.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">f.Tie Downs: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="td_ok" id="td_ok" value="OK" <?php if (($info['td_ok'] == '' && $info['td_nr'] == '')|| $info['td_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="td_nr" id="td_nr" value="NR" <?php if ($info['td_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										To secure all loads including ladders, <br>tools and equipment/material.
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">								
								<span class="en">g. Reverse Sensor: </span>
							</div>
							<label class="col-sm-8" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1">
										<input type="checkbox" name="rs_ok" id="rs_ok" value="OK" <?php if (($info['rs_ok'] == '' && $info['rs_nr'] == '')|| $info['rs_ok'] == 'OK') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>									
									<label class="col-sm-3 ">
										<input type="checkbox" name="rs_nr" id="rs_nr" value="NR" <?php if ($info['rs_nr'] == 'NR') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-8">
										Warning signal should be functioning <br>and working properly
									</label>									
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--4. Safety Equipment --->
				
				<div class="col-sm-12 ">
					<div class="col-sm-12" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span class="en">Comments</span>
								<span class="sp" style="display: none;">Comentarios</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="comments" id="comments" rows="5" cols="50" style="width:100%"><?php if ($info['comments'] != '') echo $info['comments']; ?></textarea>
							</div>							
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12 ">
				<div class="col-sm-3 row">								
					<button type="button" class="btn btn-danger" onclick="window.location.href='/portal/'">Cancel</button>
					&nbsp;
					<input type="submit" name="save" class="btn btn-primary" value="Submit">
				</div>		
			</div>
			
			<div style="clear:both;"><br></div>
		</fieldset>
	</form>	
	
</div>


<script src="/js/jquery.signaturepad.js"></script>
<script>
$(document).ready(function () {
	$("#defensive_driving").validate({
		rules: {
			d_date: "required",
			vehicle_no: "required", 
			division : "required", 
			topic: "required",
			month_of: "required",
			print_name: "required",	
			comments_dd: "required",
			department: "required",
			s_date: "required",
			vehicle_year: "required",
			make_model: "required",
			odometer_reading: "required",
			driver_name: "required",
			veh_number: "required",
			date_maintenance: "required",
			odometer_time: "required",
			comments: "required",	
		},
	});
	
	$("#d_date,#s_date,#date_maintenance").datetimepicker({
		lang:'en',
		timepicker:false,
		format:'m/d/Y',
		closeOnDateSelect: true,
		scrollInput: false,
	});
	
	<?php if(isset($info['signature']) && trim($info['signature'])!=''){ ?>
	$('#sign_ipad').signaturePad({drawOnly:true,validateFields:false, lineWidth :0}).regenerate('<?php echo $info['signature'] ?>');
	<?php }else{ ?>
	$('#sign_ipad').signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
	<?php } ?>
});
</script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>