<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';

if($_POST['search_val']){
    while (list($index,$ob)=each($_POST)) {
		$info[$index]=ms($ob);
	}
	$add="";
	
	if ($info['status']=="approved") {
			$add.= " AND status = 'approved'";
	} elseif ($info['status']=="rejected") {
			$add.= " AND status = 'rejected'";
	} elseif ($info['status']=="submitted") {
			$add.= " AND status = 'submitted'";
	} else {
			$add.= " AND status != 'open' AND status!= 'deleted'";
	}

	if ($info['sort']=="new") $add.= " ORDER BY finished DESC"; 
	if ($info['sort']=="old") $add.= " ORDER BY finished ASC"; 
	if ($info['sort']=="last") $add.= " ORDER BY last_name ASC"; 
	if ($info['sort']=="first") $add.= " ORDER BY first_name ASC"; 

	$query = "SELECT * FROM application WHERE client = '3' AND last_name LIKE '%".$info['last_name']."%' $add";
	
	$result = mysql_query($query);
 
	while ($ob = mysql_fetch_object($result)) {
		$apps[$ob->id] = $ob;             
	}
}
?>

<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div id="frame" style="height: auto;min-height: 550px;padding-top: 0px;">
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
		
	<h3 style="text-align: center; margin-top: 0px; margin-bottom: 35px;">
		Employee Search <br> Safety Infraction Report
	</h3>
   
	<form name="form2" action="" method="post" class="well form-horizontal">
		
		<div class="col-sm-4 nopad">			
			<label class="col-sm-3 control-label nopad">Last Name:</label>
			<div class="col-sm-8 nolpad">
				<input class="form-control" type="text" name="last_name" value="<?=stripslashes($info['last_name'])?>">
			</div>
		</div>	
		<div class="col-sm-3 nopad">
			<label class="col-sm-4 control-label nopad">Status:</label>
			<div class="col-sm-7 nolpad">
				<select class="form-control" name="status" style="width:100px;margin-right:20px;">
					<option value="">Any</option>
					<option value="submitted"<?=$info['status']=="submitted"?" selected":""?>>New</option>
					<option value="approved"<?=$info['status']=="approved"?" selected":""?>>Approved</option>
					<option value="rejected"<?=$info['status']=="rejected"?" selected":""?>>Rejected</option>
				</select>
			</div>
		</div>
		<div class="col-sm-3 nopad">
			<label class="col-sm-3 control-label nopad">Sort: </label>
			<div class="col-sm-6 nolpad">
				<select class="form-control" name="sort" style="width:110px;margin-right:20px;">
					<option value="new"<?=$info['sort']=="new"?" selected":""?>>Newest</option>
					<option value="old"<?=$info['sort']=="old"?" selected":""?>>Oldest</option>
					<option value="last"<?=$info['sort']=="last"?" selected":""?>>Last Name</option>
					<option value="first"<?=$info['sort']=="first"?" selected":""?>>First Name</option>
				</select>
			</div>
		</div>
		
		<div class="col-sm-2 nopad">
			<input type="submit" class="btn btn-primary" name="search_val" value="Search" >
			<input type="button" class="btn btn-warning" value="Back"  onclick="window.location.href='/portal/';">
		</div>
		<div class="clearfix"></div>
	</form>
    
	<?php
    if($_POST['search_val']){
    ?>
	<? if ($qs[0]=="last"||$qs[0]=="first") { $info['sort']=$qs[0]; } ?>
	<div style="height:400px;overflow-y:auto;">
		<table class="table table-bordered table-striped table-condensed">
			<tr>
				<th width="250">Name</th>			
				<th width="70">Status</th>			
				<th width="115">Completed</th>
			</tr>
			<? $array=$apps; if ($array) { $num=0; reset($array); while (list($index,$ob)=each($array)) { ?>
			<?
			$t="";$c="";
			if ($ob->status=="approved")  { $t="<span>Approved</span>"; $c="app"; }
			if ($ob->status=="rejected")  { $t="<span>Rejected</span>"; $c="dis"; }
			if ($ob->status=="submitted") { $t="<span>New</span>";      $c="new"; }
			?>
			<tr class="<?=$c?>">
				<td width="350">
					<a href="/portal/weekly_tailgate/safety_infraction.php?emp=<?php echo $ob->id; ?>" >
						<?=$info['sort']=="last"?"<b>":""?><?=$ob->last_name?><?=$info['sort']=="last"?"</b>":""?>, <?=$info['sort']=="first"?"<b>":""?><?=$ob->first_name?><?=$info['sort']=="first"?"</b>":""?>
					</a>
				</td>
				<td width="70"><?=$t?></td>
				<td width="115"><?=date("m/d/Y g:i a",strtotime($ob->finished))?></td>
			</tr>
			<? }} else { ?>
			<tr><td colspan="3">No matching results</td></tr>
			<? } ?>
		</table>
	</div>
	<?php
	}
	?>
</div>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>