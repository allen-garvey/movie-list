<?php
	include(VIEWS_PATH.'head.php');
	include(VIEWS_PATH.'header.php'); 
?>
<main>
	<table id='movie_table'>
		<thead>
			<tr>
				<?php include(VIEWS_PATH.$page_controller->get_name().'_table_headings.php'); ?>
			</tr>
		</thead>
		<tbody>
			<?= $page_controller->get_table_content_rows(); ?>
		</tbody>
	</table>
</main>
<?php include(VIEWS_PATH.'footer.php'); ?>