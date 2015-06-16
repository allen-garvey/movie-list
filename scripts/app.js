(function(){
	var movieApp = angular.module('movieApp', []);

	movieApp.controller('movieModalCtrl', function($scope){
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
		
		$scope.isTheaterReleaseValid = true;
		$scope.isDVDReleaseValid = true;
		$scope.movie = {'dvd_release' : '',
						'theater_release' : '', 
						'movie_genre' : '1'};

		$scope.$watch('movie.theater_release', function(newValue,oldValue) {
			$scope.isTheaterReleaseValid = (newValue === '') || $scope.isValidDate(newValue);
	    });

	    $scope.$watch('movie.dvd_release', function(newValue,oldValue) {
			$scope.isDVDReleaseValid = (newValue === '') || $scope.isValidDate(newValue);
	    });

	    $scope.addMovieFormAction = function(){
	    	console.log('movie added');
	    	$scope.add_movie_form.$error.required.map(function(item){item.$setDirty();});
	    	
	    };

	});
})();