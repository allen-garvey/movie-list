<?php 
include_once('controllers/index_functions.php');
include_once('controllers/page_controller.php');

$page_controller = new AGED_Page_Controller('Main');
?>
<!DOCTYPE html>
<html>
	<head><title><?php echo $page_controller->get_title() ?></title>

		<link rel='stylesheet' type='text/css' href='styles/style.css'/>
		<!-- <script type="text/javascript" src="scripts/add-edit-movie.js"></script> -->
	</head>

<body>
<div id='center'>
	<?php echo $page_controller->get_nav(); ?>

	<main>
		<table id='movie_table'>
			<tr>
				<th></th><th><a href="index.php?sort=title">Title</a></th><th><a href="index.php?sort=pre_rating desc">Pre</a></th><th><a href="index.php?sort=release,release_date">Release</a></th><th></th>
			</tr>
			<?php echo get_table_contents($page_controller->get_sort_variables()); ?>
		</table>
		<div class="form_control">
			<form>
				<button type="button" onclick="add_movie()" class='add_movie'>Add Movie</button>
			</form>
		</div>
	</main>


</div>


</body></html>