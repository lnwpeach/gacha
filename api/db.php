<?php
	session_start();
	// ob_start();
	include(__DIR__."/../config.php");
    
    $pdo  = new PDO('mysql:host='.$host.';dbname='.$db.';charset=utf8', $username, $password);

    if(!isset($_POST['json'])) {
		$answer['message'] = 'No data receive';
		exit(json_encode($answer));
	}

	if (get_magic_quotes_gpc())
	{
		$_POST['json'] = stripslashes($_POST['json']);
	}

	$data = json_decode($_POST['json'], true);

    $answer = [];
    $answer['success'] = 0;
    $answer['message'] = '';
?>