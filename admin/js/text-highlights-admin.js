jQuery(function( $ ) {
	'use strict';

	$(document).on('input', '.text__input', function () {
		if ($(this).val() !== '' && $(this).parent().next().length == 0) {
			$(document).find('.key_phrases').append(
				`<div class="textInput"> <input class="widefat text__input" placeholder="text" type="text" name="key_phrases[]" value=""> </div>`
			);
		}
		if ($(this).val() === '') {
			$(this).parent().remove();
		}
	});

});
