<script src='<?= SCRIPTS_URL; ?>jquery-2.2.4.min.js'></script>
<script src='<?= SCRIPTS_URL; ?>jquery-ui.min.js'></script>
<script src='<?= SCRIPTS_URL; ?>bootstrap.min.js'></script>
<script>
	var config = {};
	config.MOVIES_API_URL = '<?= API_URL.'movies.php'; ?>';
	config.MOVIES_ACTIVATE_API_URL = '<?= API_URL.'activate.php'; ?>';
	config.RATING_MIN = <?= Movie_List_Constants::$min_rating; ?>;
	config.RATING_MAX = <?= Movie_List_Constants::$max_rating; ?>;
	config.PAGE_TYPE = <?= $page_controller->get_page_type(); ?>;
</script>
<script src='<?= SCRIPTS_URL; ?>app.js'></script>