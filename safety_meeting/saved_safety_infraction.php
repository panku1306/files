<?php 
# ini_set('display_errors', 1);
# error_reporting(E_ALL);
include_once dirname(dirname(dirname(__FILE__))). '/_inc.php';
include_once dirname(dirname(dirname(__FILE__))). '/_head.php'; 

checkauth2();

$query = "SELECT * FROM safety_infraction ORDER BY id DESC";
$st_dt = $en_dt = "";
if(isset($_POST['submit'])){
	$info = $_POST;
	
    $st_dt = $start_dt = date("Y-m-d", strtotime($_POST['date_timepicker_start']));
    $en_dt = $end_dt = date("Y-m-d", strtotime($_POST['date_timepicker_end']));
    $query = "SELECT * FROM safety_infraction where date_infraction >= '".$start_dt."' AND date_infraction <= '".$end_dt."' ORDER BY id DESC";
	
	if($_POST['division']){
		$query = "SELECT * FROM safety_infraction left join application on safety_infraction.emp = application.id where safety_infraction.date_infraction >= '".$start_dt."' AND safety_infraction.date_infraction <= '".$end_dt."' AND application.division ='".$_POST['division']."'  ORDER BY safety_infraction.id DESC";
	}
}

$result = mysql_query($query);
$count =0;
?>
<style>
#banner {color: green;font-weight: bold;text-align: center;}
input, textarea, select, .uneditable-input {font-size: 12px !important;}
input.error, textarea.error, select.error{background: #ffe1b8;border-color: #e1972d;}
.swcont{display:none;}
.nodate{display:none;}
.showdate{display:block;}
#personal_edit{clear:both;}
</style>

<hr>
<div id="frame" style="height: auto;">
	<div>
		<span style="font-size:16px;font-weight:bold;">Saved Safety Infraction List : </span>  
		<span style="display:block;float:right;font-size:12px;font-weight:bold;"> 
			<a href="/portal/weekly_tailgate/landing_sinfraction.php" tile="Saved DD">
				<img src="file-new.png" style="height:20px; padding: 0px; margin: -3px 0px 0px;"/> New Safety Infraction
			</a>
		</span>
		<div class="clr"><br></div>		
	</div>
	<form class="form-inline" name="safety_infraction" method="post" action="saved_safety_infraction.php">
		<div class="form-group">
			<label style="font-weight:normal;">Start Date : </label>
			<input type="text" class="form-control" id="date_timepicker_start" name="date_timepicker_start" value="<? if(!empty($st_dt)) {echo date('m/d/Y', strtotime($st_dt));}else{echo "";} ?>" required="required">
		</div>
		&nbsp;
		<div class="form-group">
			<label style="font-weight:normal;">End Date : </label>
			<input type="text" class="form-control" id="date_timepicker_end" name="date_timepicker_end" value="<? if(!empty($en_dt)) {echo date('m/d/Y', strtotime($en_dt));}else{echo "";} ?>" required="required">
		</div>
		&nbsp;
		<div class="form-group">
			<?php	
			$query_div = "SELECT * FROM divisions WHERE client = $client AND active = '1'";
			$result_div = mysql_query($query_div);
			while ($ob = mysql_fetch_object($result_div)) {
				$divisions[$ob->id] = $ob;
			}
			?>
			<label style="font-weight:normal;">Division : </label>
			<select id="div" class="form-control" name="division">
				<option value=""<?=$info['division']==""?" selected":""?>>All Division</option>
				<?php $array = $divisions; 
				if ($array) {
					reset($array); 
					while (list($index,$ob)=each($array)) { 			
				?>
				<option value="<?php echo $ob->id?>" <?php echo ($info['division'] == $ob->id)?" selected":""; ?>><?php echo $ob->nickname?></option>
				<?php
					}
				} 
				?>
			</select>
		</div>
		<button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-search"></span> Search</button>
		<button type="button" class="btn btn-danger" onclick="window.location.href='/portal/weekly_tailgate/saved_safety_infraction.php'">Cancel</button>
	</form>
	<br/>	
	<fieldset>
		<table class="table table-striped table-condensed" style="border-bottom:1px solid #ddd;">
			<tr class="hots_list" style="margin-top: 1px; font-weight: bold;">
				<td width="10">#</td>	
				<td width="135">Employee Name</td>
				<td width="115">Date</td>	
				<td width="80">Action</td>
			</tr>
			<?php
			while ($ob = mysql_fetch_object($result)) {										
				$count++;
			?>		
			<tr class="hots_list">
				<td width="10"><a href="safety_infraction.php?id=<?php echo $ob->id; ?>&emp=<?php echo $ob->emp; ?>"> <?=$count;?> </a></td>
				<td width="135"> <?=$ob->emp_name;?></td>
				<td width="115"><?= date('m/d/Y', strtotime($ob->created));?></td>
				<td width="10"><a href="safety_infraction.php?id=<?php echo $ob->id; ?>&emp=<?php echo $ob->emp; ?>"> <img src="edit.png" style="height:20px; padding: 0px; margin: -3px 0px 0px;"/> Edit </a></td>
			</tr>
			<?php 
			}
			?>
		</table>
	</fieldset>
</div>
<script>
$(document).ready(function () {
	$('#date_timepicker_start').datetimepicker({
		format:'m/d/Y',
		timepicker:false,
		onShow:function( ct ){
			this.setOptions({
				maxDate:$('#date_timepicker_end').val()?$('#date_timepicker_end').val():0,
				formatDate:'m/d/Y',
			})
		},
		closeOnDateSelect: true,
		yearEnd: <?php echo date('Y', strtotime('now'));?>
	});
	$('#date_timepicker_end').datetimepicker({
		format:'m/d/Y',
		timepicker:false,
		onShow:function( ct ){
			this.setOptions({
				minDate:$('#date_timepicker_start').val()?$('#date_timepicker_start').val():false,
				formatDate:'m/d/Y',
				maxDate:'0',
			})
		},
		closeOnDateSelect: true,
		yearEnd: <?php echo date('Y', strtotime('now'));?>
	});
	console.log($('#date_timepicker_start').val())
});
</script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_appfoot.php'; ?>