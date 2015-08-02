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
	
	console.log('inited');
}

function matchWarning() {
    $(".match").each(function() {
		var len = $(this).find('.match-party').length;
		if(len == 2) {
			$(this).removeClass("match-error");
			$(this).addClass("match-ok");
		} else {
			$(this).removeClass("match-ok");
			$(this).addClass("match-error");
		}
	});
}

// Allows for match re-ordering
$( ".competition" ).sortable().disableSelection();

// Allows for player swaps
initSortable($(".match"));
