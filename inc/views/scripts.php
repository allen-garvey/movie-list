<script src='<?= SCRIPTS_URL; ?>jquery-2.1.3.min.js'></script>
<script src='<?= SCRIPTS_URL; ?>bootstrap.min.js'></script>
<script>
	var config = {};
	config.RATING_MIN = <?= Movie_List_Constants::$min_rating; ?>;
	config.RATING_MAX = <?= Movie_List_Constants::$max_rating; ?>;
</script>
<script src='<?= SCRIPTS_URL; ?>app2.js'></script>