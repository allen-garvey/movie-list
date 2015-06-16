<!DOCTYPE html>
<html <?php if($page_controller->uses_ng()){echo "ng-app='movieApp'";} ?>>
	<head>
		<title><?php echo $page_controller->get_title() ?></title>
		<link rel='stylesheet' type='text/css' href='styles/bootstrap.min.css'/>
		<link rel='stylesheet' type='text/css' href='styles/bootstrap-theme.min.css'/>
		<link rel='stylesheet' type='text/css' href='styles/style.css'/>
	</head>
<body>