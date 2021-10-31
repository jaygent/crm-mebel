$(document).ready(function() {
	$('form').submit(function(event) {
		var json;
		event.preventDefault();
		$(".lds-ring").removeClass('noactivelds');
		/* $('form').find ('input, textearea, select').each(function() {
  if($(this).val()===''){$(this).focus();$(this).css('border','2px solid red');exit}else{$(this).css('border','2px solid green')}
});*/
		$.ajax({
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
			success: function(result) {
				$('form').removeClass('noactivelds');
				$(".lds-ring").addClass('noactivelds');
				json = jQuery.parseJSON(result);
				if (json.url) {
					window.location.href = '/' + json.url;
				} else if(json.status) {
					alert(json.status + ' - ' + json.message);
				}
			},
		});
	});
});
