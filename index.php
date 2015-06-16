<?php 
require_once('inc/config.php');
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
			<button type="button" class='btn btn-lg btn-primary' data-backdrop="static" data-toggle="modal" data-target="#add_edit_movie_modal">Add Movie</button>
		</div>
	</main>

<div class="modal fade" id='add_edit_movie_modal'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Add Movie</h4>
			</div>
			<div class="modal-body">
				<form>
					<div class='form-group'>
						<label for="movie_title" class="control-label">Movie Title</label>
	            		<input type="text" class="form-control" id="movie_title" placeholder='The Terminator' />
					</div>
					<div class='form-group'>
						<label for="movie_genre" class="control-label">Movie Genre</label>
	            		<select class="form-control" id="movie_genre">
	            			<?php 
	            				while($genre = pg_fetch_array($movie_genre_result)){
	            					echo "<option value='$genre[genre_id]'>$genre[title]</option>";
	            				}
	            			 ?>
	            		</select>
					</div>
					<div class='form-group'>
						<label for="movie_theater_release" class="control-label">Theater Release Date</label>
	            		<input type="date" class="form-control" id="movie_theater_release" placeholder='01/31/1987' />
					</div>
					<div class='form-group'>
						<label for="movie_dvd_release" class="control-label">DVD Release Date</label>
	            		<input type="date" class="form-control" id="movie_dvd_release" placeholder='01/31/1992' />
					</div>
					<div class='form-group'>
						<label for="movie_pre_rating" class="control-label">Pre-rating</label>
	            		<input type="number" class="form-control" id="movie_pre_rating" placeholder='1-99' min='1' max='100' />
					</div>
					<div class='form-group hide'>
						<label for="movie_post_rating" class="control-label">Post-rating</label>
	            		<input type="number" class="form-control" id="movie_post_rating" placeholder='1-99' min='1' max='100' />
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary">Save</button>
			 </div>
		</div>
	</div>
</div>


<?php include(VIEWS_PATH.'scripts.php'); ?>
</body></html>