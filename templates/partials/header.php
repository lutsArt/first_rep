<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
    <title>Monitor</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <meta http-equiv="Cache-Control" content="no-cache">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="-1">
		<meta name="viewport" content="width=1020">
		<meta content="all" name="robots">
		<script src="/scripts/prerender.js"></script>
		<link rel="stylesheet" type="text/css" href="/styles/cab.min.css">

	<?php
		if (isset($model['bootstrap'])) {
			echo '
			<!-- Latest compiled and minified CSS -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
			<!-- Optional theme -->
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
			';
		}
	?>
</head>
 <body lang="ua">
	 <div id="internal-view">
		 <div class="brand__panel" style="height: 75px;">
			 <div class="brand__group">
				 <img src="/images/logo1.png" style="width: 50px; "/>
				 <a href="/" style="
				 	color: #ffffff;
				 	font-size: 20px;
				 	position: absolute;
				 	padding-left: 10px;
				 ">
					 Київський національний економічний університет <br/>
 					 <span style="font-size: 15px">імені Вадима Гетьмана</span>
				 </a>
			 </div>
			 <div class="brand__search">
				 <div class="notification">
					 <div class="notification__entry notification__entry--warn">
						 <span></span>
					 </div>
					 <div class="notification__entry notification__entry--message">
						 <span></span>
					 </div>
				 </div>
			 </div>
			 <div class="brand__user" style="padding: 8px 150px 0 4px; width: 25%; line-height: inherit;">
				 <p style="color: #ffffff;
				 	font-size: 20px;
				 	position: absolute;
				 	padding-left: 10px;">
					 <span style="font-size: 12px;">KNEU</span> <br/>
					 MONITORING-LIST
				 </p>
			 </div>
		 </div>
		 <div class="brand__flex">
