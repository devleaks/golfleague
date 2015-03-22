<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii2fullcalendar\yii2fullcalendar as Calendar;
/* @var $this yii\web\View */
/* @var $searchModel app\models\FlightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('igolf', 'Calendar');
$this->params['breadcrumbs'][] = $this->title;
$calendar_name = 'calendar';
?>
<div class="calendar-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<div id="date_picker"></div>

    <?= Calendar::widget([
		'id' => $calendar_name,
		'events' => $events,
		'clientOptions' => [
			// 'height' => 400
		]
    ]) ?>

</div>
<script>
<script type="text/javascript">
<?php
$this->beginBlock('JS_DATEPICKER'); ?>
$("#date_picker").datepicker({
   // While using year and month change I prefer to use inline  date picker  like (  <div id="datepicker"></div>   )
	changeMonth: true,
	changeYear: true,
	onChangeMonthYear: function(year, month, inst) {
		var date = new Date();
		//console.log('y='+year+', m='+month+"="+date);
		$('#<?= $calendar_name ?>').fullCalendar('gotoDate', new Date(year, month-1, date.getDate())); 
	}
});
<?php $this->endBlock(); ?>
</script>
<style>
.ui-datepicker-calendar { display: none; }
</style>
<?php
$this->registerJs($this->blocks['JS_DATEPICKER'], yii\web\View::POS_END);
