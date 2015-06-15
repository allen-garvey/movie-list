<?php
require_once('inc/config.php');
require_once(ROOT_PATH.'controllers/page_controller.php');

$page_controller = new AGED_Suggestions_Controller();

include(ROOT_PATH.'inc/views/head.php');
?>
	<?php echo $page_controller->get_nav(); ?>
	<main>
		<table id='movie_table'>
			<tr>
				<th></th>
				<th><a href="suggestions.php?sort=title,pre_rating desc">Title</a></th>
				<th><a href="suggestions.php?sort=pre_rating desc,title">Pre</a></th>
				<th><a href="suggestions.php?sort=release_date,pre_rating desc,title">Release</a></th>
				<th><a href='suggestions.php?sort=genre,pre_rating desc,title'>Genre</a></th>
			</tr>
			<?php echo $page_controller->get_table_content_rows(); ?>
		</table>
	</main>
</body></html>