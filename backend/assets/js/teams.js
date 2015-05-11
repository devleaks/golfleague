
/** S U B R O U T I N E S
 *
 */
var handleString = '<div class="team-info"><span class="handle glyphicon glyphicon-move"></span>Team No <span class="team-number">#</span>—Total handicap: <span class="team-handicap">0</span>.</div>';
var maxSizeDefault = 2;

function getFlightMaxSize() {
	var maxSize = $('#GLflightSize').val();
	if(!parseInt(maxSize) > 0) maxSize = maxSizeDefault;
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
				if ($(this).children('li').length > maxSize && !$(this).hasClass('bench')) {
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
	var maxSize = getFlightMaxSize();
    
    $('.flight').not('.new').not('.bench').each(function() {
		var len = $(this).find('li').length;
		if(len == maxSize) {
			$(this).removeClass("flight-error");
			$(this).addClass("flight-full");
		} else if(len <= maxSize) {
			$(this).removeClass("flight-error");
			$(this).removeClass("flight-full");
		} else if(len >= maxSize) {
			$(this).removeClass("flight-full");
			$(this).addClass("flight-error");
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

	list.parent().find('span.team-number').text(orderNr);
	list.parent().find('span.team-handicap').text(Math.round(10*sum)/10);

}


function saveFlights() {
	var flights = new Array();
	$( ".flight" ).not('.new').not('.bench').each(function() {
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

/** To recompute flights start time when first start time and time interval changed */
$('#GLflightSize').change(function() {
	cleanUp();
});

/** to change golfers from flights to flights */
initSortable($(".flight"));

$(".flight").not('.new').not('.bench').prepend(handleString);

$(".flight.bench").prepend('<div class="bench-info">Bench - Unassigned players.</div>');

$('#teams-form').submit(function () {
	var flights = new Array();
	$( ".flight" ).not('.new').not('.bench').each(function() {
		flights.push( {
			id: $(this).attr('id'),
			handicap: $(this).find('span.team-handicap').text(),
			registrations: $(this).sortable( "toArray" )
		} );
	});
	$('#savedteams').val(JSON.stringify(flights));
	//console.log($('#savedflights').val());
});
