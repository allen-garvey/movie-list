<?php 
include_once('controllers/page_controller.php');

$page_controller = new AGED_Rated_Controller();
?>
<!DOCTYPE html>
<html>
	<head><title><?php echo $page_controller->get_title() ?></title>
		<link rel='stylesheet' type='text/css' href='styles/style.css'>
	</head>

<body>
	<div id='center'>
		<?php echo $page_controller->get_nav(); ?>
		<main>
			<table id='movie_table'>
				<tr>
					<th></th><th><a href="rated.php?sort=title">Title</a></th><th><a href="rated.php?sort=pre_rating desc">Pre</a></th><th><a href="rated.php?sort=post_rating desc">Post</a></th><th><a href="rated.php?sort=rating_difference desc">Diff</a></th><th><a href='rated.php?sort=genre'>Genre</a></th><th><a href='rated.php?sort=date_watched desc'>Watched</a></th>
				</tr>
				<?php echo $page_controller->get_table_content_rows(); ?>
			</table>
		</main>
	</div>

</body></html>