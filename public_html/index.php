<?php 
require_once('../inc/config.php');
include_once(CONTROLLERS_PATH.'page_controller.php');
require_once(CONTROLLERS_PATH.'localhost_database_pg.php');
$page_controller = new AGED_Index_Controller();

//get movie genres
$db_manager = new AGED_PG_Database_Manager;
$con = $db_manager->get_database_connection_object();
$movie_genre_result = pg_query($con, 'SELECT genre_id, title FROM m_genre ORDER BY title;') or die(pg_last_error($con)); 
pg_close($con);

?>

<?php 
	include(VIEWS_PATH.'head.php');
	include(VIEWS_PATH.'header.php'); 
?>
	<main>
		<table id='movie_table'>
			<thead>
				<tr>
					<th></th>
					<th><a href="<?= HOME_URL; ?>?sort=title">Title</a></th>
					<th><a href="<?= HOME_URL; ?>?sort=pre_rating desc,title">Pre</a></th>
					<th><a href="<?= HOME_URL; ?>?sort=release,release_date,title">Release</a></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?= $page_controller->get_table_content_rows(); ?>
			</tbody>
		</table>
		<div class='center add_button'>
			<button type="button" class='btn btn-lg btn-primary' id="add-movie-button">Add Movie</button>
		</div>
	</main>
	<?php include(VIEWS_PATH.'footer.php'); ?>
</body></html>