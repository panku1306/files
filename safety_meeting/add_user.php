<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';
$page = "portal"; # Hide admin layout in portal page

if ($_POST && $_POST['save'] == 'Save') {
	$err=0;
	while (list($index,$ob)=each($_POST)){
		$info[$index]=ms($ob);
	}
	
	if(!$info['first_name']) $err+=1;
    if(!$info['last_name']) $err+=2;
    
    if(!$info['month']) $err+=4;
    if(!$info['day']) $err+=8;    
    if(!$info['year']) $err+=16;
	
	$dob = $info['year']."-".$info['month']."-".$info['day'];
    
    if(!$info['ssn']) $err+=32;
	if($info['ssn'] != ''){
		if( !preg_match("/^\d{3}\-\d{2}\-\d{4}$/", $info['ssn']) ) { 
			$err+=32;
		}
	}
	
	if($err == ''){
		$fullname = $info['first_name']." ".$info['last_name'];		
		
		$insert_user = mysql_query("INSERT INTO `application` (`first_name`,`last_name`,`dob`,`ssn`,`client`,`status`,`started`,`finished`,`fullname`)  
		VALUES ( '".$info['first_name']."', '".$info['last_name']."', '".$dob."', '".$info['ssn']."', '3', 'submitted', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."', '".$fullname."') ");
		
		if($insert_user) {
			$_SESSION['success_msg'] = "User has been added succesfully";			
		}
		else{
			$_SESSION['error_msg'] = "Sorry, an error occurred. Try again!";
		}

		header('Location:add_user.php');exit;
	}
}
?>

<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div style="height: auto;min-height: 550px;">
	<?php if (isset($_SESSION['success_msg'])) { ?>
	<div class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php
		echo $_SESSION['success_msg']; 
		unset($_SESSION['success_msg']);
		?>
	</div>
	<?php } elseif (isset($_SESSION['error_msg'])) { ?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<?php		
		echo $_SESSION['error_msg']; 
		unset($_SESSION['error_msg']);
		?>
	</div>
	<?php } ?>

	<form name="form_val" id="form_val" action="" method="post" class="well form-horizontal">
		<fieldset style="overflow: hidden;">
			<legend>
				<div class="col-xs-12 col-sm-12 col-lg-12 nopad">Add New User</div>
			</legend>
			<span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
			
			<div id="personal_edit" >
				<div class="col-sm-12 row">
					<div class="col-sm-5">
						<div class="form-group">
							<label class="col-sm-4 control-label nopad">
								<span class="en">First Name</span>								
								<span class="error">*</span>								
							</label>
							<div class="col-sm-8 nopad">
								<input type="text" class="form-control<?=$err&1?" error":""?>" name="first_name" id="first_name" >
							</div>
						</div>
					</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="col-sm-4 control-label nopad">
								<span class="en">Last Name</span>								
								<span class="error">*</span>
							</label>
							<div class="col-sm-8 nopad">
								<input type="text" class="form-control<?=$err&2?" error":""?>" name="last_name" id="last_name" >
							</div>								
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 row">
					<div class="col-sm-5">
						<div class="form-group">
							<label class="col-sm-4 control-label nopad">
								<span class="en"> Date of Birth</span>								
								<span class="error">*</span>								
							</label>
							<div class="col-sm-8 nopad" style="padding-left: 15px;">
								<div class="form-group col-sm-3 col-xs-3 nopad">
									<select name="month" id="month" class="form-control nopad<?=$err&4?" error":""?>" placeholder="MM">
										<option value="" disabled selected>MM</option>
										<?php 
										for($m = 1; $m <13; $m++){
											$monthName = date("M", mktime(0, 0, 0, $m, 10));
											echo '<option value="'.str_pad($m,2,"0",STR_PAD_LEFT). '">'.$monthName.'</option>';
										}
										?>
									</select>									
								</div>
								<div class="form-group col-sm-4 col-xs-4 nopad" style="margin: 0 0 0 20px;">
									<select name="day" id="day" class="form-control nopad<?=$err&8?" error":""?>" placeholder="DD">
										<option value="" disabled selected>DD</option>
										<?php  
										for($dt = 1; $dt <= 31; $dt++){
											$value = str_pad($dt,2,"0",STR_PAD_LEFT);
											echo "<option value='$value'>$value</option>";
										}
										?>
									</select>
								</div>
								<div class="form-group col-sm-5 col-xs-5 nopad" style="margin: 0 0 0 10px;">
									<select name="year" id="year" class="form-control nopad<?=$err&16?" error":""?>" placeholder="YYYY">
										<option value="" disabled selected>YYYY</option>
										<?php
										$curent_year = (int)date('Y');
										for($y = 1915; $y <= $curent_year; $y++){
											echo "<option value='$y'>$y</option>";
										}
										?>
									</select>
								</div>
							</div>							
						</div>
					</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="col-sm-4 control-label nopad">
								<span class="en">SSN</span>
								<span class="sp" style="display: none;"> </span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-8 nopad" style="">
								<input class="form-control<?=$err&32?" error":""?>" type="text" name="ssn" id="ssn" placeholder="###-##-####" >
							</div>							
						</div>
					</div>
				</div>
			
				<div class="col-sm-12 row">
					<input type="submit" name="save" class="btn btn-primary" value="Save" >
					&nbsp;
					<button type="button" value="Back" class="btn btn-warning" onclick="window.location.href='sop_training.php'">Back</button>
				</div>
			</div>
		</fieldset>
	</form>
	
	<br>
</div>
<style>
.swcont{display:none;}
.pc_button{text-align: right;}
</style>
<script>
$(document).ready(function() {		
	$("#form_val").validate({
		rules: {
			first_name: "required",
			last_name: "required",
			ssn: "required",
			day: "required",
			month: "required",
			year: "required",
		},
	});
});
</script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>
