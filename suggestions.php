<?php 
include_once ('controllers/suggestions_functions.php');
include_once('controllers/page_controller.php');
include_once('controllers/localhost_database_pg.php');
include_once('models/constants.php');

$page_controller = new AGED_Page_Controller('Suggestions');
?>
<!DOCTYPE html>
<html>
	<head><title><?php echo $page_controller->get_title() ?></title>
		<link rel='stylesheet' type='text/css' href='styles/style.css'>
	</head>

<body>
	<div id='center'>
		<?php echo $page_controller->get_nav(); ?>
		<main>
			<table id='movie_table'>
				<tr>
					<th></th><th>Title</th><th>Pre</th><th>Release</th><th>Genre</th>
				</tr>
				<?php echo get_suggestion_table_rows(); ?>
			</table>
		</main>
	</div>

</body></html>