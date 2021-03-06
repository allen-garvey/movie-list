<div class="modal fade" id='add_edit_movie_modal'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><span class="hide-for-edit">Add</span><span class="hide-for-add">Edit</span> Movie</h4>
			</div>
			<div class="modal-body">
				<form name="add_movie_form" id="movie_form">
					<div class='form-group'>
						<label for="movie_title" class="control-label">Movie Title</label>
	            		<input name="title" type="text" class="form-control" id="movie_title" placeholder='The Terminator' required="required" />
					</div>
					<div class='form-group'>
						<label for="movie_genre" class="control-label">Movie Genre</label>
	            		<select name="movie_genre" class="form-control" id="movie_genre">
	            			<?php 
	            				while($genre = pg_fetch_array($movie_genre_result)){
	            					echo "<option value='$genre[genre_id]'>$genre[title]</option>";
	            				}
	            			 ?>
	            		</select>
					</div>
					<div class='form-group'>
						<label for="theater_release" class="control-label">Theater Release Date</label>
	            		<input name="theater_release" type="date" class="form-control" id="movie_theater_release" placeholder='01/31/1987' />
					</div>
					<div class='form-group'>
						<label for="dvd_release" class="control-label">DVD Release Date</label>
	            		<input name="dvd_release" type="date" class="form-control" id="movie_dvd_release" placeholder='01/31/1992' />
					</div>
					<div class='form-group'>
						<label for="pre_rating" class="control-label">Pre-rating</label>
	            		<input name="pre_rating" type="number" class="form-control" id="movie_pre_rating" placeholder='<?= Movie_List_Constants::$min_rating . '&ndash;'. Movie_List_Constants::$max_rating; ?>' min='<?= Movie_List_Constants::$min_rating; ?>' max='<?= Movie_List_Constants::$max_rating; ?>' />
					</div>
					<div class='form-group hide-for-add'>
						<label for="post_rating" class="control-label">Post-rating</label>
	            		<input name="post_rating" type="number" class="form-control" id="movie_post_rating" placeholder='<?= Movie_List_Constants::$min_rating . '&ndash;'. Movie_List_Constants::$max_rating; ?>' min='<?= Movie_List_Constants::$min_rating; ?>' max='<?= Movie_List_Constants::$max_rating; ?>' />
					</div>
					<div class="modal-footer">
						<p id="modal_errors"></p>
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				</form>
				<form class='hide-for-add' id='activate_form'>
					<input type="submit" value="Activate" class="btn btn-default" id="activate_active_submit" />
					<input type="submit" value="Deactivate" class="btn btn-danger" id="activate_deactive_submit" />
				</form>
			</div>
		</div>
	</div>
</div>
