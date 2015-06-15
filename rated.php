<?php 
require_once('inc/config.php');
require_once(ROOT_PATH.'controllers/page_controller.php');

$page_controller = new AGED_Rated_Controller();

include(ROOT_PATH.'inc/views/head.php');
?>
	<?php echo $page_controller->get_nav(); ?>
	<main>
		<table id='movie_table'>
			<tr>
				<th></th>
				<th><a href="rated.php?sort=title">Title</a></th><th><a href="rated.php?sort=pre_rating desc">Pre</a></th>
				<th><a href="rated.php?sort=post_rating desc,title">Post</a></th><th><a href="rated.php?sort=rating_difference desc">Diff</a></th>
				<th><a href='rated.php?sort=genre,title'>Genre</a></th>
				<th><a href='rated.php?sort=date_watched desc,title'>Watched</a></th>
			</tr>
			<?php echo $page_controller->get_table_content_rows(); ?>
		</table>
	</main>

</body></html>