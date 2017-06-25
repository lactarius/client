/**
 * Short piece for ACGrid Pager
 *
 * @author Petr Blazicek 2017.
 */
$(function () {
	var selector = 'ul.pagination.bottom-top-no-margin span[contenteditable=true]';
	if ($(selector).length) {

		var bckp = $('#pg-num').attr('href');
		$(document).on('click', selector, function () {

			$(this).html('').on('keydown', function (e) {
				if (e.keyCode == 13) {

					e.preventDefault();
					var page = $(this).html();
					var newlnk = bckp.replace('xxxxx', page);
					$('#pg-num').attr('href', newlnk);
					$(this).blur();
					$('#pg-num').trigger('click').attr('href', bckp);

				}
			});

		});

	}
});
