/**
 * Custom contact form
 *
 * - show_if conditional fields
 */
define([
    'jquery',
    'mage/translate',
], function ($, $t) {
    'use strict';

    var component = function(config, node) {
        var $form = $(node);

        var refreshVisibilities = function() {
            $('[name]', $form).each(function () {
                var name = $(this).attr('name');

                if (config[name] && config[name]['show_if'] != '') {
                    var condition = config[name]['show_if'];

                    // We support literal field names as condition which means "if non-empty"
                    // Extended with backwards compatibly extended with expressions like
                    // fieldsname:"value" and so on
					
					var conditionarr = condition.match(/[^:]*/g);

                    var dependencyField = $form.find('[name="' + conditionarr[0] + '"]');

                    if (dependencyField.attr('type') == 'checkbox') {
                        var conditionSatisfied = dependencyField.is(':checked');
                    } else {
						if (conditionarr[2]!='') {
                       		var conditionSatisfied = dependencyField.val() == conditionarr[2];
						}
						else {
							var conditionSatisfied = dependencyField.val() != '';
						}
                    }

                    if (conditionSatisfied) {
                        $(this).parents('.field').show();
                    } else {
                        $(this).parents('.field').hide();
                    }
                }
            });
        }

        refreshVisibilities();

        $('[name]', $form).on('change keyup', refreshVisibilities);
    }

    return component;
});
