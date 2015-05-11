<?php

use yii\helpers\Url;
?>
<script type="text/javascript">
<?php $this->beginBlock('JS_END') ?>
/** S U B R O U T I N E S
 *
 */
var handleString = '<div class="flight-info"><span class="handle glyphicon glyphicon-move"></span>Team No <span class="flight-number">#</span>—Total handicap: <span class="flight-handicap">0</span>.</div>';

function initSortable(element) {
	
	element.sortable({
		items: 'li',
		connectWith: ".flight",
		placeholder: "placeholder",
		start: function (event, ui) {
				ui.item.toggleClass("dragged");
			},
		stop: function (event, ui) {
				ui.item.toggleClass("dragged");
			},
		receive: function(event, ui) {
				if ($(this).children('li').length > 4 && $(this).attr('id') != 'flight-source') {
					$(ui.sender).sortable('cancel');
				} else {
					if($(this).hasClass('new')) {
						//console.log('create new');
		                var clone = $(this).clone();
		                $(this).removeClass('new');
						now = new Date();
						$(this).attr('id', 'flight-'+now.getTime());
		                clone.empty();
						initSortable(clone);
		                $(this).parent().after(clone.wrap('<li></li>').parent());
						$(this).prepend(handleString);
					}
					cleanUp();
				}
			}
	});

}


function cleanUp() {
	
	var cnt = 1;
    
    $('.flight').not('.new,.source').each(function() {
		var len = $(this).find('li').length;
		if(len == 4) {
			$(this).addClass("flight-full");
		} else if(len <= 4) {
			$(this).removeClass("flight-full");
		} else if(len >= 4) {
			$(this).addClass("flight-error");
		}
		if(len === 0) {
			$(this).parent().remove();
		} else {
			flight_handicap($(this), cnt++);
		}
	});
    
}


function flight_handicap(list, orderNr) {

	var sum = 0;

	list.find('li').each(function() {
	    var value = $(this).data('handicap');
	    if(!isNaN(value) && value.length != 0) {
	        sum += parseFloat(value);
	    } else {
	        sum += 36; // default handicap
		}
	});

	list.parent().find('span.flight-number').text(orderNr);
	list.parent().find('span.flight-handicap').text(Math.round(10*sum)/10);

}


function saveFlights() {
	
	var flights = new Array();

	$( ".flight" ).not('.new,.source').each(function() {
		flights.push( {
			id: $(this).attr('id'),
			golfers: $(this).sortable( "toArray" )
		} );
	});

	return JSON.stringify(flights);

}


/** M A I N
 *
 */
/** To change order of flights */
$("#flight-case").sortable({
	handle : '.handle',
	cursor: 'move',
	stop: function (event, ui) {
				cleanUp();
			},
});

/** To change golfers from flights to flights */
initSortable($(".flight"));
$(".flight").not('.new,.source').prepend(handleString);
/** Initialization on first run */
cleanUp();

$('#flights-form').submit(function () {
	var flights = new Array();
	$( ".flight" ).not('.new,.source').each(function() {
		flights.push( {
			id: $(this).attr('id'),
			position: $(this).find('span.flight-number').text(),
			registrations: $(this).sortable( "toArray" )
		} );
	});
	$('#savedflights').val(JSON.stringify(flights));
	console.log($('#savedflights').val());
});
<?php $this->endBlock(); ?>

<?php $this->beginBlock('JS_READY') ?>
$('.golfer-search').keydown(yii.process.golferSearch);
$('a[data-action]').click(yii.process.action);
<?php $this->endBlock(); ?>
</script>
<?php
yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END);
$this->registerJs($this->blocks['JS_READY'], yii\web\View::POS_READY);
