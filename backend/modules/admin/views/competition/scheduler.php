<?php

use common\assets\RecurrenceRuleAsset;
use common\models\Recurrence;

use kartik\builder\Form;
use kartik\datetime\DateTimePicker;
use kartik\helpers\Enum;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use kartik\widgets\TouchSpin;

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Competition */
/* @var $form yii\widgets\ActiveForm */

RecurrenceRuleAsset::register($this);

$model = new Recurrence();

?>
<div class="recurrence-form">

	<?php
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => 12,
	    'attributes' => [	
			'frequency' => [
				'label' => Yii::t('igolf', 'Recurrence'),
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Recurrence::getLocalizedConstants('FREQUENCY_'),
	            'columnOptions' => ['colspan' => 4],
			],
			'interval' => [
				'type' => Form::INPUT_WIDGET,
				'widgetClass'=> TouchSpin::className(),
				'options' => ['pluginOptions' => [
					'initval' => 1,
					'min' => 1,
					'max' => 30
				]],
	            'columnOptions' => ['colspan' => 4],
			],
			'repeat' => [
				'label' => Yii::t('igolf', 'every'),
				'type' => Form::INPUT_STATIC,
	            'columnOptions' => ['colspan' => 4],
			],
		],	
	]);
	
	echo '<div class="recurrence_byweekday">';
	echo $form->field($model, 'byweekday')->checkboxButtonGroup(Enum::dayList())->label(false);
	echo '</div>';

	echo '<div class="recurrence_bymonth">';
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => 12,
	    'attributes' => [				
			'option' => [
				'type' => Form::INPUT_RADIO_LIST,
				'items' => ['bymonthday'=>Yii::t('igolf', 'on day')],
	            'columnOptions' => ['colspan' => 2],
				'label' => false,
			],
			'bymonthday' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Enum::dateList(1, 31),
	            'columnOptions' => ['colspan' => 2],
				'label' => false,
			],
		],	
	]);

	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => 12,
	    'attributes' => [	
			'option' => [
				'type' => Form::INPUT_RADIO_LIST,
				'items' => ['byposday'=>Yii::t('igolf', 'on the')],
	            'columnOptions' => ['colspan' => 2],
				'label' => false,
			],
			'bypos4month' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Recurrence::getLocalizedConstants('POSITION_'),
	            'columnOptions' => ['colspan' => 4],
				'label' => false,
			],
			'weekday4month' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Enum::dayList() + Recurrence::getLocalizedConstants('DAY_'),
	            'columnOptions' => ['colspan' => 4],
				'label' => false,
			],
		],	
	]);
	echo '</div>';

	echo '<div class="recurrence_byyear">';
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => 12,
	    'attributes' => [	
			'option' => [
				'type' => Form::INPUT_RADIO_LIST,
				'items' => ['bymonth'=>Yii::t('igolf', 'on')],
	            'columnOptions' => ['colspan' => 2],
				'label' => false,
			],
			'bymonth' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Enum::monthList(),
	            'columnOptions' => ['colspan' => 4],
				'label' => false,
			],
			'monthday4year' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Enum::dateList(1, 31),
	            'columnOptions' => ['colspan' => 2],
				'label' => false,
			],
		],	
	]);

	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => 12,
	    'attributes' => [	
			'option' => [
				'type' => Form::INPUT_RADIO_LIST,
				'items' => ['byposmonth'=>Yii::t('igolf', 'on the')],
	            'columnOptions' => ['colspan' => 2],
				'label' => false,
			],
			'bypos4year' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Recurrence::getLocalizedConstants('POSITION_'),
	            'columnOptions' => ['colspan' => 3],
				'label' => false,
			],
			'weekday4year' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Enum::dayList() + Recurrence::getLocalizedConstants('DAY_'),
	            'columnOptions' => ['colspan' => 3],
				'label' => false,
			],
			'month4year' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Enum::monthList(),
	            'columnOptions' => ['colspan' => 4],
				'label' => false,
			],
		],	
	]);
	echo '</div>';

	echo '<div class="recurrence_until">';
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => 12,
	    'attributes' => [	
			'until' => [
				'type' => Form::INPUT_DROPDOWN_LIST,
				'items' => Recurrence::getLocalizedConstants('UNTIL_'),
	            'columnOptions' => ['colspan' => 4],
			],
			'count' => [
				'type' => Form::INPUT_WIDGET,
				'widgetClass'=> TouchSpin::className(),
				'options' => ['pluginOptions' => [
					'initval' => 1,
					'min' => 1,
					'max' => 20
				]],
	            'columnOptions' => ['colspan' => 4],
			],
			'date_end' => [
				'type' => Form::INPUT_WIDGET,
				'widgetClass'=> DatePicker::className(),
				'options' => ['pluginOptions' => [
	                'format' => 'yyyy/mm/dd',
	                'todayHighlight' => true
	            ]],
	            'columnOptions' => ['colspan' => 4],
				'label' => Yii::t('igolf', 'End Date'),
			],
		],
	]);
	?>

		<div class="form-group">
		    <?= Html::button(Yii::t('igolf', 'Preview'), ['class' => 'btn btn-primary action-preview']) ?>
		</div>

	</div><!-- .recurrence_until -->

</div>
<script type="text/javascript">
<?php
$this->beginBlock('JS_SCHEDULER'); ?>

/** Collects info from schedule form and regenrates rrule */
function day2idx(d) {
	return [RRule.MO,RRule.TU,RRule.WE,RRule.TH,RRule.FR,RRule.SA,RRule.SU][(parseInt(d) + 5) % 7];
}

function pos2idx(d) {
	pos = {
		FIRST: 1,
		SECOND: 2,
		THIRD: 3,
		FOURTH: 4,
		LAST: -1
	}
	return pos[d];
}

function display_rrule() {
	options = {wkst: RRule.MO};
	freq = $('#recurrence-frequency').val();
	intok = false; // needs interval?
	switch(freq) {
		case 'DAILY':
			options['freq'] = RRule.DAILY;
			intok = true;
			break;
		case 'WEEKLY':
			options['freq'] = RRule.WEEKLY;
			intok = true;
			days = $('input[name="Recurrence[byweekday][]"]:checked').map(function(_, el) {
				idx = (parseInt($(el).val()) + 5) % 7;
				console.log($(el).val()+'>'+idx);
				return day2idx($(el).val());
			}).get();
			console.log(days)
			if(days.length > 0)
				options['byweekday'] = days;
			break;
		case 'MONTHLY':
			options['freq'] = RRule.MONTHLY;
			intok = true;
			switch($('input[name="Recurrence[option]"]:checked').val()) {
				case 'bymonthday':
					options['bymonthday'] = parseInt($('#recurrence-bymonthday').val()) + 1;
					break;
				case 'byposday':
//					options['bysetpos'] = pos2idx($('#recurrence-bypos4month').val());
//					options['byweekday'] = day2idx($('#recurrence-weekday4month').val());
					pos = pos2idx($('#recurrence-bypos4month').val());
					day = day2idx($('#recurrence-weekday4month').val());
					console.log(pos+':'+day)
					options['byweekday'] = day.nth(pos);
					break;
			}
			break;
		case 'YEARLY':
			options['freq'] = RRule.YEARLY;
			intok = true;
			switch($('input[name="Recurrence[option]"]:checked').val()) {
				case 'bymonth':
					options['bymonth'] = $('#recurrence-bymonth').val();
					options['bymonthday'] = parseInt($('#recurrence-monthday4year').val()) + 1;
					break;
				case 'byposmonth':
					options['bysetpos'] = pos2idx($('#recurrence-bypos4year').val());
					options['byweekday'] = day2idx($('#recurrence-weekday4year').val());
					options['bymonth'] = $('#recurrence-month4year').val();
					break;
			}
			break;
		default:
			$('#match-recurrence').val('');
			$('#recurrence-text').html('');
			return;
	}

	if(intok && ((val = $('#recurrence-interval').val()) > 1)) {
		options['interval'] = val;
	}

	if(($('#recurrence-until').val() == 'COUNT') && ((val = $('#recurrence-count').val()) > 0)) {
		options['count'] = val;
	} else if(($('#recurrence-until').val() == 'DATE') && ((val = $('#recurrence-date_end').val()) != '')) {
		options['until'] = new Date(val);		
	}
		
	rule = new RRule(options);
	$('#match-recurrence').val(rule.toString());
	$('#recurrence-text').html(rule.toText()+'<br/><pre>'+rule.toString()+'</pre>');
}

/** Setup schedule form based on previously entered rrule */
function setup_rrule(rrule) {
	
}

jQuery(function ($) {

$('.field-recurrence-interval, .field-recurrence-repeat, .recurrence_byweekday, .recurrence_bymonth, .recurrence_byyear, .recurrence_until, .field-recurrence-count, .field-recurrence-date_end').toggle(false);

$('#recurrence-frequency').change(function() {
	switch($(this).val()) {
		case 'DAILY':
			$('.recurrence_byweekday, .recurrence_bymonth, .recurrence_byyear').toggle(false);
			$('.field-recurrence-interval, .field-recurrence-repeat, .recurrence_until').toggle(true);
			$('#recurrence-interval').trigger('change');
			break;
		case 'WEEKLY':
			$('.recurrence_bymonth, .recurrence_byyear').toggle(false);
			$('.field-recurrence-interval, .field-recurrence-repeat, .recurrence_byweekday, .recurrence_until').toggle(true);
			$('#recurrence-interval').trigger('change');
			break;
		case 'WEEKDAYS':
			$('.field-recurrence-interval, .field-recurrence-repeat, .recurrence_byweekday, .recurrence_bymonth, .recurrence_byyear').toggle(false);
			$('.recurrence_until').toggle(true);
			break;
		case 'MONTHLY':
			$('.field-recurrence-repeat, .recurrence_byweekday, .recurrence_byyear').toggle(false);
			$('.field-recurrence-interval, .recurrence_bymonth, .recurrence_until').toggle(true);
			break;
		case 'YEARLY':
			$('.field-recurrence-repeat, .recurrence_byweekday, .recurrence_bymonth').toggle(false);
			$('.field-recurrence-interval, .recurrence_byyear, .recurrence_until').toggle(true);
			break;
		default:
		$('.field-recurrence-interval, .field-recurrence-repeat, .recurrence_byweekday, .recurrence_bymonth, .recurrence_byyear, .recurrence_until').toggle(false);
	}
	display_rrule();
});

$('#recurrence-interval').change(function() { /* adjust plural form of count; @todo: localized words */
	words = {
	//	item: ['singular', 'plural'],
		day: ['day', 'days'],
		week: ['week', 'weeks'],
		month: ['month', 'months'],	
		year: ['year', 'years']
	}
	switch($('#recurrence-frequency').val()) {
		case 'DAILY':
			$('.field-recurrence-repeat > div.form-control-static').html($('#recurrence-interval').val() > 1 ? words.day[1] : words.day[0]);
			break;
		case 'WEEKLY':
			$('.field-recurrence-repeat > div.form-control-static').html($('#recurrence-interval').val() > 1 ? words.week[1] : words.week[0]);
			break;
	}
	display_rrule();
});

$('#recurrence-count').change(function() {
	display_rrule();
});

$('#recurrence-until').change(function() {
	switch($(this).val()) {
		case 'COUNT':
			$('.field-recurrence-count').toggle(true);
			$('.field-recurrence-date_end').toggle(false);
			break;
		case 'DATE':
			$('.field-recurrence-count').toggle(false);
			$('.field-recurrence-date_end').toggle(true);
			break;
		default:
			$('.field-recurrence-count, .field-recurrence-date_end').toggle(false);
	}
	display_rrule();
});

});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_SCHEDULER'], yii\web\View::POS_READY);
