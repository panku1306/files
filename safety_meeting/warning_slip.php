<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_inc.php';
require_once '../signature-to-image-master/signature-to-image.php';
if(isset($_GET['id'])){
	$id = $_GET['id'];
}
//$qs = explode('/', $_SERVER['QUERY_STRING']);

if ($_POST) {
	
	$err = 0;
	while (list($index, $ob) = each($_POST)) {
		$info[$index] = $ob;
	}
	$date = strtotime(str_replace('-','/',$_POST['date_warning']));
	if (!$_POST['date_warning']) {
		$err+=1;
	} elseif ($date) {
		$date = date("Y-m-d",$date);
	} else {
		$err+=1;
	}
	
	if (!$info['emp_name']) $err+=2;
	
	if ($info['sigPad_admin_val']) {
		$info['sigPad_admin_val'] = $_POST['sigPad_admin_val'];
	} else {
		$err+=4;
	}
	if ($info['sigPad_emp_val']) {
		$info['sigPad_emp_val'] = $_POST['sigPad_emp_val'];
	} else {
		$err+=8;
	}
	
	if ($err == '') {
		
		$check_data = mysql_query("select * from `warning_slip` where `id`='" . $id . "'");

		if (mysql_num_rows($check_data) > 0) {
			$row_id = mysql_fetch_array($check_data);
			$row_id_val = $row_id['id'];
			
			$insrt_det = mysql_query("update `warning_slip` set
					`date_warning`='" .date('Y-m-d',strtotime($info['date_warning']))."',
					 `emp_name`='" . $info['emp_name']. "',
					 `document_verbal`='" . $info['document_verbal']."',
					 `2nd_warning`='" . $info['2nd_warning'] . "',
					 `3rd_warning`='" . $info['3rd_warning'] . "',
					 `position`='" . $info['position'] . "',
					 `tardine`='" . $info['tardine'] . "',
					 `consum_alcol`='" . $info['consum_alcol'] . "',
					 `misconduct`='" . $info['misconduct'] . "',
					 `use_drugs`='" . $info['use_drugs'] . "',
					 `refuse_inst`='" . $info['refuse_inst'] . "',
					 `u_influence`='" . $info['u_influence'] . "',
					 `f_report`='" . $info['f_report'] . "',
					 `f_injury`='" . $info['f_injury'] . "',
					 `p_work`='" . $info['p_work'] . "',
					 `other`='" . $info['other'] . "',
					 `o_input`='" . $info['o_input'] . "',
					 `explanation`='" . $info['explanation'] . "',
					 `admin_name`='" . $info['admin_name'] . "',
					 `admin_position`='" . $info['admin_position'] . "',
					 `sigPad_admin_val`='" . $info['sigPad_admin_val'] . "',
					 `date_admin`='" .date('Y-m-d',strtotime($info['date_admin'])) . "',
					 `date_emp`='" .date('Y-m-d',strtotime($info['date_emp'])). "',
					 `sigPad_emp_val`='" . $info['sigPad_emp_val'] . "'
					 	where `id`='" . $id . "'");
					print_r($insrt_det);
						
				//$stat = mysql_query($insrt_det);
				$output_super = filter_input(INPUT_POST, 'sigPad_admin_val', FILTER_UNSAFE_RAW);				
				$img_sup = sigJsonToImage(json_decode($output_super), array('imageSize' => array(400, 65)));
				imagejpeg($img_sup, '../html2pdf_v4.03/examples/res/warning_slip_admin_draw_' . $id . '.jpg');
				
				$output_super = filter_input(INPUT_POST, 'sigPad_emp_val', FILTER_UNSAFE_RAW);				
				$img_sup = sigJsonToImage(json_decode($output_super), array('imageSize' => array(400, 65)));
				imagejpeg($img_sup, '../html2pdf_v4.03/examples/res/warning_slip_emp_draw_' . $id . '.jpg');
				
				if($insrt_det){
				
					require_once(dirname(__FILE__).'/../class/NextCodeMailer.class.php');
					$mail = new NextCodeMailer();
					$mail->config = parse_ini_file(dirname(__FILE__)."/../conf/NextCode.conf.php", true);				
					$mail->setNextCodeDefaults();
					$url = 'http://'.$_SERVER['HTTP_HOST'].'/html2pdf_v4.03/examples/warning_slip_doc.php?&id=' . $id;
					//echo $url;exit;
					$binary_content = file_get_contents($url);
					
					$mail->addAddress('santanu.manna1991@gmail.com');		
					//$mail->addBCC('steve@nextcode.info', "Nextcode");
					$mail->isHTML(true);# Set email format to HTML
					$mail->Subject = 'Vehicle Claims';
					$mail->Body = 'There should be a PDF attached to this message with your info for Warning Slip. Check it out!';
					$mail->AltBody = 'There should be a PDF attached to this message with your info for Warning Slip. Check it out!';
					$mail->AddStringAttachment($binary_content, "warning_slip_doc.pdf", 'base64', 'application/pdf');
									
					# $mail must have been created
					if ($mail->send()) {
						$_SESSION['done'] = true;
					} else {
						$_SESSION['done'] = false;
					}
				}
				
			if($insrt_det)$_SESSION['updated']=2;			
		} else {
			 //print_r($_POST);exit;
				$insrt_det = mysql_query("insert into `warning_slip` set
					`date_warning`='" .date('Y-m-d',strtotime($info['date_warning']))."',
					 `emp_name`='" . $info['emp_name']. "',
					 `document_verbal`='" . $info['document_verbal']."',
					 `2nd_warning`='" . $info['2nd_warning'] . "',
					 `3rd_warning`='" . $info['3rd_warning'] . "',
					 `position`='" . $info['position'] . "',
					 `tardine`='" . $info['tardine'] . "',
					 `consum_alcol`='" . $info['consum_alcol'] . "',
					 `misconduct`='" . $info['misconduct'] . "',
					 `use_drugs`='" . $info['use_drugs'] . "',
					 `refuse_inst`='" . $info['refuse_inst'] . "',
					 `u_influence`='" . $info['u_influence'] . "',
					 `f_report`='" . $info['f_report'] . "',
					 `f_injury`='" . $info['f_injury'] . "',
					 `p_work`='" . $info['p_work'] . "',
					 `other`='" . $info['other'] . "',
					 `o_input`='" . $info['o_input'] . "',
					 `explanation`='" . $info['explanation'] . "',
					 `admin_name`='" . $info['admin_name'] . "',
					 `admin_position`='" . $info['admin_position'] . "',
					 `sigPad_admin_val`='" . $info['sigPad_admin_val'] . "',
					 `date_admin`='" .date('Y-m-d',strtotime($info['date_admin'])) . "',
					 `date_emp`='" .date('Y-m-d',strtotime($info['date_emp'])). "',
					 `sigPad_emp_val`='" . $info['sigPad_emp_val'] . "'");
					 
				$row_id_val = mysql_insert_id();
				$output_super = filter_input(INPUT_POST, 'sigPad_admin_val', FILTER_UNSAFE_RAW);				
				$img_sup = sigJsonToImage(json_decode($output_super), array('imageSize' => array(400, 65)));
				imagejpeg($img_sup, '../html2pdf_v4.03/examples/res/warning_slip_admin_draw_' . $id . '.jpg');
				
				$output_super = filter_input(INPUT_POST, 'sigPad_emp_val', FILTER_UNSAFE_RAW);				
				$img_sup = sigJsonToImage(json_decode($output_super), array('imageSize' => array(400, 65)));
				imagejpeg($img_sup, '../html2pdf_v4.03/examples/res/warning_slip_emp_draw_' . $id . '.jpg');
				
				if($row_id_val){
				
					require_once(dirname(__FILE__).'/../class/NextCodeMailer.class.php');
					$mail = new NextCodeMailer();
					$mail->config = parse_ini_file(dirname(__FILE__)."/../conf/NextCode.conf.php", true);				
					$mail->setNextCodeDefaults();
					$url = 'http://'.$_SERVER['HTTP_HOST'].'/html2pdf_v4.03/examples/warning_slip_doc.php?&id=' . $row_id_val;
					
					$binary_content = file_get_contents($url);
									
					$mail->addAddress('santanu.manna1991@gmail.com');
					//$mail->addBCC('steve@nextcode.info', "Nextcode");				
					$mail->isHTML(true);# Set email format to HTML
					$mail->Subject = 'Vehicle Claims';
					$mail->Body = 'There should be a PDF attached to this message with your info for Warning Slip. Check it out!';
					$mail->AltBody = 'There should be a PDF attached to this message with your info for Warning Slip. Check it out!';
					$mail->AddStringAttachment($binary_content, "warning_slip_doc.pdf", 'base64', 'application/pdf');
									
					# $mail must have been created
					if ($mail->send()) {
						$_SESSION['done'] = true;
					} else {
						$_SESSION['done'] = false;
					}
				}
				
				$row_id_val = mysql_insert_id();
				if($row_id_val)$_SESSION['updated']=1;			
		}
	}
}

$query = "SELECT * FROM warning_slip WHERE id = '" . $id . "'";
$result = mysql_query($query);

while ($ob = mysql_fetch_array($result)) {
	$info= $ob;
	
}
?>
<? include_once $_SERVER['DOCUMENT_ROOT'].'/_apphead.php'; ?>
<!--[if lt IE 9]><script src="/js/flashcanvas.js"></script><![endif]-->
<script src="/js/jquery.signaturepad.js"></script>
<script type="text/javascript" src='js/jquery.validate.js'></script>
<script src="/js/json2.min.js"></script>
<script type="text/javascript" src="/weekly_tailgate/js/jquery.datetimepicker.js"></script>
<script>
	$(document).ready(function() {		
		$("#form_val").validate({
			rules: {
				date_warning: "required",
				emp_name: "required",
				position: "required",
				admin_name: "required",
				admin_position: "required",
				sigPad_admin_val: "required",
				admin_date: "required",
				sigPad_emp_val: "required",
				emp_date: "required",
			},
			
		});
	});

	$(document).ready(function() {
		$( "#date_warning" ).datetimepicker({
			timepicker:false,
			format:'m/d/Y',
		});
		$("#date_admin").datetimepicker({
			timepicker: false,
			format: 'm/d/Y',
		});
		$('#date_emp').datetimepicker({
			timepicker:false,
			format: 'm/d/Y',
			
		});
	});
</script>

<link type="text/css" rel="stylesheet" href="/weekly_tailgate/css/jquery.datetimepicker.css" >
<style>
    .pad {
		position: relative;
		cursor: url("/img/pen.cur"), crosshair;
		cursor: url("/img/pen.cur") 16 16, crosshair;
		-ms-touch-action: none;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		-o-user-select: none;
		user-select: none;
    }
    input.error, textarea.error, select.error{
        background: #ffe1b8;
		border-color: #e1972d;
    }
	#banner {
		background: none repeat scroll 0 0 #75cd7c;
		box-shadow: 0 0 4px #666;
		color: #fff;
		font-weight: bold;
		height: 25px;
		left: 0;
		padding-top: 5px;
		position: fixed;
		text-align: center;
		text-shadow: 0 -1px 0 #444;
		top: 0;
		width: 100%;
	}
</style>


<div id="frame" style="height: auto;">
	<? if ($_SESSION['updated'] == 1) { ?>
	<div id="banner"><p>Information saved successfully.</p></div>
	<? }else if($_SESSION['updated'] == 2){?>
	<div id="banner"><p>Information updated successfully.</p></div>
	<? }else{
		if($input == true){
	?>
	<div id="banner"><p>Information Can't Be added/updated, Input all fields respect to each document</p></div>
	<?
		}
	}?>
	
	<form id="form_val" method="post" action="" name="form_val" enctype="multipart/form-data">
	    <span style="display:block;float:right;font-size:12px;font-weight:bold;"> 
				<a href="saved_warning_slip.php" title="Saved infraction">
					<img src="folder.png" style="height:20px; padding: 0px; margin: -3px 0px 0px;"/> Saved Warning Slips
				</a>
			</span>
		<br><br/>
		<div style="display:block;float:right;font-size:13px;font-weight:bold;">
			<input type="checkbox" name="document_verbal" value="document_verbal" <?php if ($info['document_verbal'] == 'document_verbal') echo 'checked'; ?>>Document Verbal<br>
			<input type="checkbox" name="2nd_warning" value="2nd_warning" <?php if ($info['2nd_warning'] == '2nd_warning') echo 'checked'; ?> >2nd Warning<br>
			<input type="checkbox" name="3rd_warning" value="3rd_warning" <?php if ($info['3rd_warning'] == '3rd_warning') echo 'checked'; ?> >3rd Warning<br>
	    </div>
		<br>
		<br>
		<div class="control-group"  style="width: 100%;">
			<div class="pull-left" style="margin-right:5px;width: 100%;">
				<label> <h2 style="text-align:center;"><b>WARNING SLIP<b></h2> </label>
					<br/>
			</div>
		</div>
		<br>
		<br>
		<span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
		<fieldset>
			<label style="font-size:17px;"><b>EMPLOYEE RECEIVING WARNING:</b></label> 
			<br>
			<br>
			<div id="personal_edit" >
				<div class="control-group"  style="float: left;width: 100%;">
					<div class="pull-left" style="margin-right:25px;">
						<span class="en"><b>Name:</b></span>
						<span class="sp" style="display: none;">Nombre</span>
						<span class="error" style="margin-right:22px;">*</span>
						<input type="text"  style="width: 310px" class="<?=$err&2?" error":""?>" name="emp_name" id="emp_name" class="" value="<? if($info['emp_name'] != '') echo $info['emp_name']; ?>">
					</div>
					<div style="margin-right:25px;">
						<span class="en"><b>Date:</b></span>
						<span class="sp" style="display: none;"></span>
						<span class="error" style="margin-right:5px;">*</span>
						<input type="text" class="<?=$err&1?" error":""?>" name="date_warning" id="date_warning" placeholder="mm/dd/yyyy" class="" value="<? if($info['date_warning'] != '') echo $info['date_warning']; ?>">
					</div>
					
				</div>
				<div class="control-group"  style="float: left;width: 100%;">
					<div class="pull-left" style="margin-right:5px;">
						<span class="en"><b>Position:</b></span>
						<span class="sp" style="display: none;"></span>
						<span class="error" style="margin-right:5px;">*</span>
						<input type="text" style="width: 310px" class="<?=$err&2?" error":""?>" name="position" id="position" class="" value="<? if($info['position'] != '') echo $info['position']; ?>">
					</div>
				</div>
				<br>
				<br>
				<label style="font-size:17px;"><b>REASON FOR WARNING:</b></label> 
				<P>Mark(X) Reason and Provide Explanation</p>
				<br>
				<div class="control-group"  style="float: left; margin-right:25px; width: 100%;align:center;">
					<div>
						<div class="pull-left" style="margin-left:100px; width: 250px; display: inline-block;">
							
							<input type="checkbox" name="tardine" value="tardine" <?php if ($info['tardine'] == 'tardine') echo 'checked'; ?>> <b>&nbsp;Absenteeism/Tardine</b><br>
						</div>
						
						<div class="pull-left" style="margin-left:25px; display: inline-block;" >
							<input type="checkbox" name="consum_alcol" value="consum_alcol" <?php if ($info['consum_alcol'] == 'consum_alcol') echo 'checked'; ?>><b>&nbsp; Consuming Alcohol on the Job </b><br>
						</div>
					</div>
					<div>
						<div class="pull-left" style="margin-left:100px; width: 250px; display: inline-block;">
							<input type="checkbox" name="misconduct" value="misconduct" <?php if ($info['misconduct'] == 'misconduct') echo 'checked'; ?>><b>&nbsp; Misconduct </b><br>
						</div>
						<div class="pull-left" style="margin-left:25px; display: inline-block;">
							<input type="checkbox" name="use_drugs" value="use_drugs" <?php if ($info['use_drugs'] == 'use_drugs') echo 'checked'; ?>><b>&nbsp; Use of Drugs on the Job</b><br>
						</div>
					</div>
					<div>
						<div class="pull-left" style="margin-left:100px; width: 250px; display: inline-block; ">
							<input type="checkbox" name="refuse_inst" value="refuse_inst" <?php if ($info['refuse_inst'] == 'refuse_inst') echo 'checked'; ?>><b>&nbsp; Refuse to Follow Instructions </b><br>
						</div>
						<div class="pull-left" style="margin-left:25px;">
							<input type="checkbox" name="u_influence" value="u_influence" <?php if ($info['u_influence'] == 'u_influence') echo 'checked'; ?>><b>&nbsp;  Under the Influence of Liquor or Drugs  </b><br>
						</div>
					</div>
					<div>
						<div class="pull-left" style="margin-left:100px; display: inline-block; width: 250px;">
							<input type="checkbox" name="f_report" value="f_report" <?php if ($info['f_report'] == 'f_report') echo 'checked'; ?>><b>&nbsp;  Failure to Report Safety Issue </b><br>
						</div>
						<div class="pull-left" style="margin-left:25px; display: inline-block;">
							<input type="checkbox" name="f_injury" value="f_injury" <?php if ($info['f_injury'] == 'f_injury') echo 'checked'; ?>><b>&nbsp; Failure to Report Injury/Illness   </b><br>
						</div>
					</div>
					<div>
						<div class="pull-left" style="margin-left:100px; display: inline-block; width: 250px; ">
							<input type="checkbox" name="p_work" value="p_work" <?php if ($info['p_work'] == 'p_work') echo 'checked'; ?>><b>&nbsp; Poor Work Performance/Quali </b><br>
						</div>
						<div class="pull-left" style="margin-left:25px;display: inline-block;">
							<input type="checkbox" name="other" value="other" <?php if ($info['other'] == 'other') echo 'checked'; ?>><b>&nbsp; Other  </b><br>
						</div>
						<div class="pull-left" style="display: inline-block;">
							<span><input type="text" name="o_input" class="" value="<?php if($info['o_input'] != '') echo $info['o_input'];  ?>"></input></span>
						</div>
					</div>
				</div>
				<div class="control-group"  style="float: left;width: 100%; margin-top:10px;">
					<div class="pull-left" style="margin-right:5px;">
						<label style="font-size:17px;"><b>EXPLANATION:</b></label>
						<br>
						<textarea name="explanation" id="explanation" rows="2" cols="80" style="width: 700px"><?php if ($info['explanation'] != '') echo $info['explanation']; ?></textarea>
					</div>
				</div>
				<br>
				<label style="font-size:17px;"><b>PERSON ADMINISTERING WARNING: </b></label> 
				<br>
				<div class="control-group"  style="float: left;width: 100%;">
					<div class="pull-left" style="margin-right:15px; display: inline-block; ">
						<span class="en"><b>Name:</b></span>
						<span class="sp" style="display: none;"></span>
						<span class="error" style="margin-right:5px;">*</span>
						<input type="text"  style="width: 310px" class="<?=$err&2?" error":""?>" name="admin_name" id="admin_name" class="" value="<? if($info['admin_name'] != '') echo $info['admin_name']; ?>">
					</div>
					<div class="pull-left" style="margin-right:5px; display: inline-block;">
						<span class="en"><b>Position:</b></span>
						<span class="sp" style="display: none;"></span>
						<span class="error" style="margin-right:5px;">*</span>
						<input type="text" style="width: 250px" class="<?=$err&2?" error":""?>" name="admin_position" id="admin_position" class="" value="<? if($info['admin_position'] != '') echo $info['admin_position']; ?>">
					</div>
				</div>
				<div class="control-group"  style="float: left;  width: 100%; margin-top: 5px; margin-bottom: 25px;">
					<div style="float: left;margin-right:5px;">
						<label style="float: left; display: inline-block;">
							<span class="en"><b>Sign: </b></span>
							<span class="sp" style="display: none;">Capataz sesión</span>
							<span class="error" style="margin-right:5px;">*</span>
						</label>
						<div class="sigPad_admin <?=$err&4?" error":""?>" style="float: left">
							<div class="sig sigWrapper" style="width:400px;height:100px;overflow:hidden;border-radius:5px;">
								<div class="typed"></div>
								<canvas class="pad" width="370" height="150"></canvas>
								<input type="hidden" name="sigPad_admin_val" id="sigPad_admin_val" value="<?php echo $info['sigPad_admin_val']; ?>" class="output">
							</div>
							&nbsp;<a href="#clear" class="clearButton">clear signature</a><br/>
						</div>
						<div class="pull-left" style="margin-right:5px; display: inline-block;">
							<span class="en"><b>Date:</b></span>
							<span class="sp" style="display: none;"></span>
							<span class="error" style="margin-right:5px;">*</span>
							<input type="text" class="<?=$err&1?" error":""?>" name="date_admin" id="date_admin" placeholder="mm/dd/yyyy" class="" value="<? if($info['date_admin'] != '') echo $info['date_admin']; ?>">
						</div>
					</div>
				</div>
				<br>
				<label style="font-size:17px;"><b>EMPLOYEE: </b></label> 
				<br>
				<div class="control-group"  style="float: left;  width: 100%; margin-top: 5px; margin-bottom: 5px;">
					<div style="float: left;margin-right:5px;">
						<label style="float: left; display: inline-block;">
							<span class="en"><b>Sign: </b></span>
							<span class="sp" style="display: none;">Capataz sesión</span>
							<span class="error" style="margin-right:5px;">*</span>
						</label>
						<div class="sigPad_emp<?=$err&8?" error":""?>" style="float: left; display: inline-block;">
							<div class="sig sigWrapper" style="width:400px;height:100px;overflow:hidden;border-radius:5px;">
								<div class="typed"></div>
								<canvas class="pad" width="370" height="150"></canvas>
								<input type="hidden" name="sigPad_emp_val" id="sigPad_emp_val" value="<?php echo $info['sigPad_emp_val']; ?>" class="output">
							</div>
							&nbsp;<a href="#clear" class="clearButton">clear signature</a><br/>
						</div>
						<div class="pull-left" style="margin-right:5px; display: inline-block;">
							<span class="en"><b>Date:</b></span>
							<span class="sp" style="display: none;"></span>
							<span class="error" style="margin-right:5px;">*</span>
							<input type="text" class="<?=$err&1?" error":""?>" name="date_emp" id="date_emp" placeholder="mm/dd/yyyy" class="" value="<? if($info['date_emp'] != '') echo $info['date_emp']; ?>">
						</div>
					</div>
				</div>
				
			</div>				
		</fieldset>
		<input type="submit" name="save" class="btn btn-primary" value="Save">
		<button type="button" class="btn" data-dismiss="modal" aria-hidden="true" onclick="window.location.href = 'http://dor2.si-portal.nextcode.info/';">Back</button>
	</form>
</div>
<script>
<? if ($_SESSION['updated']) { $_SESSION['updated']=0; ?>
$('#banner').delay(5000).slideUp();
<? } ?>
$(document).ready(function() {
	var jsarr = <?php echo json_encode($sign_array); ?>;
	if (jsarr != null) {
		for (var i = 0; i < jsarr.length; i++)
		{
			var sign = jsarr[i];
			if (sign != '')
			{
				$('.sigPad_' + (i + 1)).signaturePad({drawOnly: true}).regenerate(sign);
			}
			else {
				$('.sigPad_' + (i + 1)).signaturePad({drawOnly: true});
			}

		}
	}
	else {
		for (var i = 1; i <= 10; i++)
		{
			$('.sigPad_' + i).signaturePad({drawOnly: true});
		}
	}
	var sig = '<?php echo htmlspecialchars_decode($info['sigPad_admin_val']); ?>';
	if (sig != '')
	{
		$('.sigPad_admin').signaturePad({drawOnly: true}).regenerate(sig);
	}
	else {
		$('.sigPad_admin').signaturePad({drawOnly: true});
	}
	var sig = '<?php echo htmlspecialchars_decode($info['sigPad_emp_val']); ?>';
	if (sig != '')
	{
		$('.sigPad_emp').signaturePad({drawOnly: true}).regenerate(sig);
	}
	else {
		$('.sigPad_emp').signaturePad({drawOnly: true});
	}

});
</script>
<? include_once $_SERVER['DOCUMENT_ROOT'].'/_appfoot.php'; ?>
