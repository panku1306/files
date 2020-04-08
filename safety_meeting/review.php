<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';

function validateDate($date, $format = 'Y-m-d'){
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) == $date;
}

$query_div = "SELECT * FROM divisions WHERE client = $client AND active = '1'";
$result_div = mysql_query($query_div);
while ($ob = mysql_fetch_object($result_div)) {
	$divisions[$ob->id] = $ob;
}

if(!empty($_GET['doc_id']) && $_GET['doc_id'] != ''){
	$check_doc_data = mysql_query("SELECT * FROM `safety_meeting_doc` WHERE `id`='".$_GET['doc_id']."'");
	
	if(mysql_num_rows($check_doc_data) > 0){
		$fet_details = mysql_fetch_assoc($check_doc_data);
	}else{
		$_SESSION['error_msg'] = "Tailgate doesn't exist. Contact Admin!";
		header('Location:/portal/safety_meeting/archives.php');
        exit;	
	}
}else{
	$_SESSION['error_msg'] = "Tailgate doesn't exist. Contact Admin!";
	header('Location:/portal/safety_meeting/archives.php');
	exit;	
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
	
    if (!$info['foreman_email']) $err+=32;
    $foreman_email = $info['foreman_email'];
	
		
    if (!$err) {
				
		$curr_wk_start = date("Y-m-d",strtotime('monday this week'));
		$curr_wk_end = date("Y-m-d",strtotime("sunday this week"));

        $squery = "INSERT INTO `weekly_tailgate` SET
			`tailgate_doc_id` = '".$info['tailgate_doc_id']."',
            `archive_submission` = '1',
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
	    
        if($row_id_val){
            
			# Send email
            require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');				
            $mail = new NextCodeMailer();
            
            /* Gets the data from a URL */
            $url = $base_url.'/html2pdf_v4.03/examples/safety_meeting_pdfdoc.php?uid='.$row_id_val;
            $binary_content = file_get_contents($url);
            
            $mail->From = 'noreply@nextcode.info';
            $mail->FromName = 'NextCode.Info';		
            
            if($info['foreman_division'] == '1'){
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

                ##header('Location:/portal/safety_meeting/tailgate.php');
                #exit;	
            }  else   {
                $_SESSION['error_msg'] = "Weekly Tailgate report submitted but mail sending failed. Contact Admin!";
            }
        }else   {
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
		<h3 class="ttext">Weekly Tailgate Sign-in Sheet</h3>
		<p id="error_msg" style="color: red; font-weight: bold;text-align: center;display: none;">
			(Please input all fields marked with *)
		</p>
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
			if(!empty($fet_details)){
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
			<div class="text-center" style="border:none; height:600px; overflow:auto;padding: 10px 0px; width:90%;margin: 20px auto;">		<img id="fred" style="width:100%;" src="/uploaded_content/<?php echo $fet_details['first_aid_image']; ?>">
			</div>
			<?php						
					}
				}
			?>
			<input type="hidden" name="tailgate_doc_id" id="tailgate_doc_id" value="<?php echo $_GET['doc_id']; ?>" />
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
                                <input type="text" name="print_nm_<?php echo $i; ?>" id="print_nm_<?php echo $i; ?>" class="form-control amore" value="<? if($info['print_nm_'.$i] != '') echo $info['print_nm_'.$i]; ?>">
                            </div>		
                        </div>
                    </div>
                    <div class="col-sm-6" style="padding-left: 0px;"> 
                        <div class="form-group">
                            <label class="col-md-5 control-label">
                                <span class="en">Last Name</span>
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
                            </label>
                            <div class="col-md-7">
                                <input type="text" name="dob_<?php echo $i; ?>" id="dob_<?php echo $i; ?>" placeholder="MM/DD/YYYY" class="form-control dob amore"  value="<?php if($info['dob_'.$i] != '') echo $info['dob_'.$i]; ?>">													
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
                            <a href="#clear" class="clearButton">Clear signature</a><br/>
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
<style>
label {font-weight: 600;}.col-sm-4.nopad input[type="radio"] { margin-right: 5px;} 
.pad{ width: 100%;}
p.error, span.error{color: red;}
</style>
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

	/*if ($('#alternate_topic').val().length == 0)
	{
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

	if (error_count > 0){
		$('html, body').animate({
		scrollTop: "0px"
	    }, 800);
		$('#error_msg').show();
		return false;
	}
	else{
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
            '<div class="col-sm-12 nopad" id="trow_'+counter+'"><div class="clr"></div><div class="col-sm-12"><div class="col-sm-6" style="padding-left: 0px;"><div class="form-group"><label class="col-md-4 control-label"><span class="en">'+counter+'. First Name</span></label><div class="col-md-7"><input type="text" name="print_nm_'+counter+'" id="print_nm_'+counter+'" class="form-control amore" value=""></div></div></div><div class="col-sm-6" style="padding-left: 0px;"><div class="form-group"><label class="col-md-5 control-label"><span class="en">Last Name</span></label><div class="col-md-7"><input type="text" class="form-control amore" id="last_name_'+counter+'" name="last_name_'+counter+'" value=""</div></div></div></div><div class="col-sm-12 nopad"><div class="col-sm-6" style="padding-left: 0px;"> <div class="form-group"><label class="col-md-4 control-label"><span class="en">Date Of Birth</span></label><div class="col-md-7"><input type="text" name="dob_'+counter+'" id="dob_'+counter+'" placeholder="MM/DD/YYYY" class="form-control dob amore"  value=""></div></div></div></div><div class="clr">&nbsp;</div><div class="col-sm-12 nopad"><div class="col-sm-6 nopad"><label><span class="en">Sign Name</span></label></div></div><div class="col-sm-12 nopad"><div id="sigPad_'+counter+'"><div class="sig sigWrapper" style="border-radius:3px;height:110px;margin-top:5px; overflow:hidden;width:655px;"><div class="typed"></div><canvas class="pad" width="655" height="110" style=""></canvas><input type="hidden" name="sign_nm_'+counter+'" id="sign_nm_'+counter+'" value="" class="output amore"></div><a href="#clear" class="clearButton">Clear signature</a><br/></div></div><div class="clr"><br></div><div class="col-sm-12 nopad"><button type="button" id="remove'+counter+'" class="remove btn btn-md btn-danger glyphicon glyphicon-minus"></button></div><div class="clr"></div></br></div>'
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
<style>
label {font-weight: 600;}.col-sm-4.nopad input[type="radio"] { margin-right: 5px;} 
.pad{ width: 100%;}
p.error, span.error{color: red;}
.signerr{border: 2px solid red;}
</style>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>