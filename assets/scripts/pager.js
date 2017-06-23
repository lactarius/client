/**
 * Pager jQuery plugin
 *
 * @author Petr Blazicek 2017.
 */
(function ($) {
	$.fn.acpager = function (options) {
		var defaults = {
			server: 'server.php'
		};

		var el = this;
		var $el = $(el);
		var elId = el.attr('id');
		var meta = el.data('opts');

		var opts = $.extend({}, defaults, meta, options);

		function send (cmd, value) {
			$.post(opts.server, {
				cmd: cmd,
				value: value
			}, function (payload) {
				if (payload.result) {
					alert('Rubyy!!');
				}
			});
		}

		$('#' + elId + ' a').click(function () {

			var idParts = $(this).attr('id').split('-');
			var cmd = idParts[idParts.length - 1];
			var value = $(this).find('span').html();
			send(cmd, value);

		});

		$('#' + elId + ' span[contenteditable=true]').focus(function () {
			$(this)
				.html('')
				.on('keydown', function (e) {
					if (e.keyCode == 13) {
						e.preventDefault();
						alert($(this).html());
						$(this).blur();
					}
				});
		});
	};
})(jQuery);

$(function () {
	if ($('ul.pagination.bottom-top-no-margin').length) {
		$('ul.pagination.bottom-top-no-margin').acpager();
	}
});
