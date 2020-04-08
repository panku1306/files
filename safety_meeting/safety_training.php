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
	
	$date = strtotime(str_replace('-','/',$_POST['date_employed']));
	if (!$_POST['date_employed']) {
		$err+=1;
	} elseif ($date) {
		$date = date("Y-m-d",$date);
	} else {
		$err+=1;
	}
	
	if (!$info['emp_name']) $err+=2;
	if (!$info['assign_dept']) $err+=4;	
	if (!$info['work_assign']) $err+=8;	
	if ($info['sigPad_ppst_val']) {
		$info['sigPad_ppst_val'] = $_POST['sigPad_ppst_val'];
	} else {
		$err+=16;
	}
	if ($info['sigPad_emp_val']) {
		$info['sigPad_emp_val'] = $_POST['sigPad_emp_val'];
	} else {
		$err+=32;
	}
		
	if ($err == '') {
		$check_data = mysql_query("select * from `safety_training` where `id`='" . $id . "'");
		if (mysql_num_rows($check_data) > 0) {
			$row_id = mysql_fetch_array($check_data);
			$row_id_val = $row_id['id'];
			$insrt_det = mysql_query("update `safety_training` set
					 `date_employed`='" .date('Y-m-d',strtotime($info['date_employed']))."',
					  emp = '".$info['emp']."',
					 `emp_name`='" . $info['emp_name']. "',
					 `assign_dept`='" . $info['assign_dept'] ."',
					 `work_assign`='" . $info['work_assign'] . "',
					 `phy_limitations`='" . $info['phy_limitations'] . "',
					 `polici_program`='" . $info['polici_program'] . "',
					 `gds_rules`='" . $info['gds_rules'] . "',
					 `sre_procedure`='" . $info['sre_procedure'] . "',
					 `ppp_equipment`='" . $info['ppp_equipment'] . "',
					 `mhe_procedure`='" . $info['mhe_procedure'] . "',
					 `proper_lift`='" . $info['proper_lift'] . "',
					 `hww_injury`='" . $info['hww_injury'] . "',
					 `housekeeping`='" . $info['housekeeping'] . "',
					 `operation_fork`='" . $info['operation_fork'] . "',
					 `hazadrs_job`='" . $info['hazadrs_job'] . "',
					 `operation_vehicle`='" . $info['operation_vehicle'] . "',
					 `inm_safe`='" . $info['inm_safe'] . "',
					 `msds_use`='" . $info['msds_use'] . "',
					 `sigPad_ppst_val`='" . $info['sigPad_ppst_val'] . "',
					 `sigPad_emp_val`='" . $info['sigPad_emp_val'] . "'
					  where `id`='" . $id . "'");
			
		} 
		else {
			$insrt_det = mysql_query("insert into `safety_training` set
				 `date_employed`='" .date('Y-m-d',strtotime($info['date_employed']))."',
				 `emp` = '".$info['emp']."',
				 `emp_name`='" . $info['emp_name']. "',
				 `assign_dept`='" . $info['assign_dept'] ."',
				 `work_assign`='" . $info['work_assign'] . "',
				 `phy_limitations`='" . $info['phy_limitations'] . "',
				 `polici_program`='" . $info['polici_program'] . "',
				 `gds_rules`='" . $info['gds_rules'] . "',
				 `sre_procedure`='" . $info['sre_procedure'] . "',
				 `ppp_equipment`='" . $info['ppp_equipment'] . "',
				 `mhe_procedure`='" . $info['mhe_procedure'] . "',
				 `proper_lift`='" . $info['proper_lift'] . "',
				 `hww_injury`='" . $info['hww_injury'] . "',
				 `housekeeping`='" . $info['housekeeping'] . "',
				 `operation_fork`='" . $info['operation_fork'] . "',
				 `hazadrs_job`='" . $info['hazadrs_job'] . "',
				 `operation_vehicle`='" . $info['operation_vehicle'] . "',
				 `inm_safe`='" . $info['inm_safe'] . "',
				 `msds_use`='" . $info['msds_use'] . "',
				 `sigPad_ppst_val`='" . $info['sigPad_ppst_val'] . "',
				 `sigPad_emp_val`='" . $info['sigPad_emp_val'] . "'");
			$row_id_val = mysql_insert_id();
		}
		
		if($insrt_det){
			require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');
			$mail = new NextCodeMailer();	
			
			$url = $base_url.'/html2pdf_v4.03/examples/safety_training_doc.php?id='.$row_id_val.'&emp=' . $info['emp'];				
			$binary_content = file_get_contents($url);
											
			$mail->From = 'noreply@nextcode.info';
			$mail->FromName = 'NextCode.Info';			
						
			$mail->AddBCC('si-notifications@nextcode.info');
			$mail->addAddress('pankaj1983samal@gmail.com');	
			
			$mail->isHTML(true);# Set email format to HTML
			$mail->Subject = 'Safety Training - #'.$info['emp'];
			$mail->Body = 'There should be a PDF attached to this message with your info for Safety Training. Check it out!';
			$mail->AltBody = 'There should be a PDF attached to this message with your info for Safety Training. Check it out!';
			$mail->AddStringAttachment($binary_content, "safety_training_doc.pdf", 'base64', 'application/pdf');
			
			# $mail must have been created		
			if($mail->send()) {
				$_SESSION['success_msg'] = "Safety training details has been sent to user email.";		
			}
			else{
				$_SESSION['error_msg'] = "Sorry, mail couldn't be send. Contact Admin!";
			}		
		}else{
			$_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";
		}	
	}
}

$query = "SELECT * FROM safety_training WHERE id = '" . $id . "'";
$result = mysql_query($query);
while ($ob = mysql_fetch_array($result)) {
	$info= $ob;	
}

if(!empty($_GET['emp'])){
	$emp_que = "SELECT * FROM application WHERE id = '" . $_GET['emp'] . "'";
	$emp_res = mysql_query($emp_que);
	while ($ob = mysql_fetch_array($emp_res)) {
		$emp_info = $ob;
	}
}
?>
<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div id="frame" style="height: auto;">
	
	<form id="form_val" class="form-horizontal" name="form_val" action="" method="post" enctype="multipart/form-data">
	
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
		
		<h3 style="text-align: center; text-decoration: underline; margin-top: 0px; margin-bottom: 35px;">EMPLOYEE SAFETY TRAINING</h3>
		<p>This report to be made up by the supervisor/foreman and the employee immediately after employment and filed in and your personal file. One copy to be return Construction manager.</p>
		
		<span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
		<fieldset>
			<div id="personal_edit" >
				<div class="col-sm-12 ">
					<div class="col-sm-6 nopad" >
					  <div class="form-group">
						<label class="col-sm-5 control-label">
							<span class="en">Date Employed</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error">*</span>
						</label>
						<div class="col-sm-5">
							<input type="text" class="form-control<?=$err&1?" error":""?>" name="date_employed" id="date_employed" placeholder="MM/DD/YYYY" class="" value="<? if($info['date_employed'] != ''){echo date('m/d/Y', strtotime($info['date_employed']));}?>">
					    </div>
					  </div>  
					 </div>
				     <div class="col-sm-6 nopad">
						<div class="form-group">
						  <label class="col-sm-7 control-label">
							<span class="en">Name(PRINT) First,Middle,Last</span>
							<span class="sp" style="display: none;">Número de trabajo</span>
							<span class="error">*</span>
						  </label>
						  <div class="col-sm-5">
								<input type="text" class="form-control<?=$err&2?" error":""?>" name="emp_name" id="emp_name" class="" value="<? if($info['emp_name'] != ''){ echo $info['emp_name'];} else{echo $emp_info['first_name'].' '.$emp_info['last_name'];}?>">
						  </div>
					   </div>  
					</div>
				</div>
				<div class="col-sm-12 ">
					<div class="col-sm-6 nopad">
						<div class="form-group">
						  <label class="col-sm-5 control-label">
							<span class="en">Assigned Department</span>
							<span class="sp" style="display: none;"> </span>
							<span class="error">*</span>
						  </label>
						  <div class="col-sm-5">
						     <input type="text" class="form-control<?=$err&4?" error":""?>" name="assign_dept" id="assign_dept" class="" value="<? if($info['assign_dept'] != '') echo $info['assign_dept']; ?>">
					      </div>
					    </div> 
					  </div>
					  <div class="col-sm-6 nopad">
						 <div class="form-group">
						    <label class="col-sm-7 control-label">
							  <span class="en">Work Assignment</span>
							  <span class="sp" style="display: none;">Fecha</span>
							  <span class="error">*</span>
						    </label>
						    <div class="col-sm-5">
						      <input type="text" class="form-control<?=$err&8?" error":""?>" name="work_assign" id="work_assign" value="<? if($info['work_assign'] != '') echo $info['work_assign']; ?>">
					        </div>
					     </div>  
					  </div>
				   </div>
				<div class="col-sm-12  ">
					<div class="col-sm-6  nopad">
						<div class="form-group">
							<label class="col-sm-12">
								<span class="en">What are your physical limitations (if any)?</span>
								<span class="sp" style="display: none;"> </span>
							</label>
							<div class="col-sm-12">
								<textarea class="form-control" name="phy_limitations" id="phy_limitations" rows="2" cols="20" style="width: 700px"><?php if ($info['phy_limitations'] != '') echo $info['phy_limitations']; ?></textarea>
							</div>
						</div>
					</div> 
				</div>
				<div class="col-sm-12  ">
				  <div class="form-group">
					<label  class="col-sm-12">
						<b><u>I HAVE BEEN INSTRUCTED IN THE FOLLOWING, WHERE APPLICABLE:</u></b>(please initial each line)
					</label>
				   </div>
				</div>  
				<div class="col-sm-12 ">
					<div class="form-group">
					   <label class="col-sm-6 control-label"><b>1. Southland's safety policies and programs</b></label>
					  <div class="col-sm-5">  
						 <input type="text" class="form-control" name="polici_program" id="polici_program" value="<? if($info['polici_program'] != '') echo $info['polici_program']; ?>">
					  </div>
					</div>
				</div>
				<div class="col-sm-12 ">
				  <div class="form-group">
					<label class="col-sm-6 control-label"><b>2. General and departmental safety rules</b></label>
					  <div class="col-sm-5">
						<input type="text" class="form-control"  name="gds_rules" id="gds_rules" value="<? if($info['gds_rules'] != '') echo $info['gds_rules']; ?>">
					  </div>
				  </div>
				</div>
				<div class="col-sm-12  ">
				   <div class="form-group">
					  <label  class="col-sm-6 control-label"><b>3. Safety rule and enforcement procedures</b></label>
					   <div class="col-sm-5">
						 <input type="text" class="form-control" name="sre_procedure" id="sre_procedure" value="<? if($info['sre_procedure'] != '') echo $info['sre_procedure']; ?>">
					   </div>
				   </div>
				</div>
				<div class="col-sm-12 " >
				  <div class="form-group">
					 <label class="col-sm-6 control-label"><b>4. Proper personal protective equipement as needed<br>(hardhat, and safety glasses are mandatory)</b></label>
						<div class="col-sm-5">
						  <input type="text" class="form-control" name="ppp_equipment" id="ppp_equipment" value="<? if($info['ppp_equipment'] != '') echo $info['ppp_equipment']; ?>">
						</div>
				   </div>
				</div>	
				<div class="col-sm-12 " >
				  <div class="form-group">
					<label class="col-sm-6 control-label"><b>5. Material handling equipement and procedures</b></label>
					   <div class="col-sm-5">
						<input type="text" class="form-control"  name="mhe_procedure" id="mhe_procedure" value="<? if($info['mhe_procedure'] != '') echo $info['mhe_procedure']; ?>">
					   </div>
				  </div>
				</div>
				<div class="col-sm-12 " >
				  <div class="form-group">
					<label  class="col-sm-6 control-label"><b>6. Proper lifting and use of hoists and cranes</b></label>
					  <div class="col-sm-5">
						<input type="text" class="form-control" name="proper_lift" id="proper_lift" value="<? if($info['proper_lift'] != '') echo $info['proper_lift']; ?>">
					  </div>
				  </div>
				</div>
				<div class="col-sm-12 " >
				  <div class="form-group">
					 <label class="col-sm-6 control-label"><b>7. How, when and where to report injuries</b></label>
						<div class="col-sm-5">
						  <input type="text" class="form-control" name="hww_injury" id="hww_injury" value="<? if($info['hww_injury'] != '') echo $info['hww_injury']; ?>">
						</div>
				  </div>
				</div>		
				<div class="col-sm-12 " >
				  <div class="form-group">
					<label class="col-sm-6 control-label" ><b>8. Housekeeping procedures</b></label>
					   <div class="col-sm-5">
						 <input type="text" class="form-control" name="housekeeping" id="housekeeping" value="<? if($info['housekeeping'] != '') echo $info['housekeeping']; ?>">
					   </div>
				  </div>
				</div>		
				<div class="col-sm-12 " >
				  <div class="form-group">
					<label class="col-sm-6 control-label"><b>9. Proper operation of forklifts</b></label>
						<div class="col-sm-5">
						  <input type="text" class="form-control" name="operation_fork" id="operation_fork" value="<? if($info['operation_fork'] != '') echo $info['operation_fork']; ?>">
						</div>
				   </div>
				</div>		
				<div class="col-sm-12 " >
				   <div class="form-group">
					  <label class="col-sm-6 control-label"><b>10. Special hazards of hazardous jobs</b></label>
						 <div class="col-sm-5">
						   <input type="text" class="form-control" name="hazadrs_job" id="hazadrs_job" value="<? if($info['hazadrs_job'] != '') echo $info['hazadrs_job']; ?>">
						 </div>
				   </div>
				</div>		
				<div class="col-sm-12 " >
				  <div class="form-group">
					<label class="col-sm-6 control-label"><b>11. Safe operation of vehicles</b></label>
					   <div class="col-sm-5" >
						  <input type="text" class="form-control" name="operation_vehicle" id="operation_vehicle" value="<? if($info['operation_vehicle'] != '') echo $info['operation_vehicle']; ?>">
					   </div>
				   </div>
				</div>		
				<div class="col-sm-12 " >
				 <div class="form-group">
					<label class="col-sm-6 control-label"><b>12. Importance and necessity of mechanical safeguards</b></label>
					  <div class="col-sm-5" >
						<input type="text" class="form-control" name="inm_safe" id="inm_safe" value="<? if($info['inm_safe'] != '') echo $info['inm_safe']; ?>">
					  </div>
				 </div>
				</div>		
				<div class="col-sm-12 " >
					<div class="form-group">
						<label class="col-sm-6 control-label"><b>13. M.S.D.S Their location and use</b></label>
					        <div class="col-sm-5">
							  <input type="text" class="form-control" name="msds_use" id="msds_use" value="<? if($info['msds_use'] != '') echo $info['msds_use']; ?>">
						   </div>
					 </div>
				</div>
				<div class="col-sm-12 ">
					<p><b> DEPARTMENTAL SUPERVISORY HAS OBSERVED THE EMPLOYEE'S WORK HABITS DURING THE FIRST 30 DAYS OF EMPLOYEMENT AND AGREES THAT SAFETY WORK HABITS ARE ACCEPTABLE .</b></p>
			    </div>
				<div class="col-sm-12 ">
				  <hr> 
				  <p>IMPORTANT: If this employee is transferred to another type of job, a new safety instruction report most be made out. </p>
				</div>
				<div class="col-sm-12 ">
					<div class="col-sm-6 nopad">
						<label>
							<span class="en">Person Performing Safety Training</span>
							<span class="sp" style="display: none;">Capataz sesión</span>
							<span class="error">*</span>
						</label>
						<div class="sigPad_ppst <?=$err&16?" error":""?>">
							<div class="sig sigWrapper" style="width:400px;height:110px;overflow:hidden;border-radius:5px;">
								<div class="typed"></div>
								  <canvas class="pad" width="400" height="110"></canvas>
								  <input type="hidden" name="sigPad_ppst_val" id="sigPad_foreman_val" value="<?php echo $info['sigPad_ppst_val']; ?>" class="output">
							    </div>
							    &nbsp;<a href="#clear" class="clearButton">clear signature</a><br/>
						    </div>
					    </div>
					<div class="col-sm-6 nopad">
						<label>
							<span class="en">Employee</span>
							<span class="sp" style="display: none;">Capataz sesión</span>
							<span class="error">*</span>
						</label>
						<div class="sigPad_emp <?=$err&32?" error":""?>">
							<div class="sig sigWrapper" style="width:400px;height:110px;overflow:hidden;border-radius:5px;">
								<div class="typed"></div>
								  <canvas class="pad" width="400" height="110"></canvas>
								  <input type="hidden" name="sigPad_emp_val" id="sigPad_emp_val" value="<?php echo $info['sigPad_emp_val']; ?>" class="output">
							    </div>
							    &nbsp;<a href="#clear" class="clearButton">clear signature</a><br/>
						    </div>
					    </div>
				    </div>
			</div>
			
			<div class="clear">&nbsp;</div>
			<div class="col-sm-12 ">
				<div class="col-sm-3 row">
					<input type="hidden" name="emp" value="<?php echo !empty($_GET['emp'])?$_GET['emp']:$info['emp']; ?>" />			
					<button type="button" class="btn btn-danger" onclick="window.location.href='/portal/';">Cancel</button>
					&nbsp;
					<input type="submit" name="save" class="btn btn-primary" value="Submit">			
				</div>		
			</div>			
			<div class="clear">&nbsp;</div>
		 </fieldset>  
        
	</form>
</div>

<script src="/js/jquery.signaturepad.js"></script>
<script>
$(document).ready(function() {	
	$("#form_val").validate({
		rules: {
			date_employed: "required",
			emp_name: "required",        
			assign_dept: "required",
			work_assign: "required",		
		},
	});
	var jsarr = <?php echo json_encode($sign_array); ?>;
	if (jsarr != null) {
		for (var i = 0; i < jsarr.length; i++){
			var sign = jsarr[i];
			if (sign != ''){
				$('.sigPad_' + (i + 1)).signaturePad({drawOnly: true}).regenerate(sign);
			}
			else {
				$('.sigPad_' + (i + 1)).signaturePad({drawOnly: true});
			}
		}
	}
	else {
		for (var i = 1; i <= 10; i++){
			$('.sigPad_' + i).signaturePad({drawOnly: true});
		}
	}
	var sig = '<?php echo htmlspecialchars_decode($info['sigPad_ppst_val']); ?>';
	if (sig != ''){
		$('.sigPad_ppst').signaturePad({drawOnly: true}).regenerate(sig);
	}
	else {
		$('.sigPad_ppst').signaturePad({drawOnly: true});
	}
	var sig = '<?php echo htmlspecialchars_decode($info['sigPad_emp_val']); ?>';
	if (sig != ''){
		$('.sigPad_emp').signaturePad({drawOnly: true}).regenerate(sig);
	}
	else {
		$('.sigPad_emp').signaturePad({drawOnly: true});
	}
	
	$( "#date_employed" ).datetimepicker({
		lang:'en',
		timepicker:false,
		format:'m/d/Y',
		closeOnDateSelect: true,
		scrollInput: false,
	});
});
</script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>