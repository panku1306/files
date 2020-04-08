<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';

$qs = split('/',$_SERVER['QUERY_STRING']);

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
	
    if($err == ''){
		# Set doc id if submitted
		if($_POST['doc_id']){
			$docID = " `doc_id`='".$info['doc_id']."', ";
		}else{
			$docID = " ";
		}
			
		$curr_wk_start = date("Y-m-d",strtotime('monday this week'));
		$curr_wk_end = date("Y-m-d",strtotime("sunday this week"));

        $squery = "INSERT INTO `safety_meeting_report` SET 
			".$docID."
            `user_id`='".$qs[0]."',
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
        $print_data = array();
        $lname_data = array();
		$dob_data = array();
        $sign_data = array();
        $user_ids = array();
		$check_data_sign = mysql_query("SELECT * FROM `safety_meeting_signlist` WHERE `user_id`='".$row_id_val."'");		
	    if(mysql_num_rows($check_data_sign) > 0){
	    	
			for($i = 1;$i<=$counter;$i++){
				$check_user_id = mysql_query("SELECT * FROM `application` WHERE `first_name`='".$info['print_nm_'.$i]."' AND `last_name`='".$info['last_name_'.$i]."' AND `dob`='".date('Y-m-d', strtotime($info['dob_'.$i]))."' AND (`status`='submitted' OR `status`='approved') ORDER BY id  DESC LIMIT 1");
				
		        if(mysql_num_rows($check_user_id) > 0){
		        	$app_user_ids = mysql_fetch_object($check_user_id);
					
		        	if(!empty($app_user_ids->id)){
		        		array_push($user_ids, $app_user_ids->id);
		        	}
				}

                array_push($print_data, $info['print_nm_'.$i]);
                array_push($lname_data, $info['last_name_'.$i]);
				array_push($dob_data, $info['dob_'.$i]);
                array_push($sign_data, $_POST['sign_nm_'.$i]);
			}

            $updt_qr = mysql_query("UPDATE `safety_meeting_signlist` SET print_data = '".serialize($print_data)."', lname_data = '".serialize($lname_data)."', dob_data = '".serialize($dob_data)."', sign_data = '".serialize($sign_data)."', employee_ids = '".implode(',',$user_ids)."' WHERE `user_id`='".$row_id_val."'");
	    } 
		else {
			$insrt_data =  mysql_query("INSERT INTO `safety_meeting_signlist` SET `user_id`='".$row_id_val."'");
			
			for($i = 1;$i<=$counter;$i++){
				$check_user_id = mysql_query("SELECT * FROM `application` WHERE `first_name`='".$info['print_nm_'.$i]."' AND `last_name`='".$info['last_name_'.$i]."' AND `dob`='".date('Y-m-d', strtotime($info['dob_'.$i]))."' AND (`status`='submitted' OR `status`='approved') ORDER BY id  DESC LIMIT 1");
				
		        if(mysql_num_rows($check_user_id) > 0){
		        	$app_user_ids = mysql_fetch_object($check_user_id);
					
		        	if(!empty($app_user_ids->id)){
		        		array_push($user_ids, $app_user_ids->id);
		        	}
				}
                
                array_push($print_data, $info['print_nm_'.$i]);
                array_push($lname_data, $info['last_name_'.$i]);
				array_push($dob_data, $info['dob_'.$i]);
                array_push($sign_data, $_POST['sign_nm_'.$i]);
			}
            
            $updt_qr = mysql_query("UPDATE `safety_meeting_signlist` SET print_data = '".serialize($print_data)."', lname_data = '".serialize($lname_data)."', dob_data = '".serialize($dob_data)."', sign_data = '".serialize($sign_data)."', employee_ids = '".implode(',',$user_ids)."' WHERE `user_id`='".$row_id_val."'");
	    }
	    
		# Store joblist data
		$check_data_list = mysql_query("SELECT * FROM `safety_joblist` WHERE `user_id`='".$row_id_val."'");
	    if(mysql_num_rows($check_data_list) > 0){
			$updat = mysql_query("UPDATE `safety_joblist` SET
                `job_name`='".$info['job_name']."',
                `job_number`='".$info['job_number']."',
                `checked_by`='".$info['checked_by']."',
                `date`='".date('Y-m-d',strtotime($info['safety_date']))."',
                `recordkeeping_a`='".$info['n_com']."',
                `recordkeeping_b`='".$info['emer_com']."',
                `recordkeeping_c`='".$info['osha_com']."',
                `personal_a`='".$info['sfty_com']."',
                `personal_b`='".$info['face_com']."',
                `personal_c`='".$info['res_com']."',
                `personal_d`='".$info['weld_com']."',
                `first_aid_a`='".$info['avl_com']."',
                `first_aid_b`='".$info['st_com']."',
                `first_aid_c`='".$info['cpr_com']."',
                `first_aid_d`='".$info['med_com']."',
                `scaffold_a`='".$info['cp_com']."',
                `scaffold_b`='".$info['sgp_com']."',
                `scaffold_c`='".$info['fp_com']."',
                `scaffold_d`='".$info['clr_com']."',
                `ladders_a`='".$info['free_com']."',
                `ladders_b`='".$info['sl_com']."',
                `cylinder_a`='".$info['cp_com_cyl']."',
                `cylinder_b`='".$info['oxy_com']."',
                `cylinder_c`='".$info['empty_com']."',
                `tools_a`='".$info['ins_com']."',
                `tools_b`='".$info['hand_com']."',
                `tools_c`='".$info['unsafe_com']."',
                `tools_d`='".$info['tools_com']."',
                `housekeeping_a`='".$info['main_com']."',
                `housekeeping_b`='".$info['aisles_com']."',
                `housekeeping_c`='".$info['work_com']."',
                `electrical_a`='".$info['elc_com']."',
                `electrical_b`='".$info['elc_com']."',
                `electrical_c`='".$info['cords_com']."',
                `electrical_d`='".$info['wp_com']."',
                `fall_a`='".$info['gaurd_com']."',
                `fall_b`='".$info['frs_com']."',
                `fall_c`='".$info['osf_com']."',
                `fall_d`='".$info['opb_com']."',
                `fire_a`='".$info['flm_com']."',
                `fire_b`='".$info['adq_def']."',
                `fire_c`='".$info['vm_na']."',
                `excavations_a`='".$info['over_com']."',
                `excavations_b`='".$info['lad_com']."',
                `excavations_c`='".$info['cp_com_2']."',
                `hazzard_a`='".$info['ml_com']."',
                `hazzard_b`='".$info['emp_com']."',
                `hazzard_c`='".$info['hz_com']."',
                `hazzard_d`='".$info['ef_com']."',
                `comments`='".$info['comments_safety']."'
                WHERE `user_id`='".$row_id_val."'");
	    }
		else{
			$updat = mysql_query("INSERT INTO `safety_joblist` SET
                `user_id`='".$row_id_val."',
                `job_name`='".$info['job_name']."',
                `job_number`='".$info['job_number']."',
                `checked_by`='".$info['checked_by']."',
                `date`='".date('Y-m-d',strtotime($info['safety_date']))."',
                `recordkeeping_a`='".$info['n_com']."',
                `recordkeeping_b`='".$info['emer_com']."',
                `recordkeeping_c`='".$info['osha_com']."',
                `personal_a`='".$info['sfty_com']."',
                `personal_b`='".$info['face_com']."',
                `personal_c`='".$info['res_com']."',
                `personal_d`='".$info['weld_com']."',
                `first_aid_a`='".$info['avl_com']."',
                `first_aid_b`='".$info['st_com']."',
                `first_aid_c`='".$info['cpr_com']."',
                `first_aid_d`='".$info['med_com']."',
                `scaffold_a`='".$info['cp_com']."',
                `scaffold_b`='".$info['sgp_com']."',
                `scaffold_c`='".$info['fp_com']."',
                `scaffold_d`='".$info['clr_com']."',
                `ladders_a`='".$info['free_com']."',
                `ladders_b`='".$info['sl_com']."',
                `cylinder_a`='".$info['cp_com_cyl']."',
                `cylinder_b`='".$info['oxy_com']."',
                `cylinder_c`='".$info['empty_com']."',
                `tools_a`='".$info['ins_com']."',
                `tools_b`='".$info['hand_com']."',
                `tools_c`='".$info['unsafe_com']."',
                `tools_d`='".$info['tools_com']."',
                `housekeeping_a`='".$info['main_com']."',
                `housekeeping_b`='".$info['aisles_com']."',
                `housekeeping_c`='".$info['work_com']."',
                `electrical_a`='".$info['elc_com']."',
                `electrical_b`='".$info['elc_com']."',
                `electrical_c`='".$info['cords_com']."',
                `electrical_d`='".$info['wp_com']."',
                `fall_a`='".$info['gaurd_com']."',
                `fall_b`='".$info['frs_com']."',
                `fall_c`='".$info['osf_com']."',
                `fall_d`='".$info['opb_com']."',
                `fire_a`='".$info['flm_com']."',
                `fire_b`='".$info['adq_def']."',
                `fire_c`='".$info['vm_na']."',
                `excavations_a`='".$info['over_com']."',
                `excavations_b`='".$info['lad_com']."',
                `excavations_c`='".$info['cp_com_2']."',
                `hazzard_a`='".$info['ml_com']."',
                `hazzard_b`='".$info['emp_com']."',
                `hazzard_c`='".$info['hz_com']."',
                `hazzard_d`='".$info['ef_com']."',
                `comments`='".$info['comments_safety']."'");
	    }
	    
		# Send email
		require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');				
		$mail = new NextCodeMailer();
		
		/* Gets the data from a URL */
        $url = $base_url.'/html2pdf_v4.03/examples/safety_meeting_pdfdoc.php?uid='.$row_id_val;
		$binary_content = file_get_contents($url);
		
		$mail->From = 'noreply@nextcode.info';
		$mail->FromName = 'NextCode.Info';		
		
		$mail->addAddress('RRitchie@enviseco.com');
        
        if(isset($_POST['foreman_email']) && !empty($_POST['foreman_email'])){
            $mail->addAddress($_POST['foreman_email']);
        }
		
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

            #header('Location:/portal/safety_meeting/');
            #exit;	
		}  else   {
		    $_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";
		}
	}		
}
?>
<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

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
		<span id="error_msg" style="color: red; font-weight: bold;text-align: center;display: none;">
            (Please input all fields marked with *)
        </span>
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
			$check_doc_data = mysql_query("SELECT * FROM `safety_meeting_doc` WHERE `user_id`='".$qs[0]."' AND DATE(NOW()) BETWEEN `week_start_date` AND `week_end_date` ORDER BY id DESC LIMIT 1");
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
			<input type="hidden" name="doc_id" id="doc_id" value="<?php echo $fet_details['id']; ?>" />
			<?php
			}
			?>				
			<div class="clr"><br></div>
			
			<div class="col-sm-12">
				<div class="col-sm-6" style="padding-left: 0px;"> 
					<div class="form-group">
						<label class="col-md-4 control-label">
							<span class="en">Date</span>
							<span class="sp" style="display: none;">Fecha</span>
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
							<span class="sp" style="display: none;">Tiempo</span>
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
							<span class="sp" style="display: none;">Ubicación del sitio de trabajo</span>
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
							<span class="sp" style="display: none;">Tema</span>
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
							<span class="sp" style="display: none;">Tema alternativo</span>
							<span class="error">*</span>
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
							<span class="sp" style="display: none;">Líder de la Discusión</span>
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
                            </label>
                            <div class="col-md-7">
                                <input type="text" name="print_nm_<?php echo $i; ?>" id="print_nm_<?php echo $i; ?>" class="form-control" value="<? if($info['print_nm_'.$i] != '') echo $info['print_nm_'.$i]; ?>">
                            </div>		
                        </div>
                    </div>
                    <div class="col-sm-6" style="padding-left: 0px;"> 
                        <div class="form-group">
                            <label class="col-md-5 control-label">
                                <span class="en">Last Name</span>
                            </label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="last_name_<?php echo $i; ?>" name="last_name_<?php echo $i; ?>" value="<? if($info['last_name_'.$i] != '') echo $info['last_name_'.$i]; ?>">
                            </div>		
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-6" style="padding-left: 0px;"> 
                        <div class="form-group">
                            <label class="col-md-4 control-label">
                                <span class="en">Date Of Birth</span>
                            </label>
                            <div class="col-md-7">
                                <input type="text" name="dob_<?php echo $i; ?>" id="dob_<?php echo $i; ?>" placeholder="MM/DD/YYYY" class="form-control dob"  value="<?php if($info['dob_'.$i] != '') echo $info['dob_'.$i]; ?>">													
                            </div>		
                        </div>
                    </div>				
                </div>

                <div class="clr">&nbsp;</div>
                <div class="col-sm-12">
                    <div class="col-sm-6 nopad">
                        <label>
                            <span class="en">Sign Name</span>					
                        </label>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div id="sigPad_<?php echo $i; ?>">
                        <div class="sig sigWrapper" style="border-radius:3px;height:110px;margin-top:5px; overflow:hidden;width:655px;">
                            <div class="typed"></div>
                            <canvas class="pad" width="655" height="110" style=""></canvas>
                            <input type="hidden" name="sign_nm_<?php echo $i; ?>" id="sign_nm_<?php echo $i; ?>" value="<?php echo $info['output_'.$i]; ?>" class="output">
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
                    <span class="sp" style="display: none;">Comentarios / Sugerencias:</span>
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
                        <span class="sp" style="display: none;">Fecha de la próxima reunión</span>
                        <span class="error">*</span>
                    </label>
                    <div class="col-md-7">
                        <input type="text" name="date_meeting" id="date_meeting" placeholder="MM/DD/YYYY" class="form-control<?=$err&4?" error":""?>"  value="<?php if($info['date_meeting'] != '') echo $info['date_meeting']; ?>">
                    </div>		
                </div>
            </div>				
        </div>
			
        <div class="col-sm-12">
            <div class="col-sm-7" style="padding-left: 0px;"> 
                <div class="form-group">
                    <label class="col-md-4 control-label">
                        <span class="en">Foreman Sign</span>
                        <span class="sp" style="display: none;">Capataz sesión</span>
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
                        <span class="sp" style="display: none;">Capataz de impresión</span>
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
                        <span class="sp" style="display: none;">División Capataz</span>
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
        <div>
            <div class="col-sm-12">
                <div class="col-sm-7" style="padding-left: 0px;"> 
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <span class="en">Job Name</span>
                            <span class="sp" style="display: none;">Nombre del trabajo</span>
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
                            <span class="sp" style="display: none;">Número de trabajo</span>
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
                            <span class="sp" style="display: none;">comprobado por</span>
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
                            <span class="sp" style="display: none;">Fecha</span>
                            <span class="error">*</span>
                        </label>
                        <div class="col-md-7">
                            <input type="text" name="safety_date" id="safety_date" class="form-control<?=$err&16?" error":""?>" placeholder="MM/DD/YYYY" value="<? if($info['safety_date'] != '') echo $info['safety_date']; ?>">
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
                        <span class="sp <?=$err&64?" error":""?>" style="display: none;">Mantenimiento de Registros</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>			
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Notices, Posters Federal 5in 1,OSHA notice,Payroll: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Avisos o carteles Federal 5in 1​​, aviso OSHA, Nómina:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Contactos de emergencia:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Registro 300 de OSHA:</span>
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
                        <span class="sp <?=$err&128?" error":""?>" style="float: left;margin-right: 12px;display: none;">Equipo de Protección Personal</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>	
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. In use safety glasses,hard hats work boots and gloves: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. En uso de gafas de seguridad, cascos botas y guantes de trabajo:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Cara escudos oor gafas utilizadas para trabajos en altura:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Los respiradores disponibles:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">d. Pantallas de soldadura:</span>
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
                        <span class="sp <?=$err&256?" error":""?>" style="display: none;">Kits de Primeros Auxilios</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Available in gang box add jobsite trailer: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Disponible en caja eléctrica agregar remolque sitio de trabajo:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Adecuadamente equipada con los guantes, vendas y antisépticos:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Personal de RCP y primeros auxilios entrenados:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">d. Ubicación e información de contacto Información del hospital comunicó:</span>
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
                        <span class="sp <?=$err&512?" error":""?>" style="display: none;">Andamios</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Competent person certified: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Persona competente certifica: </span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Planking Andamios grado:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Protección contra caídas:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">d. Borrar de la basura / residuos:</span>
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
                        <span class="sp <?=$err&1024?" error":""?>" style="display: none;">Escaleras</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Free from defects, with safet feet,blocked,cleated or otherwise secured: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Libre de defectos, con los pies safet, bloqueado, cleated o asegurarse de alguna forma:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. escaleras rectas en 1 a 4 de tono:</span>
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
                        <span class="sp <?=$err&2048?" error":""?>" style="display: none;">Cilindros</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Capped,stored in an upright position: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Convocado, almacenado en posición vertical:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Oxígeno propiedad / Accetylene separado:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Cilindros de gas vacíos marcados:</span>
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
                        <span class="sp <?=$err&4096?" error":""?>" style="display: none;">Herramientas / Equipo</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Inspected to ensure safe operating condition: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Inspeccionado para garantizar condiciones seguras de funcionamiento:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Herramientas de mano libre de defectos:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. / Herramientas inutilizables / equipo inseguro etiquetado como "no utilizar":</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">d. Tools/Equipment property guarded:</span>
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
                        <span class="sp <?=$err&8192?" error":""?>" style="display: none;">Servicio de limpieza</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Maintained: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Maintained:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Los pasillos y exitways claras con "despacho 24:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Las áreas de trabajo unculttered y escombros removidos:</span>
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
                        <span class="sp <?=$err&16384?" error":""?>" style="display: none;">Eléctrico</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>			
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Electrical equipment gounded: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. El equipo eléctrico gounded:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Herramientas duplicaron insultados:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Cordones en buenas condiciones:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">d. Paneles eléctricos cubiertos si se activan:</span>
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
                        <span class="sp <?=$err&32768?" error":""?>" style="display: none;">Protección contra las caídas</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Guardrails,midrails,toeboards: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Las barandillas, largueros intermedios, tablas de pie:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Otoño sistemas de retención:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Abra pisos lados o plataformas equipadas con baranda estándar:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">d. Apertura (interior / perímetro) correctamente con barricadas o cubierta:</span>
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
                        <span class="sp <?=$err&65536?" error":""?>" style="display: none;">Prevención de Incendios</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Flammable and explosive materials stored safely: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Los materiales inflamables y explosivos almacenados de forma segura:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Número adecuado de extintores disponibles con las etiquetas y clips:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Vehículos y equipos móviles provistos de extintores:</span>
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
                        <span class="sp <?=$err&131072?" error":""?>" style="display: none;">excavaciones</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. Over 4 ft shored, benched or sloped as required: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. Más de 4 pies apuntalados, o tienen una pendiente según sea necesario:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Pasos o escaleras a intervalos de 25 pies:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Persona competente en el sitio:</span>
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
                        <span class="sp <?=$err&262144?" error":""?>" style="display: none;">Comunicación Hazard</span>
                        <span class="error"></span>
                    </label> 
                </div>
                <div class="col-sm-4 nopad"></div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-7 nopad">
                    <span class="en" style="float: left;margin-right: 12px;">a. SDS and Labels available: </span>
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">a. SDS y etiquetas disponibles:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">b. Los empleados informados sobre HAZCOM:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">c. Información HAZCOM cartel colocado:</span>
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
                    <span class="sp" style="float: left;margin-right: 12px;display: none;">d. Los empleados están familiarizados con SDS familiarizados con los libros y su localización:</span>
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
                        <span class="sp" style="display: none;">Comentarios</span>
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
				<input id="sBtn" type="button" name="save" class="btn btn-primary" value="Submit" onclick="return do_submit();">
			</div>		
		</div>
		</fieldset>
	</form>
		
	<br>
</div>

<script src="/js/jquery.signaturepad.js"></script>
<style>
label {font-weight: 600;}.col-sm-4.nopad input[type="radio"] { margin-right: 5px;} 
.pad{ width: 100%;}
p.error, span.error{color: red;}
</style>
<script>
function do_submit(){
	var error_count = 0;
	var frm = document.form_val;
	if ($('#date_report').val().length == 0)
	{
		$('#date_report').addClass(' error');
		error_count++;
	}
	if ($('#report_time').val().length == 0)
	{
		$('#report_time').addClass(' error');
		error_count++;
	}
	if ($('#jobsite_location').val().length == 0)
	{
		$('#jobsite_location').addClass(' error');
		error_count++;
	}
	if ($('#topic').val().length == 0)
	{
		$('#topic').addClass(' error');
		error_count++;
	}
	if ($('#alternate_topic').val().length == 0)
	{
		$('#alternate_topic').addClass(' error');
		error_count++;
	}
	if ($('#discussion_leader').val().length == 0)
	{
		$('#discussion_leader').addClass(' error');
		error_count++;
	}
        
	if ($('#comments').val().length == 0)
	{
		$('#comments').addClass(' error');
		error_count++;
	}
	if ($('#date_meeting').val().length == 0)
	{
		$('#date_meeting').addClass(' error');
		error_count++;
	}
	
	if ($('#sigPad_foreman_val').val().length == 0)
	{
		error_count++;
	}
	if ($('#foreman_print').val().length == 0)
	{
		$('#foreman_print').addClass(' error');
		error_count++;
	}
	if ($('#foreman_division').val() == '')
	{
		$('#foreman_division').addClass(' error');
		error_count++;
	}
	
	if ($('#job_name').val().length == 0)
	{
		$('#job_name').addClass(' error');
		error_count++;
	}
	if ($('#job_number').val().length == 0)
	{
		$('#job_number').addClass(' error');
		error_count++;
	}
	if ($('#checked_by').val().length == 0)
	{
		$('#checked_by').addClass(' error');
		error_count++;
	}
	if ($('#safety_date').val().length == 0)
	{
		$('#safety_date').addClass(' error');
		error_count++;
	}
	if ($('#comments_safety').val().length == 0)
	{
		$('#comments_safety').addClass(' error');
		error_count++;
	}
    if ($('#foreman_email').val().length == 0)
    {
        $('#foreman_email').addClass(' error');
        error_count++;
    }
	if (error_count > 0)
	{
		$('html, body').animate({
		scrollTop: "0px"
	    }, 800);
		$('#error_msg').show();
		return false;
	}
	else{
		$('#error_msg').hide();
        $('#sBtn').prop('disabled', true);
		document.getElementById('form_val').submit();
	}
}
 
$(document).ready(function () {
	<?php
    $loop = isset($info['counter'])?$info['counter']:1;
    for($i=1; $i <= $loop; $i++){
        if(isset($info['sign_nm_'.$i]) && trim($info['sign_nm_'.$i])!=''){
    ?>
    $('#sigPad_<?=$i?>').signaturePad({drawOnly:true,validateFields:false, lineWidth :0}).regenerate('<?php echo $info["sign_nm_".$i] ?>');
    <?php
        }else{
    ?>
    $('#sigPad_<?=$i?>').signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
    <?php
        }
    }   
    ?>  
    
    var counter = $('#counter').val();
    $('#add').on('click',function(e){
        
        e.preventDefault();
        counter++;

        $("#items").append(
            '<div class="col-sm-12 nopad" id="trow_'+counter+'"><div class="clr"></div><div class="col-sm-12"><div class="col-sm-6" style="padding-left: 0px;"><div class="form-group"><label class="col-md-4 control-label"><span class="en">'+counter+'. First Name</span></label><div class="col-md-7"><input type="text" name="print_nm_'+counter+'" id="print_nm_'+counter+'" class="form-control" value=""></div></div></div><div class="col-sm-6" style="padding-left: 0px;"><div class="form-group"><label class="col-md-5 control-label"><span class="en">Last Name</span></label><div class="col-md-7"><input type="text" class="form-control" id="last_name_'+counter+'" name="last_name_'+counter+'" value=""</div></div></div></div><div class="col-sm-12 nopad"><div class="col-sm-6" style="padding-left: 0px;"> <div class="form-group"><label class="col-md-4 control-label"><span class="en">Date Of Birth</span></label><div class="col-md-7"><input type="text" name="dob_'+counter+'" id="dob_'+counter+'" placeholder="MM/DD/YYYY" class="form-control dob"  value=""></div></div></div></div><div class="clr">&nbsp;</div><div class="col-sm-12 nopad"><div class="col-sm-6 nopad"><label><span class="en">Sign Name</span></label></div></div><div class="col-sm-12 nopad"><div id="sigPad_'+counter+'"><div class="sig sigWrapper" style="border-radius:3px;height:110px;margin-top:5px; overflow:hidden;width:655px;"><div class="typed"></div><canvas class="pad" width="655" height="110" style=""></canvas><input type="hidden" name="sign_nm_'+counter+'" id="sign_nm_'+counter+'" value="" class="output"></div><a href="#clear" class="clearButton">Clear signature</a><br/></div></div><div class="clr"><br></div><div class="col-sm-12 nopad"><button type="button" id="remove'+counter+'" class="remove btn btn-md btn-danger glyphicon glyphicon-minus"></button></div><div class="clr"></div></br></div>'
        )
        
        $('#sigPad_'+counter).signaturePad({drawOnly:true,validateFields:false, lineWidth :0});
        
        $("#remove"+counter).on('click',function(e){
            e.preventDefault();
            $("#trow_"+counter).remove();
            counter--;
        });
        $('#counter').val(counter);

        $(".dob").datetimepicker({
            lang:'en',
            timepicker:false,
            format:'m/d/Y',
            closeOnDateSelect: true,
            scrollInput: false,
        });
    });

	var sig = '<?php echo htmlspecialchars_decode($info["sigPad_foreman_val"]); ?>';
	if (sig != ''){
		 $('.sigPad_foreman').signaturePad({drawOnly:true}).regenerate(sig);
	}
	else{
		 $('.sigPad_foreman').signaturePad({drawOnly:true});
	}
	$("#date_meeting, #safety_date, .dob").datetimepicker({
		lang:'en',
		timepicker:false,
		format:'m/d/Y',
		closeOnDateSelect: true,
		scrollInput: false,
	}); 
});
 </script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>