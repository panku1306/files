<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';
$page = "portal"; # Hide admin layout in portal page

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

$query_sop = "SELECT * FROM sop_doc GROUP BY sop_tool_name";		
$res_sop = mysql_query($query_sop);
?>

<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div style="height: auto;min-height: 550px;">

	<h3 class="box-title" style="margin: 0px;">Search - SOP Training</h3><br> 	
   
    <div class="well form-inline" style="overflow: hidden;">
        <div class="control-group">
			<div style="float: left;width: 100%;">
				<label class="col-sm-5 nopad">
						<span class="en">SOP Type :</span>
						<span class="sp" style="display: none;">Tipo de :</span>
						<span class="error">*</span>
						&nbsp;
				</label>
				<div class="col-sm-12 nopad">
				<?php 
				if(isset($res_sop) && !empty($res_sop)){
					while ($stn = mysql_fetch_object($res_sop)) { ?>
					
					<div class="col-sm-2" style="padding-left:0px;">
						<img id="imgpreview" src="/attachments/sop_doc/c80x80_<?=$stn->sop_tool_image?>" style="background:#fff; max-width: 85px;">
						<div class="clearfix" style="height:5px;width:100%;">&nbsp;</div>							
						<label style="text-align:left;" class="col-sm-11 radio-inline nopad">							
							<input id="sop_type_<?php echo $stn->id; ?>" name="sop_type" value="<?php echo $stn->id; ?>"  class="nopad sop-radiob" type="radio" style="float:left; margin: 3px 2px 0 0 !important;position: relative;">
							<span class="col-sm-9 nopad" style="float:left; word-wrap: break-word;"><?php echo $stn->sop_tool_name; ?></span>
							<div class="clearfix"></div>
						</label>
					</div>
				<?php		
					}
				}
				?>
				</div>
			</div>           
			
        </div>
	</div>
	
	<form name="form2" action="" method="post" class="well form-horizontal">
		<div class="col-sm-12">
			<div class="col-sm-3">
				<div class="form-group">
					<label class="col-sm-5 control-label nopad">Last Name:</label>
					<div class="col-sm-7 nolpad">
						<input class="form-control" type="text" name="last_name" value="<?=stripslashes($info['last_name'])?>">
					</div>
				</div>
			</div>		
			<div class="col-sm-3">
				<div class="form-group">
					<label class="col-sm-4 control-label nopad">Status:</label>
					<div class="col-sm-7 nolpad">
						<select class="form-control" name="status" style="/*width:100px;margin-right:20px;*/">
							<option value="">Any</option>
							<option value="submitted"<?=$info['status']=="submitted"?" selected":""?>>New</option>
							<option value="approved"<?=$info['status']=="approved"?" selected":""?>>Approved</option>
							<option value="rejected"<?=$info['status']=="rejected"?" selected":""?>>Rejected</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="col-sm-4 control-label nopad">Sort: </label>
					<div class="col-sm-7 nolpad">
						<select class="form-control" name="sort" style="/*width:110px;margin-right:20px;*/">
							<option value="new"<?=$info['sort']=="new"?" selected":""?>>Newest</option>
							<option value="old"<?=$info['sort']=="old"?" selected":""?>>Oldest</option>
							<option value="last"<?=$info['sort']=="last"?" selected":""?>>Last Name</option>
							<option value="first"<?=$info['sort']=="first"?" selected":""?>>First Name</option>
						</select>
					</div>
				</div>
			</div>		
			<div class="col-sm-3 text-center" style="padding: 0px;">
				<input type="submit" name="search_val" value="Search" class="btn btn-primary">
				<input type="button" name="search_val" value="Back" class="btn btn-warning" onclick="window.location.href='/portal/';">
				
				<?php if(!empty($_POST['search_val']) && mysql_num_rows($result) == 0){?>
				<a href="add_user.php" class="btn btn-primary">	Add User</a>
				<?php } ?>			
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
	</form>
	
	<div style="height:400px;overflow-y:auto;">
		<?php
		if($_POST['search_val']){
		?>
			<? if ($qs[0]=="last"||$qs[0]=="first") { $info['sort']=$qs[0]; } ?>

		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th>Name</th>
					<th>Status</th>				
					<th>Finished</th>
				</tr>
			</thead>
			<? $array=$apps; if ($array) { $num=0; reset($array); while (list($index,$ob)=each($array)) { ?>

			<?
			$t="";$c="";
			if ($ob->status=="approved")  { $t="<span>Approved</span>"; $c="app"; }
			if ($ob->status=="rejected")  { $t="<span>Rejected</span>"; $c="dis"; }
			if ($ob->status=="submitted") { $t="<span>New</span>";      $c="new"; }
			?>

			<tr class="<?=$c?>">
				<td width="350"><a href="javascript:void(0);" onclick="submit_to_the_next(<?=$ob->id?>)"><?=$info['sort']=="last"?"<b>":""?><?=$ob->last_name?><?=$info['sort']=="last"?"</b>":""?>, <?=$info['sort']=="first"?"<b>":""?><?=$ob->first_name?><?=$info['sort']=="first"?"</b>":""?></a></td>
				<td width="70"><?=$t?></td>			
				<td width="115"><?=date("m/d g:i a",strtotime($ob->finished))?></td>
			</tr>
			<? }} else { ?>
			<tr><td colspan="3">No matching results</td></tr>
			<? } ?>
		</table>
		<?php
		}
		?>		
	</div>
	<br>
	<br>
</div>
<style>
.swcont{display:none;}
.pc_button{text-align: right;}
</style>
<script>
function submit_to_the_next(obj_id){
	if (!$("input:radio[name='sop_type']").is(":checked")){
		alert("Please select a SOP type!");
		return false;
	}
	else{
		var action = '/portal/weekly_tailgate/sop_sign.php?app_id='+obj_id+'&sop_id='+$("input:radio[name='sop_type']:checked").val();
		window.location.href = action;	
	}
}
</script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>
