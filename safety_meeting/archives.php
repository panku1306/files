<?php
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';

$mt_docs = "SELECT * FROM safety_meeting_doc ORDER BY id DESC LIMIT 150";
$res_docs = mysql_query($mt_docs);
while ($ob = mysql_fetch_assoc($res_docs)) {
	$docs[$ob['id']]= $ob;
}
?>
<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div id="frame" style="height: auto;"> 
	
	<h3 class="box-title" style="margin: 0px;text-align:center;">Weekly Tailate Archives</h3><br> 
	<?php if (isset($_SESSION['success_msg'])){ ?>
	<div class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php 
		echo $_SESSION['success_msg'];
		unset($_SESSION['success_msg']); 
		?>
	</div><br>
	<?php } elseif ($_SESSION['error_msg']) { ?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php 
		echo $_SESSION['error_msg'];
		unset($_SESSION['error_msg']); 
		?>
	</div><br>
	<?php } ?>
	
	<div id="listdiv" style="height:500px;overflow-y:auto;">
		<table id="dataList" class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th>Sl. No.</th>
					<th style="text-align:center;">Memo Date</th>
					<th style="text-align:center;">Topic</th>
					<th style="text-align:center;">Memo From</th>
					<th style="text-align:center;">Memo To</th>
					<th style="text-align:center;">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				foreach($docs as $key=>$doc){
				?>
				<tr>
					<td><?php echo $key; ?></td>
					<td style="text-align:center;"><?php echo date('m/d/Y', strtotime($doc['memo_date'])); ?></td>
					<td><?php echo $doc['memo_topic']; ?></td>
					<td style="text-align:center;"><?php echo $doc['memo_from']; ?></td>
					<td style="text-align:center;"><?php echo $doc['memo_to']; ?></td>
					<td>
					<a class="btn btn-xs btn-primary" href="/portal/safety_meeting/review.php?doc_id=<?php echo $doc['id']; ?>">
						<i class="fa fa-fw fa-eye"></i> View
					</a>
					</td>
				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
	
	<br>
	<br>
	<br>
	<div class="clr">&nbsp;</div>
</div>
<script>
$(document).ready(function () {
	$("#dataList-list").DataTable({
		"lengthChange": false,
		"language": {
			"paginate": {
			  "previous": "<i class='fa fa-angle-double-left'></i>",
			  "next": "<i class='fa fa-angle-double-right'></i>"
			}
		  }
	});
})
</script>