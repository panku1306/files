<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';
$_SESSION['lang'] = 'en';


if (isset($_POST)) {
    $err=[];
    $d= '';
    $arr = array_slice($_POST,0,20);
    $data = ["quiz1" => "true","quiz2"=>"true","quiz3"=>"false","quiz4"=>"false","quiz5"=>"true","quiz6"=>"true","quiz7"=>"false","quiz8"=>"true","quiz9"=>"true","quiz10"=>"false","quiz11"=>"true","quiz12"=>"true","quiz13"=>"true","quiz14"=>"false","quiz15"=>"false","quiz16"=>"true","quiz17"=>"true","quiz18"=>"true","quiz19"=>"true","quiz20"=>"true"];
    /*
    if ($arr == $data){
        echo "All quiz value is exists in the given array";
    }else{
        echo "something is wrong";
    }
    */
    foreach($arr as $key=>$val){
        if($data[$key]!= $val){
            $d .= $key." value is different from given value "."\n".'<br />';
            // echo $d;
            //echo "The value are different";
        }elseif($data[$key] == $val){
           // echo "The value are same"; 
        }
    }        
}

include_once dirname(dirname(dirname(__FILE__))).'/_head.php';
?>

<hr>
<div id="frame" style="height: auto;"> 
	<form id="form_val" class="form-horizontal" name="form_val" method="post">
			
		<fieldset>		
			<h3 class="ttext">Quiz</h3>
            
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
            <p style="color: red; font-weight: bold;text-align: center;">
               <?php echo $d;?>
            </p>
		
			<div id="personal_edit" >
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8"> 
                                <span class="en"  style="float: left;margin-right: 12px;">
                                    1. Employees with a safety attitude have the right attitude. 
                                </span>
                            </div>
                            <div class="col-sm-4">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" id="quiz1" name="quiz1" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" id="quiz1" name="quiz1" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 " >
                                <span class="en"  style="float: left;margin-right: 12px;">
                                2. Employer safety programs should provide for frequent and regular inspection of the job sites, materials and equipment. 
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" id="quiz2" name="quiz2" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" id="quiz2" name="quiz2" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                       </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 " >
                                <span class="en"  style="float: left;margin-right: 12px;"> 3. Any employee can operate equipment and machinery at the worksite if they have some idea of how it works. 
                                </span> 
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz3" id="quiz3" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz3" id="quiz3" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 " >
                                <span class="en"  style="float: left;margin-right: 12px;">4. Guardrails, covers,personal fall arrest systems and safety ropes are all types of fall protection.
                                </span> 
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz4" id="quiz4" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz4" id="quiz4" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 " >
                                <span class="en"  style="float: left;margin-right: 12px;"> 5. Rerouting or properly covering exposed cables/cords that cross pathways is one way to prevent slips, trips and falls.
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz5" id="quiz5" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio"  name="quiz5" id="quiz5" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 " > 
                                <span class="en"  style="float: left;margin-right: 12px;">6. Employees must always wear hard hats to protect themseleves from falling objects. 
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz6" id="quiz6" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz6" id="quiz6" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 " > 
                                <span class="en"  style="float: left;margin-right: 12px;">7. fall protection is required any time you use a ladder. 
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz7" id="quiz7" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz7" id="quiz7" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 "> 
                                <span class="en"  style="float: left;margin-right: 12px;">8. When lifting heavy objects it is always a good idea to get help or use special equipment.
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz8" id="quiz8" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz8" id="quiz8" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 " > 
                                <span class="en"  style="float: left;margin-right: 12px;">9. Employees should always assume all overhead power lines are energized. 
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz9" id="quiz9" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz9" id="quiz9" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 ">
                            <span class="en"  style="float: left;margin-right: 12px;"> 10. SDSs are required for most chemicals used at the worksite and should be kept locked up in the supervisor's office for safety.
                            </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz10" id="quiz10" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz10" id="quiz10" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 "> 
                                <span class="en"  style="float: left;margin-right: 12px;">11. Trenches and excavations must be inspected daily for evidence of possible cave-ins, hazardous atmospheres, failure of protective systems or other unsafe conditions.
                            </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz11" id="quiz11" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz11" id="quiz11" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 ">
                                <span class="en"  style="float: left;margin-right: 12px;"> 12. Guardrails should be installed along all open sides and ends of platforms. 
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz12" id="quiz12" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz12" id="quiz12" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 ">
                                <span class="en"  style="float: left;margin-right: 12px;"> 13. Power tools must be fitted with guards and safety switches.
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz13" id="quiz13" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz13" id="quiz13" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 "> 
                                <span class="en"  style="float: left;margin-right: 12px;">14. Fatal electrocution is the only real risk when working near overhead power lines.
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz14" id="quiz14" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz14" id="quiz14" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>    
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 ">
                            <span class="en"  style="float: left;margin-right: 12px;"> 15. Ladders with structural defects can be used if the employee thinks it is still safe.
                            </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz15" id="quiz15" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz15" id="quiz15" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 "> 
                                <span class="en"  style="float: left;margin-right: 12px;">16. Employees should never enter into a confined or enclosed space unless properly trained and instructed by their employer. 
                                </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz16" id="quiz16" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz16" id="quiz16" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 ">
                            <span class="en"  style="float: left;margin-right: 12px;"> 17. PPE must fit propperly, be worn properly and be maintained properly to be effective.
                            </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz17" id="quiz17" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz17" id="quiz17" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 ">
                            <span class="en"  style="float: left;margin-right: 12px;"> 18. Smoking is prohibited at most construction sites or is permitted in designated areas only.
                            </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz18" id="quiz18" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz18" id="quiz18" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                       <div class="form-group">
                            <div class="col-sm-8 "> 
                            <span class="en"  style="float: left;margin-right: 12px;">19. You should know the locations of all first aid kits and who is cerified in first-aid at the worksite.
                            </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz19" id="quiz19" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz19" id="quiz19" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>    
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-8 "> 
                            <span class="en"  style="float: left;margin-right: 12px;">20. Employees should take personal responsibility for their safety, theiir co-workers and others on the jobsite. </span>
                            </div>
                            <div class="col-sm-4 ">
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz20" id="quiz20" value="true" style="float: left;" >
                                    True
                                </label>
                                <label style="float: left;margin-right: 12px;">
                                    <input type="radio" name="quiz20" id="quiz20" value="false"  style="float: left;" >
                                    False
                                </label>
                            </div>
                        </div>    
                    </div>
                    <p class="col-sm-12 "><b>I have watched and understand the information contained in this program and have passed the quiz reagarding Safety Orientation-Construction.  </b></p>
                </div>
                <div class="clr">&nbsp;<br><br></div>
                <!---div class="col-sm-12">
                   <div class="col-sm-6">
                        <div class="form-group">
                            <label class="col-sm-4">
                                <span class="en">Printed Name</span>
                            </label>
                            <div class="col-sm-6">
                               <input type="text" name="p_name" id="p_name" placeholder="Printed Name"  class="form-control <?php //echo $err&1?" error":""?>" value="">
                           </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">
                                <span class="en">Date</span>
                            </label>
                            <div class="col-sm-6">
                              <input type="text" name="dob" id="dob" placeholder="MM/DD/YYYY"  class="form-control" value="<?=date("m/d/Y"); ?>">
                            </div>							
                        </div>
                    </div>							
                </div---->
                <!--div class="col-sm-12">
                    <div class="col-sm-4">
                        <label class="control-label">
                            <span class="en">Signature:</span>
                            <span class="sp" style="display: none;"></span>
                            <span class="error">*</span>
                        </label>
                    </div>
                    <div class="col-sm-8">
                        <div id="sign_ipad1">
                            <div  class="sig sigWrapper current" style="cursor:crosshair;width:585px;height: 130px; overflow: hidden;">
                                <div style="display: none;" class="typed"></div>
                                <canvas class="pad" width="585" height="130"></canvas>
                                <input name="quiz_sign" id="quiz_sign" value="" class="output" type="hidden">
                            </div>
                            <a href="#clear" class="clearButton">Clear signature</a>
                        </div>
                    </div>
                </div--->
                <div class="clr"><br></div>                  
			</div>

            <div class="clr">&nbsp;<br><br></div>
			<div class="clr">&nbsp;<br><br></div>	
			<div class="col-sm-12 ">
				<div class="col-sm-3 row">			
					<button type="button" class="btn btn-danger" onclick="window.location.href='/portal/'">Cancel</button>
					&nbsp;
					<input id="frmsubmit" type="submit" class="btn btn-primary" value="Submit">
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
   
 
</script>
<style>
    span.error{color:red;}
    .signerr{border: 2px solid red;}
</style>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>