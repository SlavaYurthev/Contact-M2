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

                    // currently we support only literal field names as condition which means "if non-empty"
                    // in the future this might be backwards compatibly extended with expressions like
                    // fieldsname=="value" and so on

                    var dependencyField = $form.find('[name="' + condition + '"]');

                    if (dependencyField.attr('type') == 'checkbox') {
                        var conditionSatisfied = dependencyField.is(':checked');
                    } else {
                        var conditionSatisfied = dependencyField.val() != '';
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
