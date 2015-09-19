function initSortable(element) {
	
	element.sortable({
		items: '.match-party',
		connectWith: ".match",
		placeholder: "highlight",
		handle: '.party-handle',
		start: function (event, ui) {
				ui.item.toggleClass("dragged");
			},
		stop: function (event, ui) {
				ui.item.toggleClass("dragged");
			},
		receive: function(event, ui) {
				console.log('receiving...');
				var len = $(this).find('.match-party').length;
				if (len > 3) {
					//console.log('would have '+$(this).children('li').length+' items, cancelling...');
					$(ui.sender).sortable('cancel');
					console.log('cancelled.');
				}
				matchWarning();
			}
	});
	matchWarning();	
	//console.log('inited');
}

function matchWarning() {
	ok = true;
    $(".match").each(function() {
		var len = $(this).find('.match-party').length;
		if(len > 2) {
			$(this).removeClass("match-ok");
			$(this).addClass("match-error");
			ok = false;
		} else {
			$(this).removeClass("match-error");
			$(this).addClass("match-ok");
		}
	});
	$(".match-error").toggle(!ok);
	return ok;
}

// Allows for match re-ordering
$( ".competition" ).sortable().disableSelection();

// Allows for player swaps
initSortable($(".match"));

$('.matchFormSubmit').click(function () {
	if(matchWarning()) {
		var matches = new Array();
		$( ".match" ).each(function() {
			parties = new Array();
			$(this).find('.match-party').each(function () {
				parties.push( $(this).data('registration') );
			});
			//console.log(parties);
			matches.push( {
				id: $(this).data('match'),
				competitors: parties,
			} );
		});
		$('#savedmatches').val(JSON.stringify(matches));
		console.log(JSON.stringify(matches));
		$('#matches-form').submit();
	} else
		console.log('there are errors');
});