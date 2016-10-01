<?php
require_once('../inc/config.php');
require_once(CONTROLLERS_PATH.'page_controller.php');

$page_controller = new AGED_Suggestions_Controller();
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
</main>
<?php include(VIEWS_PATH.'footer.php'); ?>