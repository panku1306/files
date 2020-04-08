<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';

if(!isset($_GET['emp']) || empty($_GET['emp'])){
	$_SESSION['error_msg'] = "Search and select an employee for safety infraction report!";
	header('Location:/portal/weekly_tailgate/landing_sinfraction.php');
	exit;
}

if(isset($_GET['id'])){
	$id = $_GET['id'];
}

if ($_POST) {
	
	$err = 0;
	while (list($index, $ob) = each($_POST)) {
		$info[$index] = ms($ob);
	}
	$date = strtotime(str_replace('-','/',$_POST['date_infraction']));
	if (!$_POST['date_infraction']) {
		$err+=1;
	} elseif ($date) {
		$date = date("Y-m-d",$date);
	} else {
		$err+=1;
	}
	
	if (!$info['emp_name']) $err+=2;
	
	if ($info['sigPad_emp_val']) {
		$info['sigPad_emp_val'] = $_POST['sigPad_emp_val'];
	} else {
		$err+=4;
	}
	if ($info['sigPad_supv_val']) {
		$info['sigPad_supv_val'] = $_POST['sigPad_supv_val'];
	} else {
		$err+=8;
	}
	
	if ($err == '') {
		
		$check_data = mysql_query("select * from `safety_infraction` where `id`='" . $id . "'");
		
		if (mysql_num_rows($check_data) > 0) {
			$row_id = mysql_fetch_array($check_data);
			$row_id_val = $row_id['id'];
			$insrt_det = mysql_query("update `safety_infraction` set
					 `date_infraction`='" .date('Y-m-d',strtotime($info['date_infraction']))."',
					 `emp` = '".$info['emp']."',
					 `emp_name`='" . $info['emp_name']. "',
					 `1st_offense`='" . $info['1st_offense']."',
					 `2nd_offense`='" . $info['2nd_offense'] . "',
					 `3rd_offense`='" . $info['3rd_offense'] . "',
					 `unsafe_act`='" . $info['unsafe_act'] . "',
					 `corrective_act`='" . $info['corrective_act'] . "',
					 `emp_cmnt`='" . $info['emp_cmnt'] . "',
					 `sigPad_emp_val`='" . $_POST['sigPad_emp_val'] . "',
					 `sigPad_supv_val`='" . $_POST['sigPad_supv_val'] . "',
					 `modified` = '" .date('Y-m-d h:i:s')."' 
					 where `id`='" . $id . "'");
		} 
		else {			
			$insrt_que = "insert into `safety_infraction` set
				 `date_infraction`='" .date('Y-m-d',strtotime($info['date_infraction']))."',
				 `emp` = '".$info['emp']."',
				 `emp_name`='" . $info['emp_name']. "',
				 `1st_offense`='" . $info['1st_offense']."',
				 `2nd_offense`='" . $info['2nd_offense'] . "',
				 `3rd_offense`='" . $info['3rd_offense'] . "',
				 `unsafe_act`='" . $info['unsafe_act'] . "',
				 `corrective_act`='" . $info['corrective_act'] . "',
				 `emp_cmnt`='" . $info['emp_cmnt'] . "',
				 `sigPad_emp_val`='" . $_POST['sigPad_emp_val'] . "',
				 `sigPad_supv_val`='" . $_POST['sigPad_supv_val'] . "',
				 `created` = '" .date('Y-m-d h:i:s')."'";
			
			$insrt_det = mysql_query($insrt_que);
			$row_id_val = mysql_insert_id();		
		}
		
		if($insrt_det){			
			require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');
			$mail = new NextCodeMailer();	
			
			$url = $base_url.'/html2pdf_v4.03/examples/safety_infraction_doc.php?id='.$row_id_val.'&emp=' . $info['emp'];
			$binary_content = file_get_contents($url);
			
			$mail->From = 'noreply@nextcode.info';
			$mail->FromName = 'NextCode.Info';			
			
			$mail->AddBCC('si-notifications@nextcode.info');
			$mail->addAddress('pankaj1983samal@gmail.com');
			
			$mail->isHTML(true);# Set email format to HTML
			$mail->Subject = 'Southland - Safety Infraction - #'.$info['emp'];
			$mail->Body = 'There should be a PDF attached to this message with your info for Safety Infraction. Check it out!';
			$mail->AltBody = 'There should be a PDF attached to this message with your info for Safety Infraction. Check it out!';
			$mail->AddStringAttachment($binary_content, "safety_infraction_doc.pdf", 'base64', 'application/pdf');
							
			# $mail must have been created		
			if($mail->send()) {
				$_SESSION['success_msg'] = "Safety Infraction report has been sent to user email.";	

				header('Location:/portal/weekly_tailgate/landing_sinfraction.php');
				exit;
			}
			else{
				$_SESSION['error_msg'] = "Sorry, mail couldn't be send. Contact Admin!";
				header('Location:/portal/weekly_tailgate/landing_sinfraction.php');
				exit;
			}
		}else{				
			$_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";
		}
	}
}

$query = "SELECT * FROM safety_infraction WHERE safety_infraction.id = '" . $id . "'";
$result = mysql_query($query);
while ($ob = mysql_fetch_array($result)) {
	$info = $ob;
}

if(!empty($_GET['emp'])){
	$emp_que = "SELECT * FROM application WHERE id = '" . $_GET['emp'] . "'";
	$emp_res = mysql_query($emp_que);
	while ($ob = mysql_fetch_array($emp_res)) {
		$emp_info = $ob;
	}
}
?>
<?php include_once dirname(dirname(dirname(__FILE__))).'/_head.php';  ?>

<hr>
<div id="frame" >
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
		
		
		<fieldset>
			<h3 class="ttext" style="margin-bottom: 10px;">SAFETY INFRACTION</h3>
			<span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
				
			<div class="col-sm-12 row">
				<div class="col-sm-12 row" style="padding-right:0px;">
					<span style="display:block;float:right;font-size:12px;font-weight:bold;"> 
						<a href="saved_safety_infraction.php" title="Saved infraction">
							<img src="folder.png" style="height:20px; padding: 0px; margin: -3px 0px 0px;"/> Saved Infraction
						</a>
					</span>
				</div>
			</div>
			
			<div class="col-sm-12 row">
				<div class="col-sm-12 row" style="padding-right:0px;">
					<div style="display:block;float:right;font-size:13px;font-weight:bold;">
						<input type="checkbox" name="1st_offense" value="1st_offense" <?php if ($info['1st_offense'] == '1st_offense') echo 'checked'; ?>>1st Offense<br>
						<input type="checkbox" name="2nd_offense" value="2nd_offense" <?php if ($info['2nd_offense'] == '2nd_offense') echo 'checked'; ?> >2nd Offense<br>
						<input type="checkbox" name="3rd_offense" value="3rd_offense" <?php if ($info['3rd_offense'] == '3rd_offense') echo 'checked'; ?> >3rd Offense<br>
					<br/></div>
				</div>
			</div>
			
			<div id="personal_edit" >
				
				<div class="col-sm-12 row">
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-3 control-label">
								<span class="en">Date:</span>								
								<span class="error">*</span>
							</label>
							<div class="col-md-6">
								<input type="text" class="form-control<?=$err&1?" error":""?>" name="date_infraction" id="date_infraction" placeholder="MM/DD/YYYY" class="" value="<? if($info['date_infraction'] != '') {echo date('m/d/Y', strtotime($info['date_infraction']));}else{echo date('m/d/Y');} ?>">
							</div>							
						</div>
					</div>					
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-3 control-label">
								<span class="en">Employee</span>								
								<span class="error">*</span>
							</label>
							<div class="col-md-6">
								<input type="text" class="form-control<?=$err&2?" error":""?>" name="emp_name" id="emp_name" value="<? if($info['emp_name'] != '') {echo $info['emp_name'];}else{echo $emp_info['first_name'].' '.$emp_info['last_name'];} ?>">
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 row">
					<div class="col-sm-12" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-12 control-label">
								<span class="en">1. What was the unsafe act or behavior observed?</span>								
								<span class="error">*</span>
							</label>
							<div class="col-md-12">
								<textarea class="form-control" name="unsafe_act" id="unsafe_act" rows="2" cols="40" style="width:100%"><?php if ($info['unsafe_act'] != '') echo $info['unsafe_act']; ?></textarea>
							</div>							
						</div>
					</div>
				</div>
					
				<div class="col-sm-12 row">
					<div class="col-sm-12" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-12 control-label">
								<span class="en">2.  What corrective action should the employee take to correct the safety violation? </span>
								<span class="error">*</span>
							</label>
							<div class="col-md-12">
								<textarea class="form-control" name="corrective_act" id="corrective_act" rows="2" cols="40" style="width:100%"><?php if ($info['corrective_act'] != '') echo $info['corrective_act']; ?></textarea>
							</div>							
						</div>
					</div>
				</div>	
				
				<div class="col-sm-12 row">
					<div class="col-sm-12" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-12 control-label">
								<span class="en">3. Employee Comments:</span>								
								<span class="error">*</span>
							</label>
							<div class="col-md-12">
								<textarea class="form-control" name="emp_cmnt" id="emp_cmnt" rows="2" cols="40" style="width:100%"><?php if ($info['emp_cmnt'] != '') echo $info['emp_cmnt']; ?></textarea>
							</div>							
						</div>
					</div>
				</div>	
				
				<div class="col-sm-12 row">
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-6 control-label">
								<span class="en">Employee's Signature: </span>								
								<span class="error">*</span>
							</label>
							<div class="col-md-6">
								<div class="sigPad_emp <?=$err&4?" error":""?>" style="">
									<div class="sig sigWrapper" style="width:450px;height:100px;overflow:hidden;border-radius:5px;">
										<div class="typed"></div>
										<canvas class="pad" width="450" height="100"></canvas>
										<input type="hidden" name="sigPad_emp_val" id="sigPad_emp_val" value="<?php echo $info['sigPad_emp_val']; ?>" class="output">
									</div>
									&nbsp;<a href="#clear" class="clearButton">clear signature</a><br/>
								</div>
							</div>							
						</div>
					</div>	
				</div>
				<div class="col-sm-12 row">					
					<div class="col-sm-6" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-6 control-label">
								<span class="en">Supervisor’s Signature:</span>								
								<span class="error">*</span>
							</label>
							<div class="col-md-6">
								<div class="sigPad_supv<?=$err&8?" error":""?>" style="">
									<div class="sig sigWrapper" style="width:450px;height:100px;overflow:hidden;border-radius:5px;">
										<div class="typed"></div>
										<canvas class="pad" width="450" height="100"></canvas>
										<input type="hidden" name="sigPad_supv_val" id="sigPad_supv_val" value="<?php echo $info['sigPad_supv_val']; ?>" class="output">
									</div>
									&nbsp;<a href="#clear" class="clearButton">clear signature</a><br/>
								</div>
							</div>							
						</div>
					</div>
				</div>
				
			</div>
			
			<div class="col-sm-12 ">
				<div class="col-sm-3 row">
					<input type="hidden" name="emp" value="<?php echo !empty($_GET['emp'])?$_GET['emp']:$info['emp']; ?>" />
					<input type="hidden" name="division" value="<?php echo $emp_info['division']; ?>" />			
					
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
$(document).ready(function() {
	$("#form_val").validate({
		rules: {
			date_infraction: "required",
			emp_name: "required",
			unsafe_act: "required",
			corrective_act: "required",
			emp_cmnt: "required",
			sigPad_emp_val: "required",
			sigPad_supv_val: "required", 	
		},		
	});
	
	$( "#date_infraction" ).datetimepicker({
		setDate: new Date(),
		timepicker:false,
		format:'m/d/Y',
	});
	
	var sig = '<?php echo htmlspecialchars_decode($info['sigPad_emp_val']); ?>';	
	if (sig != ''){
		$('.sigPad_emp').signaturePad({drawOnly: true}).regenerate(sig);
	}else {
		$('.sigPad_emp').signaturePad({drawOnly: true});
	}
	
	var sig = '<?php echo htmlspecialchars_decode($info['sigPad_supv_val']); ?>';	
	if (sig != ''){
		$('.sigPad_supv').signaturePad({drawOnly: true}).regenerate(sig);
	}else {
		$('.sigPad_supv').signaturePad({drawOnly: true});
	}
});
</script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>