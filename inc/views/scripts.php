<script src='<?= SCRIPTS_URL; ?>jquery-2.2.4.min.js'></script>
<script src='<?= SCRIPTS_URL; ?>bootstrap.min.js'></script>
<script>
	var config = {};
	config.BASE_URL = '<?= BASE_URL; ?>';
	config.RATING_MIN = <?= Movie_List_Constants::$min_rating; ?>;
	config.RATING_MAX = <?= Movie_List_Constants::$max_rating; ?>;
</script>
<script src='<?= SCRIPTS_URL; ?>app.js'></script>