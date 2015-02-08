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
		<form>
			<button type="button" class='btn btn-lg btn-primary' onclick="add_movie()" class='add_movie'>Add Movie</button>
		</form>
	</main>



</body></html>