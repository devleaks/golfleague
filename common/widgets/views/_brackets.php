<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegistrationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="wrapper">

    <div class="brackets">
    </div>

	<hr/>
	<?php VarDumper::dumpAsString($data, 4, true) ?>

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_BRACKETS') ?>
var rounds = <?= $rounds ?>;
var titles = <?= $titles ?>;
(function($){
	$(".brackets").brackets({
		titles: titles,
		rounds: rounds,
		color_title: 'black',
		border_color: '#0F0',
		color_player: 'black',
		bg_player: 'white',
		color_player_hover: 'black',
		bg_player_hover: '#cfc',
		border_radius_player: '4px',
		border_radius_lines: '10px',
	});
})(jQuery);

var detailurl = "<?= Url::to(['leaderboard'])?>";

$('.matchlink').click(function() {
	round = $(this).data('round');
	console.log(round);
	window.location = detailurl+"?id="+round; 
});

<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_BRACKETS'], yii\web\View::POS_READY);

