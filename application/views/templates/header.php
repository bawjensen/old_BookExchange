<?php $boolarray = Array(false => 'false', true => 'true'); ?>

<!DOCTYPE html>
<html>
<meta charset='utf-8'> 
<head>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/scripts.js"></script>
	<link rel="stylesheet" type="text/css" href="/style.css">
	<title><?php echo $title ?></title>
</head>
<body>
<div id="container">

<!-- Colors go from 00CCFF to 00FF99 -->

<div id="header" class="viewblockwrapper">

	<span id="headertext">
		<a href="/"><span id="headerwebsitename">(Unofficial) Wheaton Book Exchange</span></a>
		<span id="headerpagetitle"> - <?php echo $title; ?></span>
	</span>
	<?php if ($loggedin): ?>
		<a class="headerlinks" id="accountlink" href="/account">
		<?php echo $username; ?> - My Account
		</a>
		<a class="headerlinks" id="loginregisterlink" href="/logout">Logout</a>
	<?php else: ?>
		<a class="headerlinks" id="loginregisterlink" href="/login">Login|Register</a>
	<?php endif ?>
</div>

<div id="mainbody">