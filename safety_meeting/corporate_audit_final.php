<?php 
# ini_set('display_errors', 1);
# error_reporting(E_ALL);

include_once dirname(dirname(dirname(__FILE__))). '/_inc.php';
if(isset($_GET['id'])){
	$id = $_GET['id'];
}

if(!empty($_POST) && isset($_POST['performed_by'])){  
	$keys  	= '';
	$vals  	= '';
	$count	=1;
	$update_query  = '';
	$score =0;
	$points = array('ans1'=>5,'ans2'=>5,'ans3'=>5,'ans4'=>4,'ans5'=>4,'ans6'=>5,'ans7'=>5,'ans8'=>4,'ans9'=>3,'ans10'=>3,'ans11'=>3,'ans12'=>3,'ans13'=>5,'ans14'=>5,'ans15'=>5,'ans16'=>4,'ans17'=>5,'ans18'=>3,'ans19'=>5,'ans20'=>4,'ans21'=>4,'ans22'=>5,'ans23'=>4,'ans24'=>3,'ans25'=>3,'ans26'=>4,'ans27'=>3,'ans28'=>5,'ans29'=>5,'ans30'=>4,'ans31'=>5,'ans32'=>5,'ans33'=>4,'ans34'=>4,'ans35'=>5,'ans36'=>4,'ans37'=>5,'ans38'=>5,'ans39'=>5,'ans40'=>5,'ans41'=>4,'ans42'=>5,'ans43'=>1,'ans44'=>2,'ans45'=>4,'ans46'=>4,'ans47'=>4,'ans48'=>4,'ans49'=>4,'ans50'=>4,'ans51'=>5,'ans52'=>5,'ans53'=>4,'ans4'=>4,'ans54'=>4,'ans55'=>5,'ans56'=>3,'ans57'=>4,'ans58'=>5,'ans59'=>4,'ans60'=>4,'ans61'=>5,'ans62'=>4,'ans63'=>5,'ans64'=>5,'ans65'=>4,'ans66'=>5,'ans67'=>5,'ans68'=>5,'ans69'=>5,'ans70'=>5,'ans71'=>4,'ans72'=>5,'ans73'=>5,'ans74'=>5,'ans75'=>4,'ans76'=>5,'ans77'=>5,'ans78'=>5,'ans79'=>5,'ans80'=>5,'ans81'=>5,'ans82'=>4,'ans83'=>4,'ans84'=>3,'ans85'=>3,'ans86'=>4,'ans87'=>4,'ans88'=>4,'ans89'=>3,'ans90'=>4,'ans91'=>3,'ans92'=>3,'ans93'=>3,'ans94'=>4,'ans95'=>4,'ans96'=>4,'ans97'=>4,'ans98'=>4,'ans99'=>3,'ans100'=>4,'ans101'=>2,'ans102'=>2,'ans103'=>4,'ans104'=>4,'ans105'=>5,'ans106'=>3,'ans107'=>4,'ans108'=>4,'ans109'=>4,'ans110'=>5,'ans111'=>4,'ans112'=>5,'ans113'=>5,'ans114'=>5,'115'=>4,'ans116'=>5,'ans117'=>5,'ans118'=>5,'ans119'=>5,'ans120'=>4,'ans121'=>4,'ans122'=>5,'ans123'=>4,'ans124'=>3,'ans125'=>5,'ans126'=>4,'ans127'=>5,'ans128'=>5,'ans129'=>4,'ans130'=>5,'ans131'=>5,'ans132'=>4,'ans133'=>4,'ans134'=>5,'ans135'=>5,'ans136'=>5,'ans137'=>5,'ans138'=>5,'ans139'=>4,'ans140'=>4,'ans141'=>3,'ans142'=>4,'ans143'=>5,'ans144'=>5,'ans145'=>5,'ans146'=>4,'ans147'=>4,'ans148'=>4,'ans149'=>5,'ans150'=>4,'ans151'=>4,'ans152'=>5,'ans153'=>5,'ans154'=>4,'ans155'=>4,'ans156'=>4,'ans157'=>4,'ans158'=>5,'ans159'=>5,'ans160'=>5,'ans162'=>5,'ans163'=>5,'ans164'=>5,'ans165'=>5,'ans166'=>5,'ans167'=>5,'ans168'=>5,'ans169'=>5,'ans170'=>4,'ans171'=>3,'ans172'=>1,'ans173'=>3,'ans174'=>2,'ans175'=>4);
	
	$division = "'".$_POST['division']."'";
	$performed_by = "'".$_POST['performed_by']."'";
	$caf_date = "'".DateTime::createFromFormat('m/d/Y', $_POST['caf_date'])->format('Y-m-d')."'";
	$reviewed_with = "'".$_POST['reviewed_with']."'";
	
	foreach($_POST as $key=>$value){
	
		if(array_key_exists($key, $points) && $_POST[$key] == 'no'){			
			$score  = $score + $points[$key];
		}
		$_POST[$key] = ms($value);
	}	
	
	$now = date('Y-m-d');
	if(!isset($_GET['id'])){
		
		$sql = "INSERT INTO corporate_audit(division, performed_by, date, review_with, question_data, score, created) VALUES ($division, $performed_by, $caf_date, $reviewed_with, '".mysql_real_escape_string(serialize($_POST))."', $score, '$now')";
		
		$query_stat = mysql_query($sql);
		$id = mysql_insert_id();
		
	}else{
		$id = $_GET['id'];
		$sql = "UPDATE corporate_audit SET division = $division , performed_by = $performed_by, date = $caf_date, review_with = $reviewed_with, question_data = '".mysql_real_escape_string(serialize($_POST))."', score = $score, updated = '$now' WHERE id = $id";
		
		$query_stat = mysql_query($sql);
	}
	
	if($query_stat){
		# Send email
		require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');				
		$mail = new NextCodeMailer();
		
		/* gets the data from a URL */
		$url = $base_url.'/html2pdf_v4.03/examples/corporate_audit_doc.php?id='.$id;
		$binary_content = file_get_contents($url);
		
		$mail->From = 'noreply@nextcode.info';
		$mail->FromName = 'NextCode.Info';			
					
		$mail->AddBCC('si-notifications@nextcode.info');
		$mail->addAddress('pankaj1983samal@gmail.com');	
		
		$mail->isHTML(true);# Set email format to HTML
		$mail->Subject = 'Corporate Audit';
		$mail->Body    = 'There should be a PDF attached to this message with your info for corporate audit report. Check it out!';
		$mail->AltBody = 'There should be a PDF attached to this message with your info for corporate audit report. Check it out!';
		$mail->AddStringAttachment($binary_content, "corporate_audit.pdf",'base64','application/pdf');	
		
		# $mail must have been created		
		if($mail->send()) {
			$_SESSION['success_msg'] = "Corporate Audit details has been sent to user email.";		
		}
		else{
			$_SESSION['error_msg'] = "Sorry, mail couldn't be send. Contact Admin!";
		}
	}else{
		$_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";
	}	
}

$query = "SELECT question_data FROM corporate_audit WHERE id = '" . $id . "'";
$result = mysql_query($query);
while ($ob = mysql_fetch_assoc($result)) {
	foreach(unserialize($ob['question_data']) as $key=>$val){
		$info[$key] = stripslashes($val);
	}
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
	<form id="corporate_audit_final" class="form-horizontal"  method="post" action="" name="corporate_audit_final" enctype="multipart/form-data">	
	
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
		
		<span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
		<fieldset>
			<h3 style="text-align: center; text-decoration: underline; margin-top: 0px; margin-bottom: 10px;">Corporate Audit</h3>			
			<span style="display:block;float:right;font-size:12px;font-weight:bold;"> 
				<a href="saved_audits.php" tile="Saved Audits">
					<img src="folder.png" style="height:20px; padding: 0px; margin: -3px 0px 0px;"/> Saved Audits
				</a>
			</span>
			<br/>
			
			<div id="personal_edit" >
				<div class="col-sm-12 ">
					<div class="col-sm-5" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Division</span>
								<span class="sp" style="display: none;">Número de trabajo</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-6">
								<select class="form-control" name="division">
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
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-5" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Performed by </span>
								<span class="sp" style="display: none;"></span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-6">
								<input type="text" name="performed_by" id="performed_by" class="form-control" value="<? if($info['performed_by'] != '') echo $info['performed_by']; ?>">
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 ">
					<div class="col-sm-5" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Date</span>
								<span class="sp" style="display: none;">Fecha</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-6">
								<input type="text" name="caf_date" id="caf_date" class="form-control<?= $err & 16 ? " error" : "" ?>" placeholder="MM/DD/YYYY" value="<? if($info['caf_date'] != '') echo date('m/d/Y', strtotime($info['caf_date']));?>">
							</div>							
						</div>
					</div>
					<div class="col-sm-1"></div>
					<div class="col-sm-5" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Reviewed with</span>
								<span class="sp" style="display: none;"></span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-6">
								<input type="text" name="reviewed_with" id="reviewed_with" class="form-control" value="<? if($info['reviewed_with'] != '') echo $info['reviewed_with']; ?>">
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 "><!--General Safety --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">1. General Safety</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Are all new Southland Employees completing New Hire Orientations?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input  type="radio" name="ans1" id="ans1" value="yes" style="display:inline-block;" <?php if ($info['ans1'] == '' || $info['ans1'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input class="" type="radio" name="ans1" id="ans2" value="no" <?php if ($info['ans1'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans1_cmt" id="ans1_cmt"  placeholder="comments" value="<? if($info['ans1_cmt'] != '') echo $info['ans1_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans1_aaction" id="ans1_aaction" value="<? if($info['ans1_aaction'] != '') echo $info['ans1_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans1_date" id="ans1_date" placeholder="MM/DD/YYYY" value="<? if($info['ans1_date'] != '') echo date('m/d/Y', strtotime($info['ans1_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Has the Code of Safe Practices and Safety and Health Rules been issued to all personnel?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans2" id="ans3" value="yes" style="display:inline-block;" <?php if ($info['ans2'] == '' || $info['ans2'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans2" id="ans4" value="no" <?php if ($info['ans2'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans2_cmt" id="ans2_cmt"  placeholder="comments" value="<? if($info['ans2_cmt'] != '') echo $info['ans2_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans2_aaction" id="ans2_aaction" value="<? if($info['ans2_aaction'] != '') echo $info['ans2_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans2_date" id="ans2_date" placeholder="MM/DD/YYYY" value="<? if($info['ans2_date'] != '') echo date('m/d/Y', strtotime($info['ans2_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Have all Southland employees signed the receipts of the Southland Code of Safe Practices Booklet upon completion of orientation?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans3" id="ans5" value="yes" style="display:inline-block;" <?php if ($info['ans3'] == '' || $info['ans3'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans3" id="ans6" value="no" <?php if ($info['ans3'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans3_cmt" id="ans3_cmt"  placeholder="comments" value="<? if($info['ans3_cmt'] != '') echo $info['ans3_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans3_aaction" id="ans3_aaction" value="<? if($info['ans3_aaction'] != '') echo $info['ans3_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans3_date" id="ans3_date" placeholder="MM/DD/YYYY" value="<? if($info['ans3_date'] != '') echo date('m/d/Y', strtotime($info['ans3_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d. Is refresher training being carried out at regular intervals?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans4" id="ans7" value="yes" style="display:inline-block;" <?php if ($info['ans4'] == '' || $info['ans4'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans4" id="ans8" value="no" <?php if ($info['ans4'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans4_cmt" id="ans4_cmt"  placeholder="comments" value="<? if($info['ans4_cmt'] != '') echo $info['ans4_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans4_aaction" id="ans4_aaction" value="<? if($info['ans4_aaction'] != '') echo $info['ans4_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans4_date" id="ans4_date" placeholder="MM/DD/YYYY" value="<? if($info['ans4_date'] != '') echo date('m/d/Y', strtotime($info['ans4_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e. Are individual training records available?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans5" id="ans9" value="yes" style="display:inline-block;" <?php if ($info['ans5'] == '' || $info['ans5'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans5" id="ans10" value="no" <?php if ($info['ans5'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans5_cmt" id="ans5_cmt"  placeholder="comments" value="<? if($info['ans5_cmt'] != '') echo $info['ans5_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans5_aaction" id="ans5_aaction" value="<? if($info['ans5_aaction'] != '') echo $info['ans5_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans5_date" id="ans5_date" placeholder="MM/DD/YYYY" value="<? if($info['ans5_date'] != '') echo date('m/d/Y', strtotime($info['ans5_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f. Are Tailgate Safety Meetings being held at least weekly?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans6" id="ans11" value="yes" style="display:inline-block;" <?php if ($info['ans6'] == '' || $info['ans6'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans6" id="ans12" value="no" <?php if ($info['ans6'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans6_cmt" id="ans6_cmt"  placeholder="comments" value="<? if($info['ans6_cmt'] != '') echo $info['ans6_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans6_aaction" id="ans6_aaction" value="<? if($info['ans6_aaction'] != '') echo $info['ans6_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans6_date" id="ans6_date" placeholder="MM/DD/YYYY" value="<? if($info['ans6_date'] != '') echo date('m/d/Y', strtotime($info['ans6_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">g. Are attendance sheets being signed and returned each week?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans7" id="ans13" value="yes" style="display:inline-block;" <?php if ($info['ans7'] == '' || $info['ans7'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans7" id="ans14" value="no" <?php if ($info['ans7'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans7_cmt" id="ans7_cmt"  placeholder="comments" value="<? if($info['ans7_cmt'] != '') echo $info['ans7_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans7_aaction" id="ans7_aaction" value="<? if($info['ans7_aaction'] != '') echo $info['ans7_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans7_date" id="ans7_date" placeholder="MM/DD/YYYY" value="<? if($info['ans7_date'] != '') echo date('m/d/Y', strtotime($info['ans7_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">h. Are pre-task plans being signed and returned each day?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans8" id="ans15" value="yes" style="display:inline-block;" <?php if ($info['ans8'] == '' || $info['ans8'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans8" id="ans16" value="no" <?php if ($info['ans8'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans8_cmt" id="ans8_cmt"  placeholder="comments" value="<? if($info['ans8_cmt'] != '') echo $info['ans8_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans8_aaction" id="ans8_aaction" value="<? if($info['ans8_aaction'] != '') echo $info['ans8_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans8_date" id="ans8_date" placeholder="MM/DD/YYYY" value="<? if($info['ans8_date'] != '') echo date('m/d/Y', strtotime($info['ans8_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">i. Is the OSHA Right To Work Poster Posted in all Southland sites and facilities?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans9" id="ans17" value="yes" style="display:inline-block;" <?php if ($info['ans9'] == '' || $info['ans9'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans9" id="ans18" value="no" <?php if ($info['ans9'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans9_cmt" id="ans9_cmt"  placeholder="comments" value="<? if($info['ans9_cmt'] != '') echo $info['ans9_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans9_aaction" id="ans9_aaction" value="<? if($info['ans9_aaction'] != '') echo $info['ans9_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans9_date" id="ans9_date" placeholder="MM/DD/YYYY" value="<? if($info['ans9_date'] != '') echo date('m/d/Y', strtotime($info['ans9_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">j. Are all state and federal posters displaced in all southland sites and facilities</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans10" id="ans19" value="yes" style="display:inline-block;" <?php if ($info['ans10'] == '' || $info['ans10'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans10" id="ans20" value="no" <?php if ($info['ans10'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans10_cmt" id="ans10_cmt"  placeholder="comments" value="<? if($info['ans10_cmt'] != '') echo $info['ans2_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans10_aaction" id="ans10_aaction" value="<? if($info['ans10_aaction'] != '') echo $info['ans10_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans10_date" id="ans10_date" placeholder="MM/DD/YYYY" value="<? if($info['ans10_date'] != '') echo date('m/d/Y', strtotime($info['ans10_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">k. Is OSHA 300-A displayed (Feb1 to April 30th only)</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans11" id="ans21" value="yes" style="display:inline-block;" <?php if ($info['ans11'] == '' || $info['ans11'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans11" id="ans22" value="no" <?php if ($info['ans11'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans11_cmt" id="ans11_cmt"  placeholder="comments" value="<? if($info['ans11_cmt'] != '') echo $info['ans11_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans11_aaction" id="ans11_aaction" value="<? if($info['ans11_aaction'] != '') echo $info['ans11_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans11_date" id="ans11_date" placeholder="MM/DD/YYYY" value="<? if($info['ans11_date'] != '') echo date('m/d/Y', strtotime($info['ans11_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">l. Are all safety manuals available and updated as appropriate?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans12" id="ans23" value="yes" style="display:inline-block;" <?php if ($info['ans12'] == '' || $info['ans12'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans12" id="ans24" value="no" <?php if ($info['ans12'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans12_cmt" id="ans12_cmt"  placeholder="comments" value="<? if($info['ans12_cmt'] != '') echo $info['ans12_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans12_aaction" id="ans12_aaction" value="<? if($info['ans12_aaction'] != '') echo $info['ans12_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans12_date" id="ans12_date" placeholder="MM/DD/YYYY" value="<? if($info['ans12_date'] != '') echo date('m/d/Y', strtotime($info['ans12_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--General Safety --->
				
				<div class="col-sm-12 "><!--Emergency Action Plan --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">2. Emergency Action Plan</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there a written emergency plan at the site/ facility? </span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans13" id="ans25" value="yes" style="display:inline-block;" <?php if ($info['ans13'] == '' || $info['ans13'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans13" id="ans26" value="no" <?php if ($info['ans13'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans13_cmt" id="ans13_cmt"  placeholder="comments" value="<? if($info['ans13_cmt'] != '') echo $info['ans13_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans13_aaction" id="ans13_aaction" value="<? if($info['ans13_aaction'] != '') echo $info['ans13_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans13_date" id="ans13_date" placeholder="MM/DD/YYYY" value="<? if($info['ans13_date'] != '') echo date('m/d/Y', strtotime($info['ans13_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Is it posted in a conspicuous location?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans14" id="ans27" value="yes" style="display:inline-block;" <?php if ($info['ans14'] == '' || $info['ans14'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans14" id="ans28" value="no" <?php if ($info['ans14'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans14_cmt" id="ans14_cmt"  placeholder="comments" value="<? if($info['ans14_cmt'] != '') echo $info['ans14_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans14_aaction" id="ans14_aaction" value="<? if($info['ans14_aaction'] != '') echo $info['ans14_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans14_date" id="ans14_date" placeholder="MM/DD/YYYY" value="<? if($info['ans14_date'] != '') echo date('m/d/Y', strtotime($info['ans14_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Is the emergency action plan reviewed and revised periodically?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans15" id="ans29" value="yes" style="display:inline-block;" <?php if ($info['ans15'] == '' || $info['ans15'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans15" id="ans30" value="no" <?php if ($info['ans15'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans15_cmt" id="ans15_cmt"  placeholder="comments" value="<? if($info['ans15_cmt'] != '') echo $info['ans15_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans15_aaction" id="ans15_aaction" value="<? if($info['ans15_aaction'] != '') echo $info['ans15_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans15_date" id="ans15_date" placeholder="MM/DD/YYYY" value="<? if($info['ans15_date'] != '') echo date('m/d/Y', strtotime($info['ans15_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d. Have emergency escape procedures and routes been developed and communicated to all employees?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans16" id="ans31" value="yes" style="display:inline-block;" <?php if ($info['ans16'] == '' || $info['ans16'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans16" id="ans32" value="no" <?php if ($info['ans16'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans16_cmt" id="ans16_cmt"  placeholder="comments" value="<? if($info['ans16_cmt'] != '') echo $info['ans16_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans16_aaction" id="ans16_aaction" value="<? if($info['ans16_aaction'] != '') echo $info['ans16_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans16_date" id="ans16_date" placeholder="MM/DD/YYYY" value="<? if($info['ans16_date'] != '') echo date('m/d/Y', strtotime($info['ans16_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e. Does the division have an emergency response plan for weather emergencies? NOTE: if there are no exposure to tornadoes, hurricanes, or other weather emergencies, mark "N/A"</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans17" id="ans33" value="yes" style="display:inline-block;" <?php if ($info['ans17'] == '' || $info['ans17'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans17" id="ans34" value="no" <?php if ($info['ans17'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans17_cmt" id="ans17_cmt"  placeholder="comments" value="<? if($info['ans17_cmt'] != '') echo $info['ans17_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans17_aaction" id="ans17_aaction" value="<? if($info['ans17_aaction'] != '') echo $info['ans17_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans17_date" id="ans17_date" placeholder="MM/DD/YYYY" value="<? if($info['ans17_date'] != '') echo date('m/d/Y', strtotime($info['ans17_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f. Does the division have an emergency response plan for earthquakes? NOTE: if there are no significant exposures to earthquakes mark "N/A"</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans18" id="ans35" value="yes" style="display:inline-block;" <?php if ($info['ans18'] == '' || $info['ans18'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans18" id="ans36" value="no" <?php if ($info['ans18'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans18_cmt" id="ans18_cmt"  placeholder="comments" value="<? if($info['ans18_cmt'] != '') echo $info['ans18_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans18_aaction" id="ans18_aaction" value="<? if($info['ans18_aaction'] != '') echo $info['ans18_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans18_date" id="ans18_date" placeholder="MM/DD/YYYY" value="<? if($info['ans18_date'] != '') echo date('m/d/Y', strtotime($info['ans18_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">g. Are there emergency response plans for dealing with medical emergencies at all Southland facilities and each job site?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans19" id="ans37" value="yes" style="display:inline-block;" <?php if ($info['ans19'] == '' || $info['ans19'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans19" id="ans38" value="no" <?php if ($info['ans19'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans19_cmt" id="ans19_cmt"  placeholder="comments" value="<? if($info['ans19_cmt'] != '') echo $info['ans19_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans19_aaction" id="ans19_aaction" value="<? if($info['ans19_aaction'] != '') echo $info['ans19_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans19_date" id="ans19_date" placeholder="MM/DD/YYYY" value="<? if($info['ans19_date'] != '') echo date('m/d/Y', strtotime($info['ans19_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">h. Is the employee alarm system that provides warning for emergency action recognizable and perceptible above ambient conditions?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans20" id="ans39" value="yes" style="display:inline-block;" <?php if ($info['ans20'] == '' || $info['ans20'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans20" id="ans40" value="no" <?php if ($info['ans20'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans20_cmt" id="ans20_cmt"  placeholder="comments" value="<? if($info['ans20_cmt'] != '') echo $info['ans20_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans20_aaction" id="ans20_aaction" value="<? if($info['ans20_aaction'] != '') echo $info['ans20_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans20_date" id="ans20_date" placeholder="MM/DD/YYYY" value="<? if($info['ans20_date'] != '') echo date('m/d/Y', strtotime($info['ans20_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">i. Are alarm systems properly maintained and tested regularly?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans21" id="ans41" value="yes" style="display:inline-block;" <?php if ($info['ans21'] == '' || $info['ans21'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans21" id="ans42" value="no" <?php if ($info['ans21'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans21_cmt" id="ans21_cmt"  placeholder="comments" value="<? if($info['ans21_cmt'] != '') echo $info['ans21_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans21_aaction" id="ans21_aaction" value="<? if($info['ans21_aaction'] != '') echo $info['ans21_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans21_date" id="ans21_date" placeholder="MM/DD/YYYY" value="<? if($info['ans21_date'] != '') echo date('m/d/Y', strtotime($info['ans21_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">j. Do employees know their responsibilities for reporting emergencies, actions during and emergency, and for performing rescue and medical duties?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans22" id="ans43" value="yes" style="display:inline-block;" <?php if ($info['ans22'] == '' || $info['ans22'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans22" id="ans44" value="no" <?php if ($info['ans22'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans22_cmt" id="ans22_cmt"  placeholder="comments" value="<? if($info['ans22_cmt'] != '') echo $info['ans22_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans22_aaction" id="ans22_aaction" value="<? if($info['ans22_aaction'] != '') echo $info['ans22_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans22_date" id="ans22_date" placeholder="MM/DD/YYYY" value="<? if($info['ans22_date'] != '') echo date('m/d/Y', strtotime($info['ans22_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">k. Is it regularly updated?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans23" id="ans45" value="yes" style="display:inline-block;" <?php if ($info['ans23'] == '' || $info['ans23'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans23" id="ans46" value="no" <?php if ($info['ans23'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans23_cmt" id="ans23_cmt"  placeholder="comments" value="<? if($info['ans23_cmt'] != '') echo $info['ans23_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans23_aaction" id="ans23_aaction" value="<? if($info['ans23_aaction'] != '') echo $info['ans23_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans23_date" id="ans23_date" placeholder="MM/DD/YYYY" value="<? if($info['ans23_date'] != '') echo date('m/d/Y', strtotime($info['ans23_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">l. Are emergency practice drills carried out at stipulated intervals?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans24" id="ans47" value="yes" style="display:inline-block;" <?php if ($info['ans24'] == '' || $info['ans24'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans24" id="ans48" value="no" <?php if ($info['ans24'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans24_cmt" id="ans24_cmt"  placeholder="comments" value="<? if($info['ans24_cmt'] != '') echo $info['ans24_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans24_aaction" id="ans24_aaction" value="<? if($info['ans24_aaction'] != '') echo $info['ans24_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans24_date" id="ans24_date" placeholder="MM/DD/YYYY" value="<? if($info['ans24_date'] != '') echo date('m/d/Y', strtotime($info['ans24_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">m. Does the layout of the area allow ease of emergency evacuation?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans25" id="ans49" value="yes" style="display:inline-block;" <?php if ($info['ans25'] == '' || $info['ans25'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans25" id="ans50" value="no" <?php if ($info['ans25'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans25_cmt" id="ans25_cmt"  placeholder="comments" value="<? if($info['ans25_cmt'] != '') echo $info['ans25_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans25_aaction" id="ans25_aaction" value="<? if($info['ans25_aaction'] != '') echo $info['ans25_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans25_date" id="ans25_date" placeholder="MM/DD/YYYY" value="<? if($info['ans25_date'] != '') echo date('m/d/Y', strtotime($info['ans25_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">n. Are fire alarm points, fire extinguishers, and emergency exits clearly marked so that they are easily seen?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans26" id="ans51" value="yes" style="display:inline-block;" <?php if ($info['ans26'] == '' || $info['ans26'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans26" id="ans52" value="no" <?php if ($info['ans26'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control"  type="text" style="" name="ans26_cmt" id="ans26_cmt"  placeholder="comments" value="<? if($info['ans26_cmt'] != '') echo $info['ans26_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans26_aaction" id="ans26_aaction" value="<? if($info['ans26_aaction'] != '') echo $info['ans26_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans26_date" id="ans26_date" placeholder="MM/DD/YYYY" value="<? if($info['ans26_date'] != '') echo date('m/d/Y', strtotime($info['ans26_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">o. Is all this equipment inspected regularly?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans27" id="ans53" value="yes" style="display:inline-block;" <?php if ($info['ans27'] == '' || $info['ans27'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans27" id="ans54" value="no" <?php if ($info['ans27'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans27_cmt" id="ans27_cmt"  placeholder="comments" value="<? if($info['ans27_cmt'] != '') echo $info['ans27_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans27_aaction" id="ans27_aaction" value="<? if($info['ans27_aaction'] != '') echo $info['ans27_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans27_date" id="ans27_date" placeholder="MM/DD/YYYY" value="<? if($info['ans27_date'] != '') echo date('m/d/Y', strtotime($info['ans27_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">p. Are all fire exit doors operable?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans28" id="ans55" value="yes" style="display:inline-block;" <?php if ($info['ans28'] == '' || $info['ans28'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans28" id="ans56" value="no" <?php if ($info['ans28'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans28_cmt" id="ans28_cmt"  placeholder="comments" value="<? if($info['ans28_cmt'] != '') echo $info['ans28_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans28_aaction" id="ans28_aaction" value="<? if($info['ans28_aaction'] != '') echo $info['ans28_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans28_date" id="ans28_date" placeholder="MM/DD/YYYY" value="<? if($info['ans28_date'] != '') echo date('m/d/Y', strtotime($info['ans28_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Emergency Action Plan --->
				
				
				<div class="col-sm-12 "><!--Training --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">3. Training</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>
					
					<p>General Training Information</p>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Are records kept documenting who provided the training, and the dates of which the training took place?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans29" id="ans57" value="yes" style="display:inline-block;" <?php if ($info['ans29'] == '' || $info['ans29'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans29" id="ans58" value="no" <?php if ($info['ans29'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans29_cmt" id="ans29_cmt"  placeholder="comments" value="<? if($info['ans29_cmt'] != '') echo $info['ans29_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans29_aaction" id="ans29_aaction" value="<? if($info['ans29_aaction'] != '') echo $info['ans29_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans29_date" id="ans29_date" placeholder="MM/DD/YYYY" value="<? if($info['ans29_date'] != '') echo date('m/d/Y', strtotime($info['ans29_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Does the employee safety training include documented quizzes/testing to verify employee understanding of the training presented on higher risk activates (confined space entry, lockout/tag out, trenching and excavation, etc.?)</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans30" id="ans59" value="yes" style="display:inline-block;" <?php if ($info['ans30'] == '' || $info['ans30'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans30" id="ans60" value="no" <?php if ($info['ans30'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans30_cmt" id="ans30_cmt"  placeholder="comments" value="<? if($info['ans30_cmt'] != '') echo $info['ans30_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans30_aaction" id="ans30_aaction" value="<? if($info['ans30_aaction'] != '') echo $info['ans30_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans30_date" id="ans30_date" placeholder="MM/DD/YYYY" value="<? if($info['ans30_date'] != '') echo date('m/d/Y', strtotime($info['ans30_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<p>Confined Spaces</p>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there evidence that all necessary employees have been trained in confined space entry?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans31" id="ans61" value="yes" style="display:inline-block;" <?php if ($info['ans31'] == '' || $info['ans31'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans31" id="ans62" value="no" <?php if ($info['ans31'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans32_cmt" id="ans32_cmt"  placeholder="comments" value="<? if($info['ans31_cmt'] != '') echo $info['ans31_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans31_aaction" id="ans31_aaction" value="<? if($info['ans31_aaction'] != '') echo $info['ans31_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans31_date" id="ans31_date" placeholder="MM/DD/YYYY" value="<? if($info['ans31_date'] != '') echo date('m/d/Y', strtotime($info['ans31_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Is there proper documentation to show that foreman have held additional safety meetings every time employees are required to enter a confined space?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans32" id="ans63" value="yes" style="display:inline-block;" <?php if ($info['ans32'] == '' || $info['ans32'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans32" id="ans64" value="no" <?php if ($info['ans32'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans32_cmt" id="ans32_cmt"  placeholder="comments" value="<? if($info['ans32_cmt'] != '') echo $info['ans32_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans32_aaction" id="ans32_aaction" value="<? if($info['ans32_aaction'] != '') echo $info['ans32_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans32_date" id="ans32_date" placeholder="MM/DD/YYYY" value="<? if($info['ans32_date'] != '') echo date('m/d/Y', strtotime($info['ans32_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<p>Scaffolding</p>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there evidence that all necessary employees have been trained in scaffolding before performing work on any scaffold?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans33" id="ans65" value="yes" style="display:inline-block;" <?php if ($info['ans33'] == '' || $info['ans33'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans33" id="ans66" value="no" <?php if ($info['ans33'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans33_cmt" id="ans33_cmt"  placeholder="comments" value="<? if($info['ans33_cmt'] != '') echo $info['ans33_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans33_aaction" id="ans33_aaction" value="<? if($info['ans33_aaction'] != '') echo $info['ans33_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans33_date" id="ans33_date" placeholder="MM/DD/YYYY" value="<? if($info['ans33_date'] != '') echo date('m/d/Y', strtotime($info['ans33_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Does the scaffold training include hazard awareness, load capacities, fall protection, and protection from falling objects?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans34" id="ans67" value="yes" style="display:inline-block;" <?php if ($info['ans34'] == '' || $info['ans34'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans34" id="ans68" value="no" <?php if ($info['ans34'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans34_cmt" id="ans34_cmt"  placeholder="comments" value="<? if($info['ans34_cmt'] != '') echo $info['ans34_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans34_aaction" id="ans34_aaction" value="<? if($info['ans34_aaction'] != '') echo $info['ans34_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans34_date" id="ans34_date" placeholder="MM/DD/YYYY" value="<? if($info['ans34_date'] != '') echo date('m/d/Y', strtotime($info['ans34_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Is there evidence that all employees have been trained on ladder safety?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans35" id="ans69" value="yes" style="display:inline-block;" <?php if ($info['ans35'] == '' || $info['ans35'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans35" id="ans70" value="no" <?php if ($info['ans35'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans35_cmt" id="ans35_cmt"  placeholder="comments" value="<? if($info['ans35_cmt'] != '') echo $info['ans35_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans35_aaction" id="ans35_aaction" value="<? if($info['ans35_aaction'] != '') echo $info['ans35_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans35_date" id="ans35_date" placeholder="MM/DD/YYYY" value="<? if($info['ans35_date'] != '') echo date('m/d/Y', strtotime($info['ans35_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<p>Tools and Equipment</p>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there evidence that all employees have been trained on proper use of construction tools and equipment?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans36" id="ans71" value="yes" style="display:inline-block;" <?php if ($info['ans36'] == '' || $info['ans36'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans36" id="ans72" value="no" <?php if ($info['ans36'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans36_cmt" id="ans36_cmt"  placeholder="comments" value="<? if($info['ans36_cmt'] != '') echo $info['ans36_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans36_aaction" id="ans36_aaction" value="<? if($info['ans36_aaction'] != '') echo $info['ans36_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans36_date" id="ans36_date" placeholder="MM/DD/YYYY" value="<? if($info['ans36_date'] != '') echo date('m/d/Y', strtotime($info['ans36_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">	<p>Hot Work</p> </div>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there evidence that all necessary employees have been trained on hot work safety?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans37" id="ans73" value="yes" style="display:inline-block;" <?php if ($info['ans37'] == '' || $info['ans37'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans37" id="ans74" value="no" <?php if ($info['ans37'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans37_cmt" id="ans37_cmt"  placeholder="comments" value="<? if($info['ans37_cmt'] != '') echo $info['ans37_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans37_aaction" id="ans37_aaction" value="<? if($info['ans37_aaction'] != '') echo $info['ans37_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans37_date" id="ans37_date" placeholder="MM/DD/YYYY" value="<? if($info['ans37_date'] != '') echo date('m/d/Y', strtotime($info['ans37_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Is there evidence that hot work permits have been obtained before the start of any hot work?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans38" id="ans75" value="yes" style="display:inline-block;" <?php if ($info['ans38'] == '' || $info['ans38'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans38" id="ans76" value="no" <?php if ($info['ans38'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans38_cmt" id="ans38_cmt"  placeholder="comments" value="<? if($info['ans38_cmt'] != '') echo $info['ans38_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans38_aaction" id="ans38_aaction" value="<? if($info['ans38_aaction'] != '') echo $info['ans38_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans38_date" id="ans38_date" placeholder="MM/DD/YYYY" value="<? if($info['ans38_date'] != '') echo date('m/d/Y', strtotime($info['ans38_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Is there evidence that all necessary employees have been trained in safe welding and burning operations?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans39" id="ans77" value="yes" style="display:inline-block;" <?php if ($info['ans39'] == '' || $info['ans39'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans39" id="ans78" value="no" <?php if ($info['ans39'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans39_cmt" id="ans39_cmt"  placeholder="comments" value="<? if($info['ans39_cmt'] != '') echo $info['ans39_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans39_aaction" id="ans39_aaction" value="<? if($info['ans39_aaction'] != '') echo $info['ans39_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans39_date" id="ans39_date" placeholder="MM/DD/YYYY" value="<? if($info['ans39_date'] != '') echo date('m/d/Y', strtotime($info['ans39_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d. Is there evidence that written authorization's from responsible authorities have been obtained before all welding and burning operations have begun?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans40" id="ans79" value="yes" style="display:inline-block;" <?php if ($info['ans40'] == '' || $info['ans40'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans40" id="ans80" value="no" <?php if ($info['ans40'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans40_cmt" id="ans40_cmt"  placeholder="comments" value="<? if($info['ans40_cmt'] != '') echo $info['ans40_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans40_aaction" id="ans40_aaction" value="<? if($info['ans40_aaction'] != '') echo $info['ans40_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans40_date" id="ans40_date" placeholder="MM/DD/YYYY" value="<? if($info['ans40_date'] != '') echo date('m/d/Y', strtotime($info['ans40_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<p>Trench Safety</p>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there evidence that all necessary employees have been trained in excavation and trench safety?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans41" id="ans81" value="yes" style="display:inline-block;" <?php if ($info['ans41'] == '' || $info['ans41'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans41" id="ans82" value="no" <?php if ($info['ans41'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans41_cmt" id="ans41_cmt"  placeholder="comments" value="<? if($info['ans41_cmt'] != '') echo $info['ans41_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans41_aaction" id="ans41_aaction" value="<? if($info['ans41_aaction'] != '') echo $info['ans41_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans41_date" id="ans41_date" placeholder="MM/DD/YYYY" value="<? if($info['ans41_date'] != '') echo date('m/d/Y', strtotime($info['ans41_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Is there evidence that a "competent person" (one who has taken an approved course in shoring" has assessed the project site conditions and shoring requirements before any work in the trench has begun? </span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans42" id="ans83" value="yes" style="display:inline-block;" <?php if ($info['ans42'] == '' || $info['ans42'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans42" id="ans84" value="no" <?php if ($info['ans42'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans42_cmt" id="ans42_cmt"  placeholder="comments" value="<? if($info['ans42_cmt'] != '') echo $info['ans42_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans42_aaction" id="ans42_aaction" value="<? if($info['ans42_aaction'] != '') echo $info['ans42_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans42_date" id="ans42_date" placeholder="MM/DD/YYYY" value="<? if($info['ans42_date'] != '') echo date('m/d/Y', strtotime($info['ans42_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Is there evidence that all employees have been trained in material storage and handling? </span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans43" id="ans85" value="yes" style="display:inline-block;" <?php if ($info['ans43'] == '' || $info['ans43'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans43" id="ans86" value="no" <?php if ($info['ans43'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans43_cmt" id="ans43_cmt"  placeholder="comments" value="<? if($info['ans43_cmt'] != '') echo $info['ans43_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans43_aaction" id="ans43_aaction" value="<? if($info['ans43_aaction'] != '') echo $info['ans43_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans43_date" id="ans43_date" placeholder="MM/DD/YYYY" value="<? if($info['ans43_date'] != '') echo date('m/d/Y', strtotime($info['ans43_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<p>Rigging</p>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there evidence that all necessary employees have been trained in safe rigging practices? </span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans44" id="ans87" value="yes" style="display:inline-block;" <?php if ($info['ans44'] == '' || $info['ans44'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans44" id="ans88" value="no" <?php if ($info['ans44'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="	" name="ans44_cmt" id="ans44_cmt"  placeholder="comments" value="<? if($info['ans44_cmt'] != '') echo $info['ans44_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans44_aaction" id="ans44_aaction" value="<? if($info['ans44_aaction'] != '') echo $info['ans44_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans44_date" id="ans44_date" placeholder="MM/DD/YYYY" value="<? if($info['ans44_date'] != '') echo date('m/d/Y', strtotime($info['ans44_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Is there evidence that only personnel certified in rigging practices have engaged in rigging activates? </span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans45" id="ans89" value="yes" style="display:inline-block;" <?php if ($info['ans45'] == '' || $info['ans45'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans45" id="ans90" value="no" <?php if ($info['ans45'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans45_cmt" id="ans45_cmt"  placeholder="comments" value="<? if($info['ans45_cmt'] != '') echo $info['ans45_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans45_aaction" id="ans45_aaction" value="<? if($info['ans45_aaction'] != '') echo $info['ans45_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans45_date" id="ans45_date" placeholder="MM/DD/YYYY" value="<? if($info['ans45_date'] != '') echo date('m/d/Y', strtotime($info['ans45_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Is there evidence that all necessary employees have been trained in crane, motor vehicles, and heavy equipment safety? </span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans46" id="ans91" value="yes" style="display:inline-block;" <?php if ($info['ans46'] == '' || $info['ans46'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans46" id="ans92" value="no" <?php if ($info['ans46'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans46_cmt" id="ans46_cmt"  placeholder="comments" value="<? if($info['ans46_cmt'] != '') echo $info['ans46_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans46_aaction" id="ans46_aaction" value="<? if($info['ans46_aaction'] != '') echo $info['ans46_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans46_date" id="ans46_date" placeholder="MM/DD/YYYY" value="<? if($info['ans46_date'] != '') echo date('m/d/Y', strtotime($info['ans46_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d. Have all qualified persons received formal classroom instruction, including manufacturer's directions, load capacities, distances, refueling, ramps, visibility, and balancer and counterbalances?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans47" id="ans93" value="yes" style="display:inline-block;" <?php if ($info['ans47'] == '' || $info['ans47'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans47" id="ans94" value="no" <?php if ($info['ans47'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans47_cmt" id="ans47_cmt"  placeholder="comments" value="<? if($info['ans47_cmt'] != '') echo $info['ans47_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans47_aaction" id="ans47_aaction" value="<? if($info['ans47_aaction'] != '') echo $info['ans47_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans47_date" id="ans47_date" placeholder="MM/DD/YYYY" value="<? if($info['ans47_date'] != '') echo date('m/d/Y', strtotime($info['ans47_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<p>Specialized Training</p>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there evidence that only necessary personnel who have been certified and trained by a qualified person will operate all cranes?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans48" id="ans95" value="yes" style="display:inline-block;" <?php if ($info['ans48'] == '' || $info['ans48'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans48" id="ans96" value="no" <?php if ($info['ans48'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans48_cmt" id="ans48_cmt"  placeholder="comments" value="<? if($info['ans48_cmt'] != '') echo $info['ans48_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans48_aaction" id="ans48_aaction" value="<? if($info['ans48_aaction'] != '') echo $info['ans48_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans48_date" id="ans48_date" placeholder="MM/DD/YYYY" value="<? if($info['ans48_date'] != '') echo date('m/d/Y', strtotime($info['ans48_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Is there evidence that only necessary personnel who have been certified and trained by a qualified person will operate all motor vehicles?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans49" id="ans97" value="yes" style="display:inline-block;" <?php if ($info['ans49'] == '' || $info['ans49'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans49" id="ans98" value="no" <?php if ($info['ans49'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans49_cmt" id="ans49_cmt"  placeholder="comments" value="<? if($info['ans49_cmt'] != '') echo $info['ans49_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans49_aaction" id="ans49_aaction" value="<? if($info['ans49_aaction'] != '') echo $info['ans49_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans49_date" id="ans49_date" placeholder="MM/DD/YYYY" value="<? if($info['ans49_date'] != '') echo date('m/d/Y', strtotime($info['ans49_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Is there evidence that only necessary personnel who have been certified and trained by a qualified person will operate all aerial-lifts?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans50" id="ans99" value="yes" style="display:inline-block;" <?php if ($info['ans50'] == '' || $info['ans50'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans50" id="ans100" value="no" <?php if ($info['ans50'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans50_cmt" id="ans50_cmt"  placeholder="comments" value="<? if($info['ans50_cmt'] != '') echo $info['ans50_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans50_aaction" id="ans50_aaction" value="<? if($info['ans50_aaction'] != '') echo $info['ans50_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans50_date" id="ans50_date" placeholder="MM/DD/YYYY" value="<? if($info['ans50_date'] != '') echo date('m/d/Y', strtotime($info['ans50_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d. Is there evidence that only necessary personnel who have been certified and trained by a qualified person will operate all forklifts?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans51" id="ans101" value="yes" style="display:inline-block;" <?php if ($info['ans51'] == '' || $info['ans51'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans51" id="ans102" value="no" <?php if ($info['ans51'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans51_cmt" id="ans51_cmt"  placeholder="comments" value="<? if($info['ans51_cmt'] != '') echo $info['ans51_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans51_aaction" id="ans51_aaction" value="<? if($info['ans51_aaction'] != '') echo $info['ans51_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans51_date" id="ans51_date" placeholder="MM/DD/YYYY" value="<? if($info['ans51_date'] != '') echo date('m/d/Y', strtotime($info['ans51_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e. Is there evidence that only necessary personnel who have been certified and trained by a qualified person will operate all scissor lifts?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans52" id="ans103" value="yes" style="display:inline-block;" <?php if ($info['ans52'] == '' || $info['ans52'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans52" id="ans104" value="no" <?php if ($info['ans52'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans52_cmt" id="ans52_cmt"  placeholder="comments" value="<? if($info['ans52_cmt'] != '') echo $info['ans52_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans52_aaction" id="ans52_aaction" value="<? if($info['ans52_aaction'] != '') echo $info['ans52_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans52_date" id="ans52_date" placeholder="MM/DD/YYYY" value="<? if($info['ans52_date'] != '') echo date('m/d/Y', strtotime($info['ans52_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f. Is there evidence that all necessary employees have been trained on respirator safety?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans53" id="ans105" value="yes" style="display:inline-block;" <?php if ($info['ans53'] == '' || $info['ans53'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans53" id="ans106" value="no" <?php if ($info['ans53'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans53_cmt" id="ans53_cmt"  placeholder="comments" value="<? if($info['ans53_cmt'] != '') echo $info['ans53_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans53_aaction" id="ans53_aaction" value="<? if($info['ans53_aaction'] != '') echo $info['ans53_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans53_date" id="ans53_date" placeholder="MM/DD/YYYY" value="<? if($info['ans53_date'] != '') echo date('m/d/Y', strtotime($info['ans53_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">g. Do foreman/supervisors receive documented regulatory compliance and hazard recognition training for their areas of responsibility?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans54" id="ans107" value="yes" style="display:inline-block;" <?php if ($info['ans54'] == '' || $info['ans54'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans54" id="ans108" value="no" <?php if ($info['ans54'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans54_cmt" id="ans54_cmt"  placeholder="comments" value="<? if($info['ans54_cmt'] != '') echo $info['ans54_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans54_aaction" id="ans54_aaction" value="<? if($info['ans54_aaction'] != '') echo $info['ans54_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans54_date" id="ans54_date" placeholder="MM/DD/YYYY" value="<? if($info['ans54_date'] != '') echo date('m/d/Y', strtotime($info['ans54_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row"> <p>Fall Protection</p> </div>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there evidence that all employees have been trained in how and when to use fall protection?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans55" id="ans109" value="yes" style="display:inline-block;" <?php if ($info['ans55'] == '' || $info['ans55'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans55" id="ans110" value="no" <?php if ($info['ans55'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans55_cmt" id="ans55_cmt"  placeholder="comments" value="<? if($info['ans55_cmt'] != '') echo $info['ans55_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans55_aaction" id="ans55_aaction" value="<? if($info['ans55_aaction'] != '') echo $info['ans55_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans55_date" id="ans55_date" placeholder="MM/DD/YYYY" value="<? if($info['ans55_date'] != '') echo date('m/d/Y', strtotime($info['ans55_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Is there evidence that all employees have been trained in how and when to inspect fall protection?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans56" id="ans111" value="yes" style="display:inline-block;" <?php if ($info['ans56'] == '' || $info['ans56'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans56" id="ans112" value="no" <?php if ($info['ans56'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans56_cmt" id="ans56_cmt"  placeholder="comments" value="<? if($info['ans56_cmt'] != '') echo $info['ans56_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans56_aaction" id="ans56_aaction" value="<? if($info['ans56_aaction'] != '') echo $info['ans56_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate" type="text" name="ans56_date" id="ans56_date" placeholder="MM/DD/YYYY" value="<? if($info['ans56_date'] != '') echo date('m/d/Y', strtotime($info['ans56_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Training --->	
				
				<div class="col-sm-12 "><!--Records--->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">4.Records</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Are records of First- Aid Injuries Available?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans57" id="ans113" value="yes" style="display:inline-block;" <?php if ($info['ans57'] == '' || $info['ans57'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans57" id="ans114" value="no" <?php if ($info['ans57'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans57_cmt" id="ans57_cmt"  placeholder="comments" value="<? if($info['ans57_cmt'] != '') echo $info['ans57_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans57_aaction" id="ans57_aaction" value="<? if($info['ans57_aaction'] != '') echo $info['ans57_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans57_date" id="ans57_date" placeholder="MM/DD/YYYY" value="<? if($info['ans57_date'] != '') echo date('m/d/Y', strtotime($info['ans57_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Are records of safety walks available?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans58" id="ans115" value="yes" style="display:inline-block;" <?php if ($info['ans58'] == '' || $info['ans58'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans58" id="ans116" value="no" <?php if ($info['ans58'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans58_cmt" id="ans58_cmt"  placeholder="comments" value="<? if($info['ans58_cmt'] != '') echo $info['ans58_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans58_aaction" id="ans58_aaction" value="<? if($info['ans58_aaction'] != '') echo $info['ans58_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans58_date" id="ans58_date" placeholder="MM/DD/YYYY" value="<? if($info['ans58_date'] != '') echo date('m/d/Y', strtotime($info['ans58_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Are records of equipment inspections available?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans59" id="ans117" value="yes" style="display:inline-block;" <?php if ($info['ans59'] == '' || $info['ans59'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans59" id="ans118" value="no" <?php if ($info['ans59'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans59_cmt" id="ans59_cmt"  placeholder="comments" value="<? if($info['ans59_cmt'] != '') echo $info['ans59_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans59_aaction" id="ans59_aaction" value="<? if($info['ans59_aaction'] != '') echo $info['ans59_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans59_date" id="ans59_date" placeholder="MM/DD/YYYY" value="<? if($info['ans59_date'] != '') echo date('m/d/Y', strtotime($info['ans59_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d. Are records of complete safety audits available?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans60" id="ans119" value="yes" style="display:inline-block;" <?php if ($info['ans60'] == '' || $info['ans60'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans60" id="ans120" value="no" <?php if ($info['ans60'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans60_cmt" id="ans60_cmt"  placeholder="comments" value="<? if($info['ans60_cmt'] != '') echo $info['ans60_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans60_aaction" id="ans60_aaction" value="<? if($info['ans60_aaction'] != '') echo $info['ans60_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans60_date" id="ans60_date" placeholder="MM/DD/YYYY" value="<? if($info['ans60_date'] != '') echo date('m/d/Y', strtotime($info['ans60_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e. Are personal safety records available?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans61" id="ans121" value="yes" style="display:inline-block;" <?php if ($info['ans61'] == '' || $info['ans61'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans61" id="ans122" value="no" <?php if ($info['ans61'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans61_cmt" id="ans61_cmt"  placeholder="comments" value="<? if($info['ans61_cmt'] != '') echo $info['ans61_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans61_aaction" id="ans61_aaction" value="<? if($info['ans61_aaction'] != '') echo $info['ans61_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans61_date" id="ans61_date" placeholder="MM/DD/YYYY" value="<? if($info['ans61_date'] != '') echo date('m/d/Y', strtotime($info['ans61_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f. Are sites performing daily muster sheets?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans62" id="ans123" value="yes" style="display:inline-block;" <?php if ($info['ans62'] == '' || $info['ans62'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans62" id="ans124" value="no" <?php if ($info['ans62'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans62_cmt" id="ans62_cmt"  placeholder="comments" value="<? if($info['ans62_cmt'] != '') echo $info['ans62_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans62_aaction" id="ans62_aaction" value="<? if($info['ans62_aaction'] != '') echo $info['ans62_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans62_date" id="ans62_date" placeholder="MM/DD/YYYY" value="<? if($info['ans62_date'] != '') echo date('m/d/Y', strtotime($info['ans62_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Records--->
				
				<div class="col-sm-12 "><!--Equipment--->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">5.Equipment</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Does each piece of equipment have an equipment manual?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans63" id="ans125" value="yes" style="display:inline-block;" <?php if ($info['ans63'] == '' || $info['ans63'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans63" id="ans126" value="no" <?php if ($info['ans63'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans63_cmt" id="ans63_cmt"  placeholder="comments" value="<? if($info['ans63_cmt'] != '') echo $info['ans63_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans63_aaction" id="ans63_aaction" value="<? if($info['ans63_aaction'] != '') echo $info['ans63_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans63_date" id="ans63_date" placeholder="MM/DD/YYYY" value="<? if($info['ans63_date'] != '') echo date('m/d/Y', strtotime($info['ans63_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Is there evidence that there have been inspections on individual pieces of equipment?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans64" id="ans127" value="yes" style="display:inline-block;" <?php if ($info['ans64'] == '' || $info['ans64'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans64" id="ans128" value="no" <?php if ($info['ans64'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans64_cmt" id="ans64_cmt"  placeholder="comments" value="<? if($info['ans64_cmt'] != '') echo $info['ans64_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans64_aaction" id="ans64_aaction" value="<? if($info['ans64_aaction'] != '') echo $info['ans64_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans64_date" id="ans64_date" placeholder="MM/DD/YYYY" value="<? if($info['ans64_date'] != '') echo date('m/d/Y', strtotime($info['ans64_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Are there equipment maintenance records available?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans65" id="ans129" value="yes" style="display:inline-block;" <?php if ($info['ans65'] == '' || $info['ans65'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans65" id="ans130" value="no" <?php if ($info['ans65'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans65_cmt" id="ans65_cmt"  placeholder="comments" value="<? if($info['ans65_cmt'] != '') echo $info['ans65_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans65_aaction" id="ans65_aaction" value="<? if($info['ans65_aaction'] != '') echo $info['ans65_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans65_date" id="ans65_date" placeholder="MM/DD/YYYY" value="<? if($info['ans65_date'] != '') echo date('m/d/Y', strtotime($info['ans65_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
						
				</div><!--Equipment--->	
				
				<div class="col-sm-12 "><!--Electrical Safety--->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">6.Electrical Safety</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there evidence that all employees have had classroom training on electrical safety?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans66" id="ans131" value="yes" style="display:inline-block;" <?php if ($info['ans66'] == '' || $info['ans66'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans66" id="ans132" value="no" <?php if ($info['ans66'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans66_cmt" id="ans66_cmt"  placeholder="comments" value="<? if($info['ans66_cmt'] != '') echo $info['ans66_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans66_aaction" id="ans66_aaction" value="<? if($info['ans66_aaction'] != '') echo $info['ans66_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans66_date" id="ans66_date" placeholder="MM/DD/YYYY" value="<? if($info['ans66_date'] != '') echo date('m/d/Y', strtotime($info['ans66_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Has an assessment process been conducted/implemented by a qualified person to identify all live electrical work hazards, tasks and exposures?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans67" id="ans133" value="yes" style="display:inline-block;" <?php if ($info['ans67'] == '' || $info['ans67'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans67" id="ans134" value="no" <?php if ($info['ans67'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans67_cmt" id="ans67_cmt"  placeholder="comments" value="<? if($info['ans67_cmt'] != '') echo $info['ans67_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans67_aaction" id="ans67_aaction" value="<? if($info['ans67_aaction'] != '') echo $info['ans67_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans67_date" id="ans67_date" placeholder="MM/DD/YYYY" value="<? if($info['ans67_date'] != '') echo date('m/d/Y', strtotime($info['ans67_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Have specific safety related work practices and training been provided, per current NFPA 70E requirements, for employees who may be exposed to open/live electrical equipment and/or components?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans68" id="ans135" value="yes" style="display:inline-block;" <?php if ($info['ans68'] == '' || $info['ans68'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans68" id="ans136" value="no" <?php if ($info['ans68'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans68_cmt" id="ans68_cmt"  placeholder="comments" value="<? if($info['ans68_cmt'] != '') echo $info['ans68_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans68_aaction" id="ans68_aaction" value="<? if($info['ans68_aaction'] != '') echo $info['ans68_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans68_date" id="ans68_date" placeholder="MM/DD/YYYY" value="<? if($info['ans68_date'] != '') echo date('m/d/Y', strtotime($info['ans68_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d. Has employee arc blast/arc flash personal protective equipment been selected by a qualified person, based on anticipated exposures and current NFPA 70E requirements, and is use strictly enforced?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans69" id="ans137" value="yes" style="display:inline-block;" <?php if ($info['ans69'] == '' || $info['ans69'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans69" id="ans138" value="no" <?php if ($info['ans69'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans69_cmt" id="ans69_cmt"  placeholder="comments" value="<? if($info['ans69_cmt'] != '') echo $info['ans69_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans69_aaction" id="ans69_aaction" value="<? if($info['ans69_aaction'] != '') echo $info['ans69_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans69_date" id="ans69_date" placeholder="MM/DD/YYYY" value="<? if($info['ans69_date'] != '') echo date('m/d/Y', strtotime($info['ans69_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e. Have insulated tools and equipment been selected by a qualified person for employees who work on live electrical systems and components, based on their exposure and current NFPA 70E requirements?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans70" id="ans139" value="yes" style="display:inline-block;" <?php if ($info['ans70'] == '' || $info['ans70'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans70" id="ans140" value="no" <?php if ($info['ans70'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans70_cmt" id="ans70_cmt"  placeholder="comments" value="<? if($info['ans70_cmt'] != '') echo $info['ans70_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans70_aaction" id="ans70_aaction" value="<? if($info['ans70_aaction'] != '') echo $info['ans70_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans70_date" id="ans70_date" placeholder="MM/DD/YYYY" value="<? if($info['ans70_date'] != '') echo date('m/d/Y', strtotime($info['ans70_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f. Is there signed documentation and certification that all employees have been retrained on electrical safety anytime the employee has a change in job assignments, in machines, or a new hazard has been introduced?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans71" id="ans141" value="yes" style="display:inline-block;" <?php if ($info['ans71'] == '' || $info['ans71'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans71" id="ans142" value="no" <?php if ($info['ans71'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans71_cmt" id="ans71_cmt"  placeholder="comments" value="<? if($info['ans71_cmt'] != '') echo $info['ans71_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans71_aaction" id="ans71_aaction" value="<? if($info['ans71_aaction'] != '') echo $info['ans71_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans71_date" id="ans71_date" placeholder="MM/DD/YYYY" value="<? if($info['ans71_date'] != '') echo date('m/d/Y', strtotime($info['ans71_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">g. Is there evidence that all personnel who may be exposed to energized equipment have received a copy of the Southland Industries Electrical Safety Program?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans72" id="ans143" value="yes" style="display:inline-block;" <?php if ($info['ans72'] == '' || $info['ans72'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans72" id="ans144" value="no" <?php if ($info['ans72'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans72_cmt" id="ans72_cmt"  placeholder="comments" value="<? if($info['ans72_cmt'] != '') echo $info['ans72_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans72_aaction" id="ans72_aaction" value="<? if($info['ans72_aaction'] != '') echo $info['ans72_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans72_date" id="ans72_date" placeholder="MM/DD/YYYY" value="<? if($info['ans72_date'] != '') echo date('m/d/Y', strtotime($info['ans72_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">h. Are your start and test teams trained to work in compliance with NFPA 70 E?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans72h" id="ans143h" value="yes" style="display:inline-block;" <?php if ($info['ans72h'] == '' || $info['ans72h'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans72h" id="ans144h" value="no" <?php if ($info['ans72h'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans72h_cmt" id="ans72h_cmt"  placeholder="comments" value="<? if($info['ans72h_cmt'] != '') echo $info['ans72h_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans72h_aaction" id="ans72h_aaction" value="<? if($info['ans72h_aaction'] != '') echo $info['ans72h_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans72h_date" id="ans72h_date" placeholder="MM/DD/YYYY" value="<? if($info['ans72h_date'] != '') echo date('m/d/Y', strtotime($info['ans72h_date']));?>">
								</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">i. Do you have documentation to show that you are having your gloves tested every 6 months?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans72i" id="ans143i" value="yes" style="display:inline-block;" <?php if ($info['ans72i'] == '' || $info['ans72i'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans72i" id="ans144i" value="no" <?php if ($info['ans72i'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans72i_cmt" id="ans72i_cmt"  placeholder="comments" value="<? if($info['ans72i_cmt'] != '') echo $info['ans72i_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans72i_aaction" id="ans72i_aaction" value="<? if($info['ans72i_aaction'] != '') echo $info['ans72i_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans72i_date" id="ans72i_date" placeholder="MM/DD/YYYY" value="<? if($info['ans72i_date'] != '') echo date('m/d/Y', strtotime($info['ans72i_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Electrical Safety--->
				
				<div class="col-sm-12 "><!--Lockout/Tagout--->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">7.Lockout/Tagout</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Is there a job specific written lockout/tag out procedure?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans73" id="ans145" value="yes" style="display:inline-block;" <?php if ($info['ans73'] == '' || $info['ans73'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans73" id="ans146" value="no" <?php if ($info['ans73'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans73_cmt" id="ans73_cmt"  placeholder="comments" value="<? if($info['ans73_cmt'] != '') echo $info['ans73_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans73_aaction" id="ans73_aaction" value="<? if($info['ans73_aaction'] != '') echo $info['ans73_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans73_date" id="ans73_date" placeholder="MM/DD/YYYY" value="<? if($info['ans73_date'] != '') echo date('m/d/Y', strtotime($info['ans73_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b. Do employees receive lockout/tagout training</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans74" id="ans147" value="yes" style="display:inline-block;" <?php if ($info['ans74'] == '' || $info['ans74'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans74" id="ans148" value="no" <?php if ($info['ans74'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans74_cmt" id="ans74_cmt"  placeholder="comments" value="<? if($info['ans74_cmt'] != '') echo $info['ans74_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans74_aaction" id="ans74_aaction" value="<? if($info['ans74_aaction'] != '') echo $info['ans74_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans74_date" id="ans74_date" placeholder="MM/DD/YYYY" value="<? if($info['ans74_date'] != '') echo date('m/d/Y', strtotime($info['ans74_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c. Is a lockout/tagout program followed to secure energized equipment during repairs and maintenance?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans75" id="ans149" value="yes" style="display:inline-block;" <?php if ($info['ans75'] == '' || $info['ans75'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans75" id="ans150" value="no" <?php if ($info['ans75'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans75_cmt" id="ans75_cmt"  placeholder="comments" value="<? if($info['ans75_cmt'] != '') echo $info['ans75_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans75_aaction" id="ans75_aaction" value="<? if($info['ans75_aaction'] != '') echo $info['ans75_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans75_date" id="ans75_date" placeholder="MM/DD/YYYY" value="<? if($info['ans75_date'] != '') echo date('m/d/Y', strtotime($info['ans75_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d. Do employees have lockout/tagout devices, tags, and locks suitable for all equipment?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans76" id="ans151" value="yes" style="display:inline-block;" <?php if ($info['ans76'] == '' || $info['ans76'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans76" id="ans152" value="no" <?php if ($info['ans76'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans76_cmt" id="ans76_cmt"  placeholder="comments" value="<? if($info['ans76_cmt'] != '') echo $info['ans76_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans76_aaction" id="ans76_aaction" value="<? if($info['ans76_aaction'] != '') echo $info['ans76_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans76_date" id="ans76_date" placeholder="MM/DD/YYYY" value="<? if($info['ans76_date'] != '') echo date('m/d/Y', strtotime($info['ans76_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e. Does each piece of equipment have written procedures for isolating it from all energy sources?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans77" id="ans153" value="yes" style="display:inline-block;" <?php if ($info['ans77'] == '' || $info['ans77'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans77" id="ans154" value="no" <?php if ($info['ans77'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans77_cmt" id="ans77_cmt"  placeholder="comments" value="<? if($info['ans77_cmt'] != '') echo $info['ans77_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans77_aaction" id="ans77_aaction" value="<? if($info['ans77_aaction'] != '') echo $info['ans77_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans77_date" id="ans77_date" placeholder="MM/DD/YYYY" value="<? if($info['ans77_date'] != '') echo date('m/d/Y', strtotime($info['ans77_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f. Are all other employees given lockout/tagout awareness training?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans78" id="ans155" value="yes" style="display:inline-block;" <?php if ($info['ans78'] == '' || $info['ans78'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans78" id="ans156" value="no" <?php if ($info['ans78'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans78_cmt" id="ans78_cmt"  placeholder="comments" value="<? if($info['ans78_cmt'] != '') echo $info['ans78_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans78_aaction" id="ans78_aaction" value="<? if($info['ans78_aaction'] != '') echo $info['ans78_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans78_date" id="ans78_date" placeholder="MM/DD/YYYY" value="<? if($info['ans78_date'] != '') echo date('m/d/Y', strtotime($info['ans78_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">g. Is there evidence that all employees have been trained on fire protection?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans79" id="ans157" value="yes" style="display:inline-block;" <?php if ($info['ans79'] == '' || $info['ans79'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans79" id="ans158" value="no" <?php if ($info['ans79'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans79_cmt" id="ans79_cmt"  placeholder="comments" value="<? if($info['ans79_cmt'] != '') echo $info['ans79_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans79_aaction" id="ans79_aaction" value="<? if($info['ans79_aaction'] != '') echo $info['ans79_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans79_date" id="ans79_date" placeholder="MM/DD/YYYY" value="<? if($info['ans79_date'] != '') echo date('m/d/Y', strtotime($info['ans79_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Lockout/Tagout--->
				
				<div class="col-sm-12 "><!--Hazard Communication--->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">8.Hazard Communication</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a. Have all Southland employees acknowledged in writing that they have received a briefing on the Hazard Communication Program and that they agree to follow all directions, written, verbal, and visual pertaining to the program?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans80" id="ans159" value="yes" style="display:inline-block;" <?php if ($info['ans80'] == '' || $info['ans80'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans80" id="ans160" value="no" <?php if ($info['ans80'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans80_cmt" id="ans80_cmt"  placeholder="comments" value="<? if($info['ans80_cmt'] != '') echo $info['ans80_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans80_aaction" id="ans80_aaction" value="<? if($info['ans80_aaction'] != '') echo $info['ans80_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans80_date" id="ans80_date" placeholder="MM/DD/YYYY" value="<? if($info['ans80_date'] != '') echo date('m/d/Y', strtotime($info['ans80_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Is the Hazard Communication Program available to all employees?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans81" id="ans161" value="yes" style="display:inline-block;" <?php if ($info['ans81'] == '' || $info['ans81'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans81" id="ans162" value="no" <?php if ($info['ans81'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control"  type="text" style="" name="ans81_cmt" id="ans81_cmt"  placeholder="comments" value="<? if($info['ans81_cmt'] != '') echo $info['ans81_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans81_aaction" id="ans81_aaction" value="<? if($info['ans81_aaction'] != '') echo $info['ans81_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans81_date" id="ans81_date" placeholder="MM/DD/YYYY" value="<? if($info['ans81_date'] != '') echo date('m/d/Y', strtotime($info['ans81_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Are there available lists of all chemical products used at company work places or stored on company property?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans82" id="ans163" value="yes" style="display:inline-block;" <?php if ($info['ans82'] == '' || $info['ans82'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans82" id="ans164" value="no" <?php if ($info['ans82'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans82_cmt" id="ans82_cmt"  placeholder="comments" value="<? if($info['ans82_cmt'] != '') echo $info['ans82_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans82_aaction" id="ans82_aaction" value="<? if($info['ans82_aaction'] != '') echo $info['ans82_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans82_date" id="ans82_date" placeholder="MM/DD/YYYY" value="<? if($info['ans82_date'] != '') echo date('m/d/Y', strtotime($info['ans82_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Are there labels of all containers of all chemicals used?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans83" id="ans165" value="yes" style="display:inline-block;" <?php if ($info['ans83'] == '' || $info['ans83'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans83" id="ans166" value="no" <?php if ($info['ans83'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans83_cmt" id="ans83_cmt"  placeholder="comments" value="<? if($info['ans83_cmt'] != '') echo $info['ans83_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans83_aaction" id="ans83_aaction" value="<? if($info['ans83_aaction'] != '') echo $info['ans83_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans83_date" id="ans83_date" placeholder="MM/DD/YYYY" value="<? if($info['ans83_date'] != '') echo date('m/d/Y', strtotime($info['ans83_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Are Safety Data Sheets readily available?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans84" id="ans167" value="yes" style="display:inline-block;" <?php if ($info['ans84'] == '' || $info['ans84'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans84" id="ans168" value="no" <?php if ($info['ans84'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans84_cmt" id="ans84_cmt"  placeholder="comments" value="<? if($info['ans84_cmt'] != '') echo $info['ans84_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans84_aaction" id="ans84_aaction" value="<? if($info['ans84_aaction'] != '') echo $info['ans84_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans84_date" id="ans84_date" placeholder="MM/DD/YYYY" value="<? if($info['ans84_date'] != '') echo date('m/d/Y', strtotime($info['ans84_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f.Are all lists of chemicals updated in a timely fashion, showing that the chemicals are actually in use or in storage?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans85" id="ans169" value="yes" style="display:inline-block;" <?php if ($info['ans85'] == '' || $info['ans85'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans85" id="ans170" value="no" <?php if ($info['ans85'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans85_cmt" id="ans85_cmt"  placeholder="comments" value="<? if($info['ans85_cmt'] != '') echo $info['ans85_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans85_aaction" id="ans85_aaction" value="<? if($info['ans85_aaction'] != '') echo $info['ans85_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans85_date" id="ans85_date" placeholder="MM/DD/YYYY" value="<? if($info['ans85_date'] != '') echo date('m/d/Y', strtotime($info['ans85_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Hazard Communication--->
				
				<div class="col-sm-12 "><!--Community Right-To-Know--->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">9.Community Right-To-Know </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.As the materials in use at Southland Industries change, are the lists of hazardous materials being submitted to local emergency planning committees?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans86" id="ans171" value="yes" style="display:inline-block;" <?php if ($info['ans86'] == '' || $info['ans86'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans86" id="ans172" value="no" <?php if ($info['ans86'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans86_cmt" id="ans86_cmt"  placeholder="comments" value="<? if($info['ans86_cmt'] != '') echo $info['ans86_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans86_aaction" id="ans86_aaction" value="<? if($info['ans86_aaction'] != '') echo $info['ans86_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans86_date" id="ans86_date" placeholder="MM/DD/YYYY" value="<? if($info['ans86_date'] != '') echo date('m/d/Y', strtotime($info['ans86_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.As the materials in use at Southland Industries change, are the lists of hazardous materials being submitted to the state emergency response commission?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans87" id="ans173" value="yes" style="display:inline-block;" <?php if ($info['ans87'] == '' || $info['ans87'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans87" id="ans174" value="no" <?php if ($info['ans87'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans87_cmt" id="ans87_cmt"  placeholder="comments" value="<? if($info['ans87_cmt'] != '') echo $info['ans87_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans87_aaction" id="ans87_aaction" value="<? if($info['ans87_aaction'] != '') echo $info['ans87_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans87_date" id="ans87_date" placeholder="MM/DD/YYYY" value="<? if($info['ans87_date'] != '') echo date('m/d/Y', strtotime($info['ans87_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.As the materials in use at Southland Industries change, are the lists of hazardous materials being submitted to the local fire department?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans88" id="ans175" value="yes" style="display:inline-block;" <?php if ($info['ans88'] == '' || $info['ans88'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans88" id="ans176" value="no" <?php if ($info['ans88'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans88_cmt" id="ans88_cmt"  placeholder="comments" value="<? if($info['ans88_cmt'] != '') echo $info['ans88_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans88_aaction" id="ans88_aaction" value="<? if($info['ans88_aaction'] != '') echo $info['ans88_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans88_date" id="ans88_date" placeholder="MM/DD/YYYY" value="<? if($info['ans88_date'] != '') echo date('m/d/Y', strtotime($info['ans88_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Are lists of chemicals maintained in a central file in the construction manager's office?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans89" id="ans177" value="yes" style="display:inline-block;" <?php if ($info['ans89'] == '' || $info['ans89'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans89" id="ans178" value="no" <?php if ($info['ans89'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans89_cmt" id="ans89_cmt"  placeholder="comments" value="<? if($info['ans89_cmt'] != '') echo $info['ans89_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans89_aaction" id="ans89_aaction" value="<? if($info['ans89_aaction'] != '') echo $info['ans89_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans89_date" id="ans89_date" placeholder="MM/DD/YYYY" value="<? if($info['ans89_date'] != '') echo date('m/d/Y', strtotime($info['ans89_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Community Right-To-Know--->
				
				<div class="col-sm-12 "><!--Ergonomics--->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">10.Ergonomics </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Are employees using the ergonomic workspace planner on connect?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans90" id="ans179" value="yes" style="display:inline-block;" <?php if ($info['ans90'] == '' || $info['ans90'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans90" id="ans180" value="no" <?php if ($info['ans90'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans90_cmt" id="ans90_cmt"  placeholder="comments" value="<? if($info['ans90_cmt'] != '') echo $info['ans90_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans90_aaction" id="ans90_aaction" value="<? if($info['ans90_aaction'] != '') echo $info['ans90_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans90_date" id="ans90_date" placeholder="MM/DD/YYYY" value="<? if($info['ans90_date'] != '') echo date('m/d/Y', strtotime($info['ans90_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Are workstations and tasks assessed for ergonomic hazards?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans91" id="ans181" value="yes" style="display:inline-block;" <?php if ($info['ans91'] == '' || $info['ans91'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans91" id="ans182" value="no" <?php if ($info['ans91'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans91_cmt" id="ans91_cmt"  placeholder="comments" value="<? if($info['ans91_cmt'] != '') echo $info['ans91_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans91_aaction" id="ans91_aaction" value="<? if($info['ans91_aaction'] != '') echo $info['ans91_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans91_date" id="ans91_date" placeholder="MM/DD/YYYY" value="<? if($info['ans91_date'] != '') echo date('m/d/Y', strtotime($info['ans91_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Ergonomics--->
				
				<div class="col-sm-12 "><!--Hearing Conservation --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">11.Hearing Conservation  </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Are noise levels being measured and are there records of the notice levels being kept?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans92" id="ans183" value="yes" style="display:inline-block;" <?php if ($info['ans92'] == '' || $info['ans92'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans92" id="ans184" value="no" <?php if ($info['ans92'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans92_cmt" id="ans92_cmt"  placeholder="comments" value="<? if($info['ans92_cmt'] != '') echo $info['ans92_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans92_aaction" id="ans92_aaction" value="<? if($info['ans92_aaction'] != '') echo $info['ans92_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans92_date" id="ans92_date" placeholder="MM/DD/YYYY" value="<? if($info['ans92_date'] != '') echo date('m/d/Y', strtotime($info['ans92_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Is there an ongoing preventive health program to educate employees in safe levels of noise and exposure, effects of noise on their health, and use of personal protection?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans93" id="ans185" value="yes" style="display:inline-block;" <?php if ($info['ans93'] == '' || $info['ans93'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans93" id="ans186" value="no" <?php if ($info['ans93'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans93_cmt" id="ans93_cmt"  placeholder="comments" value="<? if($info['ans93_cmt'] != '') echo $info['ans93_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans93_aaction" id="ans93_aaction" value="<? if($info['ans93_aaction'] != '') echo $info['ans93_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans93_date" id="ans93_date" placeholder="MM/DD/YYYY" value="<? if($info['ans93_date'] != '') echo date('m/d/Y', strtotime($info['ans93_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Hearing Conservation --->
				
				<div class="col-sm-12 "><!--Lighting --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">12.Lighting  </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Has lighting in office and shop areas adequate?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans94" id="ans187" value="yes" style="display:inline-block;" <?php if ($info['ans94'] == '' || $info['ans94'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans94" id="ans188" value="no" <?php if ($info['ans94'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans94_cmt" id="ans94_cmt"  placeholder="comments" value="<? if($info['ans94_cmt'] != '') echo $info['ans94_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans94_aaction" id="ans94_aaction" value="<? if($info['ans94_aaction'] != '') echo $info['ans94_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans94_date" id="ans94_date" placeholder="MM/DD/YYYY" value="<? if($info['ans94_date'] != '') echo date('m/d/Y', strtotime($info['ans94_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Has emergency lighting been tested?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans95" id="ans189" value="yes" style="display:inline-block;" <?php if ($info['ans95'] == '' || $info['ans95'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans95" id="ans190" value="no" <?php if ($info['ans95'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans95_aaction" id="ans95_aaction" value="<? if($info['ans95_aaction'] != '') echo $info['ans95_aaction'];?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans95_aaction" id="ans95_aaction" value="<? if($info['ans95_aaction'] != '') echo $info['ans95_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans95_date" id="ans95_date" placeholder="MM/DD/YYYY" value="<? if($info['ans95_date'] != '') echo date('m/d/Y', strtotime($info['ans95_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--Lighting --->	
				
				<div class="col-sm-12 "><!--13.PPE --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">13.PPE </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Are jobs or tasks assessed for hazards that require personal protective equipment?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans96" id="ans191" value="yes" style="display:inline-block;" <?php if ($info['ans96'] == '' || $info['ans96'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans96" id="ans192" value="no" <?php if ($info['ans96'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control"  type="text" style="" name="ans96_cmt" id="ans96_cmt"  placeholder="comments" value="<? if($info['ans96_cmt'] != '') echo $info['ans96_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans96_aaction" id="ans96_aaction" value="<? if($info['ans96_aaction'] != '') echo $info['ans96_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans96_date" id="ans96_date" placeholder="MM/DD/YYYY" value="<? if($info['ans96_date'] != '') echo date('m/d/Y', strtotime($info['ans96_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Is there personal protective equipment readily available at all job sites?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans97" id="ans193" value="yes" style="display:inline-block;" <?php if ($info['ans97'] == '' || $info['ans97'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans97" id="ans194" value="no" <?php if ($info['ans97'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans97_cmt" id="ans97_cmt"  placeholder="comments" value="<? if($info['ans97_cmt'] != '') echo $info['ans97_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans97_aaction" id="ans97_aaction" value="<? if($info['ans97_aaction'] != '') echo $info['ans97_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans97_date" id="ans97_date" placeholder="MM/DD/YYYY" value="<? if($info['ans97_date'] != '') echo date('m/d/Y', strtotime($info['ans97_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Is all protective equipment maintained in a sanitary condition and ready for use?</span>
								<span class="sp"></span>	
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans98" id="ans195" value="yes" style="display:inline-block;" <?php if ($info['ans98'] == '' || $info['ans98'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans98" id="ans196" value="no" <?php if ($info['ans98'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans98_cmt" id="ans98_cmt"  placeholder="comments" value="<? if($info['ans98_cmt'] != '') echo $info['ans98_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans98_aaction" id="ans98_aaction" value="<? if($info['ans98_aaction'] != '') echo $info['ans98_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans98_date" id="ans98_date" placeholder="MM/DD/YYYY" value="<? if($info['ans98_date'] != '') echo date('m/d/Y', strtotime($info['ans98_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Are signs regarding exits from buildings, room capacity, floor loading, exposure to x-ray, microwave, or other harmful radiation or substances posted where required?</span>
								<span class="sp"></span>	
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans99" id="ans197" value="yes" style="display:inline-block;" <?php if ($info['ans99'] == '' || $info['ans99'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans99" id="ans198" value="no" <?php if ($info['ans99'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans99_cmt" id="ans99_cmt"  placeholder="comments" value="<? if($info['ans99_cmt'] != '') echo $info['ans99_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans99_aaction" id="ans99_aaction" value="<? if($info['ans99_aaction'] != '') echo $info['ans99_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans99_date" id="ans99_date" placeholder="MM/DD/YYYY" value="<? if($info['ans99_date'] != '') echo date('m/d/Y', strtotime($info['ans99_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Is there evidence of all employees having training in proper use, maintenance and storage of all Southland provided PPE?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans100" id="ans199" value="yes" style="display:inline-block;" <?php if ($info['ans100'] == '' || $info['ans100'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans100" id="ans200" value="no" <?php if ($info['ans100'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans100_cmt" id="ans100_cmt"  placeholder="comments" value="<? if($info['ans100_cmt'] != '') echo $info['ans100_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans100_aaction" id="ans100_aaction" value="<? if($info['ans100_aaction'] != '') echo $info['ans100_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans100_date" id="ans100_date" placeholder="MM/DD/YYYY" value="<? if($info['ans100_date'] != '') echo date('m/d/Y', strtotime($info['ans100_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--13.PPE --->
				
				<div class="col-sm-12 "><!--14.Safety Management System --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">14.Safety Management System</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Does the division hold departments, locations, job sites, and/or supervisors accountable via internal charge backs, for the losses attributable to their operations?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans101" id="ans201" value="yes" style="display:inline-block;" <?php if ($info['ans101'] == '' || $info['ans101'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans101" id="ans202" value="no" <?php if ($info['ans101'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans101_cmt" id="ans101_cmt"  placeholder="comments" value="<? if($info['ans101_cmt'] != '') echo $info['ans101_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans101_aaction" id="ans101_aaction" value="<? if($info['ans101_aaction'] != '') echo $info['ans101_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans101_date" id="ans101_date" placeholder="MM/DD/YYYY" value="<? if($info['ans101_date'] != '') echo date('m/d/Y', strtotime($info['ans101_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Does the division have clearly defined, written and measureable loss prevention goals that target specific loss areas, compliance needs, or safety program operations?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans102" id="ans203" value="yes" style="display:inline-block;" <?php if ($info['ans102'] == '' || $info['ans102'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans102" id="ans204" value="no" <?php if ($info['ans102'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans102_cmt" id="ans102_cmt"  placeholder="comments" value="<? if($info['ans102_cmt'] != '') echo $info['ans102_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans102_aaction" id="ans102_aaction" value="<? if($info['ans102_aaction'] != '') echo $info['ans102_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans102_date" id="ans102_date" placeholder="MM/DD/YYYY" value="<? if($info['ans102_date'] != '') echo date('m/d/Y', strtotime($info['ans102_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Are project manager, department/division manager, and other supervisor safety management activities formally outlined, in writing, as measureable job performance evaluation requirements or as bonus prequalifies?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans103" id="ans205" value="yes" style="display:inline-block;" <?php if ($info['ans103'] == '' || $info['ans103'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans103" id="ans206" value="no" <?php if ($info['ans103'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans103_cmt" id="ans103_cmt"  placeholder="comments" value="<? if($info['ans103_cmt'] != '') echo $info['ans103_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans103_aaction" id="ans103_aaction" value="<? if($info['ans103_aaction'] != '') echo $info['ans103_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans103_date" id="ans103_date" placeholder="MM/DD/YYYY" value="<? if($info['ans103_date'] != '') echo date('m/d/Y', strtotime($info['ans103_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Is there a documented system in place for follow-up on hazards noted in the inspections and surveys indicating actions to be taken, to whom they are assigned, and a completion deadline?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans104" id="ans207" value="yes" style="display:inline-block;" <?php if ($info['ans104'] == '' || $info['ans104'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans104" id="ans208" value="no" <?php if ($info['ans104'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans104_cmt" id="ans104_cmt"  placeholder="comments" value="<? if($info['ans104_cmt'] != '') echo $info['ans104_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans104_aaction" id="ans104_aaction" value="<? if($info['ans104_aaction'] != '') echo $info['ans104_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans104_date" id="ans104_date" placeholder="MM/DD/YYYY" value="<? if($info['ans104_date'] != '') echo date('m/d/Y', strtotime($info['ans104_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Does the division have a formal process for job safety pre-planning on larger or unusual jobs?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans105" id="ans209" value="yes" style="display:inline-block;" <?php if ($info['ans105'] == '' || $info['ans105'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans105" id="ans210" value="no" <?php if ($info['ans105'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans105_cmt" id="ans105_cmt"  placeholder="comments" value="<? if($info['ans105_cmt'] != '') echo $info['ans105_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans105_aaction" id="ans105_aaction" value="<? if($info['ans105_aaction'] != '') echo $info['ans105_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans105_date" id="ans105_date" placeholder="MM/DD/YYYY" value="<? if($info['ans105_date'] != '') echo date('m/d/Y', strtotime($info['ans105_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f.Is there a clearly set out organizational structure for all safety documentation?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans106" id="ans211" value="yes" style="display:inline-block;" <?php if ($info['ans106'] == '' || $info['ans106'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans106" id="ans212" value="no" <?php if ($info['ans106'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans106_cmt" id="ans106_cmt"  placeholder="comments" value="<? if($info['ans106_cmt'] != '') echo $info['ans106_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans106_aaction" id="ans106_aaction" value="<? if($info['ans106_aaction'] != '') echo $info['ans106_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans106_date" id="ans106_date" placeholder="MM/DD/YYYY" value="<? if($info['ans106_date'] != '') echo date('m/d/Y', strtotime($info['ans106_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">g.Do all levels of management and supervision indicate a commitment to safety?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans107" id="ans213" value="yes" style="display:inline-block;" <?php if ($info['ans107'] == '' || $info['ans107'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans107" id="ans214" value="no" <?php if ($info['ans107'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans107_cmt" id="ans107_cmt"  placeholder="comments" value="<? if($info['ans107_cmt'] != '') echo $info['ans107_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans107_aaction" id="ans107_aaction" value="<? if($info['ans107_aaction'] != '') echo $info['ans107_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans107_date" id="ans107_date" placeholder="MM/DD/YYYY" value="<? if($info['ans107_date'] != '') echo date('m/d/Y', strtotime($info['ans107_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">h.Can they be seen as placing a high priority on safety as on productivity, cost, and quality?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans108" id="ans215" value="yes" style="display:inline-block;" <?php if ($info['ans108'] == '' || $info['ans108'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans108" id="ans216" value="no" <?php if ($info['ans108'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans108_cmt" id="ans108_cmt"  placeholder="comments" value="<? if($info['ans108_cmt'] != '') echo $info['ans108_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans108_aaction" id="ans108_aaction" value="<? if($info['ans108_aaction'] != '') echo $info['ans108_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans108_date" id="ans108_date" placeholder="MM/DD/YYYY" value="<? if($info['ans108_date'] != '') echo date('m/d/Y', strtotime($info['ans108_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">i.Are the encouraging discussion on safety issues and demonstrating a commitment to participate in resolving problems?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans109" id="ans217" value="yes" style="display:inline-block;" <?php if ($info['ans109'] == '' || $info['ans109'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans109" id="ans218" value="no" <?php if ($info['ans109'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans109_cmt" id="ans109_cmt"  placeholder="comments" value="<? if($info['ans109_cmt'] != '') echo $info['ans109_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans109_aaction" id="ans109_aaction" value="<? if($info['ans109_aaction'] != '') echo $info['ans109_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans109_date" id="ans109_date" placeholder="MM/DD/YYYY" value="<? if($info['ans109_date'] != '') echo date('m/d/Y', strtotime($info['ans109_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">j.Are they setting examples by following all procedures such as the wearing gloves, safety glasses, and hard hats?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans110" id="ans219" value="yes" style="display:inline-block;" <?php if ($info['ans110'] == '' || $info['ans110'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans110" id="ans220" value="no" <?php if ($info['ans110'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans110_cmt" id="ans110_cmt"  placeholder="comments" value="<? if($info['ans110_cmt'] != '') echo $info['ans110_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans110_aaction" id="ans110_aaction" value="<? if($info['ans110_aaction'] != '') echo $info['ans110_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans110_date" id="ans110_date" placeholder="MM/DD/YYYY" value="<? if($info['ans110_date'] != '') echo date('m/d/Y', strtotime($info['ans110_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">k.Is there an accountability system for ensuring employees comply with safety and health rules and hazard/injury reporting responsibilities?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans111" id="ans221" value="yes" style="display:inline-block;" <?php if ($info['ans111'] == '' || $info['ans111'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans111" id="ans222" value="no" <?php if ($info['ans111'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans111_cmt" id="ans111_cmt"  placeholder="comments" value="<? if($info['ans111_cmt'] != '') echo $info['ans111_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans111_aaction" id="ans111_aaction" value="<? if($info['ans111_aaction'] != '') echo $info['ans111_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans111_date" id="ans111_date" placeholder="MM/DD/YYYY" value="<? if($info['ans111_date'] != '') echo date('m/d/Y', strtotime($info['ans111_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">l.Does the division have a written protocol for conduction hazard assessments prior to imitating any high-risk job or task at a job site?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans112" id="ans223" value="yes" style="display:inline-block;" <?php if ($info['ans112'] == '' || $info['ans112'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans112" id="ans224" value="no" <?php if ($info['ans112'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans112_cmt" id="ans112_cmt"  placeholder="comments" value="<? if($info['ans112_cmt'] != '') echo $info['ans112_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans112_aaction" id="ans112_aaction" value="<? if($info['ans112_aaction'] != '') echo $info['ans112_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans112_date" id="ans112_date" placeholder="MM/DD/YYYY" value="<? if($info['ans112_date'] != '') echo date('m/d/Y', strtotime($info['ans112_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">m.Is a written topic/subject outline and sign-in/attendance sheet kept for all initial and on-going safety employee safety talks and training sessions?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans113" id="ans225" value="yes" style="display:inline-block;" <?php if ($info['ans113'] == '' || $info['ans113'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans113" id="ans226" value="no" <?php if ($info['ans113'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans113_cmt" id="ans113_cmt"  placeholder="comments" value="<? if($info['ans113_cmt'] != '') echo $info['ans113_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans113_aaction" id="ans113_aaction" value="<? if($info['ans113_aaction'] != '') echo $info['ans113_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans113_date" id="ans113_date" placeholder="MM/DD/YYYY" value="<? if($info['ans113_date'] != '') echo date('m/d/Y', strtotime($info['ans113_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">n.Does the division use a formal process for job safety pre-planning on larger or unusual jobs?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans114" id="ans227" value="yes" style="display:inline-block;" <?php if ($info['ans114'] == '' || $info['ans114'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>									
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans114" id="ans228" value="no" <?php if ($info['ans114'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans114_cmt" id="ans114_cmt"  placeholder="comments" value="<? if($info['ans114_cmt'] != '') echo $info['ans114_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans114_aaction" id="ans114_aaction" value="<? if($info['ans114_aaction'] != '') echo $info['ans114_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans114_date" id="ans114_date" placeholder="MM/DD/YYYY" value="<? if($info['ans114_date'] != '') echo date('m/d/Y', strtotime($info['ans114_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--14.Safety Management System --->
				
				<div class="col-sm-12 "><!--15.Substance Abuse Prevention and Control --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">15.Substance Abuse Prevention and Control</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Have personnel received training on reasonable suspicion, substance abuse recognition, drug policy implementation, enforcement procedures, etc.?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans115" id="ans229" value="yes" style="display:inline-block;" <?php if ($info['ans115'] == '' || $info['ans115'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans115" id="ans230" value="no" <?php if ($info['ans115'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans115_cmt" id="ans115_cmt"  placeholder="comments" value="<? if($info['ans115_cmt'] != '') echo $info['ans115_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans115_aaction" id="ans115_aaction" value="<? if($info['ans115_aaction'] != '') echo $info['ans115_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans115_date" id="ans115_date" placeholder="MM/DD/YYYY" value="<? if($info['ans115_date'] != '') echo date('m/d/Y', strtotime($info['ans115_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Has formal, documented training been provided for employees and supervisors on the importance, scope, limitations, and application of the Transitional Duty Policy?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans116" id="ans231" value="yes" style="display:inline-block;" <?php if ($info['ans116'] == '' || $info['ans116'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans116" id="ans232" value="no" <?php if ($info['ans116'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans116_cmt" id="ans116_cmt"  placeholder="comments" value="<? if($info['ans116_cmt'] != '') echo $info['ans116_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans116_aaction" id="ans116_aaction" value="<? if($info['ans116_aaction'] != '') echo $info['ans116_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans116_date" id="ans116_date" placeholder="MM/DD/YYYY" value="<? if($info['ans116_date'] != '') echo date('m/d/Y', strtotime($info['ans116_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--15.Substance Abuse Prevention and Control --->
								
				<div class="col-sm-12 "><!--16.Strain and Sprain Injury Prevention --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">16.Strain and Sprain Injury Prevention</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Is there a Stretch and Flex Program implemented to help reduce injuries from strains and sprains?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans117" id="ans233" value="yes" style="display:inline-block;" <?php if ($info['ans117'] == '' || $info['ans117'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans117" id="ans234" value="no" <?php if ($info['ans117'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans117_cmt" id="ans117_cmt"  placeholder="comments" value="<? if($info['ans117_cmt'] != '') echo $info['ans117_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans117_aaction" id="ans117_aaction" value="<? if($info['ans117_aaction'] != '') echo $info['ans117_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans117_date" id="ans117_date" placeholder="MM/DD/YYYY" value="<? if($info['ans117_date'] != '') echo date('m/d/Y', strtotime($info['ans117_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Does the division have documented training programs for supervisors and employees on safe material handling, storage, lifting, and layout areas (for job sites)?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans118" id="ans235" value="yes" style="display:inline-block;" <?php if ($info['ans118'] == '' || $info['ans118'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans118" id="ans236" value="no" <?php if ($info['ans118'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans118_cmt" id="ans118_cmt"  placeholder="comments" value="<? if($info['ans118_cmt'] != '') echo $info['ans118_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans118_aaction" id="ans118_aaction" value="<? if($info['ans118_aaction'] != '') echo $info['ans118_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans118_date" id="ans118_date" placeholder="MM/DD/YYYY" value="<? if($info['ans118_date'] != '') echo date('m/d/Y', strtotime($info['ans118_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Does the division Strain and Sprain Injury Prevention Program provide employee information and promote stretching/warm-up at the start of the day and prior to any heavy lifting, repetitive duty, or out-of position task, as needed?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans119" id="ans237" value="yes" style="display:inline-block;" <?php if ($info['ans119'] == '' || $info['ans119'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans119" id="ans238" value="no" <?php if ($info['ans119'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans119_cmt" id="ans119_cmt"  placeholder="comments" value="<? if($info['ans119_cmt'] != '') echo $info['ans119_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans119_aaction" id="ans119_aaction" value="<? if($info['ans119_aaction'] != '') echo $info['ans119_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans119_date" id="ans119_date" placeholder="MM/DD/YYYY" value="<? if($info['ans119_date'] != '') echo date('m/d/Y', strtotime($info['ans119_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Are ergonomic storage and layout practices and the hazards of major, material handling segments of each job identified and addressed during job safety pre-planning (and are the corrective/safety measures built-in to the plan, as appropriate)?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans120" id="ans239" value="yes" style="display:inline-block;" <?php if ($info['ans120'] == '' || $info['ans120'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans120" id="ans240" value="no" <?php if ($info['ans120'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans120_cmt" id="ans120_cmt"  placeholder="comments" value="<? if($info['ans120_cmt'] != '') echo $info['ans120_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans120_aaction" id="ans120_aaction" value="<? if($info['ans120_aaction'] != '') echo $info['ans120_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans120_date" id="ans120_date" placeholder="MM/DD/YYYY" value="<? if($info['ans120_date'] != '') echo date('m/d/Y', strtotime($info['ans120_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--16.Strain and Sprain Injury Prevention --->
				
				<div class="col-sm-12 "><!--17.Supervisor Safety Training and Development  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">17.Supervisor Safety Training and Development </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Has OSHA 10-Hour,30-Hour, or Safety Trained Supervisor (STS) certification safety training been provided for Project Managers and other long term, "core" supervisors?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans121" id="ans241" value="yes" style="display:inline-block;" <?php if ($info['ans121'] == '' || $info['ans121'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans121" id="ans242" value="no" <?php if ($info['ans121'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans121_cmt" id="ans121_cmt"  placeholder="comments" value="<? if($info['ans121_cmt'] != '') echo $info['ans121_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans121_aaction" id="ans121_aaction" value="<? if($info['ans121_aaction'] != '') echo $info['ans121_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans121_date" id="ans121_date" placeholder="MM/DD/YYYY" value="<? if($info['ans121_date'] != '') echo date('m/d/Y', strtotime($info['ans121_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Do supervisors receive documented, skills development training on how to conduct effective safety orientation and employee training sessions?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans122" id="ans243" value="yes" style="display:inline-block;" <?php if ($info['ans122'] == '' || $info['ans122'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans122" id="ans244" value="no" <?php if ($info['ans122'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans122_cmt" id="ans122_cmt"  placeholder="comments" value="<? if($info['ans122_cmt'] != '') echo $info['ans122_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans122_aaction" id="ans122_aaction" value="<? if($info['ans122_aaction'] != '') echo $info['ans122_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans122_date" id="ans122_date" placeholder="MM/DD/YYYY" value="<? if($info['ans122_date'] != '') echo date('m/d/Y', strtotime($info['ans122_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Are supervisors observed and evaluated on their basic safety training and communication skills by the safety coordinator or superintendent, including constructive feedback and coaching?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans123" id="ans245" value="yes" style="display:inline-block;" <?php if ($info['ans123'] == '' || $info['ans123'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans123" id="ans246" value="no" <?php if ($info['ans123'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans123_cmt" id="ans123_cmt"  placeholder="comments" value="<? if($info['ans123_cmt'] != '') echo $info['ans123_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans123_aaction" id="ans123_aaction" value="<? if($info['ans123_aaction'] != '') echo $info['ans123_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans123_date" id="ans123_date" placeholder="MM/DD/YYYY" value="<? if($info['ans123_date'] != '') echo date('m/d/Y', strtotime($info['ans123_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Do front line supervisors meet at least annually for safety training?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans124" id="ans247" value="yes" style="display:inline-block;" <?php if ($info['ans124'] == '' || $info['ans124'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans124" id="ans248" value="no" <?php if ($info['ans124'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans124_cmt" id="ans124_cmt"  placeholder="comments" value="<? if($info['ans124_cmt'] != '') echo $info['ans124_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans124_aaction" id="ans124_aaction" value="<? if($info['ans124_aaction'] != '') echo $info['ans124_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans124_date" id="ans124_date" placeholder="MM/DD/YYYY" value="<? if($info['ans124_date'] != '') echo date('m/d/Y', strtotime($info['ans124_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Is there a formal Safety Management training process for new supervisors?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans125" id="ans249" value="yes" style="display:inline-block;" <?php if ($info['ans125'] == '' || $info['ans125'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans125" id="ans250" value="no" <?php if ($info['ans125'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans125_cmt" id="ans125_cmt"  placeholder="comments" value="<? if($info['ans125_cmt'] != '') echo $info['ans125_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans125_aaction" id="ans125_aaction" value="<? if($info['ans125_aaction'] != '') echo $info['ans125_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans125_date" id="ans125_date" placeholder="MM/DD/YYYY" value="<? if($info['ans125_date'] != '') echo date('m/d/Y', strtotime($info['ans125_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--17.Supervisor Safety Training and Development  --->
				
				<div class="col-sm-12 "><!--18.Site Safety Elements  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">18.Site Safety Elements </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Do field supervisors keep a log/notebook or other documentation of daily safety issues, corrective measures, discussions, etc.?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans126" id="ans251" value="yes" style="display:inline-block;" <?php if ($info['ans126'] == '' || $info['ans126'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans126" id="ans252" value="no" <?php if ($info['ans126'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans126_cmt" id="ans126_cmt"  placeholder="comments" value="<? if($info['ans126_cmt'] != '') echo $info['ans126_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans126_aaction" id="ans126_aaction" value="<? if($info['ans126_aaction'] != '') echo $info['ans126_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans126_date" id="ans126_date" placeholder="MM/DD/YYYY" value="<? if($info['ans126_date'] != '') echo date('m/d/Y', strtotime($info['ans126_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Do field supervisors transmit documentation of daily safety issues and corrective measures to the responsible safety personnel?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans127" id="ans253" value="yes" style="display:inline-block;" <?php if ($info['ans127'] == '' || $info['ans127'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans127" id="ans254" value="no" <?php if ($info['ans127'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans127_cmt" id="ans127_cmt"  placeholder="comments" value="<? if($info['ans127_cmt'] != '') echo $info['ans127_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans127_aaction" id="ans127_aaction" value="<? if($info['ans127_aaction'] != '') echo $info['ans127_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans127_date" id="ans127_date" placeholder="MM/DD/YYYY" value="<? if($info['ans127_date'] != '') echo date('m/d/Y', strtotime($info['ans127_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Is there a documented safety orientation follow-up with each new hire?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans128" id="ans255" value="yes" style="display:inline-block;" <?php if ($info['ans128'] == '' || $info['ans128'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans128" id="ans256" value="no" <?php if ($info['ans128'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans128_cmt" id="ans128_cmt"  placeholder="comments" value="<? if($info['ans128_cmt'] != '') echo $info['ans128_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans128_aaction" id="ans128_aaction" value="<? if($info['ans128_aaction'] != '') echo $info['ans128_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans128_date" id="ans128_date" placeholder="MM/DD/YYYY" value="<? if($info['ans128_date'] != '') echo date('m/d/Y', strtotime($info['ans128_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Have supervisors been provided with ergonomic guidelines for setup of staging/storage areas to help reduce the potential for strain/sprain injuries?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans129" id="ans257" value="yes" style="display:inline-block;" <?php if ($info['ans129'] == '' || $info['ans129'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans129" id="ans258" value="no" <?php if ($info['ans129'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans129_cmt" id="ans129_cmt"  placeholder="comments" value="<? if($info['ans129_cmt'] != '') echo $info['ans129_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans129_aaction" id="ans129_aaction" value="<? if($info['ans129_aaction'] != '') echo $info['ans129_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans129_date" id="ans129_date" placeholder="MM/DD/YYYY" value="<? if($info['ans129_date'] != '') echo date('m/d/Y', strtotime($info['ans129_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Has a documented, Project Manager Safety Observation Process been implemented to help identify, correct and coach unsafe employee work behaviors?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans130" id="ans259" value="yes" style="display:inline-block;" <?php if ($info['ans130'] == '' || $info['ans130'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans130" id="ans260" value="no" <?php if ($info['ans130'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans130_cmt" id="ans130_cmt"  placeholder="comments" value="<? if($info['ans130_cmt'] != '') echo $info['ans130_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans130_aaction" id="ans130_aaction" value="<? if($info['ans130_aaction'] != '') echo $info['ans130_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans130_date" id="ans130_date" placeholder="MM/DD/YYYY" value="<? if($info['ans130_date'] != '') echo date('m/d/Y', strtotime($info['ans130_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--18.Site Safety Elements  --->
				
				<div class="col-sm-12 "><!--19.Supervisor Accountability  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">19.Supervisor Accountability </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Is there a site safety management handbook, safety tool kit, safety box, etc. to provide site supervisors with program implementation tools, material and guidelines?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans131" id="ans261" value="yes" style="display:inline-block;" <?php if ($info['ans131'] == '' || $info['ans131'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans131" id="ans262" value="no" <?php if ($info['ans131'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans131_cmt" id="ans131_cmt"  placeholder="comments" value="<? if($info['ans131_cmt'] != '') echo $info['ans131_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans131_aaction" id="ans131_aaction" value="<? if($info['ans131_aaction'] != '') echo $info['ans131_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans131_date" id="ans131_date" placeholder="MM/DD/YYYY" value="<? if($info['ans131_date'] != '') echo date('m/d/Y', strtotime($info['ans131_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Have safety activity performance measures been developed for Foreman/field supervisors?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans132" id="ans263" value="yes" style="display:inline-block;" <?php if ($info['ans132'] == '' || $info['ans132'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans132" id="ans264" value="no" <?php if ($info['ans132'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans132_cmt" id="ans132_cmt"  placeholder="comments" value="<? if($info['ans132_cmt'] != '') echo $info['ans132_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans132_aaction" id="ans132_aaction" value="<? if($info['ans132_aaction'] != '') echo $info['ans132_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans132_date" id="ans132_date" placeholder="MM/DD/YYYY" value="<? if($info['ans132_date'] != '') echo date('m/d/Y', strtotime($info['ans132_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Does management review, document and score each Foreman/s safety activity performance? (NOTE: This review should not be conducted by the Safety Manager)</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans133" id="ans265" value="yes" style="display:inline-block;" <?php if ($info['ans133'] == '' || $info['ans133'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans133" id="ans266" value="no" <?php if ($info['ans133'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans133_cmt" id="ans133_cmt"  placeholder="comments" value="<? if($info['ans133_cmt'] != '') echo $info['ans133_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans133_aaction" id="ans133_aaction" value="<? if($info['ans133_aaction'] != '') echo $info['ans133_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans133_date" id="ans133_date" placeholder="MM/DD/YYYY" value="<? if($info['ans133_date'] != '') echo date('m/d/Y', strtotime($info['ans133_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Have foreman/supervisor safety responsibilities been clearly defined?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans134" id="ans267" value="yes" style="display:inline-block;" <?php if ($info['ans134'] == '' || $info['ans134'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans134" id="ans268" value="no" <?php if ($info['ans134'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans134_cmt" id="ans134_cmt"  placeholder="comments" value="<? if($info['ans134_cmt'] != '') echo $info['ans134_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans134_aaction" id="ans134_aaction" value="<? if($info['ans134_aaction'] != '') echo $info['ans134_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans134_date" id="ans134_date" placeholder="MM/DD/YYYY" value="<? if($info['ans134_date'] != '') echo date('m/d/Y', strtotime($info['ans134_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Have foreman/supervisors been trained to carry out their safety responsibilities?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans135" id="ans269" value="yes" style="display:inline-block;" <?php if ($info['ans135'] == '' || $info['ans135'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans135" id="ans270" value="no" <?php if ($info['ans135'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans135_cmt" id="ans135_cmt"  placeholder="comments" value="<? if($info['ans135_cmt'] != '') echo $info['ans135_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans135_aaction" id="ans135_aaction" value="<? if($info['ans135_aaction'] != '') echo $info['ans135_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans135_date" id="ans135_date" placeholder="MM/DD/YYYY" value="<? if($info['ans135_date'] != '') echo date('m/d/Y', strtotime($info['ans135_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f.Do foreman/supervisors understand their safety responsibilities on the job site and do they have access to a copy of all applicable codes and standards and all available company safety procedures, inspection forms, SDS, and permits to fulfill their safety responsibilities?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans136" id="ans271" value="yes" style="display:inline-block;" <?php if ($info['ans136'] == '' || $info['ans136'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans136" id="ans272" value="no" <?php if ($info['ans136'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans136_cmt" id="ans136_cmt"  placeholder="comments" value="<? if($info['ans136_cmt'] != '') echo $info['ans136_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans136_aaction" id="ans136_aaction" value="<? if($info['ans136_aaction'] != '') echo $info['ans136_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans136_date" id="ans136_date" placeholder="MM/DD/YYYY" value="<? if($info['ans136_date'] != '') echo date('m/d/Y', strtotime($info['ans136_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--19.Supervisor Accountability  --->				
				
				<div class="col-sm-12 "><!--20.Violence Prevention  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">20.Violence Prevention </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Does the division have an up-to-date written Workplace Violence Prevention Policy?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans137" id="ans273" value="yes" style="display:inline-block;" <?php if ($info['ans137'] == '' || $info['ans137'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans137" id="ans274" value="no" <?php if ($info['ans137'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control"  type="text" style="" name="ans137_cmt" id="ans137_cmt"  placeholder="comments" value="<? if($info['ans137_cmt'] != '') echo $info['ans137_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input  class="form-control" style="" type="text" name="ans137_aaction" id="ans137_aaction" value="<? if($info['ans137_aaction'] != '') echo $info['ans137_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans137_date" id="ans137_date" placeholder="MM/DD/YYYY" value="<? if($info['ans137_date'] != '') echo date('m/d/Y', strtotime($info['ans137_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Have employees received training and/or guidelines on how to avoid, control and report threats or acts of violence?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans138" id="ans275" value="yes" style="display:inline-block;" <?php if ($info['ans138'] == '' || $info['ans138'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans138" id="ans276" value="no" <?php if ($info['ans138'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans138_cmt" id="ans138_cmt"  placeholder="comments" value="<? if($info['ans138_cmt'] != '') echo $info['ans138_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans138_aaction" id="ans138_aaction" value="<? if($info['ans138_aaction'] != '') echo $info['ans138_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans138_date" id="ans138_date" placeholder="MM/DD/YYYY" value="<? if($info['ans138_date'] != '') echo date('m/d/Y', strtotime($info['ans138_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--20.Violence Prevention  --->
				
				<div class="col-sm-12 "><!--21.Catastrophe Planning & Business Continuation  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">21.Catastrophe Planning & Business Continuation </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Has a formal assessment been completed identifying company exposures to catastrophic events and have specific response/planning needs been identified?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans139" id="ans277" value="yes" style="display:inline-block;" <?php if ($info['ans139'] == '' || $info['ans139'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans139" id="ans278" value="no" <?php if ($info['ans139'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans139_cmt" id="ans139_cmt"  placeholder="comments" value="<? if($info['ans139_cmt'] != '') echo $info['ans139_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans139_aaction" id="ans139_aaction" value="<? if($info['ans139_aaction'] != '') echo $info['ans139_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans139_date" id="ans139_date" placeholder="MM/DD/YYYY" value="<? if($info['ans139_date'] != '') echo date('m/d/Y', strtotime($info['ans139_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Have specific disaster response/recovery plans been developed for each targeted exposure?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans140" id="ans279" value="yes" style="display:inline-block;" <?php if ($info['ans140'] == '' || $info['ans140'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans140" id="ans280" value="no" <?php if ($info['ans140'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans140_cmt" id="ans140_cmt"  placeholder="comments" value="<? if($info['ans140_cmt'] != '') echo $info['ans140_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans140_aaction" id="ans140_aaction" value="<? if($info['ans140_aaction'] != '') echo $info['ans140_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans140_date" id="ans140_date" placeholder="MM/DD/YYYY" value="<? if($info['ans140_date'] != '') echo date('m/d/Y', strtotime($info['ans140_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Do response/recovery plans include media handling and communications provisions?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans141" id="ans281" value="yes" style="display:inline-block;" <?php if ($info['ans141'] == '' || $info['ans141'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans141" id="ans282" value="no" <?php if ($info['ans141'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans141_cmt" id="ans141_cmt"  placeholder="comments" value="<? if($info['ans141_cmt'] != '') echo $info['ans141_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans141_aaction" id="ans141_aaction" value="<? if($info['ans141_aaction'] != '') echo $info['ans141_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans141_date" id="ans141_date" placeholder="MM/DD/YYYY" value="<? if($info['ans141_date'] != '') echo date('m/d/Y', strtotime($info['ans141_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Has the division established and  "Emergency Management Team" to promptly review, evaluate, and take action on any potential catastrophic events or potential Business Interruption?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans142" id="ans283" value="yes" style="display:inline-block;" <?php if ($info['ans142'] == '' || $info['ans142'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans142" id="ans284" value="no" <?php if ($info['ans142'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans142_cmt" id="ans142_cmt"  placeholder="comments" value="<? if($info['ans142_cmt'] != '') echo $info['ans142_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control"  style="" type="text" name="ans142_aaction" id="ans142_aaction" value="<? if($info['ans142_aaction'] != '') echo $info['ans142_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans142_date" id="ans142_date" placeholder="MM/DD/YYYY" value="<? if($info['ans142_date'] != '') echo date('m/d/Y', strtotime($info['ans142_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Have supervisors and/or other critical employees revived training and/or guidelines on how to recover from catastrophic events and avoid or reduce business interruption?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans143" id="ans285" value="yes" style="display:inline-block;" <?php if ($info['ans143'] == '' || $info['ans143'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans143" id="ans286" value="no" <?php if ($info['ans143'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans143_cmt" id="ans143_cmt"  placeholder="comments" value="<? if($info['ans143_cmt'] != '') echo $info['ans143_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans143_aaction" id="ans143_aaction" value="<? if($info['ans143_aaction'] != '') echo $info['ans143_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans143_date" id="ans143_date" placeholder="MM/DD/YYYY" value="<? if($info['ans143_date'] != '') echo date('m/d/Y', strtotime($info['ans143_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--21.Catastrophe Planning & Business Continuation  --->
				
				<div class="col-sm-12 "><!--22.Managed Fall Protection Program  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">22.Managed Fall Protection Program </span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Has a complete Managed Fall Protection Program (MFPP) been implemented and does it include an outline of specific duties and responsibilities for program administration and implementation, rescue, training for qualified, competent and authorized personnel?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans144" id="ans287" value="yes" style="display:inline-block;" <?php if ($info['ans144'] == '' || $info['ans144'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans144" id="ans288" value="no" <?php if ($info['ans144'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans144_cmt" id="ans144_cmt"  placeholder="comments" value="<? if($info['ans144_cmt'] != '') echo $info['ans144_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans144_aaction" id="ans144_aaction" value="<? if($info['ans144_aaction'] != '') echo $info['ans144_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans144_date" id="ans144_date" placeholder="MM/DD/YYYY" value="<? if($info['ans144_date'] != '') echo date('m/d/Y', strtotime($info['ans144_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Have specific procedures/protocols been developed for post rescue handling of a suspended worker?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans145" id="ans289" value="yes" style="display:inline-block;" <?php if ($info['ans145'] == '' || $info['ans145'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans145" id="ans290" value="no" <?php if ($info['ans145'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans145_cmt" id="ans145_cmt"  placeholder="comments" value="<? if($info['ans145_cmt'] != '') echo $info['ans145_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans145_aaction" id="ans145_aaction" value="<? if($info['ans145_aaction'] != '') echo $info['ans145_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans145_date" id="ans145_date" placeholder="MM/DD/YYYY" value="<? if($info['ans145_date'] != '') echo date('m/d/Y', strtotime($info['ans145_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--22.Managed Fall Protection Program  --->			
				
				<div class="col-sm-12 "><!--23.Housekeeping  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">23.Housekeeping</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Are working areas, passageways, storerooms, and service rooms in a clean, orderly, and sanitary condition?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans146" id="ans291" value="yes" style="display:inline-block;" <?php if ($info['ans146'] == '' || $info['ans146'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans146" id="ans292" value="no" <?php if ($info['ans146'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans146_cmt" id="ans146_cmt"  placeholder="comments" value="<? if($info['ans146_cmt'] != '') echo $info['ans146_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input  class="form-control" style="" type="text" name="ans146_aaction" id="ans146_aaction" value="<? if($info['ans146_aaction'] != '') echo $info['ans146_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans146_date" id="ans146_date" placeholder="MM/DD/YYYY" value="<? if($info['ans146_date'] != '') echo date('m/d/Y', strtotime($info['ans146_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Are aisles clear in office, high traffic, and hazard areas?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans147" id="ans293" value="yes" style="display:inline-block;" <?php if ($info['ans147'] == '' || $info['ans147'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans147" id="ans294" value="no" <?php if ($info['ans147'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans147_cmt" id="ans147_cmt"  placeholder="comments" value="<? if($info['ans147_cmt'] != '') echo $info['ans147_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans147_aaction" id="ans147_aaction" value="<? if($info['ans147_aaction'] != '') echo $info['ans147_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans147_date" id="ans147_date" placeholder="MM/DD/YYYY" value="<? if($info['ans147_date'] != '') echo date('m/d/Y', strtotime($info['ans147_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Are doorways and hallways free of obstructions to allow for clear visibility and exit?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans148" id="ans295" value="yes" style="display:inline-block;" <?php if ($info['ans148'] == '' || $info['ans148'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans148" id="ans296" value="no" <?php if ($info['ans148'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans148_cmt" id="ans148_cmt"  placeholder="comments" value="<? if($info['ans148_cmt'] != '') echo $info['ans148_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans148_aaction" id="ans148_aaction" value="<? if($info['ans148_aaction'] != '') echo $info['ans148_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans148_date" id="ans148_date" placeholder="MM/DD/YYYY" value="<? if($info['ans148_date'] != '') echo date('m/d/Y', strtotime($info['ans148_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Are floors free of oil, grease, liquids, broken and uneven surfaces or sharp objects?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans149" id="ans297" value="yes" style="display:inline-block;" <?php if ($info['ans149'] == '' || $info['ans149'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans149" id="ans298" value="no" <?php if ($info['ans149'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans149_cmt" id="ans149_cmt"  placeholder="comments" value="<? if($info['ans149_cmt'] != '') echo $info['ans149_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans149_aaction" id="ans149_aaction" value="<? if($info['ans149_aaction'] != '') echo $info['ans149_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans149_date" id="ans149_date" placeholder="MM/DD/YYYY" value="<? if($info['ans149_date'] != '') echo date('m/d/Y', strtotime($info['ans149_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Is this area free of clutter (i.e. minimal storage of combustible materials or impede emergency egress)?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans150" id="ans299" value="yes" style="display:inline-block;" <?php if ($info['ans150'] == '' || $info['ans150'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans150" id="ans300" value="no" <?php if ($info['ans150'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans150_cmt" id="ans150_cmt"  placeholder="comments" value="<? if($info['ans150_cmt'] != '') echo $info['ans150_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans150_aaction" id="ans150_aaction" value="<? if($info['ans150_aaction'] != '') echo $info['ans150_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans150_date" id="ans150_date" placeholder="MM/DD/YYYY" value="<? if($info['ans150_date'] != '') echo date('m/d/Y', strtotime($info['ans150_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f.Are aisles or walkways near moving or operating machinery and welding operations arranged so employees will not be subjected to hazards?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans151" id="ans301" value="yes" style="display:inline-block;" <?php if ($info['ans151'] == '' || $info['ans151'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans151" id="ans302" value="no" <?php if ($info['ans151'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans151_cmt" id="ans151_cmt"  placeholder="comments" value="<? if($info['ans151_cmt'] != '') echo $info['ans151_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans151_aaction" id="ans151_aaction" value="<? if($info['ans151_aaction'] != '') echo $info['ans151_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans151_date" id="ans151_date" placeholder="MM/DD/YYYY" value="<? if($info['ans151_date'] != '') echo date('m/d/Y', strtotime($info['ans151_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">g.Have employees been trained on how to inspect and use equipment?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans152" id="ans303" value="yes" style="display:inline-block;" <?php if ($info['ans152'] == '' || $info['ans152'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans152" id="ans304" value="no" <?php if ($info['ans152'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans152_cmt" id="ans152_cmt"  placeholder="comments" value="<? if($info['ans152_cmt'] != '') echo $info['ans152_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans152_aaction" id="ans152_aaction" value="<? if($info['ans152_aaction'] != '') echo $info['ans152_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans152_date" id="ans152_date" placeholder="MM/DD/YYYY" value="<? if($info['ans152_date'] != '') echo date('m/d/Y', strtotime($info['ans152_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">h.Are all supervisors and employees familiar with and have access to the PPE Policy?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans153" id="ans305" value="yes" style="display:inline-block;" <?php if ($info['ans153'] == '' || $info['ans153'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans153" id="ans306" value="no" <?php if ($info['ans153'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans153_cmt" id="ans153_cmt"  placeholder="comments" value="<? if($info['ans153_cmt'] != '') echo $info['ans153_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans153_aaction" id="ans153_aaction" value="<? if($info['ans153_aaction'] != '') echo $info['ans153_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans153_date" id="ans153_date" placeholder="MM/DD/YYYY" value="<? if($info['ans153_date'] != '') echo date('m/d/Y', strtotime($info['ans153_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--23.Housekeeping  --->		
				
				
				<div class="col-sm-12 "><!--24.Division Safety Staff  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">24.Division Safety Staff</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Are all employees in attendance  at safety meetings?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans154" id="ans307" value="yes" style="display:inline-block;" <?php if ($info['ans154'] == '' || $info['ans154'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans154" id="ans308" value="no" <?php if ($info['ans154'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans154_cmt" id="ans154_cmt"  placeholder="comments" value="<? if($info['ans154_cmt'] != '') echo $info['ans154_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans154_aaction" id="ans154_aaction" value="<? if($info['ans154_aaction'] != '') echo $info['ans154_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans154_date" id="ans154_date" placeholder="MM/DD/YYYY" value="<? if($info['ans154_date'] != '') echo date('m/d/Y', strtotime($info['ans154_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Are all safety meeting minutes being documented and retained for three years?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans155" id="ans309" value="yes" style="display:inline-block;" <?php if ($info['ans155'] == '' || $info['ans155'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans155" id="ans310" value="no" <?php if ($info['ans155'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans155_cmt" id="ans155_cmt"  placeholder="comments" value="<? if($info['ans155_cmt'] != '') echo $info['ans155_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans155_aaction" id="ans155_aaction" value="<? if($info['ans155_aaction'] != '') echo $info['ans155_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans155_date" id="ans155_date" placeholder="MM/DD/YYYY" value="<? if($info['ans155_date'] != '') echo date('m/d/Y', strtotime($info['ans155_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Are all reports, evaluations, and recommendations of the safety committee included in the safety committee minutes?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans156" id="ans311" value="yes" style="display:inline-block;" <?php if ($info['ans156'] == '' || $info['ans156'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans156" id="ans312" value="no" <?php if ($info['ans156'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans156_cmt" id="ans156_cmt"  placeholder="comments" value="<? if($info['ans156_cmt'] != '') echo $info['ans156_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans156_aaction" id="ans156_aaction" value="<? if($info['ans156_aaction'] != '') echo $info['ans156_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans156_date" id="ans156_date" placeholder="MM/DD/YYYY" value="<? if($info['ans156_date'] != '') echo date('m/d/Y', strtotime($info['ans156_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Has the safety committee set up a system for collecting safety related suggestions, reports of hazards, or other information directly from those involved in workplace operations?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans157" id="ans313" value="yes" style="display:inline-block;" <?php if ($info['ans157'] == '' || $info['ans157'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans157" id="ans314" value="no" <?php if ($info['ans157'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans157_cmt" id="ans157_cmt"  placeholder="comments" value="<? if($info['ans157_cmt'] != '') echo $info['ans157_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans157_aaction" id="ans157_aaction" value="<? if($info['ans157_aaction'] != '') echo $info['ans157_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans157_date" id="ans157_date" placeholder="MM/DD/YYYY" value="<? if($info['ans157_date'] != '') echo date('m/d/Y', strtotime($info['ans157_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Is such information reviewed during the next safety committee meeting and recorded in the minutes?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans158" id="ans315" value="yes" style="display:inline-block;" <?php if ($info['ans158'] == '' || $info['ans158'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans158" id="ans316" value="no" <?php if ($info['ans158'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans158_cmt" id="ans158_cmt"  placeholder="comments" value="<? if($info['ans158_cmt'] != '') echo $info['ans158_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans158_aaction" id="ans158_aaction" value="<? if($info['ans158_aaction'] != '') echo $info['ans158_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans158_date" id="ans158_date" placeholder="MM/DD/YYYY" value="<? if($info['ans158_date'] != '') echo date('m/d/Y', strtotime($info['ans158_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f.Does the safety committee make written recommendations to improve the workplace safety and health program?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans159" id="ans317" value="yes" style="display:inline-block;" <?php if ($info['ans159'] == '' || $info['ans159'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans159" id="ans318" value="no" <?php if ($info['ans159'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans159_cmt" id="ans159_cmt"  placeholder="comments" value="<? if($info['ans159_cmt'] != '') echo $info['ans159_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans159_aaction" id="ans159_aaction" value="<? if($info['ans159_aaction'] != '') echo $info['ans159_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans159_date" id="ans159_date" placeholder="MM/DD/YYYY" value="<? if($info['ans159_date'] != '') echo date('m/d/Y', strtotime($info['ans159_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--24.Division Safety Staff  --->
				
				
				<div class="col-sm-12 "><!--25.Signs and Tags  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">25.Signs and Tags</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Are danger signs being used only where immediate hazards exist?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans160" id="ans319" value="yes" style="display:inline-block;" <?php if ($info['ans160'] == '' || $info['ans160'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans160" id="ans320" value="no" <?php if ($info['ans160'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans160_cmt" id="ans160_cmt"  placeholder="comments" value="<? if($info['ans160_cmt'] != '') echo $info['ans160_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans160_aaction" id="ans160_aaction" value="<? if($info['ans160_aaction'] != '') echo $info['ans160_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans160_date" id="ans160_date" placeholder="MM/DD/YYYY" value="<? if($info['ans160_date'] != '') echo date('m/d/Y', strtotime($info['ans160_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Are caution signs being used to warn of potential hazards or unsafe practices?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans161" id="ans321" value="yes" style="display:inline-block;" <?php if ($info['ans161'] == '' || $info['ans161'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans161" id="ans322" value="no" <?php if ($info['ans161'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control"  type="text" style="" name="ans161_cmt" id="ans161_cmt"  placeholder="comments" value="<? if($info['ans161_cmt'] != '') echo $info['ans161_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans161_aaction" id="ans161_aaction" value="<? if($info['ans161_aaction'] != '') echo $info['ans161_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans161_date" id="ans161_date" placeholder="MM/DD/YYYY" value="<? if($info['ans161_date'] != '') echo date('m/d/Y', strtotime($info['ans161_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--25.Signs and Tags  --->
				
				<div class="col-sm-12 "><!--26.Vehicle Safety  --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">26.Vehicle Safety</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Have all Southland employees signed the Employee Acknowledgement and Motor Vehicle Authorization Release?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans162" id="ans323" value="yes" style="display:inline-block;" <?php if ($info['ans162'] == '' || $info['ans162'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans162" id="ans324" value="no" <?php if ($info['ans162'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans162_cmt" id="ans162_cmt"  placeholder="comments" value="<? if($info['ans162_cmt'] != '') echo $info['ans162_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans162_aaction" id="ans162_aaction" value="<? if($info['ans162_aaction'] != '') echo $info['ans162_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans162_date" id="ans162_date" placeholder="MM/DD/YYYY" value="<? if($info['ans162_date'] != '') echo date('m/d/Y', strtotime($info['ans162_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Are all violations being documented and kept on file?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans163" id="ans325" value="yes" style="display:inline-block;" <?php if ($info['ans163'] == '' || $info['ans163'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans163" id="ans326" value="no" <?php if ($info['ans163'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans163_cmt" id="ans163_cmt"  placeholder="comments" value="<? if($info['ans163_cmt'] != '') echo $info['ans163_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans163_aaction" id="ans163_aaction" value="<? if($info['ans163_aaction'] != '') echo $info['ans163_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans163_date" id="ans163_date" placeholder="MM/DD/YYYY" value="<? if($info['ans163_date'] != '') echo date('m/d/Y', strtotime($info['ans163_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Are all disciplinary actions being documented and kept on file? (including reprimands, termination of driving privileges, suspensions or discharge)</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans164" id="ans327" value="yes" style="display:inline-block;" <?php if ($info['ans164'] == '' || $info['ans164'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans164" id="ans328" value="no" <?php if ($info['ans164'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style=" " name="ans164_cmt" id="ans164_cmt"  placeholder="comments" value="<? if($info['ans164_cmt'] != '') echo $info['ans164_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans164_aaction" id="ans164_aaction" value="<? if($info['ans164_aaction'] != '') echo $info['ans164_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans164_date" id="ans164_date" placeholder="MM/DD/YYYY" value="<? if($info['ans164_date'] != '') echo date('m/d/Y', strtotime($info['ans164_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Are all company vehicle approvals kept on file?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans165" id="ans329" value="yes" style="display:inline-block;" <?php if ($info['ans165'] == '' || $info['ans165'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans165" id="ans330" value="no" <?php if ($info['ans165'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control"  type="text" style="" name="ans165_cmt" id="ans165_cmt"  placeholder="comments" value="<? if($info['ans165_cmt'] != '') echo $info['ans165_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control"  style="" type="text" name="ans165_aaction" id="ans165_aaction" value="<? if($info['ans165_aaction'] != '') echo $info['ans165_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans165_date" id="ans165_date" placeholder="MM/DD/YYYY" value="<? if($info['ans165_date'] != '') echo date('m/d/Y', strtotime($info['ans165_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Are all accident reports being documented and kept on file?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans166" id="ans331" value="yes" style="display:inline-block;" <?php if ($info['ans166'] == '' || $info['ans166'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans166" id="ans332" value="no" <?php if ($info['ans166'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans166_cmt" id="ans166_cmt"  placeholder="comments" value="<? if($info['ans166_cmt'] != '') echo $info['ans166_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans166_aaction" id="ans166_aaction" value="<? if($info['ans166_aaction'] != '') echo $info['ans166_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans166_date" id="ans166_date" placeholder="MM/DD/YYYY" value="<? if($info['ans166_date'] != '') echo date('m/d/Y', strtotime($info['ans166_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">f.Are all tickets, citations, and summons documented and kept on file? (must be on file for 3 years)</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans167" id="ans333" value="yes" style="display:inline-block;" <?php if ($info['ans167'] == '' || $info['ans167'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans167" id="ans334" value="no" <?php if ($info['ans167'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans167_cmt" id="ans167_cmt"  placeholder="comments" value="<? if($info['ans167_cmt'] != '') echo $info['ans167_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans167_aaction" id="ans167_aaction" value="<? if($info['ans167_aaction'] != '') echo $info['ans167_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans167_date" id="ans167_date" placeholder="MM/DD/YYYY" value="<? if($info['ans167_date'] != '') echo date('m/d/Y', strtotime($info['ans167_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">g.Is there documented evidence that all employees have completed their defensive driver courses?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans168" id="ans335" value="yes" style="display:inline-block;" <?php if ($info['ans168'] == '' || $info['ans168'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans168" id="ans336" value="no" <?php if ($info['ans168'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans168_cmt" id="ans168_cmt"  placeholder="comments" value="<? if($info['ans168_cmt'] != '') echo $info['ans168_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans168_aaction" id="ans168_aaction" value="<? if($info['ans168_aaction'] != '') echo $info['ans168_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans168_date" id="ans168_date" placeholder="MM/DD/YYYY" value="<? if($info['ans168_date'] != '') echo date('m/d/Y', strtotime($info['ans168_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--26.Vehicle Safety  --->
				
				<div class="col-sm-12 "><!--27.Online Resources --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">27.Online Resources</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Is Next Code being used?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans169" id="ans337" value="yes" style="display:inline-block;" <?php if ($info['ans169'] == '' || $info['ans169'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans169" id="ans338" value="no" <?php if ($info['ans169'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans169_cmt" id="ans169_cmt"  placeholder="comments" value="<? if($info['ans169_cmt'] != '') echo $info['ans169_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans169_aaction" id="ans169_aaction" value="<? if($info['ans169_aaction'] != '') echo $info['ans169_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans169_date" id="ans169_date" placeholder="MM/DD/YYYY" value="<? if($info['ans169_date'] != '') echo date('m/d/Y', strtotime($info['ans169_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.Are they using NextCode to do New Hire Orientation?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans170" id="ans339" value="yes" style="display:inline-block;" <?php if ($info['ans170'] == '' || $info['ans170'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans170" id="ans340" value="no" <?php if ($info['ans170'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans170_cmt" id="ans170_cmt"  placeholder="comments" value="<? if($info['ans170_cmt'] != '') echo $info['ans170_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans170_aaction" id="ans170_aaction" value="<? if($info['ans170_aaction'] != '') echo $info['ans170_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans170_date" id="ans170_date" placeholder="MM/DD/YYYY" value="<? if($info['ans170_date'] != '') echo date('m/d/Y', strtotime($info['ans170_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.Are all NextCode tags on employees hard hats?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans171" id="ans341" value="yes" style="display:inline-block;" <?php if ($info['ans171'] == '' || $info['ans171'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans171" id="ans342" value="no" <?php if ($info['ans171'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans171_cmt" id="ans171_cmt"  placeholder="comments" value="<? if($info['ans171_cmt'] != '') echo $info['ans171_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans171_aaction" id="ans171_aaction" value="<? if($info['ans171_aaction'] != '') echo $info['ans171_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans171_date" id="ans171_date" placeholder="MM/DD/YYYY" value="<? if($info['ans171_date'] != '') echo date('m/d/Y', strtotime($info['ans171_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.Are they tracking accidents with NextCode?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans172" id="ans343" value="yes" style="display:inline-block;" <?php if ($info['ans172'] == '' || $info['ans172'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans172" id="ans344" value="no" <?php if ($info['ans172'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans172_cmt" id="ans172_cmt"  placeholder="comments" value="<? if($info['ans172_cmt'] != '') echo $info['ans172_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans172_aaction" id="ans172_aaction" value="<? if($info['ans172_aaction'] != '') echo $info['ans172_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans172_date" id="ans172_date" placeholder="MM/DD/YYYY" value="<? if($info['ans172_date'] != '') echo date('m/d/Y', strtotime($info['ans172_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">e.Are they tracking near misses with NextCode?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans173" id="ans345" value="yes" style="display:inline-block;" <?php if ($info['ans173'] == '' || $info['ans173'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans173" id="ans346" value="no" <?php if ($info['ans173'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans173_cmt" id="ans173_cmt"  placeholder="comments" value="<? if($info['ans173_cmt'] != '') echo $info['ans173_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans173_aaction" id="ans173_aaction" value="<? if($info['ans173_aaction'] != '') echo $info['ans173_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans173_date" id="ans173_date" placeholder="MM/DD/YYYY" value="<? if($info['ans173_date'] != '') echo date('m/d/Y', strtotime($info['ans173_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--27.Online Resources --->	
					
				<div class="col-sm-12 "><!--28.Opened Ended Questions --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">28.Opened Ended Questions</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<p>Incentive Program</p>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.Is there a written incentive program?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans174" id="ans347" value="yes" style="display:inline-block;" <?php if ($info['ans174'] == '' || $info['ans174'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans174" id="ans348" value="no" <?php if ($info['ans174'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans174_cmt" id="ans174_cmt"  placeholder="comments" value="<? if($info['ans174_cmt'] != '') echo $info['ans174_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans174_aaction" id="ans174_aaction" value="<? if($info['ans174_aaction'] != '') echo $info['ans174_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans174_date" id="ans174_date" placeholder="MM/DD/YYYY" value="<? if($info['ans174_date'] != '') echo date('m/d/Y', strtotime($info['ans174_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.What is being incentivized? </span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans175" id="ans349" value="yes" style="display:inline-block;" <?php if ($info['ans175'] == '' || $info['ans175'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans175" id="ans350" value="no" <?php if ($info['ans175'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans175_cmt" id="ans175_cmt"  placeholder="comments" value="<? if($info['ans175_cmt'] != '') echo $info['ans175_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans175_aaction" id="ans175_aaction" value="<? if($info['ans175_aaction'] != '') echo $info['ans175_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans175_date" id="ans175_date" placeholder="MM/DD/YYYY" value="<? if($info['ans175_date'] != '') echo date('m/d/Y', strtotime($info['ans175_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.How is the incentive program being publicly presented?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans176" id="ans351" value="yes" style="display:inline-block;" <?php if ($info['ans176'] == '' || $info['ans176'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans176" id="ans352" value="no" <?php if ($info['ans176'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans176_cmt" id="ans176_cmt"  placeholder="comments" value="<? if($info['ans176_cmt'] != '') echo $info['ans176_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans176_aaction" id="ans176_aaction" value="<? if($info['ans176_aaction'] != '') echo $info['ans176_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans176_date" id="ans176_date" placeholder="MM/DD/YYYY" value="<? if($info['ans176_date'] != '') echo date('m/d/Y', strtotime($info['ans176_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--28.Opened Ended Questions --->								
				
				<div class="col-sm-12 "><!--29.Current Accident Rate --->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">29.Current Accident Rate</span>
								<span class="sp" style="display: none;"></span>
								<span class="error"></span>
							</label>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding-right: 0px;">
									<label class="col-sm-1 control-label">Yes</label>
									<label class="col-sm-1 control-label">No</label>
									<label class="col-sm-3 control-label">Comments</label>
									<label class="col-sm-3 control-label">Agreed Action</label>								
									<label class="col-sm-3 control-label">Correction Date</label>
								</div>
							</label>							
						</div>
					</div>					
					
					<p>Over the Last Calendar Year:</p>
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">a.How many total hours did the division work?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans177" id="ans353" value="yes" style="display:inline-block;" <?php if ($info['ans177'] == '' || $info['ans177'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans177" id="ans354" value="no" <?php if ($info['ans177'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans177_cmt" id="ans177_cmt"  placeholder="comments" value="<? if($info['ans177_cmt'] != '') echo $info['ans177_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans177_aaction" id="ans177_aaction" value="<? if($info['ans177_aaction'] != '') echo $info['ans177_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans177_date" id="ans177_date" placeholder="MM/DD/YYYY" value="<? if($info['ans177_date'] != '') echo date('m/d/Y', strtotime($info['ans177_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">b.How many total lost workday cases were there?</span>
								<span class="sp"></span>
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans178" id="ans355" value="yes" style="display:inline-block;" <?php if ($info['ans178'] == '' || $info['ans178'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans178" id="ans356" value="no" <?php if ($info['ans178'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans178_cmt" id="ans178_cmt"  placeholder="comments" value="<? if($info['ans178_cmt'] != '') echo $info['ans178_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans178_aaction" id="ans178_aaction" value="<? if($info['ans178_aaction'] != '') echo $info['ans178_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans178_date" id="ans178_date" placeholder="MM/DD/YYYY" value="<? if($info['ans178_date'] != '') echo date('m/d/Y', strtotime($info['ans178_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">c.How many total recordable cases were there?</span>
								<span class="sp"></span>	
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans179" id="ans357" value="yes" style="display:inline-block;" <?php if ($info['ans179'] == '' || $info['ans179'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans179" id="ans358" value="no" <?php if ($info['ans179'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans179_cmt" id="ans179_cmt"  placeholder="comments" value="<? if($info['ans179_cmt'] != '') echo $info['ans179_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans179_aaction" id="ans179_aaction" value="<? if($info['ans179_aaction'] != '') echo $info['ans179_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans179_date" id="ans179_date" placeholder="MM/DD/YYYY" value="<? if($info['ans179_date'] != '') echo date('m/d/Y', strtotime($info['ans179_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
					<div class="col-sm-12 row">						
						<div class="form-group">
							<div class="col-sm-4">
								<span class="en">d.How many Safety Professionals are employed at this division?</span>
								<span class="sp"></span>	
							</div>
							<label class="col-sm-8 control-label" style="padding-right: 0px;">							
								<div class="col-sm-12 row" style="padding: 0px; margin: 0px;">
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans180" id="ans359" value="yes" style="display:inline-block;" <?php if ($info['ans180'] == '' || $info['ans180'] == 'yes') { ?>checked="true"<?php } ?>>
									</label>											
									<label class="col-sm-1 control-label">
										<input type="radio" name="ans180" id="ans360" value="no" <?php if ($info['ans180'] == 'no') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" type="text" style="" name="ans180_cmt" id="ans180_cmt"  placeholder="comments" value="<? if($info['ans180_cmt'] != '') echo $info['ans180_cmt']; ?>">
									</label>
									<label class="col-sm-3 control-label">
										<input class="form-control" style="" type="text" name="ans180_aaction" id="ans180_aaction" value="<? if($info['ans180_aaction'] != '') echo $info['ans180_aaction'];?>">
									</label>								
									<label class="col-sm-3 control-label">
										<input style="" class="nodate form-control" type="text" name="ans180_date" id="ans180_date" placeholder="MM/DD/YYYY" value="<? if($info['ans180_date'] != '') echo date('m/d/Y', strtotime($info['ans180_date']));?>">
									</label>
								</div>
							</label>							
						</div>
					</div>
					
				</div><!--29.Current Accident Rate --->		
				
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

<script src="/js/jquery.signaturepad.js"></script>
<style>.nodate{display:none;}.showdate{display:block;}#personal_edit{clear:both;}</style>
<script>
$(document).ready(function() {		
	$("#corporate_audit_final").validate({
		rules: {
			division: "required",
			performed_by: "required",
			caf_date: "required",
			reviewed_with: "required",
		},
	});
	$("input[name^='ans']:radio").click(function(){
		if($(this).val() == 'no'){
			$(this).parents('div:first').find('.nodate').removeClass('nodate').addClass('showdate');
		}else if($(this).val() == 'yes'){
			$(this).parents('div:first').find('.showdate').removeClass('showdate').addClass('nodate');
		}		
	});
	
	$("input[name^='ans']:checked").each(function(){
		if($(this).val() == 'no'){
			$(this).parents('div:first').find('.nodate').removeClass('nodate').addClass('showdate');
		}			
	});
	
	$("#caf_date").datetimepicker({
		lang:'en',
		timepicker:false,
		format:'m/d/Y',
		closeOnDateSelect: true,
		scrollInput: false,
	});	
	$(".nodate").datetimepicker({
		lang:'en',
		timepicker:false,
		format:'m/d/Y',
		closeOnDateSelect: true,
		scrollInput: false,
	});
});
</script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>