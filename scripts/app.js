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
		$scope.movie = {'title' : null,
						'dvd_release' : null,
						'theater_release' : null, 
						'movie_genre' : $('#movie_genre option').first().val(),
						'pre_rating' : null
						};

		$scope.$watch('movie.theater_release', function(newValue,oldValue) {
			$scope.isTheaterReleaseValid = (newValue === null) || $scope.isValidDate(newValue);
	    });

	    $scope.$watch('movie.dvd_release', function(newValue,oldValue) {
			$scope.isDVDReleaseValid = (newValue === null) || $scope.isValidDate(newValue);
	    });

	    $scope.isFormValid = function(){
	    	if($('.form-group').hasClass('has-error')){
	    		return false;
	    	}
	    	if(!$scope.movie['title']){
	    		return false;
	    	}
	    	return true;
	    };

	    $scope.addMovieFormAction = function(){
	    	//$scope.add_movie_form.$error.required.map(function(item){item.$setDirty();});
	    	var lvFormSent = $scope.formSent; //because formSent will be updated asynchronously
	    	$scope.formSent = true;
	    	if($scope.isFormValid() && !lvFormSent{
				$.post('http://localhost/movie_list_2/add_edit_movie.php', {'movie' : angular.toJson($scope.movie)},function(data, status){
					data = JSON.parse(data);
					if(data['error']){
						$scope.errorMsg = data['error'];
					}
					else{
						$('tbody').html(data['table_body']);
						$('#add_edit_movie_modal').modal('hide');
					}
					$scope.formSent = false;
				});
	    	}
	    	else{
	    		$scope.errorMsg = 'Please fix your input values before trying to send the form.';
	    	}
	    	
	    };
	});
})();