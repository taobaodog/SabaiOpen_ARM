<?php
	$data_rows = $_POST["row"];
	$data_table_id = $_POST["table"];
	$data = json_encode($data_rows, true);

	switch ($data_table_id) {
		case 'NTPTable':
			file_put_contents("/www/libs/data/network.time.json", $data);
			break;
		case 'gateTable':
			file_put_contents("/www/libs/data/dhcp.json", $data);
			break;
		case 'portTable':
			file_put_contents("/www/libs/data/port_forwarding.json", $data);
			break;
	}
?>