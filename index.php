<?php 
include_once('controllers/page_controller.php');

$page_controller = new AGED_Index_Controller();
?>
<!DOCTYPE html>
<html>
	<head><title><?php echo $page_controller->get_title() ?></title>
		<?php include('inc/stylesheets.php') ?>
	</head>

<body>
	<?php echo $page_controller->get_nav(); ?>
	<main>
		<table id='movie_table'>
			<tr>
				<th></th>
				<th><a href="index.php?sort=title">Title</a></th>
				<th><a href="index.php?sort=pre_rating desc,title">Pre</a></th>
				<th><a href="index.php?sort=release,release_date,title">Release</a></th>
				<th></th>
			</tr>
			<?php echo $page_controller->get_table_content_rows(); ?>
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
						<label for="movie_title" class="control-label">Movie Title:</label>
	            		<input type="text" class="form-control" id="movie_title" placeholder='The Terminator' />
					</div>
					<div class='form-group'>
						<label for="movie_genre" class="control-label">Movie Genre:</label>
	            		<input type="text" class="form-control" id="movie_genre" placeholder='Action' />
					</div>
					<div class='form-group'>
						<label for="movie_theater_release" class="control-label">Theater Release Date:</label>
	            		<input type="date" class="form-control" id="movie_theater_release" placeholder='01/31/1987' />
					</div>
					<div class='form-group'>
						<label for="movie_dvd_release" class="control-label">DVD Release Date:</label>
	            		<input type="date" class="form-control" id="movie_dvd_release" placeholder='01/31/1992' />
					</div>
					<div class='form-group'>
						<label for="movie_pre_rating" class="control-label">Pre-rating:</label>
	            		<input type="number" class="form-control" id="movie_pre_rating" placeholder='1-99' min='1' max='100' />
					</div>
					<div class='form-group hide'>
						<label for="movie_post_rating" class="control-label">Post-rating:</label>
	            		<input type="number" class="form-control" id="movie_post_rating" placeholder='1-99' min='1' max='100' />
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save</button>
			 </div>
		</div>
	</div>
</div>


<script type="text/javascript" src='scripts/jquery-2.1.3.min.js'></script>
<script type="text/javascript" src='scripts/bootstrap.min.js'></script>
</body></html>