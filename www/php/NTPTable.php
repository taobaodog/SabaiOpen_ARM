<?php
	$act = $_POST['action'];
	$data_post = $_POST["raw"]["data"][0];
	$data_raw = $_POST["raw"]["data"][0]["DT_RowId"];
	$data_val = $_POST["raw"]["data"][0]["ntp_server"];

	$json_old_raw = file_get_contents("/www/libs/data/network.time.json");
	$json_old = json_decode($json_old_raw, true);
	$json_new = array();
	$length	 = count($json_old["aaData"]) - 1;


	function remove() {
		global $data_val, $json_old, $json_new;
		$string_to_remove = $data_val;
		foreach ($json_old["aaData"] as $key => $value) {
			if ($value["ntp_server"] != $string_to_remove) {
				$json_new[] = $value; 
			}
		}
		
		$json_old["aaData"] = $json_new;
		$data = json_encode($json_old, true);
		file_put_contents("/www/libs/data/network.time.json", $data);
		echo "NTP server has been removed.";	
	}

	function edit() {
		global $data_val, $data_raw, $json_old, $json_new, $length;
		$string_to_edit = $data_raw;
		foreach ($json_old["aaData"] as $key => $value) {
			if ($value["ntp_server"] == $data_val) {
				$res = "false";
				$json_new = $json_old["aaData"];
				break;
			} else {
				if ($key == $length) {
					foreach ($json_old["aaData"] as $key => $value) {
 						if ($value["DT_RowId"] == $string_to_edit) {
 							$value["ntp_server"] = $data_val;
							$res = "NTP server options has been changed.";
 						}
 						$json_new[] = $value;
 					}
				}
			}
		}
		$json_old["aaData"] = $json_new;
		$data = json_encode($json_old, true);
		file_put_contents("/www/libs/data/network.time.json", $data);
		echo $res;		
	}

	function add() {
		global $data_val, $data_post, $json_old, $json_new, $length;
		$string_to_add = $data_val;

		foreach ($json_old["aaData"] as $key => $value) {
			if ($value["ntp_server"] == $string_to_add) {
				$json_new = $json_old["aaData"];
				$res = "false";	
				break;
			} else {
				$json_new[] = $value;
				if ($key == $length) {
					$json_new[] = $data_post;
					$res = "New NTP server has been added.";
				} 
			}
		}
		$json_old["aaData"] = $json_new;
		$data = json_encode($json_old, true);
		file_put_contents("/www/libs/data/network.time.json", $data);
		echo $res;
	}

	switch ($act) {
		case 'deleteRow':
			remove();
			break;
		case 'editRow':
			edit();
			break;
		case 'addRow':
			add();
			break;
		default:
			echo "Something went wrong!";
			break;
	}
?>