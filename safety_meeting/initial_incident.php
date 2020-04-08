<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';
$_SESSION['lang'] = 'en';
function validateDate($date, $format = 'Y-m-d'){
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) == $date;
}

if ($_POST) {
    $err=0;
	while (list($index,$ob)=each($_POST)) {
		$info[$index]=ms($ob);
	}

    if (!$info['empl_name']) $err+=1;
    if (!$info['empl_id']) $err+=2;
    if (!$info['job_site']) $err+=3;	
    if (!$info['division']) $err+=4;	
    if (!$info['incident_type']) $err+=5;	
    
}
include_once dirname(dirname(dirname(__FILE__))).'/_head.php';
?>

<hr>
<div id="frame" style="height: auto;"> 
	<form id="form_val" class="form-horizontal" name="form_val" method="post" action="" enctype="multipart/form-data">
			
		<fieldset>		
			<h3 class="ttext">Initial Incident</h3>
			<p id="error_msg" style="color: red; font-weight: bold;text-align: center;display: none;">
				(Please input all fields marked with *)
			</p>
            <br>
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
                <div class="col-sm-12">
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">
                                <span class="en">Involved Employee Name</span>
                                <span class="error">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" name="empl_name" id="empl_name"  class="form-control  <?=$err&1?" error":""?>" value="">
							</div>							
						</div>
					</div>
                    
				</div>
                <div class="col-sm-12 ">
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">
                                <span class="en">Involved Employee ID</span>
                                <span class="error">*</span>
                            </label>
                            <div class="col-sm-6">
                               <input type="text" name="empl_id" id="empl_id" class="form-control  <?=$err&2?" error":""?>" value="">
							</div>							
						</div>
					</div>
				</div>
				<div class="clr">&nbsp;<br><br><br></div>
				<h3 class="ttext">Involved Employee Title </h3>
				<div class="clr">&nbsp;</div>
                <div class="col-sm-12">
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">
                                <span class="en">Job Site</span>
                                <span class="error">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" name="job_site" id="job_site"  class="form-control  <?=$err&3?" error":""?>" value="">
							</div>							
						</div>
					</div>
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">
                                <span class="en">Division</span>
                                <span class="error">*</span>
                            </label>
                            <div class="col-sm-6">
                               <select class="form-control  <?=$err&4?" error":""?>" name="division" id="division">
									<option value="">Select Division</option>
									<option value="dryside">Dryside</option>
                                    <option value="rainside">Rainside</option>
								</select>
							</div>							
						</div>
					</div>
				</div>
                
                <div class="col-sm-12 ">
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">
                                <span class="en">Date of Incident</span>
                            </label>
                            <div class="col-sm-6">
                            <input type="text" name="dob_inc" id="dob_inc" placeholder="MM/DD/YYYY"  class="form-control" value="<?=date("m/d/Y"); ?>">
							</div>							
						</div>
					</div>
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">
                                <span class="en">Time of Incident</span>
                            </label>
                            <div class="col-sm-6">
                            <input type="text" name="time_inc" id="time_inc" class="form-control" value="<? if($info['time_inc'] != '') echo $info['time_inc']; else echo date("H:i:s"); ?>">
							</div>							
						</div>
					</div>
				</div>
                <div class="col-sm-12 ">
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">
                                <span class="en">Incident Type</span>
                                <span class="error">*</span>
                            </label>
                            <div class="col-sm-6">
                                <select class="form-control  <?=$err&5?" error":""?>" name="incident_type" id="incident_type">
                                    <option value="">Select Incident Type</option>
                                    <option value="t1">Employee Injury</option>
                                    <option value="t2">Injury</option>
                                </select>
							</div>							
						</div>
					</div>
				</div>
                <div class="col-sm-12 ">
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <span class="en">Was a Vehicle Involved?</span>
                            </div>
                            <div class="col-sm-4">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="vehicle_involved" id="vehicle_involved" value="yes" style="float: left;">Yes</span>
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="vehicle_involved" id="vehicle_involved" value="no" style="float: left;"><span style="float: right;">No</span>
                                </label>
                            </div>
						</div>
					</div>
				</div>
                <div class="col-sm-12 ">
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <span class="en">Was an employee or directly supervised contractor injured?</span>
                            </div>
                            <div class="col-sm-4">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="e_injured" id="e_injured" value="yes" style="float: left;">Yes</span>
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="e_injured" id="e_injured" value="no" style="float: left;"><span style="float: right;">No</span>
                                </label>
                            </div>
						</div>
					</div>
				</div>
                <div class="col-sm-12 ">
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <span class="en">Was a Non‐Employee injured?</span>
                            </div>
                            <div class="col-sm-4">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="none_injured" id="none_injured" value="yes" style="float: left;">Yes</span>
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="none_injured" id="none_injured" value="no" style="float: left;"><span style="float: right;">No</span>
                                </label>
                            </div>
						</div>
					</div>
				</div>
                <div class="col-sm-12 ">
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <span class="en">Was Property Damage Involved?</span>
                            </div>
                            <div class="col-sm-4">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="property_damage" id="property_damage" value="yes" style="float: left;">Yes</span>
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="property_damage" id="property_damage" value="no" style="float: left;"><span style="float: right;">No</span>
                                </label>
                            </div>
						</div>
					</div>
				</div>
                <div class="clr">&nbsp;</div>
                <div class="col-sm-12">
                        <div class="form-group">
                            <label class="col-sm-5 control-label">
                                <span class="en">Initial Incident Description </span>
                            </label>
                        </div>				
                    </div>
                <div class="col-sm-12 ">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <textarea name="inc_description" id="inc_description" class="form-control" rows="5" cols="50"></textarea>
						</div>
					</div>
				</div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-sm-5 control-label">
                            <span class="en">Select Additional Incident Forms </span>
                        </label>
                    </div>				
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-4" style="padding-left: 0px;">
                        <label class="control-label">
                            <input type="checkbox" name="incident_investigate" id="incident_investigate" value="incident_investigate" style="margin-top: 1px; margin-right: 2px;">Incident Investigation Employee Form
                        </label>
                    </div>
                    <div class="col-sm-6" style="padding-left: 0px;">
                        <label class="control-label">
                            <input type="checkbox" name="safety_form" id="safety_form" value="safety_form" style="margin-top: 1px; margin-right: 2px;">Safety Form
                        </label>
                    </div>
                </div>
                <div class="clr">&nbsp;</div>
                <div class="col-sm-12">				 
                        <div class="col-sm-5" style="padding-left: 0px;">
                            <div class="form-group">	
                                <label class="col-sm-5 control-label">
                                    Upload file
                                </label>
                                <div class="col-sm-6">
                                    <input type="file" name="file" id="file" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
			</div>
			
			<div class="clr">&nbsp;<br><br></div>
			<div class="clr">&nbsp;<br><br></div>	
			<div class="col-sm-12 ">
				<div class="col-sm-3 row">			
					<button type="button" class="btn btn-danger" onclick="window.location.href='/portal/'">Cancel</button>
					&nbsp;
					<input id="frmsubmit" type="button" name="save" class="btn btn-primary" value="Submit" onclick="return do_submit();">
				</div>		
			</div>
		</fieldset>
	</form>
	<div class="clr">&nbsp;<br></div>	
	<br>
</div>


<style>label {font-weight: 600;}.col-sm-4.nopad input[type="radio"] { margin-right: 5px;} 
.pad{ width: 100%;}</style>
<script>
    function do_submit(){
        var error_count = 0;
        var frm = document.form_val;
        if ($('#dob_inc').val().length == 0){
            $('#dob_inc').addClass(' error');
            error_count++;
        }
        if ($('#time_inc').val().length == 0){
            $('#time_inc').addClass(' error');
            error_count++;
        }
        if ($('#empl_name').val().length == 0){
            $('#empl_name').addClass(' error');
            error_count++;
        }
        if ($('#empl_id').val().length == 0){
            $('#empl_id').addClass(' error');
            error_count++;
        }
        if ($('#job_site').val().length == 0){
            $('#job_site').addClass(' error');
            error_count++;
        }
        if ($('#division').val().length == 0){
            $('#division').addClass(' error');
            error_count++;
        }
        if ($('#incident_type').val().length == 0){
            $('#incident_type').addClass(' error');
            error_count++;
        }

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
        
    });
</script>
<style>
    span.error{color:red;}
    .signerr{border: 2px solid red;}
</style>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>