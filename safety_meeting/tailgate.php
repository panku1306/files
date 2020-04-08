<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';
$_SESSION['lang'] = 'en';

function validateDate($date, $format = 'Y-m-d'){
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) == $date;
}

$query_div = "SELECT * FROM divisions WHERE client = $client AND active = '1'";
$result_div = mysql_query($query_div);
while ($ob = mysql_fetch_object($result_div)) {
	$divisions[$ob->id] = $ob;
}

if ($_POST) {
	$err=0;
	while (list($index,$ob)=each($_POST)) {
		$info[$index]=ms($ob);
	}
    for($i = 1; $i<=$_POST['counter']; $i++){
        if(isset($_POST['sign_nm_'.$i]) && trim($_POST['sign_nm_'.$i])!=''){
            $info['sign_nm_'.$i] = $_POST['sign_nm_'.$i];
        }
    }

	# Division name for subject header
	foreach($divisions as $div) { 	
		if($info['foreman_division']== $div->id){
			$div_name = ucfirst($div->nickname);
		}else{
			continue;
		}
	}
	
    if (!$info['date_report']) $err+=1;	
	$val_inci = validateDate($info['date_report'], 'm/d/Y');
	if($val_inci != 1){
		$err+=1;
	}
	
	if (!$info['report_time']) $err+=2;
	$val_brth = validateDate($info['report_time'], 'H:i:s');
	if($val_brth != 1){
		$err+=2;
	}
		
	if (!$info['date_meeting']) $err+=4;
	$val_hire = validateDate($info['date_meeting'], 'm/d/Y');
	if($val_hire != 1){
		$err+=4;
	}
	
	if (!$info['safety_date']) $err+=16;
	$val_safety_date = validateDate($info['safety_date'], 'm/d/Y');
	if($val_safety_date != 1){
		$err+=16;
	}
    if (!$info['foreman_email']) $err+=32;
    $foreman_email = $info['foreman_email'];
	
	
	if(!isset($info['n_com']) || !isset($info['emer_com']) || !isset($info['osha_com'])){
		$err+=64;
	}
	if(!isset($info['sfty_com']) || !isset($info['face_com']) || !isset($info['res_com']) || !isset($info['weld_com'])){
		$err+=128;
	}
	if(!isset($info['avl_com']) || !isset($info['st_com']) || !isset($info['cpr_com']) || !isset($info['med_com'])){
		$err+=256;
	}
	if(!isset($info['cp_com']) || !isset($info['sgp_com']) || !isset($info['fp_com']) || !isset($info['clr_com'])){
		$err+=512;
	}
	if(!isset($info['free_com']) || !isset($info['sl_com'])){
		$err+=1024;
	}
	if(!isset($info['cp_com_cyl']) || !isset($info['oxy_com']) || !isset($info['empty_com'])){
		$err+=2048;
	}
	if(!isset($info['ins_com']) || !isset($info['hand_com']) || !isset($info['unsafe_com']) || !isset($info['tools_com'])){
		$err+=4096;
	}
	if(!isset($info['main_com']) || !isset($info['aisles_com']) || !isset($info['work_com'])){
		$err+=8192;
	}
	if(!isset($info['elc_com']) || !isset($info['tls_com']) || !isset($info['cords_com']) || !isset($info['wp_com'])){
		$err+=16384;
	}
	if(!isset($info['gaurd_com']) || !isset($info['frs_com']) || !isset($info['osf_com']) || !isset($info['opb_com'])){
		$err+=32768;
	}
	if(!isset($info['flm_com']) || !isset($info['adq_def']) || !isset($info['vm_na'])){
		$err+=65536;
	}
	if(!isset($info['over_com']) || !isset($info['lad_com']) || !isset($info['cp_com_2'])){
		$err+=131072;
	}
	if(!isset($info['ml_com']) || !isset($info['emp_com']) || !isset($info['hz_com']) || !isset($info['ef_com'])){
		$err+=262144;
	}
	
    if (!$err) {
	   
		$curr_wk_start = date("Y-m-d",strtotime('monday this week'));
		$curr_wk_end = date("Y-m-d",strtotime("sunday this week"));

        $squery = "INSERT INTO `weekly_tailgate` SET
            `tailgate_doc_id`='".$_POST['tailgate_doc_id']."',
            `week_start_date`='".$curr_wk_start."',
            `week_end_date`='".$curr_wk_end."',
            `date`='".date('Y-m-d',strtotime($info['date_report']))."',
            `time`='".$info['report_time']."',
            `jobsite_location`='".$info['jobsite_location']."',
            `topic`='".$info['topic']."',
            `alternate_topic`='".$info['alternate_topic']."',
            `discussion_leader`='".$info['discussion_leader']."',
            `comments_suggestion` ='".$info['comments']."',  
            `dt_nxt_meetng`='".date('Y-m-d',strtotime($info['date_meeting']))."',
            `foreman_email`='" . $info['foreman_email'] . "',
            `foreman_sign`='".$_POST['sigPad_foreman_val']."',
            `foreman_print`='".$info['foreman_print']."',
            `foreman_division`='".$info['foreman_division']."'";
            
        $insrt_det =  mysql_query($squery);
        $row_id_val = mysql_insert_id();
	    
		# Store signatures
		$counter = $_POST['counter'];
        $user_ids = array();
        
        for($i = 1;$i<=$counter;$i++){
            $check_user_id = mysql_query("SELECT * FROM `application` WHERE `first_name`='".$info['print_nm_'.$i]."' AND `last_name`='".$info['last_name_'.$i]."' AND `dob`='".date('Y-m-d', strtotime($info['dob_'.$i]))."' AND (`status`='submitted' OR `status`='approved') ORDER BY id  DESC LIMIT 1");
            
            if(mysql_num_rows($check_user_id) > 0){
                $app_user_ids = mysql_fetch_object($check_user_id);
                
                if(!empty($app_user_ids->id)){
                    array_push($user_ids, $app_user_ids->id);
                }
            }
        }
        if(!empty($user_ids)){
            $uid_string = " employee_ids ='".implode(',', $user_ids)."',";
        }else{
            $uid_string = " ";
        }

        $in_query =  "INSERT INTO `weekly_tailgate_signlist` SET `tailgate_id`='".$row_id_val."', form_data = '".serialize($_POST)."', $uid_string created='".date("Y-m-d h:i:s", time())."'";
        $updt_qr = mysql_query($in_query);
	    
		# Store joblist data
        $wtj_query = "INSERT INTO `weekly_tailgate_joblist` SET
            `tailgate_id`='".$row_id_val."',
            `job_name`='".$info['job_name']."',
            `job_number`='".$info['job_number']."',
            `checked_by`='".$info['checked_by']."',
            `date`='".date('Y-m-d',strtotime($info['safety_date']))."',
            `form_data` = '".serialize($_POST)."', 
            `comments` = '".$info['comments']."'";
            
        $updat = mysql_query($wtj_query);
        
		if($row_id_val){
            
			# Send email
            require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');				
            $mail = new NextCodeMailer();
            
            /* Gets the data from a URL */
            $url = $base_url.'/html2pdf_v4.03/examples/weekly_tailgate_pdf.php?uid='.$row_id_val;
            $binary_content = file_get_contents($url);
           
            $mail->From = 'noreply@nextcode.info';
            $mail->FromName = 'NextCode.Info';		
            
            /*if($info['foreman_division'] == '1'){
				$mail->addAddress('norcalsafety@southlandind.com');
			}else if($info['foreman_division'] == '2'){
				$mail->addAddress('socalimt@southlandind.com');
			}else if($info['foreman_division'] == '3'){
				$mail->addAddress('MWSafety@Southlandind.com');			
			}else if($info['foreman_division'] == '4'){		
				$mail->addAddress('MADSafety@southlandind.com');
			}
			
			if(isset($_POST['foreman_email']) && !empty($_POST['foreman_email'])){
				$mail->addAddress($_POST['foreman_email']);
			}*/
			
			$mail->AddBCC('si-notifications@nextcode.info');
        
            $mail->isHTML(true); # Set email format to HTML
            $mail->Subject = "Southland - $div_name Weekly Tailgate Report";
            $mail->Body    = 'There should be a PDF attached to this message with your info for weekly tailgate report. Check it out!';
            $mail->AltBody = 'There should be a PDF attached to this message with your info for weekly tailgate report. Check it out!';
            $mail->AddStringAttachment($binary_content, "safety_meeting.pdf",'base64','application/pdf');
            
            # $mail must have been created	   
            #if($mail->send()) {
            if(1) {
                $_SESSION['success_msg'] = "Weekly Tailgate report has been sent to user email.";	

                header('Location:/portal/safety_meeting/tailgate.php');
                exit;	
            }  else {
                $_SESSION['error_msg'] = "Weekly Tailgate report submitted but mail sending failed. Contact Admin!";
            }
        }else {
            $_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";
        }
	}		
}

include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; 
?>

<hr>
<div id="frame" style="height: auto;"> 
	<form id="form_val" class="form-horizontal" name="form_val" method="post" action="" enctype="multipart/form-data">
			
		<fieldset>
            <h3 class="ttext">
                Weekly Tailgate Sign-in Sheet
                <span>
                    <a href="/portal/safety_meeting/archives.php" class="btn btn-sm btn-warning" style="float:right;">
                        <i class="fa fa-fw fa-file"></i> <b>Archived Tailgate Meetings</b>
                    </a>
                <span>
            </h3>
            
			<p id="error_msg" style="color: red; font-weight: bold;text-align: center;display: none;">
				(Please input all fields marked with *)
			</p>
			<?php
			if($err&64 || $err&128 || $err&256 || $err&512 || $err&1024 || $err&2048 || $err&4096 || $err&8192 || $err&16384 || $err&32768 || $err&65536 || $err&131072 || $err&262144){
				echo '<span style="color: red; font-weight: bold; tet-align: center;">
						(Jobsite safety checklist compliant options are mandatory.)
					</span>';
			}
			?>
			<div class="clr">&nbsp;</div>
			

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
			
			<div id="personal_edit" >			
				<?php			
				$check_doc_data = mysql_query("SELECT * FROM `safety_meeting_doc` WHERE DATE(NOW()) BETWEEN `week_start_date` AND `week_end_date` ORDER BY id DESC LIMIT 1");
				if(mysql_num_rows($check_doc_data) > 0){
					$fet_details = mysql_fetch_array($check_doc_data);
					
					if($fet_details['first_aid_image'] != ''){
						$file_ext = pathinfo($fet_details['first_aid_image']);
						if($file_ext['extension'] == 'pdf'){
				?>
				<div class="text-center">			
					<iframe id="fred" style="border:none;height:700px; width:100%;" src="<?= $base_url; ?>/pdfviewer/web/viewer.html?file=<?= $base_url; ?>/uploaded_content/<?= $fet_details['first_aid_image']; ?>#page=1"></iframe>
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
				?>
				<input type="hidden" name="tailgate_doc_id" id="tailgate_doc_id" value="<?php echo $fet_details['id']; ?>" />
				<?php
				}
				?>			
				<div class="clr"><br></div>
				
				<div class="col-sm-12">
					<div class="col-sm-6" style="padding-left: 0px;"> 
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Date</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="date_report" id="date_report" readonly="true" class="form-control <?=$err&1?" error":""?>" value="<?=date("m/d/Y"); ?>">
							</div>		
						</div>
					</div>
					<div class="col-sm-6" style="padding-left: 0px;"> 
						<div class="form-group">
							<label class="col-md-5 control-label">
								<span class="en">Time</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="report_time" id="report_time" class="form-control <?=$err&2?" error":""?>" value="<? if($info['report_time'] != '') echo $info['report_time']; else echo date("H:i:s"); ?>">
							</div>		
						</div>
					</div>
				</div>
				
				<div class="col-sm-12">
					<div class="col-sm-6" style="padding-left: 0px;"> 
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Jobsite Location</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="jobsite_location" id="jobsite_location" class="form-control" value="<? if($info['jobsite_location'] != '') echo $info['jobsite_location']; ?>">
							</div>		
						</div>
					</div>
					<div class="col-sm-6" style="padding-left: 0px;"> 
						<div class="form-group">
							<label class="col-md-5 control-label">
								<span class="en">Topic</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="topic" id="topic" class="form-control" value="<?php echo $fet_details['memo_topic']?>" readonly>
							</div>		
						</div>
					</div>
				</div>			
				
				<div class="col-sm-12">
					<div class="col-sm-6" style="padding-left: 0px;"> 
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Alternate Topic</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="alternate_topic" id="alternate_topic" class="form-control" value="<? if($info['alternate_topic'] != '') echo $info['alternate_topic']; ?>">
							</div>		
						</div>
					</div>
					<div class="col-sm-6" style="padding-left: 0px;"> 
						<div class="form-group">
							<label class="col-md-5 control-label">
								<span class="en">Discussion Leader</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" class="form-control" id="discussion_leader" name="discussion_leader" value="<? if($info['discussion_leader'] != '') echo $info['discussion_leader']; ?>">
							</div>		
						</div>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				
				<?php
				$loop = isset($info['counter'])?$info['counter']:1;
				for($i=1; $i <= $loop; $i++){
				?>
				<div class="col-sm-12 nopad">
					<div class="clr"></div>
					<div class="col-sm-12">
						<div class="col-sm-6" style="padding-left: 0px;"> 
							<div class="form-group">
								<label class="col-md-4 control-label">
									<span class="en"><?php echo $i; ?>. First Name</span>
									<span class="error">*</span>
								</label>
								<div class="col-md-7">
									<input type="text" name="print_nm_<?php echo $i; ?>" id="print_nm_<?php echo $i; ?>" class="form-control amore" value="<? if($info['print_nm_'.$i] != '') echo $info['print_nm_'.$i]; ?>">
								</div>		
							</div>
						</div>
						<div class="col-sm-6" style="padding-left: 0px;"> 
							<div class="form-group">
								<label class="col-md-5 control-label">
									<span class="en">Last Name</span>
									<span class="error">*</span>
								</label>
								<div class="col-md-7">
									<input type="text" class="form-control amore" id="last_name_<?php echo $i; ?>" name="last_name_<?php echo $i; ?>" value="<? if($info['last_name_'.$i] != '') echo $info['last_name_'.$i]; ?>">
								</div>		
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="col-sm-6" style="padding-left: 0px;"> 
							<div class="form-group">
								<label class="col-md-4 control-label">
									<span class="en">Date Of Birth</span>
									<span class="error">*</span>
								</label>
								<div class="col-md-7">
									<input type="text" name="dob_<?php echo $i; ?>" id="dob_<?php echo $i; ?>" placeholder="MM/DD/YYYY" class="form-control maskd amore"  value="<?php if($info['dob_'.$i] != '') echo $info['dob_'.$i]; ?>">
								</div>		
							</div>
						</div>				
					</div>

					<div class="clr">&nbsp;</div>
					<div class="col-sm-12">
						<div class="col-sm-6 nopad">
							<label>
								<span class="en">Sign Name</span>
								<span class="error">*</span>
							</label>
						</div>
					</div>
					<div class="col-sm-12">
						<div id="sigPad_<?php echo $i; ?>">
							<div class="sig sigWrapper" style="border-radius:3px;height:110px;margin-top:5px; overflow:hidden;width:655px;">
								<div class="typed"></div>
								<canvas class="pad" width="655" height="110" style=""></canvas>
								<input type="hidden" name="sign_nm_<?php echo $i; ?>" id="sign_nm_<?php echo $i; ?>" value="<?php echo $info['output_'.$i]; ?>" class="output amore">
							</div>
							<a href="#clear" class="clearButton">Clear signature</a><br/>
						</div>
					</div>
					<div class="clr"><br></div>
				</div>
				<?php
				}
				?>
				<div id="items">
				</div>
			</div>
			<div class="col-sm-12">
				<button id="add" class="add btn btn-md btn-success">
					<span class="glyphicon glyphicon-plus"></span> Add More
				</button>
				<input type="hidden" id="counter" name="counter" value="<?php echo !empty($info['counter'])?$info['counter']:'1'; ?>">
			 </div>
			<div class="clear">&nbsp;<br><br></div>
			<div class="col-sm-12">				
				<div class="form-group">
					<label class="col-md-5 control-label">
						<span class="en">Comments/Suggestions: </span>
						<span class="error">*</span>
					</label>
				</div>				
			</div>
			<div class="col-sm-12">				
				<div class="form-group">
					<div class="col-md-8">
						<textarea name="comments" id="comments" class="form-control" rows="5" cols="50"><?php if($info['comments'] != '') echo $info['comments']; ?></textarea>
					</div>
				</div>
			</div>	
			<div class="clear">&nbsp;<br></div>

			<div class="col-sm-12">
				<div class="col-sm-7" style="padding-left: 0px;"> 
					<div class="form-group">
						<label class="col-md-4 control-label">
							<span class="en">Next Meeting Date</span>
							<span class="error">*</span>
						</label>
						<div class="col-md-7">
							<input type="text" name="date_meeting" id="date_meeting" placeholder="MM/DD/YYYY" class="form-control maskd <?=$err&4?" error":""?>"  value="<?php if($info['date_meeting'] != '') echo $info['date_meeting']; ?>">
						</div>		
					</div>
				</div>				
			</div>
				
			<div class="col-sm-12">
				<div class="col-sm-7" style="padding-left: 0px;"> 
					<div class="form-group">
						<label class="col-md-4 control-label">
							<span class="en">Foreman Sign</span>
							<span class="error">*</span>
						</label>
						<div class="col-md-7">
							<div class="sigPad_foreman">
								<div class="sig sigWrapper" style="border-radius:3px;height:110px;margin-top:5px; overflow:hidden;width:290px;">
									<div class="typed"></div>
									<canvas class="pad" height="110"></canvas>
									<input type="hidden" name="sigPad_foreman_val" id="sigPad_foreman_val" value="<?php echo $info['sigPad_foreman_val']; ?>" class="output">
								</div>								
								<a href="#clear" class="clearButton">clear signature</a><br/>
							</div>
						</div>		
					</div>
				</div>
				<div class="col-sm-5" style="padding-left: 0px;">
					<div class="form-group">
						<label class="col-md-5 control-label">
							<span class="en">Foreman print</span>
							<span class="error">*</span>
						</label>
						<div class="col-md-7">
							<input type="text" name="foreman_print" id="foreman_print" class="form-control"  value="<?php if($info['foreman_print'] != '') echo $info['foreman_print']; ?>">
						</div>		
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="col-sm-7" style="padding-left: 0px;"> 
					<div class="form-group">
						<label class="col-md-4 control-label">
							<span class="en">Foreman Division</span>
							<span class="error">*</span>
						</label>
						<div class="col-md-7">							
							<select class="form-control" name="foreman_division" id="foreman_division">
								<option value="">Select Division</option>								
								<?php 
								foreach($divisions as $div) { 			
								?>
								<option value="<?php echo $div->id; ?>" <?php echo $info['foreman_division']== $div->id?" selected":""; ?>>
									<?php echo $div->nickname; ?>
								</option>
								<?php
								}
								?>
							</select>
						</div>		
					</div>
				</div>
				<div class="col-sm-5" style="padding-left: 0px;">
					<div class="form-group">
						<label class="col-sm-5 control-label">
							<span class="en">Foreman Email:</span>
							<span class="error">*</span>                               
						</label>
						<div class="col-sm-7">
							<input type="email" name="foreman_email" id="foreman_email" class="form-control <?=$err&32?" error":""?>" value="<?php echo $info['foreman_email'];?>">
						</div>
					</div>
				</div>		
			</div>
			
			
			<div class="clear">&nbsp;<br><br><br></div>
			<h3 class="ttext">Jobsite Safety Checklist</h3>
			<div class="clear">&nbsp;</div>
			<div class="col-sm-12 nopad">
				<div class="col-sm-12">
					<div class="col-sm-7" style="padding-left: 0px;"> 
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Job Name</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="job_name" id="job_name" class="form-control" value="<? if($info['job_name'] != '') echo $info['job_name']; ?>">
							</div>		
						</div>
					</div>
					<div class="col-sm-5" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Job Number</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="job_number" id="job_number" class="form-control" value="<? if($info['job_number'] != '') echo $info['job_number']; ?>">
							</div>		
						</div>
					</div>
				</div>
				
				<div class="col-sm-12">
					<div class="col-sm-7" style="padding-left: 0px;"> 
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Checked By</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="checked_by" id="checked_by" class="form-control" value="<? if($info['checked_by'] != '') echo $info['checked_by']; ?>">							
							</div>		
						</div>
					</div>
					<div class="col-sm-5" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Date</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="safety_date" id="safety_date" class="form-control maskd <?=$err&16?" error":""?>" placeholder="MM/DD/YYYY" value="<? if($info['safety_date'] != '') echo $info['safety_date']; ?>">
							</div>		
						</div>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span>1. </span> 
							<span class="en <?=$err&64?" error":""?>">RecordKeeping</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>			
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Notices, Posters Federal 5in 1,OSHA notice,Payroll: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="n_com" id="n_com" value="C" style="float: left;" <?php if($info['n_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="n_com" id="n_def" value="D" <?php if($info['n_com'] == 'D') echo 'checked'; ?> style="float: left;">
						<span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="n_com" id="n_na" value="N" <?php if($info['n_com'] == 'N') echo 'checked'; ?> style="float: left;">
						<span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Emergency Contacts: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="emer_com" value="C" id="emer_com" style="float: left;" <?php if($info['emer_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="emer_com" value="D" id="emer_def" <?php if($info['emer_com'] == 'D') echo 'checked'; ?> style="float: left;">
						<span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="emer_com" value="N" id="emer_na" <?php if($info['emer_com'] == 'N') echo 'checked'; ?> style="float: left;">
						<span style="float: right;">N/A</span></label> 
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">c. OSHA 300 Log: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="osha_com" id="osha_com" value="C" <?php if($info['osha_com'] == 'C' ) { ?>checked="true"<?php } ?> style="float: left;"><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="osha_com" id="osha_def" value="D" <?php if($info['osha_com'] == 'D') echo 'checked'; ?> style="float: left;">
						<span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="osha_com" id="osha_na" value="N" <?php if($info['osha_com'] == 'N') echo 'checked'; ?>  style="float: left;">
						<span style="float: right;">N/A</span></label>
					</div>
				</div>
							
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">2. </span> 
							<span class="en <?=$err&128?" error":""?>">Personal Protective Equipment</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>	
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. In use safety glasses,hard hats work boots and gloves: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="sfty_com" id="sfty_com" value="C"  style="float: left;" <?php if($info['sfty_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="sfty_com" id="sfty_def" value="D" style="float: left;" <?php if($info['sfty_com'] == 'D') echo 'checked'; ?>  >
						<span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="sfty_com" id="sfty_na" value="N" style="float: left;" <?php if($info['sfty_com'] == 'N') echo 'checked'; ?>  >
						<span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Face shields oor goggles used for overhead work: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="face_com" id="face_com" value="C" style="float: left;" <?php if($info['face_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="face_com" id="face_def" value="D" style="float: left;" <?php if($info['face_com'] == 'D') echo 'checked'; ?> ><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="face_com" id="face_na" value="N" style="float: left;" <?php if($info['face_com'] == 'N') echo 'checked'; ?> ><span style="float: right;">N/A</span></label>	
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">c. Respirators available: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="res_com" id="res_com" value="C" style="float: left;" <?php if($info['res_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="res_com" id="res_def" value="D" style="float: left;" <?php if($info['res_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="res_com" id="res_na" value="N" style="float: left;" <?php if($info['res_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>	
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">d. Welding screens: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="weld_com" id="weld_com"  value="C" style="float: left;" <?php if($info['weld_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="weld_com" id="weld_def" value="D" style="float: left;" <?php if($info['weld_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="weld_com" id="weld_na" value="N" style="float: left;" <?php if($info['weld_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>	
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">3. </span> 
							<span class="en <?=$err&256?" error":""?>">First Aid Kits</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Available in gang box add jobsite trailer: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="avl_com" id="avl_com" value="C" style="float: left;" <?php if($info['avl_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="avl_com" id="avl_def" value="D" style="float: left;" <?php if($info['avl_com'] == 'D') echo 'checked'; ?>>
						<span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="avl_com" id="avl_na" value="N" style="float: left;" <?php if($info['avl_com'] == 'N') echo 'checked'; ?>>
						<span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Stocked adequately with gloves,bandages and antiseptics: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="st_com" id="st_com" value="C" style="float: left;" <?php if($info['st_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="st_com" id="st_def" value="D" style="float: left;" <?php if($info['st_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="st_com" id="st_na" value="N" style="float: left;" <?php if($info['st_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>		
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">c. CPR and First Aid trained personnel: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cpr_com" id="cpr_com" value="C" style="float: left;" <?php if($info['cpr_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cpr_com" id="cpr_def" value="D" style="float: left;" <?php if($info['cpr_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cpr_com" id="cpr_na" value="N" style="float: left;" <?php if($info['cpr_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">d. Medical Facility location and contact information communicated: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="med_com" id="med_com" value="C" style="float: left;" <?php if($info['med_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="med_com" id="med_def" value="D" style="float: left;" <?php if($info['med_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="med_com" id="med_na" value="N" style="float: left;" <?php if($info['med_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>	
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">4. </span> 
							<span class="en <?=$err&512?" error":""?>">Scaffold</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Competent person certified: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cp_com" id="cp_com" value="C" style="float: left;" <?php if($info['cp_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cp_com" id="cp_def" value="D" style="float: left;" <?php if($info['cp_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cp_com" id="cp_na" value="N" style="float: left;" <?php if($info['cp_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Scaffold grade planking: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="sgp_com" id="sgp_com" value="C" style="float: left;" <?php if($info['sgp_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="sgp_com" id="sgp_def" value="D" style="float: left;" <?php if($info['sgp_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="sgp_com" id="sgp_na" value="N" style="float: left;" <?php if($info['sgp_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">c. Fall Protection: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="fp_com" id="fp_com" value="C" style="float: left;" <?php if($info['fp_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="fp_com" id="fp_def" value="D" style="float: left;" <?php if($info['fp_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="fp_com" id="fp_na" value="N" style="float: left;" <?php if($info['fp_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label> 
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">d. Clear of trash/debris: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="clr_com" id="clr_com" value="C" style="float: left;" <?php if($info['clr_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="clr_com" id="clr_def" value="D" style="float: left;" <?php if($info['clr_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="clr_com" id="clr_na" value="N" style="float: left;" <?php if($info['clr_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>	
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">5. </span> 
							<span class="en <?=$err&1024?" error":""?>">Ladders</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Free from defects, with safet feet,blocked,cleated or otherwise secured: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="free_com" id="free_com" value="C" style="float: left;" <?php if($info['free_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="free_com" id="free_def" value="D" style="float: left;" <?php if($info['free_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="free_com" id="free_na" value="N" style="float: left;" <?php if($info['free_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. straight ladders at 1 to 4 pitch: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="sl_com" id="sl_com" value="C" style="float: left;" <?php if($info['sl_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="sl_com" id="sl_def" value="D" style="float: left;" <?php if($info['sl_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="sl_com" id="sl_na" value="N" style="float: left;" <?php if($info['sl_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">6. </span> 
							<span class="en <?=$err&2048?" error":""?>">Cylinders</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Capped,stored in an upright position: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;"> 
						<input type="radio" name="cp_com_cyl" id="cp_com_cyl" value="C" style="float: left;" <?php if($info['cp_com_cyl'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cp_com_cyl" id="cp_com_cyl_def" value="D" style="float: left;" <?php if($info['cp_com_cyl'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cp_com_cyl" id="cp_com_cyl_na" value="N" style="float: left;" <?php if($info['cp_com_cyl'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Oxygen/Accetylene property separated: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="oxy_com" id="oxy_com" value="C" style="float: left;" <?php if($info['oxy_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="oxy_com" id="oxy_def" value="D" style="float: left;" <?php if($info['oxy_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="oxy_com" id="oxy_na" value="N" style="float: left;" <?php if($info['oxy_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>	
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">c. Empty gas cylinders marked: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="empty_com" id="empty_com" value="C" style="float: left;" <?php if($info['empty_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="empty_com" id="empty_def" value="D" style="float: left;" <?php if($info['empty_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="empty_com" id="empty_na" value="N" style="float: left;" <?php if($info['empty_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">7. </span> 
							<span class="en <?=$err&4096?" error":""?>">Tools/Equipment</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Inspected to ensure safe operating condition: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="ins_com" id="ins_com" value="C" style="float: left;" <?php if( $info['ins_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="ins_com" id="ins_def" value="D" style="float: left;" <?php if($info['ins_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="ins_com" id="ins_na" value="N" style="float: left;" <?php if($info['ins_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Hand tools free from defects: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="hand_com" id="hand_com" value="C" style="float: left;" <?php if($info['hand_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="hand_com" id="hand_def" value="D" style="float: left;" <?php if($info['hand_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="hand_com" id="hand_na" value="N" style="float: left;" <?php if($info['hand_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">c. Unsafe/Unusable tools/equipment tagged "Do Not Use": </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="unsafe_com" id="unsafe_com" value="C" style="float: left;" <?php if($info['unsafe_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="unsafe_com" id="unsafe_def" value="D" style="float: left;" <?php if($info['unsafe_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="unsafe_com" id="unsafe_na" value="N" style="float: left;" <?php if($info['unsafe_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>	
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">d. Tools/Equipment property guarded: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="tools_com" id="tools_com" value="C" style="float: left;" <?php if($info['tools_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="tools_com" id="tools_def" value="D" style="float: left;" <?php if($info['tools_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="tools_com" id="tools_na" value="N" style="float: left;" <?php if($info['tools_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">8. </span> 
							<span class="en <?=$err&8192?" error":""?>">Housekeeping</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Maintained: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="main_com" id="main_com" value="C" style="float: left;" <?php if($info['main_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="main_com" id="main_def" value="D" style="float: left;" <?php if($info['main_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="main_com" id="main_na" value="N" style="float: left;" <?php if($info['main_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Aisles and exitways clear with 24" clearance: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="aisles_com" id="aisles_com" value="C" style="float: left;" <?php if($info['aisles_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="aisles_com" id="aisles_def" value="D" style="float: left;" <?php if($info['aisles_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="aisles_com" id="aisles_na" value="N" style="float: left;" <?php if($info['aisles_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>	
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">c. Work areas unculttered and debris removed: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="work_com" id="work_com" value="C" style="float: left;" <?php if($info['work_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="work_com" id="work_def" value="D" style="float: left;" <?php if($info['work_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="work_com" id="work_na" value="N" style="float: left;" <?php if($info['work_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">9. </span> 
							<span class="en <?=$err&16384?" error":""?>">Electrical</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>			
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Electrical equipment gounded: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="elc_com" id="elc_com" value="C" style="float: left;" <?php if($info['elc_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="elc_com" id="elc_def" value="D" style="float: left;" <?php if($info['elc_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="elc_com" id="elc_na" value="N" style="float: left;" <?php if($info['elc_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Tools doubled insulted: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="tls_com" id="tls_com" value="C" style="float: left;" <?php if($info['elc_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="tls_com" id="tls_def"  value="D" style="float: left;" <?php if($info['tls_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="tls_com" id="tls_na" value="N" style="float: left;" <?php if($info['tls_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">c. Cords in good condition: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cords_com" id="cords_com" value="C" style="float: left;" <?php if($info['cords_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cords_com" id="cords_def" value="D" style="float: left;" <?php if($info['cords_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cords_com" id="cords_na" value="N" style="float: left;" <?php if($info['cords_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">d. Electrical pannels covered if energized: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="wp_com" id="wp_com" value="C" style="float: left;" <?php if($info['wp_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="wp_com" id="wp_def" value="D" style="float: left;" <?php if($info['wp_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="wp_com" id="wp_na" value="N" style="float: left;" <?php if($info['wp_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">10. </span> 
							<span class="en <?=$err&32768?" error":""?>">Fall Protection</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Guardrails,midrails,toeboards: </span>pie:</span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="gaurd_com" id="gaurd_com" value="C" style="float: left;" <?php if($info['gaurd_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="gaurd_com" id="gaurd_def" value="D" style="float: left;" <?php if($info['gaurd_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="gaurd_com" id="gaurd_na" value="N" style="float: left;" <?php if($info['gaurd_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Fall restraint systems: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="frs_com" id="frs_com" value="C" style="float: left;" <?php if($info['frs_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="frs_com" id="frs_def" value="D" style="float: left;" <?php if($info['frs_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="frs_com" id="frs_na" value="N" style="float: left;" <?php if($info['frs_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">c. Open sided floors or platforms equiped with standard railing: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="osf_com" id="osf_com" value="C" style="float: left;" <?php if($info['osf_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="osf_com" id="osf_def" value="D" style="float: left;" <?php if($info['osf_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="osf_com" id="osf_na" value="N" style="float: left;" <?php if($info['osf_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">d. Opening (interior/perimeter) properly barricaded or covered: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="opb_com" id="opb_com" value="C" style="float: left;" <?php if($info['opb_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="opb_com" id="opb_def" value="D" style="float: left;" <?php if($info['opb_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="opb_com" id="opb_na" value="N" style="float: left;" <?php if($info['opb_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">11. </span> 
							<span class="en <?=$err&65536?" error":""?>">Fire Prevention</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Flammable and explosive materials stored safely: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="flm_com" id="flm_com" value="C" style="float: left;" <?php if($info['flm_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="flm_com" id="flm_def" value="D" style="float: left;" <?php if($info['flm_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="flm_com" id="flm_na" value="N" style="float: left;" <?php if($info['flm_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Adequate number of fire extinguishers available with tags and clips: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="adq_def" id="adf_com" value="C" style="float: left;" <?php if($info['adq_def'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="adq_def" id="adq_def" value="D" style="float: left;" <?php if($info['adq_def'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="adq_def" id="adf_na" value="N" style="float: left;" <?php if($info['adq_def'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>			
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">c. Vehicles and mobile equipment provided with extinguishers: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="vm_na" id="vm_com" value="C" style="float: left;" <?php if($info['vm_na'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="vm_na" id="vm_def" value="D" style="float: left;" <?php if($info['vm_na'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="vm_na" id="vm_na" value="N" style="float: left;" <?php if($info['vm_na'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">12. </span> 
							<span class="en <?=$err&131072?" error":""?>">Excavations</span>
							<span class="error">*</span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. Over 4 ft shored, benched or sloped as required: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="over_com" id="over_com" value="C" style="float: left;" <?php if($info['over_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="over_com" id="over_def" value="D" style="float: left;" <?php if($info['over_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="over_com" id="over_na" value="N" style="float: left;" <?php if($info['over_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Steps or ladders at 25 ft intervals: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="lad_com" id="lad_com" value="C" style="float: left;" <?php if($info['lad_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="lad_com" id="lad_def" value="D" style="float: left;" <?php if($info['lad_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="lad_com" id="lad_na" value="N" style="float: left;" <?php if($info['lad_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">c. Competent person on site: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cp_com_2" id="cp_com_2" value="C" style="float: left;" <?php if($info['cp_com_2'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cp_com_2" id="cp_def_2" value="D" style="float: left;" <?php if($info['cp_com_2'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="cp_com_2" id="cp_na_2" value="N" style="float: left;" <?php if($info['cp_com_2'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="col-sm-8 nopad">
						<label style="font-size: 15px;font-weight: bold;">
							<span class="en">13. </span> 
							<span class="en <?=$err&262144?" error":""?>">Hazard Communication</span>
							<span class="error"></span>
						</label> 
					</div>
					<div class="col-sm-4 nopad"></div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en" style="float: left;margin-right: 12px;">a. SDS and Labels available: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="ml_com" id="ml_com" value="C" style="float: left;" <?php if($info['ml_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="ml_com" id="ml_def" value="D" style="float: left;" <?php if($info['ml_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="ml_com" id="ml_na" value="N" style="float: left;" <?php if($info['ml_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">b. Employees briefed on HAZCOM: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="emp_com" id="emp_com" value="C" style="float: left;" <?php if($info['emp_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="emp_com" id="emp_def" value="D" style="float: left;" <?php if($info['emp_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="emp_com" id="emp_na" value="N" style="float: left;" <?php if($info['emp_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">c. HAZCOM information Poster posted: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="hz_com" id="hz_com" value="C" style="float: left;" <?php if($info['hz_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="hz_com" id="hz_def" value="D" style="float: left;" <?php if($info['hz_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="hz_com" id="hz_na" value="N" style="float: left;" <?php if($info['hz_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="col-sm-7 nopad">
						<span class="en"  style="float: left;margin-right: 12px;">d. Employees familiar with SDS familiar with books and their location: </span>
					</div>
					<div class="col-sm-4 nopad">
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="ef_com" id="ef_com" value="C" style="float: left;" <?php if($info['ef_com'] == 'C' ) { ?>checked="true"<?php } ?>><span style="float: right;">compliant</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="ef_com" id="ef_def" value="D" style="float: left;" <?php if($info['ef_com'] == 'D') echo 'checked'; ?>><span style="float: right;">Deficient</span>
						</label>
						<label style="float: left;margin-right: 12px;">
						<input type="radio" name="ef_com" id="ef_na" value="N" style="float: left;" <?php if($info['ef_com'] == 'N') echo 'checked'; ?>><span style="float: right;">N/A</span></label>
					</div>
				</div>
				
				<div class="clear">&nbsp;</div>
				<div class="col-sm-12">
					<div class="form-group">
						<label class="col-sm-4 control-label">
							<span class="en">Comments</span>
							<span class="error">*</span>
						</label>
						<div class="col-sm-7">
							<textarea class="form-control" name="comments_safety" id="comments_safety" rows="5" cols="50" ><?php if($info['comments_safety'] != '') echo $info['comments_safety']; ?></textarea>          
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-sm-12 ">
				<div class="col-sm-3 row">			
					<button type="button" class="btn btn-danger" onclick="window.location.href='/portal/'">Cancel</button>
					&nbsp;
					<input id="frmsubmit" type="button" name="save" class="btn btn-primary" value="Submit" onclick="return do_submit();">
				</div>		
			</div>
		</fieldset>
	</form>
	<br>
</div>

<script src="/js/jquery.signaturepad.js"></script>
<script src="/js/jquery.maskedinput.min.js"></script>
<script>
function do_submit(){
	var error_count = 0;
	var frm = document.form_val;
	if ($('#date_report').val().length == 0){
		$('#date_report').addClass(' error');
		error_count++;
	}
	if ($('#report_time').val().length == 0){
		$('#report_time').addClass(' error');
		error_count++;
	}
	if ($('#jobsite_location').val().length == 0){
		$('#jobsite_location').addClass(' error');
		error_count++;
	}
	if ($('#topic').val().length == 0){
		$('#topic').addClass(' error');
		error_count++;
	}
	/*if ($('#alternate_topic').val().length == 0){
		$('#alternate_topic').addClass(' error');
		error_count++;
	}*/
	if ($('#discussion_leader').val().length == 0){
		$('#discussion_leader').addClass(' error');
		error_count++;
	}
        
	/*if ($('#comments').val().length == 0){
		$('#comments').addClass(' error');
		error_count++;
	}
	if ($('#date_meeting').val().length == 0){
		$('#date_meeting').addClass(' error');
		error_count++;
	}*/
	
	if ($('#sigPad_foreman_val').val().length == 0){
        $('#sigPad_foreman_val').parent('div').addClass('signerr');
		error_count++;
	}else{
        $('#sigPad_foreman_val').parent('div').removeClass('signerr');
    }

	if ($('#foreman_print').val().length == 0){
		$('#foreman_print').addClass(' error');
		error_count++;
	}
	if ($('#foreman_division').val() == ''){
		$('#foreman_division').addClass(' error');
		error_count++;
	}
	if ($('#foreman_email').val().length == 0){
        $('#foreman_email').addClass(' error');
        error_count++;
    }
	
	/* For Add more signature validation */
    $('.amore').each(function(){
        if($(this).val() == ''){
            if(!$(this).hasClass('output')){
                $(this).addClass(' error');
		        error_count++;
            }else if($(this).hasClass('output')){
                $(this).parent('div').addClass('signerr');
            }
        }else{
            if($(this).hasClass('output')){
                $(this).parent('div').removeClass('signerr');
            }
        }
    });
	
	if ($('#job_name').val().length == 0){
		$('#job_name').addClass(' error');
		error_count++;
	}
	if ($('#job_number').val().length == 0){
		$('#job_number').addClass(' error');
		error_count++;
	}
	if ($('#checked_by').val().length == 0){
		$('#checked_by').addClass(' error');
		error_count++;
	}
	if ($('#safety_date').val().length == 0){
		$('#safety_date').addClass(' error');
		error_count++;
	}
	if ($('#comments_safety').val().length == 0){
		$('#comments_safety').addClass(' error');
		error_count++;
	}
    
	if (error_count > 0){
		$('html, body').animate({
		scrollTop: "0px"
	    }, 800);
		$('#error_msg').show();
		return false;
	}else{
		$('#error_msg').hide();
        $('#frmsubmit').prop('disabled', true);
		document.getElementById('form_val').submit();
	}
}
 
$(document).ready(function () {
	<?php
    $loop = isset($info['counter'])?$info['counter']:1;
    for($i=1; $i <= $loop; $i++){
        if(isset($info['sign_nm_'.$i]) && trim($info['sign_nm_'.$i])!=''){
    ?>
    $("#sigPad_<?php echo $i; ?>").signaturePad({drawOnly:true,validateFields:false, lineWidth :0}).regenerate('<?php echo $info["sign_nm_".$i] ?>');
    <?php
        }else{
    ?>
    $('#sigPad_<?php echo $i; ?>').signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
    <?php
        }
    }   
    ?>  
    
    var counter = $('#counter').val();
    $('#add').on('click',function(e){
        
        e.preventDefault();
        counter++;

        $("#items").append(
            '<div class="col-sm-12 nopad" id="trow_'+counter+'"><div class="clr"></div><div class="col-sm-12"><div class="col-sm-6" style="padding-left: 0px;"><div class="form-group"><label class="col-md-4 control-label"><span class="en">'+counter+'. First Name</span><span class="error">*</span></label><div class="col-md-7"><input type="text" name="print_nm_'+counter+'" id="print_nm_'+counter+'" class="form-control amore" value="" placeholder="First Name"></div></div></div><div class="col-sm-6" style="padding-left: 0px;"><div class="form-group"><label class="col-md-5 control-label"><span class="en">Last Name</span><span class="error">*</span></label><div class="col-md-7"><input type="text" class="form-control amore" id="last_name_'+counter+'" name="last_name_'+counter+'" value="" placeholder="Last Name"></div></div></div></div><div class="col-sm-12 "><div class="col-sm-6" style="padding-left: 0px;"> <div class="form-group"><label class="col-md-4 control-label"><span class="en">Date Of Birth</span><span class="error">*</span></label><div class="col-md-7"><input type="text" name="dob_'+counter+'" id="dob_'+counter+'" placeholder="MM/DD/YYYY" class="form-control maskd amore"  value=""></div></div></div></div><div class="clr">&nbsp;</div><div class="col-sm-12"><div class="col-sm-6 nopad"><label><span class="en">Sign Name</span><span class="error">*</span></label></div></div><div class="col-sm-12 "><div id="sigPad_'+counter+'"><div class="sig sigWrapper" style="border-radius:3px;height:110px;margin-top:5px; overflow:hidden;width:655px;"><div class="typed"></div><canvas class="pad" width="655" height="110" style=""></canvas><input type="hidden" name="sign_nm_'+counter+'" id="sign_nm_'+counter+'" value="" class="output amore"></div><a href="#clear" class="clearButton">Clear signature</a><br/></div></div><div class="clr"><br></div><div class="col-sm-12 "><button type="button" id="remove'+counter+'" class="remove btn btn-md btn-danger glyphicon glyphicon-minus"></button></div><div class="clr"></div></br></div>'
        )
        
        $('#sigPad_'+counter).signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
        $('#counter').val(counter);
		
		$(".dob").datetimepicker({
            lang:'en',
            timepicker:false,
            format:'m/d/Y',
            closeOnDateSelect: true,
            scrollInput: false,
        });
		
        $("#remove"+counter).on('click',function(e){
            e.preventDefault();
            $("#trow_"+counter).remove();
            counter--;
        });

        data_mask();
    });

	var sig = '<?php echo htmlspecialchars_decode($info["sigPad_foreman_val"]); ?>';
	if (sig != ''){
		$('.sigPad_foreman').signaturePad({drawOnly:true}).regenerate(sig);
	}
	else{
		$('.sigPad_foreman').signaturePad({drawOnly:true});
	}

    data_mask();
});

function data_mask(){
    $('.maskd').mask("99/99/9999", {placeholder: "MM/DD/YYYY"}); 

    $('.maskd').change(function() {
        if ($(this).val().substring(0, 2) > 12 || $(this).val().substring(0, 2) == "00") {
            alert("Iregular Month Format");
            return false;
        }
        if ($(this).val().substring(3, 5) > 31 || $(this).val().substring(0, 2) == "00") {
            alert("Iregular Date Format");
            return false;
        }
    });
}
</script>
<style>
label {font-weight: 600;}.col-sm-4.nopad input[type="radio"] { margin-right: 5px;} 
.pad{ width: 100%;}
p.error, span.error{color: red;}
.signerr{border: 2px solid red;}
</style>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>