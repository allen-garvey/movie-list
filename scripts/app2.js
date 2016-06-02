/*
 * 
 */
"use strict";

(function($){
	var app = {};

	//returns null if not valid or a date object if it is valid
	app.isValidDate = function(dateString) {
	    //mm dd yyyy format
	    var matches1 = dateString.match(/^(\d{2})[- \/](\d{2})[- \/](\d{4})$/);
	    if (!matches1) return;
	    var matches = dateString.match(/^(\d{1,2})[- \/](\d{1,2})[- \/](\d{4})$/);
	    if (!matches) return;

	    // parse each piece and see if it makes a valid date object
	    var month = parseInt(matches[1], 10);
	    var day = parseInt(matches[2], 10);
	    var year = parseInt(matches[3], 10);
	    var date = new Date(year, month - 1, day);
	    if (!date || !date.getTime()) return;

	    // make sure we have no funny rollovers that the date object sometimes accepts
	    // month > 12, day > what's allowed for the month
	    if (date.getMonth() + 1 != month ||
	        date.getFullYear() != year ||
	        date.getDate() != day) {
	            return;
	        }
	    return(date);
	};

	app.usDateFromDate = function(dateString){
    	if(!dateString){
    		return '';
    	}
    	var split = dateString.split('-');
    	return split[1] + '/' + split[2] + '/' + split[0];
    };

	app.formatRating = function(ratingString){
    	if(!ratingString){
    		return null;
    	}
    	return parseInt(ratingString);
    };
    app.movieFields = function(){
    	return [
				{selector: '#movie_title', key: 'title'},
				{selector: '#movie_pre_rating', key: 'pre_rating'},
				{selector: '#movie_theater_release', key: 'theater_release'},
				{selector: '#movie_dvd_release', key: 'dvd_release'},
				{selector: '#movie_genre', key: 'movie_genre'}
    			];
    };

	app.resetForAdd = function(){
		$('#add_edit_movie_modal').removeClass('edit').addClass('add');
    	var movie = {'title' : null,
					'dvd_release' : null,
					'theater_release' : null, 
					'movie_genre' : $('#movie_genre').find('option').first().val(),
					'pre_rating' : null
					};
		$.each(this.movieFields(), function(index, el) {
    		$(el.selector).val(movie[el.key]);
    	});
		this.mode = 'add';
    };
    app.resetForEdit = function(movie){
    	$('#add_edit_movie_modal').addClass('edit').removeClass('add');
    	this.mode = 'edit';

    	$.each(this.movieFields(), function(index, el) {
    		$(el.selector).val(movie[el.key]);
    	});
    }

    app.resetForm = function(){
    	//resets for both add and edit
		$('#modal_errors').text('');
		$('#movie_post_rating').val('');
		$('.form-group').removeClass('has-error');
    };

	app.showModal = function(movie){
    	if(movie){
    		this.resetForEdit(movie);
    	}
    	else{
    		this.resetForAdd();
    	}
    	this.resetForm();
    	$('#add_edit_movie_modal').modal('show');
    };

	app.edit = function(movie_id){
		var self = this;
		$.post('http://localhost/movie_list_2/edit_movie.php', {'movie' : JSON.stringify({'id' : movie_id})},function(data, status){
			if(data['error']){
				window.alert(data['error']);
				console.log(data['error']);
			}
			else{
				self.movie = data['movie'];

				self.movie = {
					'title' : data['movie']['title'],
					'movie_id' : movie_id,
					'pre_rating' : self.formatRating(data['movie']['pre_rating']),
					'post_rating' : self.formatRating(data['movie']['post_rating']),
					'theater_release' : self.usDateFromDate(data['movie']['theater_release']),
					'dvd_release' : self.usDateFromDate(data['movie']['dvd_release']),
					'movie_genre' : parseInt(data['movie']['movie_genre'])
				};
				self.showModal(self.movie);
			}
		});
	};

	app.isFormValid = function(movie){
    	if($('#movie_form .form-group').hasClass('has-error')){
    		return false;
    	}
    	if(movie.title === ''){
    		return false;
    	}
    	return true;
    };
    app.serializeForm = function($form){
    	return $form.serializeArray().reduce(function(object, current, index){ object[current.name] = current.value; return object; }, {});
    };
    //turns blank values in object to null for database
    app.normalizeBlankValues = function(obj){
    	for(var key in obj){
    		if(obj[key] === ''){
    			obj[key] = null;
    		}
    	}
    	return obj;
    };
	app.saveMovie = function(){
    	var movie = app.serializeForm($('#movie_form'));
    	movie = app.normalizeBlankValues(movie);
    	console.log(movie);
    	// return;
    	var self = this;
    	if(app.isFormValid(movie)){
			$.post('http://localhost/movie_list_2/add_edit_movie.php', {'movie' : JSON.stringify(movie), 'mode' : self.mode},function(data, status){
				if(data['error']){
					$('#modal_errors').text(data['error']);
				}
				else{
					$('tbody').html(data['table_body']);
					$('#add_edit_movie_modal').modal('hide');
				}
				$scope.formSent = false;
			});
    	}
    	else{
    		$('#modal_errors').text('Please fix your input values before trying to send the form.');
    	}
    };

	//add listeners
	$('#movie_table').on('click', '.edit-button', function(event) {
		event.preventDefault();
		var movie_id = $(this).closest('tr').data('id');
		app.edit(movie_id);
	});

	$('#add-movie-button').on('click', function(event) {
		event.preventDefault();
		app.showModal();
	});
	$('#movie_form').on('submit', function(event) {
		event.preventDefault();
		app.saveMovie();
	});


})(jQuery);
