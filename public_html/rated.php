<?php 
require_once('../inc/config.php');
require_once(CONTROLLERS_PATH.'page_controller.php');

$page_controller = new AGED_Rated_Controller();
$movie_genre_result = AGED_Page_Controller::get_movie_genre_result();
?>
<?php 
	include(VIEWS_PATH.'head.php');
	include(VIEWS_PATH.'header.php'); 
?>
<main>
	<table id='movie_table'>
		<thead>
			<tr>
				<th></th>
				<th><a href="<?= RATED_URL; ?>?sort=title">Title</a></th><th><a href="<?= RATED_URL; ?>?sort=pre_rating desc">Pre</a></th>
				<th><a href="<?= RATED_URL; ?>?sort=post_rating desc,title">Post</a></th><th><a href="<?= RATED_URL; ?>?sort=rating_difference desc">Diff</a></th>
				<th><a href='<?= RATED_URL; ?>?sort=genre,title'>Genre</a></th>
				<th><a href='<?= RATED_URL; ?>?sort=date_watched desc,title'>Watched</a></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?= $page_controller->get_table_content_rows(); ?>
		</tbody>
	</table>
</main>
<?php include(VIEWS_PATH.'footer.php'); ?>