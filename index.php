<!doctype html>

<head>
	<title>Ban lista</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<style type="text/css">
	body {
		background-color: #2c3034;
	}

	.logo{
		padding: 20px;
	}

	.author{
		color: white;
		font-weight: 800;
		font-size: 20px !important;
		padding: 40px;
		padding-left: 50px;
	}
</style>

<body>

<?php

$ftp_ip=""; //
$ftp_user=""; //
$ftp_pass=""; //

$ftp_log_path="cstrike/addons/amxmodx/configs/mdbBans/banlist.txt";
$temporary_file="bans.tmp";


try{
	$conn_id = ftp_connect($ftp_ip);
	$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
	$local = fopen($temporary_file, "w");
	$result = ftp_fget($conn_id, $local, $ftp_log_path, FTP_ASCII);
	ftp_close($conn_id);
	$lines = file($temporary_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$bans = array();
	foreach($lines as $key => $line){	
		$data = explode(' -%- ', $line);
		$nick = strpbrk($data[0], ' ');
		$type = explode('+',$data[0]);	
		switch ($type[0]) {
			case 'cen':
				$punishment = "Cenzura";
				break;

			case 'tban':
				$punishment = "Smart ban";
				break;

			case 'pwn':
				$punishment = "PWN";
				break;
			
			default:
				$punishment = "Ban";
				break;
		}			
		$fields['nick'] = $nick;
		$fields['steamid'] = $data[1];
		$fields['ip'] = $data[2];
		$fields['mid'] = $data[3] ;
		$fields['time'] = $data[4];
		$fields['length'] = $data[5];
		$fields['admin'] = $data[6];
		$fields['reason'] = $data[7];
		$fields['type'] = $punishment;
		$bans[] = $fields;
	}
}catch (Exception $e){
	echo ("Error: " . $e);
}
?>

<div class="row">
	
	<div class="col-md-4">
		<img src="http://hulk.rs/uploads/monthly_2019_01/logo.png.png.fc62aca319bc898cbe93280e01f150db.png">
		<span class="author">Created by <a href="https://www.facebook.com/wib.kalle">kalle</a> for HULK.RS</span>
	</div>	
</div>


<table class="table table-striped table-dark">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nick</th>
      <th scope="col">SteamID</th>
      <th scope="col">mID</th>
	  <th scope="col">Time</th>
	  <th scope="col">Length</th>
	  <th scope="col">Admin</th>
	  <th scope="col">Reason</th>
	  <th scope="col">Type</th>
    </tr>
  </thead>

<?php
$i = 1;
foreach($bans as $ban) {
?>
	<tbody>
    <tr>
      <th scope="row"><?= $i; ?></th>
      <td><?= $ban['nick']; ?></td>
      <td><?= $ban['steamid']; ?></td>
      <td><?= $ban['mid']; ?></td>
	  <td><?= $ban['time']; ?></td>
	  <td><?= $ban['length']; ?></td>
	  <td><?= $ban['admin']; ?></td>
	  <td><?= $ban['reason']; ?></td>
	  <td><?= $ban['type']; ?></td>
    </tr>
<?php
	$i++;
}
?>

</body>

</html>