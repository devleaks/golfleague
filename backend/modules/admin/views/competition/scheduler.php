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
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Competition */
/* @var $form yii\widgets\ActiveForm */

RecurrenceRuleAsset::register($this);

$model = new Recurrence();
Modal::begin([
    'header' => '<h3>'.Yii::t('golf', 'Repeat Event').'</h3>',
	'id' => 'recurrence-modal',
]);

?>
<div class="recurrence-form">

	<?php
	echo Form::widget([
	    'model' => $model,
	    'form' => $form,
	    'columns' => 12,
	    'attributes' => [	
			'frequency' => [
				'label' => Yii::t('golf', 'Recurrence'),
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
				'label' => Yii::t('golf', 'every'),
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
				'items' => ['bymonthday'=>Yii::t('golf', 'on day')],
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
				'items' => ['byposday'=>Yii::t('golf', 'on the')],
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
				'items' => ['bymonth'=>Yii::t('golf', 'on')],
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
				'items' => ['byposmonth'=>Yii::t('golf', 'on the')],
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
				'label' => Yii::t('golf', 'End Date'),
			],
		],
	]);
	echo '</div>';
	?>

	<div class="form-group">
	    <?= Html::button(Yii::t('golf', 'Cancel'), ['class' => 'btn btn-danger', 'data' => ['dismiss' => 'modal']]) ?>
	    <?= Html::button(Yii::t('golf', 'OK'), ['class' => 'btn btn-success action-ok', 'data' => ['dismiss' => 'modal']]) ?>
	</div>

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

function idx2pos(d) {
	pos = {
		FIRST: 1,
		SECOND: 2,
		THIRD: 3,
		FOURTH: 4,
		LAST: -1
	}
	return pos[d];
}

function freq_change(event) {
	switch($('#recurrence-frequency').val()) {
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
	console.log('yup! '+$('#recurrence-frequency').val());
}

function display_rrule() {
	options = {};
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
			$('#round-recurrence').val('');
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
	$('#round-recurrence').val(rule.toString());
	$('#round-recurrence_text').val(rule.toText());
	console.log('yoh! '+$('#recurrence-frequency').val());
}

/** Setup schedule form based on previously entered rrule */
function init_rrule(rrule) {
	// TEST
	$('#round-recurrence').val('FREQ=WEEKLY;INTERVAL=2;BYDAY=TU;UNTIL=20150929T000000Z');
	//
	rrule_str = $('#round-recurrence').val();
	if(rrule_str != '') {
		console.log('rrule_str='+rrule_str);
		rule = RRule.fromString(rrule_str);
		$('#round-recurrence_text').val(rule.toText());
		//console.log('rule='+JSON.stringify(rule));

		//setup recurrence
		switch(rule.origOptions.freq) {
			case RRule.DAILY: $('#recurrence-frequency').val('DAILY');
				break;
			case RRule.WEEKLY:	$('#recurrence-frequency').val('WEEKLY');
				for(i=0; i<7; i++) {
					loctest = i % 2 ? 'checked' : false;
					sel = 'input[name="Recurrence[byweekday][]"][value="'+(i+1)+'"]';
					$(sel).prop('checked', loctest);
					if(loctest) {
						$(sel).parent().addClass('active');
					} else {
						$(sel).parent().removeClass('active');
					}
					console.log(sel+'='+loctest);
				}
				break;
			case RRule.MONTHLY:	$('#recurrence-frequency').val('MONTHLY');
				if(rule.origOptions.bymonthday > 0) {
					$('#recurrence-bymonthday').val(rule.origOptions.bymonthday - 1);
				} else if(rule.origOptions.byweekday) {
					day = rule.origOptions.byweekday;
					pos = rule.origOptions.byweekday.nth();
					$('#recurrence-bypos4month').val(pos);
					$('#recurrence-weekday4month').val(day);
				}
				break;
			case RRule.YEARLY:	$('#recurrence-frequency').val('YEARLY');
				if(rule.origOptions.bymonthday > 0) {
					$('#recurrence-bymonth').val(rule.origOptions.bymonth);
					$('#recurrence-monthday4year').val(rule.origOptions.bymonthday - 1);
				} else if(rule.origOptions.byweekday) {
					day = rule.origOptions.byweekday;
					pos = rule.origOptions.byweekday.nth();
					$('#recurrence-bypos4year').val(pos);
					$('#recurrence-weekday4year').val(day);
					$('#recurrence-month4year').val(rule.origOptions.bymonth);
				}
				break;
		}
		//setup recurrence: reveal fields accordingly
		freq_change();

		//setup interval
		if(rule.origOptions.interval > 0) {
			$('#recurrence-interval').val(rule.interval);
			console.log('interval:'+rule.interval);
		}
			
		//setup daily details
		//setup weekly details
		//setup monthly details
		//setup yearly details
		
		//setup until
		if(rule.origOptions.count > 0) { //setup until count
			$('#recurrence-until').val('COUNT');
			$('#recurrence-count').val(rule.count);
			console.log('count:'+rule.count);
		} else if(rule.origOptions.until instanceof Date) { //setup until date
			$('#recurrence-until').val('DATE');
			date_end = new Date(rule.origOptions.until);
			console.log('date:'+date_end+'='+date_end.toISOString().slice(0, 10).replace(/-/g,'/'));
			$('#recurrence-date_end').val(date_end.toISOString().slice(0, 10).replace(/-/g,'/')); // yyyy/mm/dd
		}
	}
}

jQuery(function ($) {

init_rrule();

$('#recurrence-modal').on('shown.bs.modal', display_rrule);

$('.field-recurrence-interval, .field-recurrence-repeat, .recurrence_byweekday, .recurrence_bymonth, .recurrence_byyear, .recurrence_until, .field-recurrence-count, .field-recurrence-date_end').toggle(false);

$('#recurrence-frequency').change(freq_change);

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
});

$('.action-ok').click(display_rrule);

$('.remove-recurrence').click(function() {
	$('#round-recurrence').val('');
	$('#round-interval').val('1');
	$('#round-recurrence_text').val('');
	$('#recurrence-frequency').val('NONE').change();
	$('#recurrence-until').val('NEVER').change();
	// should also reset other fields?
});

});
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['JS_SCHEDULER'], yii\web\View::POS_READY);

Modal::end();

