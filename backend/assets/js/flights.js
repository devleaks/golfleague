
/** S U B R O U T I N E S
 *
 */
var handleString = '<div class="flight-info"><span class="handle glyphicon glyphicon-move"></span>Flight No <span class="flight-number">#</span>—Start Time <span class="flight-time">00:00</span>—Total handicap: <span class="flight-handicap">0</span>.</div>';

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
				if ($(this).children('li').length > 4) {
					//console.log('would have '+$(this).children('li').length+' items, cancelling...');
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
    
    $('.flight').not('.new').each(function() {
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

	startTime = new Date(startDate.getTime() + ((orderNr - 1)*startDelta*60*1000));
	list.parent().find('span.flight-number').text(orderNr);
	list.parent().find('span.flight-time').text(startTime.toTimeString().substr(0, 5));
	list.parent().find('span.flight-handicap').text(Math.round(10*sum)/10);

}


function adjustDate() {
	var startTime = $('#GLtimeStart').val();
	var idx = startTime.indexOf(':');
	var hours = startTime.substr(0, idx);
	var minutes = startTime.substr(idx+1, startTime.length - idx - 1);
	startDate.setHours(hours,minutes,0);
	startDelta = parseInt($('#GLdeltaStart').val());
}


function saveFlights() {
	var flights = new Array();
	$( ".flight" ).not('.new').each(function() {
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
var startDate = new Date();
var startDelta = 0;

/** To change order of flights */
$("#flight-case").sortable({
	handle : '.handle',
	cursor: 'move',
	stop: function (event, ui) {
				cleanUp();
			},
});

/** To recompute flights start time when first start time and time interval changed */
$('#GLtimeStart,#GLdeltaStart').change(function() {
	adjustDate();
	cleanUp();
});

/** to change golfers from flights to flights */
initSortable($(".flight"));
$(".flight").not('.new').prepend(handleString);

/** Initialization on first run */
adjustDate();
cleanUp();

$('#flights-form').submit(function () {
	var flights = new Array();
	$( ".flight" ).not('.new').each(function() {
		flights.push( {
			id: $(this).attr('id'),
			position: $(this).find('span.flight-number').text(),
			start_time: $(this).find('span.flight-time').text(),
			registrations: $(this).sortable( "toArray" )
		} );
	});
	$('#savedflights').val(JSON.stringify(flights));
	//console.log($('#savedflights').val());
});
