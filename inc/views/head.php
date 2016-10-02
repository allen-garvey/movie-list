<!DOCTYPE html>
<html>
	<head>
		<title><?= APP_TITLE.' | '.$page_controller->get_title() ?></title>
		<link rel='stylesheet' type='text/css' href='<?= STYLES_URL; ?>bootstrap.min.css'/>
		<link rel='stylesheet' type='text/css' href='<?= STYLES_URL; ?>bootstrap-theme.min.css'/>
		<link rel='stylesheet' type='text/css' href='<?= STYLES_URL; ?>style.css'/>
		<link rel='stylesheet' type='text/css' href='<?= STYLES_URL; ?>jquery-ui.min.css'/>
	</head>
<body class="<?= $page_controller->get_body_tags(); ?>">