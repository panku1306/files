<?php 
include_once dirname(dirname(dirname(__FILE__))). '/_inc.php';

$query = "SELECT * FROM divisions WHERE client = $client AND active = '1'";
$result = mysql_query($query);
while ($ob = mysql_fetch_object($result)) {
	$divisions[$ob->id] = $ob;
}
?>
<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div id="frame" style="height: auto;">	
	<form id="corporate_audit_final" class="form-horizontal"  method="post" action="" name="corporate_audit_final">
		<h3 style="text-align: center; text-decoration: underline; margin-top: 0px; margin-bottom: 10px;">Saved Audit List</h3>
		<span style="display:block;float:right;font-size:12px;font-weight:bold;"> 
			<a href="corporate_audit_final.php" tile="Saved Audits">
				<img src="file-new.png" style="height:20px; padding: 0px; margin: -3px 0px 0px;"/> New Audit
			</a>
		</span>
		<br/>
		<label class="col-sm-1 control-label nopad">Division: </label>
		<div class="col-sm-4">
			<select class="form-control" onchange="$('#corporate_audit_final').submit();" name="division">
				<option value="">Select Division</option>								
				<?php 
				foreach($divisions as $div) { 			
				?>
				<option value="<?php echo $div->id; ?>" <?php echo $_POST['division']== $div->id?" selected":""; ?>>
					<?php echo $div->nickname; ?>
				</option>
				<?php
				}
				?>
			</select>
		</div>
		<div class="clear">&nbsp;</div>
		
		<fieldset>
			<table class="table table-bordered table-striped table-condensed">
				<tr class="hots_list" style="margin-top: 1px; font-weight: bold;">
					<td width="10">#</td>
					<td width="135">Performed By</td>		
					<td width="135">Reviewed with</td>
					<td width="115">Date</td>	
					<td width="80">Action</td>
				</tr>
				<?php 
				if(isset($_POST['division']) && !empty($_POST['division'])){
					$division = ' WHERE division = '.$_POST['division'];
				}else{
					$division = '';
				}
				$query = "SELECT * FROM corporate_audit $division ORDER BY id DESC";
				
				$result = mysql_query($query);
				$count =0;
				while ($ob = mysql_fetch_object($result)) {										
					$count++;
				?>			
				<tr class="hots_list">
					<td width="10"><a href="corporate_audit_final.php?id=<?php echo $ob->id; ?>"> <?=$count;?> </a></td>
					<td width="135">
						<?php echo $ob->performed_by ?>
					</td>
					<td width="135"> <?=$ob->review_with;?></td>
					<td width="115"><?=$ob->date;?></td>
					<td width="10"><a href="corporate_audit_final.php?id=<?php echo $ob->id; ?>"> <img src="edit.png" style="height:20px; padding: 0px; margin: -3px 0px 0px;"/> Edit </a></td>
				</tr>
			<?php 
				}
			?>
			</table>
		</fieldset>		
	</form>
</div>

<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>