<?php
	$act = $_POST['action'];
	$data_post = $_POST["raw"]["data"][0];
	$data_row = $_POST["raw"]["data"][0]["DT_RowId"];
	
	$json_old_raw = file_get_contents("/www/libs/data/dhcp.json");
	$json_old = json_decode($json_old_raw, true);
	$json_new = array();
	$length	 = count($json_old["aaData"]) - 1;

function edit() {
		global $data_row, $data_post, $json_old, $json_new, $length;
		$string_to_edit = $data_row;
		foreach ($json_old["aaData"] as $key => $value) {
			if ($value["DT_RowId"] == $string_to_edit) {
 				$value["static"] = $data_post["static"];
 				$value["route"] = $data_post["route"];
 				$value["ip"] = $data_post["ip"];
 				$value["name"] = $data_post["name"]; 
				$res = "Gateway has been changed.";
 			}
 			$json_new[] = $value;
		}
		$json_old["aaData"] = $json_new;
		$data = json_encode($json_old, true);
		file_put_contents("/www/libs/data/dhcp.json", $data);
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
