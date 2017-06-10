/**
 * @author Petr Blazicek 2017.
 */
;(function ($) {
  $.fn.acgrid = function (options) {

    var defaults = {
      server: 'server.php',
      editable: false
    };

    var TYPE_TEXT = 1;
    var TYPE_SELECT = 2;

    var el = this;
    var $el = $(el);
    var elId;
    var meta = el.data('opts');

    var opts = $.extend({}, defaults, meta, options);

    var cash = [];

    var aEdit, aRemove, aNew;

    // start
    init();

    /**
     * Check changes done
     * @param trId
     * @returns {boolean}
     */
    function compareCash (trId) {
      var selector = '#' + trId + ' > td.grid-col-';
      var result = false;

      $.each(cash, function (i, o) {
        if ($(selector + o.name).text().trim() != o.value) {
          result = true;
          return;
        }
      });

      return result;
    }

    /**
     * Restore row data
     * @param trId
     */
    function restoreCash (trId) {
      var col = {};

      $.each(cash, function (i, o) {
        $('tr#' + trId + ' > td.grid-col-' + o.name).text(o.value);
      });
      cash = [];
    }

    /**
     * Prepares row ID
     * @param trId
     * @returns {null}
     */
    function getId (trId) {
      var idParts = trId.split('-');
      return (idParts.length) ? idParts[idParts.length - 1] : null;
    }

    /**
     * Initial state
     */
    function init () {
      if (opts.editable) {
        actionButtons(['edit', 'remove', 'new'], true);
        activateEdit();
        activateRemove();
      }
    }

    /**
     * Activate edit buttons
     */
    function activateEdit () {
      if (aEdit) return false;
      aEdit = true;
      $('.b-edit').click(function () {
        var $tr = $(this).closest('tr');
        var id = $tr.attr('id');
        elId = getId(id);
        var selector, focused;
        var cashObj;

        $.each(opts.cols, function (name, type) {
          selector = 'tr#' + id + ' td.grid-col-' + name;

          cashObj = {};
          cashObj.name = name;
          cashObj.value = $(selector).text().trim();
          cash.push(cashObj);

          if (type == TYPE_TEXT) {
            $(selector).attr('contenteditable', true);
            if (name == opts.first) {
              focused = selector;
            }
          }
        });

        actionButtons(['edit', 'remove', 'new']);

        if (focused) {
          $(focused).focus();
        }

        activateSave(id);
        activateCancel(id);
      });
    }

    /**
     * Activate remove buttons
     */
    function activateRemove () {
      if (aRemove) return false;
      aRemove = true;
      $('.b-remove').click(function () {
        alert('Hooovno..');
      });
    }

    /**
     * Activate save button
     * @param trId
     */
    function activateSave (trId) {
      var $saveButton = $('#' + trId + ' button.b-save');
      var selector = '#' + trId + ' > td.grid-col-';

      $saveButton.show().click(function () {
        if (compareCash(trId)) {
          var data = [];
          data.push({name: 'id', value: elId});
          $.each(opts.cols, function (name) {
            data.push({name: name, value: $(selector + name).text().trim()});
          });
          console.log(data);
          $.post(opts.server, data,
            function (payload) {
              if (payload.result === true) {
                alert('Ok');
              }
            }
          );
        }
        actionButtons(['save', 'cancel'], false, trId);
        init();
      });
    }

    /**
     * Activate cancel button
     * @param trId
     */
    function activateCancel (trId) {
      var $cancelButton = $('#' + trId + ' button.b-cancel');
      $cancelButton.show().click(function () {
        restoreCash(trId);
        actionButtons(['save', 'cancel'], false, trId);
        init();
      });
    }

    /**
     * Show / hide action button(s)
     * @param buttons
     * @param action
     * @param trId
     */
    function actionButtons (buttons, action, trId) {
      var parent = trId ? '#' + trId + ' > td > ' : '';
      var button = 'button.b-';
      var selector;

      $.each(buttons, function (i, name) {
        selector = parent + button + name;
        if (action) {
          $(selector).show();
        } else {
          $(selector).hide();
        }
      });
    }
  };
})(jQuery);

$(document).ready(function () {
  $('div.acgrid').acgrid();
});
