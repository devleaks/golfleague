
/** S U B R O U T I N E S
 *
 */
var handleString = '<div class="panel-heading"><span class="handle glyphicon glyphicon-move"></span>Flight No <span class="flight-number">#</span>—Start Time <span class="flight-time">00:00</span>—Total handicap: <span class="flight-handicap">0</span>.</div>';

function getFlightMaxSize() {
	var maxSize = 4;
	maxSize = $('#GLflightSize').val();
	if(!parseInt(maxSize) > 0) maxSize = 4;

	var isTeam = $('#flight-case').data('isteam');
	if(isTeam > 0) {
		teamsize = $('#flight-case').data('teamsize');
		if(parseInt(teamsize) > 0) {
			maxSize = Math.round(maxSize / parseInt(teamsize), 0);
		}
	}	
	console.log("max size="+maxSize);
	return maxSize;
}

function initSortable(element) {
	
	var maxSize = getFlightMaxSize();

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
				if ($(this).children('li').length > maxSize) {
					//console.log('would have '+$(this).children('li').length+' items, cancelling...');
					$(ui.sender).sortable('cancel');
				} else {
					if($(this).hasClass('new')) {
						//console.log('create new');
		                var clone = $(this).clone();
		                $(this).removeClass('new');$(this).removeClass('panel-info');$(this).addClass('panel-default');
						now = new Date();
						$(this).attr('id', 'flight-'+now.getTime());
						$(this).find('div.handle-shiftbot').remove();
		                clone.find('li').remove();
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
	var maxSize = getFlightMaxSize();
    
    $('.flight').not('.new').not('.bench').each(function() {
		var len = $(this).find('li').length;
		if(len == maxSize) {
			$(this).removeClass("panel-danger");
			$(this).addClass("panel-success");
		} else if(len <= maxSize) {
			$(this).removeClass("panel-danger");
			$(this).removeClass("panel-success");
		} else if(len >= maxSize) {
			$(this).removeClass("panel-success");
			$(this).addClass("panel-danger");
		}
		if(len === 0) {
			$(this).parent().remove();
		} else {
			flightHandicap($(this), cnt++);
		}
	});
    
}


function flightHandicap(list, orderNr) {

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
	console.log('start time:'+startDelta+':'+startTime);
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
	console.log('startDelta:'+$('#GLdeltaStart').val());
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
$('#GLtimeStart,#GLdeltaStart,#GLflightSize').change(function() {
	adjustDate();
	cleanUp();
});

/** to change golfers from flights to flights */
initSortable($(".flight"));
$(".flight").not('.new').not('.bench').prepend(handleString);

/** Initialization on first run - Now called in view
adjustDate();
cleanUp();
*/

$('#flights-form').submit(function () {
	var flights = new Array();
	$( ".flight" ).not('.new').not('.bench').each(function() {
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
