<?php 
require_once('../inc/config.php');
include_once(CONTROLLERS_PATH.'page_controller.php');
require_once(CONTROLLERS_PATH.'localhost_database_pg.php');
$page_controller = new AGED_Index_Controller();
$movie_genre_result = AGED_Page_Controller::get_movie_genre_result();

?>

<?php 
	include(VIEWS_PATH.'head.php');
	include(VIEWS_PATH.'header.php'); 
?>
	<main>
		<table id='movie_table'>
			<thead>
				<tr>
					<?php include(VIEWS_PATH.$page_controller->get_name().'_table_headings.php'); ?>
				</tr>
			</thead>
			<tbody>
				<?= $page_controller->get_table_content_rows(); ?>
			</tbody>
		</table>
		<div class='text-center add_button'>
			<button type="button" class='btn btn-lg btn-primary' id="add-movie-button">Add Movie</button>
		</div>
	</main>
	<?php include(VIEWS_PATH.'footer.php'); ?>

