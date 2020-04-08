<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';
$page = "portal"; # Hide admin layout in portal page

if(isset($_GET['app_id']) && !empty($_GET['app_id'])){
	$query = "SELECT * FROM application WHERE client = '3' AND id =".$_GET['app_id'];
	$result = mysql_query($query);
	$app_data = mysql_fetch_object($result);
}

if(isset($_GET['sop_id']) && !empty($_GET['sop_id'])){
	$query_sop = "SELECT * FROM sop_doc WHERE id = ".$_GET['sop_id'];		
	$res_sop = mysql_query($query_sop);
	$sop_data = mysql_fetch_object($res_sop);
}

if(isset($_GET['app_id']) && isset($_GET['sop_id'])){
	$query = "SELECT * FROM `sop_training` WHERE `sop_id`='".$_GET['sop_id']."' AND `app_id` = '".$_GET['app_id']."' ";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0){
		$value = mysql_fetch_object($result);
	}
}

#form data submit start here
if ($_POST && $_POST['submit'] == 'Submit') {
	
	$err=0;
	while (list($index,$ob)=each($_POST)) {
		$info[$index]=ms($ob);
	}
	if (!$info['sop_tool_name']) $err+=1;
	if (!$info['first_name']) $err+=2;
	if (!$info['last_name']) $err+=4;
	if (!$info['initial']) $err+=8;
	if (!$info['signature']) $err+=16;
	if (!$info['sop_id']) $err+=32;
	if (!$info['app_id']) $err+=64;
	
	if($err == ''){
		$time_value = date("Y-m-d h:i:s", time());
		$check_data = mysql_query("SELECT * FROM `sop_training` WHERE `sop_id`='".$info['sop_id']."' AND `app_id` = '".$info['app_id']."' ");
		
		if(mysql_num_rows($check_data) > 0){
			$insrt_det = mysql_query("UPDATE `sop_training` set
								`first_name`='".$info['first_name']."',
								`last_name`='".$info['last_name']."',
								`initial`='".$info['initial']."',
								`signature`='".$info['signature']."',
								`modified`='".$time_value."'
								WHERE `id`='".$info['id']."' AND `sop_id`='".$info['sop_id']."' AND `app_id` = '".$info['app_id']."' ");
			
		}
		else{
			$insrt_det = mysql_query("INSERT INTO `sop_training` (`sop_id`,`app_id`,`first_name`, `last_name`, `initial`, `signature`, `created`) VALUES ( '".$info['sop_id']."','".$info['app_id']."','".$info['first_name']."', '".$info['last_name']."', '".$info['initial']."', '".$info['signature']."', '".$time_value."') ");
		}
		
		if($insrt_det) {			
			if(!empty($info['id'])){
				$_SESSION['success_msg'] = "Information updated successfully";
			}else{
				$_SESSION['success_msg'] = "Information added succesfully";
			}
			
			header('Location:/portal/weekly_tailgate/sop_sign.php?app_id='.$info['app_id'].'&sop_id='.$info['sop_id']);exit;
		}
		else{			
			$_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";				
		}
	}
}
?>

<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div style="height: auto;min-height: 550px;">
	<?php if (isset($_SESSION['success_msg'])){ ?>
	<div class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php 
		echo $_SESSION['success_msg'];
		unset($_SESSION['success_msg']); 
		?>
	</div>
	<?php } elseif ($_SESSION['error_msg']) { ?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php 
		echo $_SESSION['error_msg'];
		unset($_SESSION['error_msg']); 
		?>
	</div>
	<?php } ?>

	<h3 class="ttext" style="margin: 0px;">SOP Training Sign-In</h3>	
	<?php 
	if ($sop_data && !empty($sop_data->sop_doc) ) {
		$file_ext = pathinfo($sop_data->sop_doc);
		if($file_ext['extension'] == 'pdf'){		
	?>
	<div class="col-sm-12 text-center" style="padding: 10px 0px;">
		<iframe id="fred" style="border:none;height:600px; width:90%;" src="https://docs.google.com/gview?url=<?php echo $base_url; ?>/attachments/sop_doc/<?=$sop_data->sop_doc?>&embedded=true"></iframe>		
	</div>
	<?php 
		}else{
	?>	
	<div class="col-sm-12 text-center" style="padding: 10px 0px;">
		<img id="fred" style="width:100%;" src="/attachments/sop_doc/<?=$sop_data->sop_doc?>">		
	</div>	
	<?php	
		}
	} 
	?>
	
	<div class="clr"><br></div>
	
	<form class="form-horizontal" id="form_val" method="post" action="" name="form_val" enctype="multipart/form-data" style="clear:both;">
		
		<fieldset style="overflow: hidden;">
			<span id="error_msg" style="color: red;font-weight: bold;margin-left: 15px;margin-bottom: 15px;display: none;">
				Please input all fields marked with *
			</span>
			
			<div id="personal_edit" >
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-md-5 control-label">
								<span class="en">SOP Tool Name:</span>								
								<span class="error">*</span>								
							</label>
							<div class="col-md-7">
								<input type="text" class="form-control<?=$err&1?" error":""?>" name="sop_tool_name" id="sop_tool_name" readonly="true" value="<?=$sop_data?$sop_data->sop_tool_name:""?>" >						
							</div>							
						</div>
					</div>	
					<div class="col-sm-1"></div>
					<div class="col-sm-5 nopad">
						&nbsp;
					</div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-md-5 control-label">
								<span class="en"> First Name:</span>								
								<span class="error">*</span>								
							</label>
							<div class="col-md-7">
								<input type="text" class="form-control<?=$err&2?" error":""?>" name="first_name" id="first_name" readonly="true" value="<?=$app_data?$app_data->first_name:""?>" >						
							</div>							
						</div>
					</div>	
					<div class="col-sm-1"></div>
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-md-5 control-label">
								<span class="en">Last Name:</span>								
								<span class="error">*</span>								
							</label>
							<div class="col-md-7">
								<input type="text" class="form-control<?=$err&4?" error":""?>" name="last_name" id="last_name" readonly="true" value="<?=$app_data?$app_data->last_name:""?>" >						
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label class="col-md-5 control-label">
								<span class="en">Initial:</span>								
								<span class="error">*</span>								
							</label>
							<div class="col-md-7">
								<input type="text" class="form-control<?=$err&8?" error":""?>" name="initial" id="initial" value="<?=stripslashes($value->initial)?>">						
							</div>							
						</div>
					</div>	
					<div class="col-sm-1"></div>
					<div class="col-sm-5 nopad">
						&nbsp;
					</div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-5 nopad">
						<div class="form-group">
							<label id="sigLabel" class="col-sm-6 control-label">
								<span class="en">Signature</span>								
								<span class="error">*</span>
								<span id="outpur_errr" style="color: red; display: none; font-weight: 100;font-weight: bold;">
									Please put signature
								</span>
							</label>
						</div>
					</div>
				</div>
				<div class="col-sm-12 ">	
					<div class="sigPad">
						<span id="sigEn" class="en"><p style="width: 265px;"><em>Using your finger, sign on the line below:</em></p></span>    
						<div class="sig sigWrapper" style="width:550px;height:120px;overflow:hidden;padding:2px;border-radius:5px;">
							<div class="typed"></div>
							<canvas class="pad" width="550" height="120"><?=stripslashes($value->signature)?></canvas>
							<input type="hidden" name="signature" id="signature" class="output">
						</div>
						&nbsp;<a href="#clear" class="clearButton">clear signature</a><br/>
					</div>
				</div>
				<div class="clr"><br></div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-3 row">								
						<input type="submit" name="submit" class="btn btn-primary" value="Submit" onclick="return do_submit();">
						&nbsp;
						<button type="button" class="btn btn-danger" onclick="window.location.href='/portal/weekly_tailgate/sop_training.php'">Cancel</button>
					</div>		
				</div>
			</div>
		</fieldset>
		<input type="hidden" name="id" value="<?=$value->id?>" readonly="true">
		<input type="hidden" name="sop_id" value="<?=$sop_data?$sop_data->id:""?>" readonly="true">
		<input type="hidden" id="app_id" name="app_id" value="<?=$app_data?$app_data->id:""?>" readonly="true">
	</form>	
		
	<br>
</div>

<style>
.swcont{display:none;}
.pc_button{text-align: right;}
</style>

<script src="/js/jquery.signaturepad.js"></script>
<script>
function do_submit(){
	var error_count = 0;	

	if ($('#sop_tool_name').val().length == 0){
		$('#sop_tool_name').addClass(' error');
		error_count++;
	}
	
	if ($('#first_name').val().length == 0){
		$('#first_name').addClass(' error');
		error_count++;
	}
	
	if ($('#last_name').val().length == 0){
		$('#last_name').addClass(' error');
		error_count++;
	}
	
	if ($('#initial').val().length == 0){
		$('#initial').addClass(' error');
		error_count++;
	}
	
	if ($('#signature').val().length == 0){
		$("#outpur_errr").css("display", "block");
		error_count++;
	}
	else{
		$("#outpur_errr").css("display", "none");
	}
			
	if (error_count > 0){
		$('html, body').animate({
			scrollTop: "600px"
		}, 800);
		
		$('#error_msg').show();
		return false;
	}
	else{
		$('#error_msg').hide();
		//document.getElementById(frmid).submit();
		return true;
	} 
}

$(document).ready(function() {
	var sig = '<?php echo htmlspecialchars_decode($value->signature); ?>';
	if (sig != '') {
		$('.sigPad').signaturePad({drawOnly:true}).regenerate(sig);
	}
	else{			
	   $('.sigPad').signaturePad({drawOnly:true});
	}
});	
</script>
	
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>