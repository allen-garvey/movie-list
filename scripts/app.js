(function(){
	var movieApp = angular.module('movieApp', []);

	movieApp.controller('movieModalCtrl', function($scope, $http){
		$scope.isValidDate = function(dateString) {
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
		$scope.formSent = false;
		$scope.errorMsg = '';
		$scope.isTheaterReleaseValid = true;
		$scope.isDVDReleaseValid = true;
		$scope.movie = {};
		$scope.mode = 'add';

		$scope.$watch('movie.theater_release', function(newValue,oldValue) {
			$scope.isTheaterReleaseValid = newValue === null || newValue === undefined || $scope.isValidDate(newValue);
	    });

	    $scope.$watch('movie.dvd_release', function(newValue,oldValue) {
			$scope.isDVDReleaseValid = (newValue === null) || newValue === undefined || $scope.isValidDate(newValue);
	    });

	    $scope.resetForm = function(){
	    	//resets for both add and edit
	    	$scope.formSent = false;
			$scope.errorMsg = '';
			$scope.isTheaterReleaseValid = true;
			$scope.isDVDReleaseValid = true;
			$('.form-group').removeClass('has-error');
	    };
	    $scope.resetForAdd = function(){
	    	$scope.movie = {'title' : null,
						'dvd_release' : null,
						'theater_release' : null, 
						'movie_genre' : $scope.getFirstGenreOption(), 
						'pre_rating' : null
						};
			$scope.mode = 'add';
	    };
	    $scope.resetForEdit = function(){
	    	$scope.mode = 'edit';
	    }

	    $scope.getFirstGenreOption = function(){
	    	//first option element is undefined because of angular ng-model at application start
	    	if($('#movie_genre option').first().val().match(/^[0-9]+$/)){
	    		return $('#movie_genre option').first().val();
	    	}
	    	return $('#movie_genre option').eq(1).val();
	    }

	    $scope.showModal = function(type){
	    	if(type === 'add'){
	    		$scope.resetForAdd();
	    	}
	    	else{
	    		$scope.resetForEdit();
	    	}
	    	$scope.resetForm();
	    	$scope.$apply();
	    	$('#add_edit_movie_modal').modal('show');
	    };

	    $scope.edit = function(movie_id){
	    	$.post('http://localhost/movie_list_2/edit_movie.php', {'movie' : JSON.stringify({'id' : movie_id})},function(data, status){
				data = JSON.parse(data);
				if(data['error']){
					$scope.errorMsg = data['error'];
					$scope.$apply();
					console.log(data['error']);
				}
				else{
					$scope.movie = data['movie'];

					$scope.movie = {
						'title' : data['movie']['title'],
						'movie_id' : movie_id,
						'pre_rating' : $scope.formatRating(data['movie']['pre_rating']),
						'post_rating' : $scope.formatRating(data['movie']['post_rating']),
						'theater_release' : $scope.usDateFromDate(data['movie']['theater_release']),
						'dvd_release' : $scope.usDateFromDate(data['movie']['dvd_release']),
						'movie_genre' : parseInt(data['movie']['movie_genre'])
					};
					$scope.showModal('edit');
				}
			});
	    };
	    $scope.formatRating = function(ratingString){
	    	if(!ratingString){
	    		return null;
	    	}
	    	return parseInt(ratingString);
	    };
	    $scope.usDateFromDate = function(dateString){
	    	if(!dateString){
	    		return '';
	    	}
	    	var split = dateString.split('-');
	    	return split[1] + '/' + split[2] + '/' + split[0];
	    };

	    $scope.isFormValid = function(){
	    	if($('.form-group').hasClass('has-error')){
	    		return false;
	    	}
	    	if(!$scope.movie['title']){
	    		return false;
	    	}
	    	return true;
	    };

	    $scope.addEditMovieFormAction = function(){
	    	//$scope.add_movie_form.$error.required.map(function(item){item.$setDirty();});
	    	var lvFormSent = $scope.formSent; //because formSent will be updated asynchronously
	    	$scope.formSent = true;
	    	if($scope.isFormValid() && !lvFormSent){
				$.post('http://localhost/movie_list_2/add_edit_movie.php', {'movie' : angular.toJson($scope.movie), 'mode' : angular.toJson($scope.mode)},function(data, status){
					data = JSON.parse(data);
					if(data['error']){
						$scope.errorMsg = data['error'];
					}
					else{
						location.reload(true); //because ajax refreshing the table has problems with registering ng-click
						// $('tbody').html(data['table_body']);
						// $('#add_edit_movie_modal').modal('hide');
					}
					$scope.formSent = false;
				});
	    	}
	    	else{
	    		$scope.errorMsg = 'Please fix your input values before trying to send the form.';
	    	}
	    	
	    };
	    $scope.modalAction = function(){
	    	$scope.addEditMovieFormAction();
	    };
	});
})();