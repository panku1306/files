<?php 
include_once dirname(dirname(dirname(__FILE__))).'/_inc.php';

if(isset($_GET['id'])){
	$id = $_GET['id'];
}

if ($_POST) {
	$err = 0;
	while (list($index, $ob) = each($_POST)) {
		$info[$index] = ms($ob);
	}
	
	if ($err == '') {
		$check_data = mysql_query("select * from `safety_checklist` where `id`='" . $id . "'");//$qs[0]
		if (mysql_num_rows($check_data) > 0) {
			$row_id = mysql_fetch_array($check_data);
			$row_id_val = $row_id['id'];
			$insrt_det = "update `safety_checklist` set
				 `job_name`='" . $info['job_name'] . "',
				 `division`='" . $info['division'] . "',
				 `job_number`='" . $info['job_number']. "',
				 `checked_by`='" . $info['checked_by'] ."',
				 `checked_by_email`='" . $info['checked_by_email'] ."',
				 `safety_date`='" . DateTime::createFromFormat('m/d/Y', $info['safety_date'])->format('Y-m-d'). "',
				 `notice`='" . $info['notice'] . "',
				 `notice_date`=" . (!empty($info['notice_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['notice_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `notice_cmt`='" . $info['notice_cmt'] . "', 
				 `emergency_con`='" . $info['emergency_con'] . "',
				 `emergency_date`=" . (!empty($info['emergency_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['emergency_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `emergency_cmt`='" . $info['emergency_cmt'] . "', 
				 `osha`='" . $info['osha'] . "',
				 `osha_date`=" . (!empty($info['osha_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['osha_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `osha_cmt`='" . $info['osha_cmt'] . "', 
				 `medical`='" . $info['medical'] . "',
				 `medical_date`=" . (!empty($info['medical_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['medical_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `medical_cmt`='" . $info['medical_cmt'] . "', 
				 
				 `safety`='" . $info['safety'] . "',
				 `saftyglas_date`=" . (!empty($info['saftyglas_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['saftyglas_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `saftyglas_cmt`='" . $info['saftyglas_cmt'] . "', 
				 `face`='" . $info['face'] . "',
				 `face_date`=" . (!empty($info['face_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['face_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `face_cmt`='" . $info['face_cmt'] . "', 
				 `respirators`='" . $info['respirators'] . "',
				 `resp_date`=" . (!empty($info['resp_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['resp_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `resp_cmt`='" . $info['resp_cmt'] . "', 
				 `welding`='" . $info['welding'] . "',
				 `welding_date`=" . (!empty($info['welding_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['welding_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `welding_cmt`='" . $info['welding_cmt'] . "', 
				 `avl_gang`='" . $info['avl_gang'] . "',
				 `avl_gang_date`=" . (!empty($info['avl_gang_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['avl_gang_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `avl_gang_cmt`='" . $info['avl_gang_cmt'] . "',
				 `stocked`='" . $info['stocked'] . "',
				 `stocked_date`=" . (!empty($info['stocked_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['stocked_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `stocked_cmt`='" . $info['stocked_cmt'] . "', 
				 `cpr`='" . $info['cpr'] . "',
				 `cpr_date`=" . (!empty($info['cpr_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['cpr_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `cpr_cmt`='" . $info['cpr_cmt'] . "', 
				 
				 `competent`='" . $info['competent'] . "',
				 `competent_date`=" . (!empty($info['competent_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['competent_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `competent_cmt`='" . $info['competent_cmt'] . "', 
				 `scaffold`='" . $info['scaffold'] . "',
				 `scaffold_date`=" . (!empty($info['scaffold_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['scaffold_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `scaffold_cmt`='" . $info['scaffold_cmt'] . "', 
				 
				 `clear`='" . $info['clear'] . "',
				 `clear_date`=" . (!empty($info['clear_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['clear_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `clear_cmt`='" . $info['clear_cmt'] . "', 
				 `free`='" . $info['free'] . "',
				 `free_date`=" . (!empty($info['free_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['free_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `free_cmt`='" . $info['free_cmt'] . "', 
				 `st_ladder`='" . $info['st_ladder'] . "',
				 `st_ladder_date`=" . (!empty($info['st_ladder_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['st_ladder_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `st_ladder_cmt`='" . $info['st_ladder_cmt'] . "', 
				 
				 `fall_Pro`='" . $info['fall_Pro'] . "',
				 `fall_Pro_date`=" . (!empty($info['fall_Pro_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['fall_Pro_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `fall_Pro_cmt`='" . $info['fall_Pro_cmt'] . "', 
				 
				 `capped`='" . $info['capped'] . "',
				 `capped_date`=" . (!empty($info['capped_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['capped_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `capped_cmt`='" . $info['capped_cmt'] . "',  	
				 `oxygen`='" . $info['oxygen'] . "',
				 `oxygen_date`=" . (!empty($info['oxygen_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['oxygen_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `oxygen_cmt`='" . $info['oxygen_cmt'] . "', 
				 `empty`='" . $info['empty'] . "',
				 `empty_date`=" . (!empty($info['empty_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['empty_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `empty_cmt`='" . $info['empty_cmt'] . "',  
				 `inspected`='" . $info['inspected'] . "',
				 `inspected_date`=" . (!empty($info['inspected_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['inspected_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `inspected_cmt`='" . $info['inspected_cmt'] . "', 
				 `hand`='" . $info['hand'] . "',
				 `hand_date`=" . (!empty($info['hand_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['hand_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `hand_cmt`='" . $info['hand_cmt'] . "',  
				 `unsafe`='" . $info['unsafe'] . "',
				 `unsafe_date`=" . (!empty($info['unsafe_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['unsafe_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `unsafe_cmt`='" . $info['unsafe_cmt'] . "',					
				 `tools`='" . $info['tools'] . "',
				 `tools_date`=" . (!empty($info['tools_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['tools_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `tools_cmt`='" . $info['tools_cmt'] . "', 
				 
				 `aisles`='" . $info['aisles'] . "',
				 `aisles_date`=" . (!empty($info['aisles_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['aisles_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `aisles_cmt`='" . $info['aisles_cmt'] . "', 
				 `work`='" . $info['work'] . "',
				 `work_date`=" . (!empty($info['work_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['work_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `work_cmt`='" . $info['work_cmt'] . "', 
				 `electrical`='" . $info['electrical'] . "',
				 `electrical_date`=" . (!empty($info['electrical_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['electrical_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `electrical_cmt`='" . $info['electrical_cmt'] . "',  
				 `tls_ins`='" . $info['tls_ins'] . "',
				 `tls_ins_date`=" . (!empty($info['tls_ins_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['tls_ins_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `tls_ins_cmt`='" . $info['tls_ins_cmt'] . "', 
				 `cords`='" . $info['cords'] . "',
				 `cords_date`=" . (!empty($info['cords_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['cords_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `cords_cmt`='" . $info['cords_cmt'] . "', 
				 `elec_panel`='" . $info['elec_panel'] . "',
				 `elec_panel_date`=" . (!empty($info['elec_panel_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['elec_panel_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `elec_panel_cmt`='" . $info['elec_panel_cmt'] . "', 
				 `gaurdails`='" . $info['gaurdails'] . "',
				 `gaurdails_date`=" . (!empty($info['gaurdails_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['gaurdails_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `gaurdails_cmt`='" . $info['gaurdails_cmt'] . "', 
				 `frs`='" . $info['frs'] . "',
				 `frs_date`=" . (!empty($info['frs_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['frs_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `frs_cmt`='" . $info['frs_cmt'] . "', 
				 `osf`='" . $info['osf'] . "',
				 `osf_date`=" . (!empty($info['osf_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['osf_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `osf_cmt`='" . $info['osf_cmt'] . "',					  
				 `opb`='" . $info['opb'] . "',
				 `opb_date`=" . (!empty($info['opb_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['opb_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `opb_cmt`='" . $info['opb_cmt'] . "', 
				 `flm_exp`='" . $info['flm_exp'] . "',
				 `flm_exp_date`=" . (!empty($info['flm_exp_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['flm_exp_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `flm_exp_cmt`='" . $info['flm_exp_cmt'] . "', 
				 `adq_num`='" . $info['adq_num'] . "',
				 `adq_num_date`=" . (!empty($info['adq_num_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['adq_num_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `adq_num_cmt`='" . $info['adq_num_cmt'] . "', 
				 `vme`='" . $info['vme'] . "',
				 `vme_date`=" . (!empty($info['vme_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['vme_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `vme_cmt`='" . $info['vme_cmt'] . "',					   	
				 `over`='" . $info['over'] . "',
				 `over_date`=" . (!empty($info['over_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['over_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `over_cmt`='" . $info['over_cmt'] . "',  	
				 `lad`='" . $info['lad'] . "',
				 `lad_date`=" . (!empty($info['lad_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['lad_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `lad_cmt`='" . $info['lad_cmt'] . "', 
				 `cp`='" . $info['cp'] . "',
				 `cp_date`=" . (!empty($info['cp_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['cp_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `cp_cmt`='" . $info['cp_cmt'] . "',  
				 `msds`='" . $info['msds'] . "',
				 `msds_date`=" . (!empty($info['msds_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['msds_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `msds_cmt`='" . $info['msds_cmt'] . "',					  	
				 `emp`='" . $info['emp'] . "', 
				 `emp_date`=" . (!empty($info['emp_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['emp_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `emp_cmt`='" . $info['emp_cmt'] . "', 
				 `hzcom`='" . $info['hzcom'] . "',
				 `hzcom_date`=" . (!empty($info['hzcom_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['hzcom_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `hzcom_cmt`='" . $info['hzcom_cmt'] . "', 
				 `ef_msds`='" . $info['ef_msds'] . "',
				 `ef_msds_date`=" . (!empty($info['ef_msds_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['ef_msds_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `ef_msds_cmt`='" . $info['ef_msds_cmt'] . "', 
				 `comments_safety`='" . $info['comments_safety'] . "' 
				  where `id`='" . $id . "'";
					 
			$stat = mysql_query($insrt_det);
		} 
		else {
			$insrt_det = "insert into `safety_checklist` set
				 `job_name`='" . $info['job_name'] . "',
				 `division`='" . $info['division'] . "',
				 `job_number`='" . $info['job_number']. "',
				 `checked_by`='" . $info['checked_by'] ."',
				 `checked_by_email`='" . $info['checked_by_email'] ."',
				 `safety_date`='" . DateTime::createFromFormat('m/d/Y', $info['safety_date'])->format('Y-m-d'). "',
				 `notice`='" . $info['notice'] . "',
				 `notice_date`=" . (!empty($info['notice_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['notice_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `notice_cmt`='" . $info['notice_cmt'] . "', 
				 `emergency_con`='" . $info['emergency_con'] . "',
				 `emergency_date`=" . (!empty($info['emergency_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['emergency_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `emergency_cmt`='" . $info['emergency_cmt'] . "', 
				 `osha`='" . $info['osha'] . "',
				 `osha_date`=" . (!empty($info['osha_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['osha_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `osha_cmt`='" . $info['osha_cmt'] . "', 
				 `medical`='" . $info['medical'] . "',
				 `medical_date`=" . (!empty($info['medical_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['medical_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `medical_cmt`='" . $info['medical_cmt'] . "', 
				 
				 `safety`='" . $info['safety'] . "',
				 `saftyglas_date`=" . (!empty($info['saftyglas_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['saftyglas_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `saftyglas_cmt`='" . $info['saftyglas_cmt'] . "', 
				 `face`='" . $info['face'] . "',
				 `face_date`=" . (!empty($info['face_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['face_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `face_cmt`='" . $info['face_cmt'] . "', 
				 `respirators`='" . $info['respirators'] . "',
				 `resp_date`=" . (!empty($info['resp_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['resp_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `resp_cmt`='" . $info['resp_cmt'] . "', 
				 `welding`='" . $info['welding'] . "',
				 `welding_date`=" . (!empty($info['welding_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['welding_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `welding_cmt`='" . $info['welding_cmt'] . "', 
				 `avl_gang`='" . $info['avl_gang'] . "',
				 `avl_gang_date`=" . (!empty($info['avl_gang_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['avl_gang_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `avl_gang_cmt`='" . $info['avl_gang_cmt'] . "',
				 `stocked`='" . $info['stocked'] . "',
				 `stocked_date`=" . (!empty($info['stocked_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['stocked_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `stocked_cmt`='" . $info['stocked_cmt'] . "', 
				 `cpr`='" . $info['cpr'] . "',
				 `cpr_date`=" . (!empty($info['cpr_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['cpr_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `cpr_cmt`='" . $info['cpr_cmt'] . "', 
				 
				 `competent`='" . $info['competent'] . "',
				 `competent_date`=" . (!empty($info['competent_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['competent_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `competent_cmt`='" . $info['competent_cmt'] . "', 
				 `scaffold`='" . $info['scaffold'] . "',
				 `scaffold_date`=" . (!empty($info['scaffold_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['scaffold_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `scaffold_cmt`='" . $info['scaffold_cmt'] . "', 
				 
				 `clear`='" . $info['clear'] . "',
				 `clear_date`=" . (!empty($info['clear_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['clear_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `clear_cmt`='" . $info['clear_cmt'] . "', 
				 `free`='" . $info['free'] . "',
				 `free_date`=" . (!empty($info['free_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['free_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `free_cmt`='" . $info['free_cmt'] . "', 
				 `st_ladder`='" . $info['st_ladder'] . "',
				 `st_ladder_date`=" . (!empty($info['st_ladder_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['st_ladder_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `st_ladder_cmt`='" . $info['st_ladder_cmt'] . "', 
				 
				 `fall_Pro`='" . $info['fall_Pro'] . "',
				 `fall_Pro_date`=" . (!empty($info['fall_Pro_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['fall_Pro_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `fall_Pro_cmt`='" . $info['fall_Pro_cmt'] . "', 
				 
				 `capped`='" . $info['capped'] . "',
				 `capped_date`=" . (!empty($info['capped_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['capped_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `capped_cmt`='" . $info['capped_cmt'] . "',  	
				 `oxygen`='" . $info['oxygen'] . "',
				 `oxygen_date`=" . (!empty($info['oxygen_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['oxygen_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `oxygen_cmt`='" . $info['oxygen_cmt'] . "', 
				 `empty`='" . $info['empty'] . "',
				 `empty_date`=" . (!empty($info['empty_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['empty_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `empty_cmt`='" . $info['empty_cmt'] . "',  
				 `inspected`='" . $info['inspected'] . "',
				 `inspected_date`=" . (!empty($info['inspected_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['inspected_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `inspected_cmt`='" . $info['inspected_cmt'] . "', 
				 `hand`='" . $info['hand'] . "',
				 `hand_date`=" . (!empty($info['hand_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['hand_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `hand_cmt`='" . $info['hand_cmt'] . "',  
				 `unsafe`='" . $info['unsafe'] . "',
				 `unsafe_date`=" . (!empty($info['unsafe_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['unsafe_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `unsafe_cmt`='" . $info['unsafe_cmt'] . "',					
				 `tools`='" . $info['tools'] . "',
				 `tools_date`=" . (!empty($info['tools_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['tools_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `tools_cmt`='" . $info['tools_cmt'] . "', 
				 
				 `aisles`='" . $info['aisles'] . "',
				 `aisles_date`=" . (!empty($info['aisles_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['aisles_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `aisles_cmt`='" . $info['aisles_cmt'] . "', 
				 `work`='" . $info['work'] . "',
				 `work_date`=" . (!empty($info['work_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['work_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `work_cmt`='" . $info['work_cmt'] . "', 
				 `electrical`='" . $info['electrical'] . "',
				 `electrical_date`=" . (!empty($info['electrical_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['electrical_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `electrical_cmt`='" . $info['electrical_cmt'] . "',  
				 `tls_ins`='" . $info['tls_ins'] . "',
				 `tls_ins_date`=" . (!empty($info['tls_ins_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['tls_ins_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `tls_ins_cmt`='" . $info['tls_ins_cmt'] . "', 
				 `cords`='" . $info['cords'] . "',
				 `cords_date`=" . (!empty($info['cords_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['cords_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `cords_cmt`='" . $info['cords_cmt'] . "', 
				 `elec_panel`='" . $info['elec_panel'] . "',
				 `elec_panel_date`=" . (!empty($info['elec_panel_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['elec_panel_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `elec_panel_cmt`='" . $info['elec_panel_cmt'] . "', 
				 `gaurdails`='" . $info['gaurdails'] . "',
				 `gaurdails_date`=" . (!empty($info['gaurdails_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['gaurdails_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `gaurdails_cmt`='" . $info['gaurdails_cmt'] . "', 
				 `frs`='" . $info['frs'] . "',
				 `frs_date`=" . (!empty($info['frs_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['frs_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `frs_cmt`='" . $info['frs_cmt'] . "', 
				 `osf`='" . $info['osf'] . "',
				 `osf_date`=" . (!empty($info['osf_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['osf_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `osf_cmt`='" . $info['osf_cmt'] . "',					  
				 `opb`='" . $info['opb'] . "',
				 `opb_date`=" . (!empty($info['opb_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['opb_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `opb_cmt`='" . $info['opb_cmt'] . "', 
				 `flm_exp`='" . $info['flm_exp'] . "',
				 `flm_exp_date`=" . (!empty($info['flm_exp_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['flm_exp_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `flm_exp_cmt`='" . $info['flm_exp_cmt'] . "', 
				 `adq_num`='" . $info['adq_num'] . "',
				 `adq_num_date`=" . (!empty($info['adq_num_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['adq_num_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `adq_num_cmt`='" . $info['adq_num_cmt'] . "', 
				 `vme`='" . $info['vme'] . "',
				 `vme_date`=" . (!empty($info['vme_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['vme_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `vme_cmt`='" . $info['vme_cmt'] . "',					   	
				 `over`='" . $info['over'] . "',
				 `over_date`=" . (!empty($info['over_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['over_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `over_cmt`='" . $info['over_cmt'] . "',  	
				 `lad`='" . $info['lad'] . "',
				 `lad_date`=" . (!empty($info['lad_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['lad_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `lad_cmt`='" . $info['lad_cmt'] . "', 
				 `cp`='" . $info['cp'] . "',
				 `cp_date`=" . (!empty($info['cp_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['cp_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `cp_cmt`='" . $info['cp_cmt'] . "',  
				 `msds`='" . $info['msds'] . "',
				 `msds_date`=" . (!empty($info['msds_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['msds_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `msds_cmt`='" . $info['msds_cmt'] . "',					  	
				 `emp`='" . $info['emp'] . "', 
				 `emp_date`=" . (!empty($info['emp_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['emp_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `emp_cmt`='" . $info['emp_cmt'] . "', 
				 `hzcom`='" . $info['hzcom'] . "',
				 `hzcom_date`=" . (!empty($info['hzcom_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['hzcom_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `hzcom_cmt`='" . $info['hzcom_cmt'] . "', 
				 `ef_msds`='" . $info['ef_msds'] . "',
				 `ef_msds_date`=" . (!empty($info['ef_msds_date'])?"'". DateTime::createFromFormat('m/d/Y', $info['ef_msds_date'])->format('Y-m-d')."'" : 'NULL'). ",
				 `ef_msds_cmt`='" . $info['ef_msds_cmt'] . "', 
				 `comments_safety`='" . $info['comments_safety'] . "'";
				
			$stat = mysql_query( $insrt_det);
			$row_id_val = mysql_insert_id();
		}
		
		
		if($stat){				
			# Send email
			require_once(dirname(dirname(dirname(__FILE__))).'/NextcodeMailer/class/NextCodeMailer.class.php');
			$mail = new NextCodeMailer();	
			
			$url = $base_url.'/html2pdf_v4.03/examples/safety_checklist_doc.php?id=' . $row_id_val;
			$binary_content = file_get_contents($url);
			
			$mail->From = 'noreply@nextcode.info';
			$mail->FromName = 'NextCode.Info';
			
			if(isset($info['checked_by_email']) && !empty($info['checked_by_email'])){
				$mail->addAddress($info['checked_by_email']);
			}
			
			# $mail->addAddress('si-notifications@nextcode.info');
			$mail->AddBCC('pankaj1983samal@gmail.com');
			
			$mail->isHTML(true);# Set email format to HTML
			$mail->Subject = 'Southland - Safety Checklist';
			$mail->Body = 'There should be a PDF attached to this message with your info for safety checklist. Check it out!';
			$mail->AltBody = 'There should be a PDF attached to this message with your info for safety checklist. Check it out!';
			$mail->AddStringAttachment($binary_content, "safety_checklist_doc.pdf", 'base64', 'application/pdf');
							
			# $mail must have been created
			if($mail->send()) {			
				$_SESSION['success_msg'] = "Safety Checklist report has been sent to user email.";				
			}
			else{				
				$_SESSION['error_msg'] = "Sorry, mail couldn't be send. Contact Admin!";
			}
		}else{				
			$_SESSION['error_msg'] = "Sorry, an error occurred. Contact Admin!";
		}		
	}
}

# Select divisions
$query = "SELECT * FROM divisions WHERE client = $client AND active = '1'";
$result = mysql_query($query);
while ($ob = mysql_fetch_object($result)) {
	$divisions[$ob->id] = $ob;
}

$query = "SELECT * FROM safety_checklist WHERE id = '" . $id . "'";
$result = mysql_query($query);
while ($ob = mysql_fetch_array($result)) {
	$info= $ob;
	
}
?>

<? include_once dirname(dirname(dirname(__FILE__))).'/_head.php'; ?>

<hr>
<div id="frame">
	<form class="form-horizontal" id="safety_checklist" method="post" action="" name="safety_checklist" enctype="multipart/form-data">	
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
		
		
		<fieldset>
			<h3 class="ttext" style="margin-bottom: 35px;">Jobsite Safety Checklist</h3>
			<span id="error_msg" style="color: red;display: none">Please input all fields marked with *</span>
			
			<div id="personal_edit" >				
				<div class="col-sm-12 row">
					<div class="col-sm-5" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-md-5 control-label">
								<span class="en">Job Name</span>
								<span class="sp">Nombre del trabajo</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-7">
								<input type="text" name="job_name" id="job_name" class="form-control" value="<? if($info['job_name'] != '') echo $info['job_name']; ?>">
							</div>							
						</div>
					</div>	
					<div class="col-sm-7">
						<div class="form-group">
							<label class="col-md-4 control-label">
								<span class="en">Div/Job Number</span>
								<span class="sp">Número de trabajo</span>
								<span class="error">*</span>
							</label>
							<div class="col-md-4">
								<select style="" name="division" class="form-control">
									<option <?php if ($info['division'] == '') { echo 'selected'; } ?> value="">All Division</option>
									<?php 
									foreach($divisions as $div) { 			
									?>
									<option value="<?php echo $div->id; ?>" <?php echo $info['division'] == $div->id?" selected":""; ?>>
										<?php echo $div->nickname; ?>
									</option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="col-md-2">
								<input style="" type="text" name="job_number" id="job_number" class="form-control" value="<? if($info['job_number'] != '') echo $info['job_number']; ?>">
							</div>							
						</div>
					</div>
				</div>				
				
				<div class="col-sm-12 row">
					<div class="col-sm-5" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Checked By</span>
								<span class="sp">comprobado por</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-7">
								<input type="text" name="checked_by" id="checked_by" class="form-control" value="<? if($info['checked_by'] != '') echo $info['checked_by']; ?>">
							</div>							
						</div>
					</div>	
					<div class="col-sm-7">
						<div class="form-group">
							<label class="col-sm-4 control-label">
								<span class="en">Date</span>
								<span class="sp">Fecha</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-6">
								<input type="text" name="safety_date" id="safety_date" class="form-control<?= $err & 16 ? " error" : "" ?>" placeholder="MM/DD/YYYY" value="<? if($info['safety_date'] != '') echo date('m/d/Y', strtotime($info['safety_date']));?>">
							</div>							
						</div>
					</div>
				</div>
				
				<div class="col-sm-12 row">
					<div class="col-sm-5" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-sm-5 control-label">
								<span class="en">Checked By Email</span>
								<span class="sp">comprobado por Email</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-7">
								<input type="text" name="checked_by_email" id="checked_by_email" class="form-control" value="<? if($info['checked_by_email'] != '') echo $info['checked_by_email']; ?>">
							</div>							
						</div>
					</div>						
				</div>
				
				<div class="col-sm-12 row">
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">1. Posting Requirements</span>
								<span class="sp">1. Requisitos de publicación</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Notices, Posters Federal 5in 1,OSHA notice,Payroll: </span>
								<span class="sp" style="font-weight: normal;display: none;">a. Avisos o carteles Federal 5in 1​​, aviso OSHA, Nómina:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="notice" id="notice_1" value="Complaint" style="display:inline-block;" <?php if ($info['notice'] == '' || $info['notice'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="notice" id="notice_2" value="Deficient" <?php if ($info['notice'] == 'Deficient') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-1">
										<input type="radio" name="notice" id="notice_3" value="N" <?php if ($info['notice'] == 'N') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-4">	
										<input class="form-control" style="" type="text" name="notice_date" id="notice_date"  placeholder="MM/DD/YYYY" value="<? if($info['notice_date'] != '') echo date('m/d/Y', strtotime($info['notice_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input class="form-control" type="text" style=" " name="notice_cmt" id="notice_cmt"  placeholder="comments" value="<? if($info['notice_cmt'] != '') echo $info['notice_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Emergency Contacts:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Contactos de emergencia:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row" style="">
									<label class="col-sm-2">
										<input type="radio" name="emergency_con" value="Complaint" id="emergency_con_1" style="display:inline-block;" <?php if ($info['emergency_con'] == '' || $info['emergency_con'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="emergency_con" value="Deficient" id="emergency_con_2" <?php if ($info['emergency_con'] == 'Deficient') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-1">
										<input type="radio" name="emergency_con" value="N" id="emergency_con_3" <?php if ($info['emergency_con'] == 'N') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-4">
										<input class="form-control" type="text" name="emergency_date" id="emergency_date"  placeholder="MM/DD/YYYY" value="<? if($info['emergency_date'] != '') echo date('m/d/Y', strtotime($info['emergency_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="emergency_cmt" id="emergency_cmt"  placeholder="comments" value="<? if($info['emergency_cmt'] != '') echo $info['emergency_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. OSHA 300A Log:</span>
								<span class="sp" style="font-weight: normal;display: none;">c. Registro 300A de OSHA:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row" style="">
									<label class="col-sm-2">
										<input type="radio" name="osha" id="osha_1" value="Complaint" <?php if ($info['osha'] == '' || $info['osha'] == 'Complaint') { ?>checked="true"<?php } ?> style="display:inline-block;">
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="osha" id="osha_2" value="Deficient" <?php if ($info['osha'] == 'Deficient') echo 'checked'; ?> style="display:inline-block;">
									</label>
									<label class="col-sm-1">
										<input type="radio" name="osha" id="osha_3" value="N" <?php if ($info['osha'] == 'N') echo 'checked'; ?>  style="display:inline-block;">
									</label>
									<label class="col-sm-4">
										<input class="form-control" type="text" name="osha_date" id="osha_date"  placeholder="MM/DD/YYYY" value="<? if($info['osha_date'] != '') echo date('m/d/Y', strtotime($info['osha_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="osha_cmt" id="osha_cmt"  placeholder="comments" value="<? if($info['osha_cmt'] != '') echo $info['osha_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">d. Medical Facility location and contact information communicated: </span>
								<span class="sp" style="font-weight: normal;display: none;">d. Ubicación e información de contacto Información del hospital comunicó:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="medical" id="medical_1" value="Complaint" style="display: inline-block;" <?php if ($info['medical'] == '' || $info['medical'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="medical" id="medical_2" value="Deficient" style="display: inline-block;" <?php if ($info['medical'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="medical" id="medical_3" value="N" style="display: inline-block;" <?php if ($info['medical'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="medical_date" id="medical_date"  placeholder="MM/DD/YYYY" value="<? if($info['medical_date'] != '') echo date('m/d/Y', strtotime($info['medical_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="medical_cmt" id="medical_cmt"  placeholder="comments" value="<? if($info['medical_cmt'] != '') echo $info['medical_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
				</div><!--1. RecordKeeping -->						
				
				<div class="col-sm-12 row"><!--2. Personal Protective Equipment -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">2. Personal Protective Equipment</span>
								<span class="sp">2. Equipo de Protección Personal</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. In use safety glasses,hard hats work boots and gloves: </span>
								<span class="sp" style="font-weight: normal;display: none;">a. En uso de gafas de seguridad, cascos botas y guantes de trabajo:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="safety" id="safety_1" value="Complaint"  style="display:inline-block;" <?php if ($info['safety'] == '' || $info['safety'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="safety" id="safety_2" value="Deficient" style="display:inline-block;" <?php if ($info['safety'] == 'Deficient') echo 'checked'; ?>  >
									</label>
									<label class="col-sm-1">
										<input type="radio" name="safety" id="safety_3" value="N" style="display:inline-block;" <?php if ($info['safety'] == 'N') echo 'checked'; ?>  >
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="saftyglas_date" id="saftyglas_date"  placeholder="MM/DD/YYYY" value="<? if($info['saftyglas_date'] != '') echo date('m/d/Y', strtotime($info['saftyglas_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="saftyglas_cmt" id="saftyglas_cmt"  placeholder="comments" value="<? if($info['saftyglas_cmt'] != '') echo $info['saftyglas_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Face shields or goggles used for overhead work:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Cara escudos or gafas utilizadas para trabajos en altura:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="face" id="face_1" value="Complaint" style="display:inline-block;" <?php if ($info['face'] == '' || $info['face'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="face" id="face_2" value="Deficient" style="display:inline-block;" <?php if ($info['face'] == 'Deficient') echo 'checked'; ?> >
									</label>
									<label class="col-sm-1">
										<input type="radio" name="face" id="face_3" value="N" style="display:inline-block;" <?php if ($info['face'] == 'N') echo 'checked'; ?> >
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="face_date" id="face_date"  placeholder="MM/DD/YYYY" value="<? if($info['face_date'] != '') echo date('m/d/Y', strtotime($info['face_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="face_cmt" id="face_cmt"  placeholder="comments" value="<? if($info['face_cmt'] != '') echo $info['face_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>					
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. Respirators available: </span>
								<span class="sp" style="font-weight: normal;display: none;">c. Los respiradores disponibles:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="respirators" id="respirators_1" value="Complaint" style="display: inline-block;" <?php if ($info['respirators'] == '' || $info['respirators'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="respirators" id="respirators_2" value="Deficient" style="display: inline-block;" <?php if ($info['respirators'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="respirators" id="respirators_3" value="N" style="display: inline-block;" <?php if ($info['respirators'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="resp_date" id="resp_date"  placeholder="MM/DD/YYYY" value="<? if($info['resp_date'] != '') echo date('m/d/Y', strtotime($info['resp_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="resp_cmt" id="resp_cmt"  placeholder="comments" value="<? if($info['resp_cmt'] != '') echo $info['resp_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">d. Welding screens:</span>
								<span class="sp" style="font-weight: normal;display: none;">d. Pantallas de soldadura:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="welding" id="welding_1"  value="Complaint" style="display: inline-block;" <?php if ($info['welding'] == '' || $info['welding'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="welding" id="welding_2" value="Deficient" style="display: inline-block;" <?php if ($info['welding'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="welding" id="welding_3" value="N" style="display: inline-block;" <?php if ($info['welding'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="welding_date" id="welding_date"  placeholder="MM/DD/YYYY" value="<? if($info['welding_date'] != '') echo date('m/d/Y', strtotime($info['welding_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="welding_cmt" id="welding_cmt"  placeholder="comments" value="<? if($info['welding_cmt'] != '') echo $info['welding_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
				</div><!--2. Personal Protective Equipment -->
				
				<div class="col-sm-12 row"><!--3. First Aid Kits -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">3. First Aid Kits</span>
								<span class="sp">3. Kits de Primeros Auxilios</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Available in gang box and jobsite trailer:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Disponible en caja eléctrica agregar remolque sitio de trabajo:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="avl_gang" id="avl_gang_1" value="Complaint" style="display: inline-block;" <?php if ($info['avl_gang'] == '' || $info['avl_gang'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="avl_gang" id="avl_gang_2" value="Deficient" style="display: inline-block;" <?php if ($info['avl_gang'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="avl_gang" id="avl_gang_3" value="N" style="display: inline-block;" <?php if ($info['avl_gang'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="avl_gang_date" id="avl_gang_date"  placeholder="MM/DD/YYYY" value="<? if($info['avl_gang_date'] != '') echo date('m/d/Y', strtotime($info['avl_gang_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="avl_gang_cmt" id="avl_gang_cmt"  placeholder="comments" value="<? if($info['avl_gang_cmt'] != '') echo $info['avl_gang_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Stocked adequately with gloves,bandages and antiseptics:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Adecuadamente equipada con los guantes, vendas y antisépticos:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="stocked" id="stocked_1" value="Complaint" style="display: inline-block;" <?php if ($info['stocked'] == '' || $info['stocked'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="stocked" id="stocked_2" value="Deficient" style="display: inline-block;" <?php if ($info['stocked'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="stocked" id="stocked_3" value="N" style="display: inline-block;" <?php if ($info['stocked'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="stocked_date" id="stocked_date"  placeholder="MM/DD/YYYY" value="<? if($info['stocked_date'] != '') echo date('m/d/Y', strtotime($info['stocked_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="stocked_cmt" id="stocked_cmt"  placeholder="comments" value="<? if($info['stocked_cmt'] != '') echo $info['stocked_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. CPR and First Aid trained personnel:</span>
								<span class="sp" style="font-weight: normal;display: none;">c. Personal de RCP y primeros auxilios entrenados:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="cpr" id="cpr_1" value="Complaint" style="display: inline-block;" <?php if ($info['cpr'] == '' || $info['cpr'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="cpr" id="cpr_2" value="Deficient" style="display: inline-block;" <?php if ($info['cpr'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="cpr" id="cpr_3" value="N" style="display: inline-block;" <?php if ($info['cpr'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="cpr_date" id="cpr_date"  placeholder="MM/DD/YYYY" value="<? if($info['cpr_date'] != '') echo date('m/d/Y', strtotime($info['cpr_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="cpr_cmt" id="cpr_cmt"  placeholder="comments" value="<? if($info['cpr_cmt'] != '') echo $info['cpr_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
										
				</div><!--3. First Aid Kits -->
				
				<div class="col-sm-12 row"><!--4. Scaffold -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">4. Scaffold</span>
								<span class="sp">4. Andamios</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Competent person certified:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Persona competente certifica:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="competent" id="competent_1" value="Complaint" style="display: inline-block;" <?php if ($info['competent'] == '' || $info['competent'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="competent" id="competent_2" value="Deficient" style="display: inline-block;" <?php if ($info['competent'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="competent" id="competent_3" value="N" style="display: inline-block;" <?php if ($info['competent'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="competent_date" id="competent_date"  placeholder="MM/DD/YYYY" value="<? if($info['competent_date'] != '') echo date('m/d/Y', strtotime($info['competent_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="competent_cmt" id="competent_cmt"  placeholder="comments" value="<? if($info['competent_cmt'] != '') echo $info['competent_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Scaffold grade planking:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Planking Andamios grado:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="scaffold" id="scaffold_1" value="Complaint" style="display: inline-block;" <?php if ($info['scaffold'] == '' || $info['scaffold'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="scaffold" id="scaffold_2" value="Deficient" style="display: inline-block;" <?php if ($info['scaffold'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="scaffold" id="scaffold_3" value="N" style="display: inline-block;" <?php if ($info['scaffold'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="scaffold_date" id="scaffold_date"  placeholder="MM/DD/YYYY" value="<? if($info['scaffold_date'] != '') echo date('m/d/Y', strtotime($info['scaffold_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="scaffold_cmt" id="scaffold_cmt"  placeholder="comments" value="<? if($info['scaffold_cmt'] != '') echo $info['scaffold_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
										
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. Clear of trash/debris:</span>
								<span class="sp" style="font-weight: normal;display: none;">c. Borrar de la basura / residuos:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="clear" id="clear_1" value="Complaint" style="display: inline-block;" <?php if ($info['clear'] == '' || $info['clear'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="clear" id="clear_2" value="Deficient" style="display: inline-block;" <?php if ($info['clear'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="clear" id="clear_3" value="N" style="display: inline-block;" <?php if ($info['clear'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="clear_date" id="clear_date"  placeholder="MM/DD/YYYY" value="<? if($info['clear_date'] != '') echo date('m/d/Y', strtotime($info['clear_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="clear_cmt" id="clear_cmt"  placeholder="comments" value="<? if($info['clear_cmt'] != '') echo $info['clear_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>					
				</div><!--4. Scaffold -->
			
				<div class="col-sm-12 row"><!--5. Ladders -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">5. Ladders</span>
								<span class="sp">5. Escaleras</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Free from defects, with safet feet,blocked,cleated or otherwise secured:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Libre de defectos, con los pies safet, bloqueado, cleated o asegurarse de alguna forma:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="free" id="free_1" value="Complaint" style="display: inline-block;" <?php if ($info['free'] == '' || $info['free'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="free" id="free_2" value="Deficient" style="display: inline-block;" <?php if ($info['free'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="free" id="free_3" value="N" style="display: inline-block;" <?php if ($info['free'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="free_date" id="free_date"  placeholder="MM/DD/YYYY" value="<? if($info['free_date'] != '') echo date('m/d/Y', strtotime($info['free_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="free_cmt" id="free_cmt"  placeholder="comments" value="<? if($info['free_cmt'] != '') echo $info['free_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. straight ladders at 1 to 4 pitch: </span>
								<span class="sp" style="font-weight: normal;display: none;">b. escaleras rectas en 1 a 4 de tono:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="st_ladder" id="st_ladder_1" value="Complaint" style="display: inline-block;" <?php if ($info['st_ladder'] == '' || $info['st_ladder'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="st_ladder" id="st_ladder_2" value="Deficient" style="display: inline-block;" <?php if ($info['st_ladder'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="st_ladder" id="st_ladder_3" value="N" style="display: inline-block;" <?php if ($info['st_ladder'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="st_ladder_date" id="st_ladder_date"  placeholder="MM/DD/YYYY" value="<? if($info['st_ladder_date'] != '') echo date('m/d/Y', strtotime($info['st_ladder_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="st_ladder_cmt" id="st_ladder_cmt"  placeholder="comments" value="<? if($info['st_ladder_cmt'] != '') echo $info['st_ladder_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. Fall Protection:</span>
								<span class="sp" style="font-weight: normal;display: none;">c. Protección contra caídas:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="fall_Pro" id="fall_Pro_1" value="Complaint" style="display: inline-block;" <?php if ($info['fall_Pro'] == '' || $info['fall_Pro'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="fall_Pro" id="fall_Pro_2" value="Deficient" style="display: inline-block;" <?php if ($info['fall_Pro'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="fall_Pro" id="fall_Pro_3" value="N" style="display: inline-block;" <?php if ($info['fall_Pro'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="fall_Pro_date" id="fall_Pro_date"  placeholder="MM/DD/YYYY" value="<? if($info['fall_Pro_date'] != '') echo date('m/d/Y', strtotime($info['fall_Pro_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="fall_Pro_cmt" id="fall_Pro_cmt"  placeholder="comments" value="<? if($info['fall_Pro_cmt'] != '') echo $info['fall_Pro_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
				</div><!--5. Ladders -->				
				
				<div class="col-sm-12 row"><!--6. Cylinders -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">6. Cylinders</span>
								<span class="sp">6. Cilindros</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Capped,stored in an upright position:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Convocado, almacenado en posición vertical:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="capped" id="capped_1" value="Complaint" style="display: inline-block;" <?php if ($info['capped'] == '' || $info['capped'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="capped" id="capped_2" value="Deficient" style="display: inline-block;" <?php if ($info['capped'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="capped" id="capped_3" value="N" style="display: inline-block;" <?php if ($info['capped'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="capped_date" id="capped_date"  placeholder="MM/DD/YYYY" value="<? if($info['capped_date'] != '') echo date('m/d/Y', strtotime($info['capped_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="capped_cmt" id="capped_cmt"  placeholder="comments" value="<? if($info['capped_cmt'] != '') echo $info['capped_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Oxygen/Accetylene property separated:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Oxígeno propiedad / Accetylene separado:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="oxygen" id="oxygen_1" value="Complaint" style="display: inline-block;" <?php if ($info['oxygen'] == '' || $info['oxygen'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="oxygen" id="oxygen_2" value="Deficient" style="display: inline-block;" <?php if ($info['oxygen'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="oxygen" id="oxygen_3" value="N" style="display: inline-block;" <?php if ($info['oxygen'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="oxygen_date" id="oxygen_date"  placeholder="MM/DD/YYYY" value="<? if($info['oxygen_date'] != '') echo date('m/d/Y', strtotime($info['oxygen_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="oxygen_cmt" id="oxygen_cmt"  placeholder="comments" value="<? if($info['oxygen_cmt'] != '') echo $info['oxygen_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. Empty gas cylinders marked:</span>
								<span class="sp" style="font-weight: normal;display: none;">c. Cilindros de gas vacíos marcados:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="empty" id="empty_1" value="Complaint" style="display: inline-block;" <?php if ($info['empty'] == '' || $info['empty'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="empty" id="empty_2" value="Deficient" style="display: inline-block;" <?php if ($info['empty'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="empty" id="empty_3" value="N" style="display: inline-block;" <?php if ($info['empty'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="empty_date" id="empty_date"  placeholder="MM/DD/YYYY" value="<? if($info['empty_date'] != '') echo date('m/d/Y', strtotime($info['empty_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="empty_cmt" id="empty_cmt"  placeholder="comments" value="<? if($info['empty_cmt'] != '') echo $info['empty_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
				</div><!--6. Cylinders -->
				
				<div class="col-sm-12 row"><!--7. Tools/Equipment -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">7. Tools/Equipment</span>
								<span class="sp">7. Herramientas / Equipo</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Inspected to ensure safe operating condition:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Inspeccionado para garantizar condiciones seguras de funcionamiento:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="inspected" id="inspected_1" value="Complaint" style="display: inline-block;" <?php if ($info['inspected'] == '' || $info['inspected'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="inspected" id="inspected_2" value="Deficient" style="display: inline-block;" <?php if ($info['inspected'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="inspected" id="inspected_3" value="N" style="display: inline-block;" <?php if ($info['inspected'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="inspected_date" id="inspected_date"  placeholder="MM/DD/YYYY" value="<? if($info['inspected_date'] != '') echo date('m/d/Y', strtotime($info['inspected_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="inspected_cmt" id="inspected_cmt"  placeholder="comments" value="<? if($info['inspected_cmt'] != '') echo $info['inspected_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Hand tools free from defects:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Herramientas de mano libre de defectos:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="hand" id="hand_1" value="Complaint" style="display: inline-block;" <?php if ($info['hand'] == '' || $info['hand'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="hand" id="hand_2" value="Deficient" style="display: inline-block;" <?php if ($info['hand'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="hand" id="hand_3" value="N" style="display: inline-block;" <?php if ($info['hand'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="hand_date" id="hand_date"  placeholder="MM/DD/YYYY" value="<? if($info['hand_date'] != '') echo date('m/d/Y', strtotime($info['hand_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="hand_cmt" id="hand_cmt"  placeholder="comments" value="<? if($info['hand_cmt'] != '') echo $info['hand_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. Unsafe/Unusable tools/equipment tagged "Do Not Use":</span>
								<span class="sp" style="font-weight: normal;display: none;">c. / Herramientas inutilizables / equipo inseguro etiquetado como "no utilizar":</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="unsafe" id="unsafe_1" value="Complaint" style="display: inline-block;" <?php if ($info['unsafe'] == '' || $info['unsafe'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="unsafe" id="unsafe_2" value="Deficient" style="display: inline-block;" <?php if ($info['unsafe'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="unsafe" id="unsafe_3" value="N" style="display: inline-block;" <?php if ($info['unsafe'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="unsafe_date" id="unsafe_date"  placeholder="MM/DD/YYYY" value="<? if($info['unsafe_date'] != '') echo date('m/d/Y', strtotime($info['unsafe_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="unsafe_cmt" id="unsafe_cmt"  placeholder="comments" value="<? if($info['unsafe_cmt'] != '') echo $info['unsafe_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">d. Tools/Equipment properly guarded:</span>
								<span class="sp" style="font-weight: normal;display: none;">d. Tools/Equipment properly guarded::</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="tools" id="tools_1" value="Complaint" style="display: inline-block;" <?php if ($info['tools'] == '' || $info['tools'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="tools" id="tools_2" value="Deficient" style="display: inline-block;" <?php if ($info['tools'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="tools" id="tools_3" value="N" style="display: inline-block;" <?php if ($info['tools'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="tools_date" id="tools_date"  placeholder="MM/DD/YYYY" value="<? if($info['tools_date'] != '') echo date('m/d/Y', strtotime($info['tools_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="tools_cmt" id="tools_cmt"  placeholder="comments" value="<? if($info['tools_cmt'] != '') echo $info['tools_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
				</div><!--7. Tools/Equipment -->
					
				<div class="col-sm-12 row"><!--8. Housekeeping -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">8. Housekeeping</span>
								<span class="sp">8. Servicio de limpieza</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<!--div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Maintained:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Maintained:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="maintained" id="maintained_1" value="Complaint" style="display: inline-block;" <?php if ($info['maintained'] == '' || $info['maintained'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="maintained" id="maintained_2" value="Deficient" style="display: inline-block;" <?php if ($info['maintained'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="maintained" id="maintained_3" value="N" style="display: inline-block;" <?php if ($info['maintained'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="maintained_date" id="maintained_date"  placeholder="MM/DD/YYYY" value="<? if($info['maintained_date'] != '') echo date('m/d/Y', strtotime($info['maintained_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="maintained_cmt" id="maintained_cmt"  placeholder="comments" value="<? if($info['maintained_cmt'] != '') echo $info['maintained_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div-->
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Aisles and exitways clear with 24" clearance:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Los pasillos y exitways claras con "despacho 24:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="aisles" id="aisles_1" value="Complaint" style="display: inline-block;" <?php if ($info['aisles'] == '' || $info['aisles'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="aisles" id="aisles_2" value="Deficient" style="display: inline-block;" <?php if ($info['aisles'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="aisles" id="aisles_3" value="N" style="display: inline-block;" <?php if ($info['aisles'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="aisles_date" id="aisles_date"  placeholder="MM/DD/YYYY" value="<? if($info['aisles_date'] != '') echo date('m/d/Y', strtotime($info['aisles_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="aisles_cmt" id="aisles_cmt"  placeholder="comments" value="<? if($info['aisles_cmt'] != '') echo $info['aisles_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Work areas and debris removed: </span>
								<span class="sp" style="font-weight: normal;display: none;">b. Las áreas de trabajo y escombros removidos:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="work" id="work_1" value="Complaint" style="display: inline-block;" <?php if ($info['work'] == '' || $info['work'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="work" id="work_2" value="Deficient" style="display: inline-block;" <?php if ($info['work'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="work" id="work_2" value="N" style="display: inline-block;" <?php if ($info['work'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="work_date" id="work_date"  placeholder="MM/DD/YYYY" value="<? if($info['work_date'] != '') echo date('m/d/Y', strtotime($info['work_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="work_cmt" id="work_cmt"  placeholder="comments" value="<? if($info['work_cmt'] != '') echo $info['work_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
				</div><!--8. Housekeeping -->
				
				<div class="col-sm-12 row"><!--9. Electrical -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">9. Electrical</span>
								<span class="sp">9. Eléctrico</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Electrical equipment gounded:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. El equipo eléctrico gounded:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="electrical" id="electrical_1" value="Complaint" style="display: inline-block;" <?php if ($info['electrical'] == '' || $info['electrical'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="electrical" id="electrical_2" value="Deficient" style="display: inline-block;" <?php if ($info['electrical'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="electrical" id="electrical_3" value="N" style="display: inline-block;" <?php if ($info['electrical'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="electrical_date" id="electrical_date"  placeholder="MM/DD/YYYY" value="<? if($info['electrical_date'] != '') echo date('m/d/Y', strtotime($info['electrical_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="electrical_cmt" id="electrical_cmt"  placeholder="comments" value="<? if($info['electrical_cmt'] != '') echo $info['electrical_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Tools doubled insulted:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Herramientas duplicaron insultados:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="tls_ins" id="tls_ins_1" value="Complaint" style="display: inline-block;" <?php if ($info['tls_ins'] == '' || $info['tls_ins'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="tls_ins" id="tls_ins_2"  value="Deficient" style="display: inline-block;" <?php if ($info['tls_ins'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="tls_ins" id="tls_ins_3" value="N" style="display: inline-block;" <?php if ($info['tls_ins'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control"  type="text" name="tls_ins_date" id="tls_ins_date"  placeholder="MM/DD/YYYY" value="<? if($info['tls_ins_date'] != '') echo date('m/d/Y', strtotime($info['tls_ins_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="tls_ins_cmt" id="tls_ins_cmt"  placeholder="comments" value="<? if($info['tls_ins_cmt'] != '') echo $info['tls_ins_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. Cords in good condition:</span>
								<span class="sp" style="font-weight: normal;display: none;">c. Cordones en buenas condiciones:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="cords" id="cords_1" value="Complaint" style="display: inline-block;" <?php if ($info['cords'] == '' || $info['cords'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="cords" id="cords_2" value="Deficient" style="display: inline-block;" <?php if ($info['cords'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="cords" id="cords_3" value="N" style="display: inline-block;" <?php if ($info['cords'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="cords_date" id="cords_date"  placeholder="MM/DD/YYYY" value="<? if($info['cords_date'] != '') echo date('m/d/Y', strtotime($info['cords_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="cords_cmt" id="cords_cmt"  placeholder="comments" value="<? if($info['cords_cmt'] != '') echo $info['cords_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">d. Electrical pannels covered if energized:</span>
								<span class="sp" style="font-weight: normal;display: none;">d. Paneles eléctricos cubiertos si se activan:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="elec_panel" id="elec_panel_1" value="Complaint" style="display: inline-block;" <?php if ($info['elec_panel'] == '' || $info['elec_panel'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="elec_panel" id="elec_panel_2" value="Deficient" style="display: inline-block;" <?php if ($info['elec_panel'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="elec_panel" id="elec_panel_3" value="N" style="display: inline-block;" <?php if ($info['elec_panel'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="elec_panel_date" id="elec_panel_date"  placeholder="MM/DD/YYYY" value="<? if($info['elec_panel_date'] != '') echo date('m/d/Y', strtotime($info['elec_panel_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="elec_panel_cmt" id="elec_panel_cmt"  placeholder="comments" value="<? if($info['elec_panel_cmt'] != '') echo $info['elec_panel_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
				</div><!--9. Electrical -->	
               
				<div class="col-sm-12 row"><!--10. Fall Protection -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">10. Fall Protection</span>
								<span class="sp">10. Protección contra las caídas</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Guardrails,midrails,toeboards:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Las barandillas, largueros intermedios, tablas de pie:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="gaurdails" id="gaurdails_1" value="omplaint" style="display: inline-block;" <?php if ($info['gaurdails'] == '' || $info['gaurdails'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="gaurdails" id="gaurdails_1" value="Deficient" style="display: inline-block;" <?php if ($info['gaurdails'] == '' || $info['gaurdails'] == 'Deficient') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="gaurdails" id="gaurdails_3" value="N" style="display: inline-block;" <?php if ($info['gaurdails'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="gaurdails_date" id="gaurdails_date"  placeholder="MM/DD/YYYY" value="<? if($info['gaurdails_date'] != '') echo date('m/d/Y', strtotime($info['gaurdails_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="gaurdails_cmt" id="gaurdails_cmt"  placeholder="comments" value="<? if($info['gaurdails_cmt'] != '') echo $info['gaurdails_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Fall restraint systems:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Otoño sistemas de retención:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="frs" id="frs_1" value="Complaint" style="display: inline-block;" <?php if ($info['frs'] == '' || $info['frs'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="frs" id="frs_2" value="Deficient" style="display: inline-block;" <?php if ($info['frs'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="frs" id="frs_3" value="N" style="display: inline-block;" <?php if ($info['frs'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="frs_date" id="frs_date"  placeholder="MM/DD/YYYY" value="<? if($info['frs_date'] != '') echo date('m/d/Y', strtotime($info['frs_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="frs_cmt" id="frs_cmt"  placeholder="comments" value="<? if($info['frs_cmt'] != '') echo $info['frs_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. Open sided floors or platforms equiped with standard railing:</span>
								<span class="sp" style="font-weight: normal;display: none;">c. Abra pisos lados o plataformas equipadas con baranda estándar:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="osf" id="osf_1" value="Complaint" style="display: inline-block;" <?php if ($info['osf'] == '' || $info['osf'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="osf" id="osf_2" value="Deficient" style="display: inline-block;" <?php if ($info['osf'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="osf" id="osf_3" value="N" style="display: inline-block;" <?php if ($info['osf'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="osf_date" id="osf_date"  placeholder="MM/DD/YYYY" value="<? if($info['osf_date'] != '') echo date('m/d/Y', strtotime($info['osf_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" " name="osf_cmt" id="osf_cmt"  placeholder="comments" value="<? if($info['osf_cmt'] != '') echo $info['osf_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">d. Opening (interior/perimeter) properly barricaded or covered:</span>
								<span class="sp" style="font-weight: normal;display: none;">d. Apertura (interior / perímetro) correctamente con barricadas o cubierta:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="opb" id="opb_1" value="Complaint" style="display: inline-block;" <?php if ($info['opb_com'] == '' || $info['opb'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="opb" id="opb_2" value="Deficient" style="display: inline-block;" <?php if ($info['opb'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="opb" id="opb_3" value="N" style="display: inline-block;" <?php if ($info['opb'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="opb_date" id="opb_date"  placeholder="MM/DD/YYYY" value="<? if($info['opb_date'] != '') echo date('m/d/Y', strtotime($info['opb_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="opb_cmt" id="opb_cmt"  placeholder="comments" value="<? if($info['opb_cmt'] != '') echo $info['opb_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
				</div><!--10. Fall Protection -->	
					
				<div class="col-sm-12 row"><!--11. Fire Prevention -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">11. Fire Prevention</span>
								<span class="sp">11. Prevención de Incendios</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Flammable and explosive materials stored safely:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Los materiales inflamables y explosivos almacenados de forma segura:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="flm_exp" id="flm_exp_1" value="Complaint" style="display: inline-block;" <?php if ($info['flm_exp'] == '' || $info['flm_exp'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="flm_exp" id="flm_exp_2" value="Deficient" style="display: inline-block;" <?php if ($info['flm_exp'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="flm_exp" id="flm_exp_3" value="N" style="display: inline-block;" <?php if ($info['flm_exp'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="flm_exp_date" id="flm_exp_date"  placeholder="MM/DD/YYYY" value="<? if($info['flm_exp_date'] != '') echo date('m/d/Y', strtotime($info['flm_exp_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="flm_exp_cmt" id="flm_exp_cmt"  placeholder="comments" value="<? if($info['flm_exp_cmt'] != '') echo $info['flm_exp_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Adequate number of fire extinguishers available with tags and clips:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Número adecuado de extintores disponibles con las etiquetas y clips:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="adq_num" id="adq_num_1" value="Complaint" style="display: inline-block;" <?php if ($info['adq_num'] == '' || $info['adq_num'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="adq_num" id="adq_num_2" value="Deficient" style="display: inline-block;" <?php if ($info['adq_num'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="adq_num" id="adq_num_3" value="N" style="display: inline-block;" <?php if ($info['adq_num'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="adq_num_date" id="adq_num_date"  placeholder="MM/DD/YYYY" value="<? if($info['adq_num_date'] != '') echo date('m/d/Y', strtotime($info['adq_num_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="adq_num_cmt" id="adq_num_cmt"  placeholder="comments" value="<? if($info['adq_num_cmt'] != '') echo $info['adq_num_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. Vehicles and mobile equipment provided with extinguishers:</span>
								<span class="sp" style="font-weight: normal;display: none;"> c. Vehículos y equipos móviles provistos de extintores</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="vme" id="vme_1" value="Complaint" style="display: inline-block;" <?php if ($info['vme'] == '' || $info['vme'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="vme" id="vme_2" value="Deficient" style="display: inline-block;" <?php if ($info['vme'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="vme" id="vme_3" value="N" style="display: inline-block;" <?php if ($info['vme'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="vme_date" id="vme_date"  placeholder="MM/DD/YYYY" value="<? if($info['vme_date'] != '') echo date('m/d/Y', strtotime($info['vme_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="vme_cmt" id="vme_cmt"  placeholder="comments" value="<? if($info['vme_cmt'] != '') echo $info['vme_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
				</div><!--11. Fire Prevention -->	
				
				<div class="col-sm-12 row"><!--12. Excavations -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">12. Excavations</span>
								<span class="sp">12. excavaciones</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. Over 4 ft shored, benched or sloped as required:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. Más de 4 pies apuntalados, o tienen una pendiente según sea necesario:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="over" id="over_1" value="Complaint" style="display: inline-block;" <?php if ($info['over'] == '' || $info['over'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="over" id="over_2" value="Deficient" style="display: inline-block;" <?php if ($info['over'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="over" id="over_3" value="N" style="display: inline-block;" <?php if ($info['over'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="over_date" id="over_date"  placeholder="MM/DD/YYYY" value="<? if($info['over_date'] != '') echo date('m/d/Y', strtotime($info['over_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="over_cmt" id="over_cmt"  placeholder="comments" value="<? if($info['over_cmt'] != '') echo $info['over_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Steps or ladders at 25 ft intervals: </span>
								<span class="sp" style="font-weight: normal;display: none;">b. Pasos o escaleras a intervalos de 25 pies:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="lad" id="lad_1" value="Complaint" style="display: inline-block;" <?php if ($info['lad'] == '' || $info['lad'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="lad" id="lad_2" value="Deficient" style="display: inline-block;" <?php if ($info['lad'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="lad" id="lad_3" value="N" style="display: inline-block;" <?php if ($info['lad'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="lad_date" id="lad_date"  placeholder="MM/DD/YYYY" value="<? if($info['lad_date'] != '') echo date('m/d/Y', strtotime($info['lad_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="lad_cmt" id="lad_cmt"  placeholder="comments" value="<? if($info['lad_cmt'] != '') echo $info['lad_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. Competent person on site: </span>
								<span class="sp" style="font-weight: normal;display: none;">c. Persona competente en el sitio:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="cp" id="cp_1" value="Complaint" style="display: inline-block;" <?php if ($info['cp'] == '' || $info['cp'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="cp" id="cp_2" value="Deficient" style="display: inline-block;" <?php if ($info['cp'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="cp" id="cp_3" value="N" style="display: inline-block;" <?php if ($info['cp'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="cp_date" id="cp_date"  placeholder="MM/DD/YYYY" value="<? if($info['cp_date'] != '') echo date('m/d/Y', strtotime($info['cp_date'])); ?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="cp_cmt" id="cp_cmt"  placeholder="comments" value="<? if($info['cp_cmt'] != '') echo $info['cp_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>	
					
				</div><!--12. Excavations -->	
			
				<div class="col-sm-12 row"><!--13. Hazzard Communication -->
					<div class="col-sm-12 row">
						<div class="form-group">
							<label class="col-sm-5 control-label" >
								<span class="en">13. Hazzard Communication</span>
								<span class="sp">13. Comunicación Hazzard</span>
								<span class="error"></span>		
							</label>
							<label class="col-sm-7" style="">
								<div class="col-sm-12 row">
									<label class="col-sm-2 control-label cdj">Compliant</label>	
									<label class="col-sm-2 control-label">Deficient</label>	
									<label class="col-sm-1 control-label">N/A</label>
									<label class="col-sm-4 control-label">Abated Date</label>
									<label class="col-sm-3 control-label">Comments</label>	
								</div>
							</label>
						</div>					
					</div>
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">a. SDS and Labels available:</span>
								<span class="sp" style="font-weight: normal;display: none;">a. SDS y etiquetas disponibles:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="msds" id="msds_1" value="Complaint" style="display: inline-block;" <?php if ($info['msds'] == '' || $info['msds'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="msds" id="msds_2" value="Deficient" style="display: inline-block;" <?php if ($info['msds'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="msds" id="msds_3" value="N" style="display: inline-block;" <?php if ($info['msds'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="msds_date" id="msds_date"  placeholder="MM/DD/YYYY" value="<? if($info['msds_date'] != '') echo date('m/d/Y', strtotime($info['msds_date'])); ?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="msds_cmt" id="msds_cmt"  placeholder="comments" value="<? if($info['msds_cmt'] != '') echo $info['msds_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">b. Employees briefed on HAZCOM:</span>
								<span class="sp" style="font-weight: normal;display: none;">b. Los empleados informados sobre HAZCOM:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="emp" id="emp_3" value="Complaint" style="display: inline-block;" <?php if ($info['emp'] == '' || $info['emp'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="emp" id="emp_2" value="Deficient" style="display: inline-block;" <?php if ($info['emp'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="emp" id="emp_3" value="N" style="display: inline-block;" <?php if ($info['emp'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="emp_date" id="emp_date"  placeholder="MM/DD/YYYY" value="<? if($info['emp_date'] != '') echo date('m/d/Y', strtotime($info['emp_date'])); ?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="emp_cmt" id="emp_cmt"  placeholder="comments" value="<? if($info['emp_cmt'] != '') echo $info['emp_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">c. HAZCOM information Poster posted:</span>
								<span class="sp" style="font-weight: normal;display: none;">c. Información HAZCOM cartel colocado:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="hzcom" id="hzcom_1" value="Complaint" style="display: inline-block;" <?php if ($info['hzcom'] == '' || $info['hzcom'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="hzcom" id="hzcom_2" value="Deficient" style="display: inline-block;" <?php if ($info['hzcom'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="hzcom" id="hzcom_3" value="N" style="display: inline-block;" <?php if ($info['hzcom'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="hzcom_date" id="hzcom_date"  placeholder="MM/DD/YYYY" value="<? if($info['hzcom_date'] != '') echo date('m/d/Y', strtotime($info['hzcom_date'])); ?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="hzcom_cmt" id="hzcom_cmt"  placeholder="comments" value="<? if($info['hzcom_cmt'] != '') echo $info['hzcom_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-sm-12 row control-label">
						<div class="form-group">
							<div class="col-sm-5">
								<span class="en" style="font-weight: normal;">d. Employees familiar with SDS books and their location:</span>
								<span class="sp" style="font-weight: normal;display: none;">d. Los empleados están familiarizados con SDS libros y su localización:</span>
							</div>
							<div class="col-sm-7">
								<div class="col-sm-12 row">
									<label class="col-sm-2">
										<input type="radio" name="ef_msds" id="ef_msds_1" value="Complaint" style="display: inline-block;" <?php if ($info['ef_msds'] == '' || $info['ef_msds'] == 'Complaint') { ?>checked="true"<?php } ?>>
									</label>
									<label class="col-sm-2">	
										<input type="radio" name="ef_msds" id="ef_msds_2" value="Deficient" style="display: inline-block;" <?php if ($info['ef_msds'] == 'Deficient') echo 'checked'; ?>>
									</label>
									<label class="col-sm-1">
										<input type="radio" name="ef_msds" id="ef_msds_3" value="N" style="display: inline-block;" <?php if ($info['ef_msds'] == 'N') echo 'checked'; ?>>
									</label>
									<label class="col-sm-4">	
										<input class="form-control" type="text" name="ef_msds_date" id="ef_msds_date"  placeholder="MM/DD/YYYY" value="<? if($info['ef_msds_date'] != '') echo date('m/d/Y', strtotime($info['ef_msds_date']));?>">
									</label>
									<label class="col-sm-3">	
										<input type="text" class="form-control" name="ef_msds_cmt" id="ef_msds_cmt"  placeholder="comments" value="<? if($info['ef_msds_cmt'] != '') echo $info['ef_msds_cmt']; ?>">
									</label>
								</div>
							</div>
						</div>
					</div>
					
				</div><!--13. Hazzard Communication -->					
				
				<div class="col-sm-12 row" style="padding-top: 20px;">
					<div class="col-sm-12" style="padding-left: 0px;">
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span class="en">Comments</span>
								<span class="sp">Comentarios</span>
								<span class="error">*</span>
							</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="comments_safety" id="comments_safety" rows="5" cols="50" style="width:100%;"><?php if($info['comments_safety'] != '') echo $info['comments_safety']; ?></textarea>
							</div>							
						</div>
					</div>
				</div>				
			</div>
			
			<div class="col-sm-12 ">
				<div class="col-sm-3 row">								
					<button type="button" class="btn btn-danger" onclick="window.location.href='/portal/'">Cancel</button>
					&nbsp;
					<input type="submit" name="save" class="btn btn-primary" value="Submit">
				</div>		
			</div>
			
			<div style="clear:both;"><br></div>
		</fieldset>
		
	</form>
</div>

<script>
$(document).ready(function() {		
	$("#safety_checklist").validate({
		rules: {
			job_name: "required",
			job_number: "required",
			division : "required", 
			checked_by: "required",
			checked_by_email: {required: true, email: true},
			safety_date: "required",
			comments_safety: "required",		
		},		
	});
	
	$("#safety_date,#notice_date,#emergency_date,#osha_date,#saftyglas_date,#face_date,#resp_date,#welding_date,#avl_gang_date,#stocked_date,#cpr_date,#medical_date,#competent_date,#scaffold_date,#fall_Pro_date,#clear_date,#free_date,#st_ladder_date,#capped_date,#oxygen_date,#empty_date,#inspected_date,#hand_date,#unsafe_date,#tools_date,#maintained_date,#aisles_date,#work_date,#electrical_date,#tls_ins_date,#cords_date,#elec_panel_date,#gaurdails_date,#frs_date,#osf_date,#opb_date,#flm_exp_date,#adq_num_date,#vme_date,#over_date,#lad_date,#cp_date,#msds_date,#emp_date,#hzcom_date,#ef_msds_date").datetimepicker({
		lang:'en',
		timepicker:false,
		format:'m/d/Y',
		closeOnDateSelect: true,
		scrollInput: false,
	});
});
</script>
<? include_once dirname(dirname(dirname(__FILE__))).'/_foot.php'; ?>