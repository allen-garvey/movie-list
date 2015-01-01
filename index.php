<?php 
include_once 'functions.php';
?>
<!DOCTYPE html>
<html>
	<head><title><?php echo get_title() ?></title>

		<link rel='stylesheet' type='text/css' href='style.css'>

		<script type="text/javascript">
			
			function add_movie(){
				var movie_table = document.getElementById('movie_table')

				var center = document.getElementById('center')
				center.innerHTML = center.innerHTML + get_add_movie_div()
				
			}

			function add_movie_to_database(){
				var title = document.getElementById('title_field').value
				var pre_rating = document.getElementById('pre_rating_field').value
				var xmlhttp=new XMLHttpRequest();
            	xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    var json = xmlhttp.responseText;
                
                    json = JSON.parse(json)
                    
                    //so that the results_div doesn't read undefined when the back button is pressed after creating schedule
                    if(json.center !== undefined){
                        remove_add_movie_div()
                        document.getElementById('center').innerHTML = json.center
                    }
                    else{
                        document.getElementById('center').innerHTML = 'There must have been some error'
                    }
                    
                }  
            }
            xmlhttp.open("GET","http://localhost/movie_list_2/add_movie.php?title="+title+"&pre_rating="+pre_rating,true);
            xmlhttp.send();

			}

			function get_add_movie_div(){
				var movie_div = "<div class='add_movie_div' id='add_movie_div'>Add Movie<br><br>Title<input type='text' id='title_field'>Pre-Rating<input type='number' min=" + <?php echo "\"'" . min_rating() . "' \""?>  + "max=" + <?php echo "\"'" . max_rating() . "' \""?> +  "step='1' value='60' id='pre_rating_field'><br>Theater Release Date <input type='date' placeholder='mm/dd/yyyy' id='theater_release_input'><br>DVD Release Date <input type='date' placeholder='mm/dd/yyyy' id='dvd_release_input'><br>Genre <input type='text' id='genre_input'><br><button class='select' id= 'cancel_button' onclick='remove_add_movie_div()'>Cancel</button><button class='select' onclick='add_movie_to_database()'>" + 'Add' + "</button></div>"
				return movie_div
			}

			function edit_movie(item_number){
				var movie_table = document.getElementById('movie_table')

				var center = document.getElementById('center')
				center.innerHTML = center.innerHTML + get_edit_movie_div(item_number)
				document.getElementById('cancel_button').disabled = true
				document.getElementById('cancel_button').className = 'disabled_button'
			}

			function get_edit_movie_div(item_number){
				var row = item_number - 1
				var name = document.getElementById('movie_table').rows[row].cells[1].innerHTML
				var movie_div = "<div class='add_movie_div' id='add_movie_div'>hello<br><br><button class='select' id= 'cancel_button' onclick='remove_add_movie_div()'>Cancel</button><button class='select' onclick='remove_add_movie_div()'>" + 'Edit' + "</button>" + name + "</div>"
				return movie_div
			}

			
			function remove_add_movie_div(){
				document.getElementById('center').removeChild(document.getElementById('add_movie_div'))
			}

			// function get_row_num_from_button(button_id){
			// 	var button_id_array = split(button_id, 'edit_button')
			// 	return ((int) button_id_array[1]) - 1
			// }

		</script>


	</head>

<body>
<div id='center'>
<?php
	echo get_index_center_div();

?>

</div>


</body></html>