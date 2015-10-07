<?php
	$urls = 'http://times.bcp.org/yb/admin/studentAdmin.php';
	$url = $_GET["id"];
	$pass = $_GET["pass"];
	$datas = array('id' => $url, 'pass' => 1);
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($datas),
		),
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($urls, false, $context);
	var_dump($result);
?>