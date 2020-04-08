<?php 
# ini_set('display_errors', 1);
# error_reporting(E_ALL);

include_once dirname(dirname(dirname(__FILE__))). '/_inc.php';
include_once dirname(dirname(dirname(__FILE__))). '/_apphead.php'; 

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
<div id="frame" style="height: auto;">	
	<form id="defensive_driving" method="post" action="" name="defensive_driving" enctype="multipart/form-data">
		<legend>
			Saved Defensive Drive List:   
			<span style="display:block;float:right;font-size:12px;font-weight:bold;"> 
				<a href="defensive_driving.php?486" tile="Saved DD">
					<img src="file-new.png" style="height:20px; padding: 0px; margin: -3px 0px 0px;"/> New Defensive Drive
				</a>
			</span>
		</legend><br/>
		<label>Division: </label>		
		<select onchange="$('#defensive_driving').submit();" name="division">
			<option <?php if ($_POST['division'] == '') { echo 'selected'; } ?> value="">All Division</option>
			<option <?php if ($_POST['division'] == 1) { echo 'selected'; } ?> value="1">Northern California</option>
			<option <?php if ($_POST['division'] == 2) { echo 'selected'; } ?> value="2">Southern California</option>
			<option <?php if ($_POST['division'] == 3) { echo 'selected'; } ?> value="3">Southwest</option>
			<option <?php if ($_POST['division'] == 4) { echo 'selected'; } ?> value="4">Mid-Atlantic</option>
			<option <?php if ($_POST['division'] == 5) { echo 'selected'; } ?> value="5">ABS</option>
			<option <?php if ($_POST['division'] == 6) { echo 'selected'; } ?> value="6">Corporate</option>
			<option <?php if ($_POST['division'] == 7) { echo 'selected'; } ?> value="7">Energy Services</option>
			<option <?php if ($_POST['division'] == 8) { echo 'selected'; } ?> value="8">National Engineering</option>
		</select>		
		<fieldset>
			<table class="table table-striped table-condensed" style="border-bottom:1px solid #ddd;">
				<tr class="hots_list" style="margin-top: 1px; font-weight: bold;">
					<td width="10">#</td>
					<td width="135">Vehicle No.</td>		
					<td width="135">Driver's Name</td>
					<td width="115">Date</td>	
					<td width="80">Action</td>
				</tr>
				<?php 
				if(isset($_POST['division']) && !empty($_POST['division'])){
					$division = ' WHERE division = '.$_POST['division'];
				}else{
					$division = '';
				}
				$query = "SELECT * FROM defensive_driving $division ORDER BY id DESC";
				
				$result = mysql_query($query);
				$count =0;
				while ($ob = mysql_fetch_object($result)) {										
					$count++;
				?>		
				<tr class="hots_list">
					<td width="10"><a href="defensive_driving.php?i_id=486&id=<?php echo $ob->id; ?>"> <?=$count;?> </a></td>
					<td width="135">
						<?php echo $ob->vehicle_no ?>
					</td>
					<td width="135"> <?=$ob->driver_name;?></td>
					<td width="115"><?=$ob->d_date;?></td>
					<td width="10"><a href="defensive_driving.php?i_id=486&id=<?php echo $ob->id; ?>"> <img src="edit.png" style="height:20px; padding: 0px; margin: -3px 0px 0px;"/> Edit </a></td>
				</tr>
			<?php 
				}
			?>
			</table>
		</fieldset>		
	</form>
</div>
<? include_once dirname(dirname(dirname(__FILE__))).'/_appfoot.php'; ?>