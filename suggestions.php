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
					<th></th><th><a href="suggestions.php?sort=title">Title</a></th><th><a href="suggestions.php?sort=pre_rating desc">Pre</a></th><th><a href="suggestions.php?sort=release_date">Release</a></th><th><a href='suggestions.php?sort=genre'>Genre</a></th>
				</tr>
				<?php echo get_suggestion_table_rows($page_controller->get_sort_variables()); ?>
			</table>
		</main>
	</div>

</body></html>