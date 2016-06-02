<div class="modal fade" id='add_edit_movie_modal'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><span class="hide-for-edit">Add</span><span class="hide-for-add">Edit</span> Movie</h4>
			</div>
			<div class="modal-body">
				<form novalidate="novalidate" name="add_movie_form" id="movie_form">
					<div class='form-group' ng-class="{'has-error': add_movie_form.movie_title.$invalid && add_movie_form.movie_title.$dirty}">
						<label for="movie_title" class="control-label">Movie Title</label>
	            		<input name="movie_title" type="text" class="form-control" id="movie_title" placeholder='The Terminator' required="required" ng-model="movie.title" />
					</div>
					<div class='form-group'>
						<label for="movie_genre" class="control-label">Movie Genre</label>
	            		<select name="movie_genre" class="form-control" id="movie_genre" ng-model="movie.movie_genre">
	            			<?php 
	            				while($genre = pg_fetch_array($movie_genre_result)){
	            					echo "<option value='$genre[genre_id]'>$genre[title]</option>";
	            				}
	            			 ?>
	            		</select>
					</div>
					<!-- && add_movie_form.theater_release.$dirty -->
					<div class='form-group' ng-class="{'has-error':!isTheaterReleaseValid && add_movie_form.theater_release.$dirty }">
						<label for="movie_theater_release" class="control-label">Theater Release Date</label>
	            		<input name="theater_release" type="text" class="form-control" id="movie_theater_release" placeholder='01/31/1987' ng-model="movie.theater_release" ng-change="validateTheaterDate()" />
					</div>
					<div class='form-group' ng-class="{'has-error':!isDVDReleaseValid && add_movie_form.dvd_release.$dirty }">
						<label for="movie_dvd_release" class="control-label">DVD Release Date</label>
	            		<input name="dvd_release" type="text" class="form-control" id="movie_dvd_release" placeholder='01/31/1992' ng-model="movie.dvd_release" />
					</div>
					<div class='form-group' ng-class="{'has-error': add_movie_form.pre_rating.$invalid && add_movie_form.pre_rating.$dirty}">
						<label for="movie_pre_rating" class="control-label">Pre-rating</label>
	            		<input name="pre_rating" type="number" class="form-control" id="movie_pre_rating" placeholder='1-99' min='1' max='100' ng-model="movie.pre_rating" />
					</div>
					<div class='form-group hide-for-add' ng-show="mode==='edit'" ng-class="{'has-error': add_movie_form.post_rating.$invalid && add_movie_form.post_rating.$dirty}">
						<label for="movie_post_rating" class="control-label">Post-rating</label>
	            		<input name="post_rating" type="number" class="form-control" id="movie_post_rating" placeholder='1-99' min='1' max='100'ng-model="movie.post_rating" />
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<p id="modal_errors">{{errorMsg}}</p>
				<button type="button" class="btn btn-primary" ng-click='modalAction()'>Save</button>
			 </div>
		</div>
	</div>
</div>
