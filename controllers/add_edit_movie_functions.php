<?php
function min_rating(){
	return 0;
}

function max_rating(){
	return 100;
}

function sanitized_rating($unsanitized_rating){
	//don't need to test for numeric because php casts this as 0 if the string doesn't contain numeric data
	$rating = (int) $unsanitized_rating;
	
	if ($rating < min_rating()) {
		$rating = min_rating();
	}
	elseif ($rating > max_rating()) {
		$rating = max_rating();
	}
	return $rating;
}

?>