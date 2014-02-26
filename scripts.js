$(function() {
	$(".offerdelete").click(function() {
		return confirm("Delete offer?");
	});

	$("#offerbook").click(function() {
		var timeToToggle = 500;
		$("#bookoffer").slideToggle(timeToToggle);
	});

	// if ($(".offerpricediv").length > 0) {
	// 	$.each($(".offerpricediv"), function() {
	// 		$(this).css('line-height', parseInt($(this).parent().height()) + 'px');
	// 		// alert($(this).parent().height() + 'px');
	// 	});

	// 	$.each($(".offercoursediv"), function() {
	// 		$(this).css('line-height', parseInt($(this).parent().height()) + 'px');
	// 		// alert($(this).parent().height() + 'px');
	// 	});
	// }

	$(".offerdiv").hover(function() {
		$(this).find('.alterlink').fadeIn(150);
	}, function() {
		$(this).find('.alterlink').fadeOut(150);
	});

	var CONTENT = {};
	$(".offeredit").click(function() {
		var offerdiv = $(this).parent();
		var editables = offerdiv.find('.editable');

		// Toggling class (for visual css styling)
		if (editables.hasClass('editmode')) { // Turning edit off
			editables.each(function() {
				$(this).html(CONTENT[$(this).prop('id')]);
			});

			offerdiv.removeClass('editmode');
			editables.removeClass('editmode');
			editables.prop('contenteditable', 'false');


		}
		else { // Turning edit on
			offerdiv.addClass('editmode');
			editables.addClass('editmode');
			editables.prop('contenteditable', 'true');

			editables.each(function() {
				CONTENT[$(this).prop('id')] = $(this).html();
			});

		}

		if ($('.editmode').length > 0) {
			$("#savealloffers").fadeIn(250);
		}
		else {
			$("#savealloffers").fadeOut(250);
		}
	});

	$("#savealloffers").click(function() {
		var parentDivsActive = $(".offerdiv.editmode");
		var ajaxCall = new XMLHttpRequest();
		var contentsArray;
		var offerID = '0';
		var tempArray;

		parentDivsActive.each(function() {
			contentsArray = {};
			$(this).children('.editable').each(function() {
				contentsArray[$(this).prop('id')] = $(this).html();
				tempArray = $(this).prop('id').split('_');
				offerID = tempArray[tempArray.length-1];
			});

			alert("Sim'd call to /updateoffer/"+offerID+" using "+JSON.stringify(contentsArray));
			// ajaxCall.open("POST", '/updateoffer/'+offerID, false);
		});


		CONTENT = {};
		parentDivsActive.removeClass('editmode');
		parentDivsActive.find('.editmode').removeClass('editmode');
		$(this).fadeOut(250);
	});

	document.querySelector('form').onkeypress = checkEnter;

});

function checkEnter(e) { // Function to test submission of form on "enter" keypress - true if anything but textarea
	if (/selling/i.test(document.URL)) {
		e = e || event;
		var isTxtArea = /textarea/i.test((e.target || e.srcElement).tagName);
		return isTxtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
	}
	else {
		return true;
	}
}