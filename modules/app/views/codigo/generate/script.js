window.deferAfterjQueryLoaded.push(function() {
    $('.checked_table_custom.grid_body').on('change', function(event) {
        if ($(this).is(':checked')) {
            $('#grid_body_label_' + $(this).val()).prop('disabled', false);
            $('#grid_body_priorities_' + $(this).val()).prop('disabled', false);
            $('#grid_body_order_' + $(this).val()).prop('disabled', false);
        }
        else {
            $('#grid_body_label_' + $(this).val()).prop('disabled', true);
            $('#grid_body_priorities_' + $(this).val()).prop('disabled', true);
            $('#grid_body_order_' + $(this).val()).prop('disabled', true);
        }
    });
});

window.deferAfterjQueryLoaded.push(function() {
    $('.checked_table_custom.form_body').on('change', function(event) {
        if ($(this).is(':checked')) {
            $('#form_body_label_' + $(this).val()).prop('disabled', false);
            $('#form_body_order_' + $(this).val()).prop('disabled', false);
            $('#form_body_type_' + $(this).val()).prop('disabled', false);
            $('#form_body_mask_' + $(this).val()).prop('disabled', false);
        }
        else {
            $('#form_body_label_' + $(this).val()).prop('disabled', true);
            $('#form_body_order_' + $(this).val()).prop('disabled', true);
            $('#form_body_type_' + $(this).val()).prop('disabled', true);
            $('#form_body_mask_' + $(this).val()).prop('disabled', true);
        }
    });
});

window.deferAfterjQueryLoaded.push(function() {
    $('#has_grid').on('change', function(event) {
        if ($(this).is(':checked')) {
            $("[data-checkbox-parent='has_grid']").each(function() {
                console.log($(this).prop('nodeName'));
                if($(this).prop('nodeName') == 'DIV') {
                    $(this).show();
                }
                if ($(this).prop('nodeName') == 'I') {
                    $(this).removeClass('fa-close').addClass('fa-check');
                    $(this).css('color', 'green');
                }
            });
        }
        else {
            $("[data-checkbox-parent='has_grid']").each(function() {
                if($(this).prop('nodeName') == 'DIV') {
                    $(this).hide();
                }
                if ($(this).prop('nodeName') == 'I') {
                    $(this).removeClass('fa-check').addClass('fa-close');
                    $(this).css('color', 'red');
                }
            });
        }
    });
});

window.deferAfterjQueryLoaded.push(function() {
    $('#has_form').on('change', function(event) {
        if ($(this).is(':checked')) {
            $("[data-checkbox-parent='has_form']").each(function() {
                console.log($(this).prop('nodeName'));
                if($(this).prop('nodeName') == 'DIV') {
                    $(this).show();
                }
                if ($(this).prop('nodeName') == 'I') {
                    $(this).removeClass('fa-close').addClass('fa-check');
                    $(this).css('color', 'green');
                }
            });
        }
        else {
            $("[data-checkbox-parent='has_form']").each(function() {
                if($(this).prop('nodeName') == 'DIV') {
                    $(this).hide();
                }
                if ($(this).prop('nodeName') == 'I') {
                    $(this).removeClass('fa-check').addClass('fa-close');
                    $(this).css('color', 'red');
                }
            });
        }
    });
});

window.deferAfterjQueryLoaded.push(function() {
    $('#has_menu').on('change', function(event) {
        if ($(this).is(':checked')) {
            $("[data-checkbox-parent='has_menu']").each(function() {
                if($(this).prop('nodeName') == 'DIV') {
                    $(this).show();
                }
                if ($(this).prop('nodeName') == 'I') {
                    $(this).removeClass('fa-close').addClass('fa-check');
                    $(this).css('color', 'green');
                }
                if ($(this).prop('nodeName') == 'SELECT') {
                    $(this).val('-1').change();
                }
                $(this).find('input.trequired').attr('required', 'required');
                $(this).find('select.trequired').attr('required', 'required');
                $(this).find('textarea.trequired').attr('required', 'required');
            });
        }
        else {
            $("[data-checkbox-parent='has_menu']").each(function() {
                if($(this).prop('nodeName') == 'DIV') {
                    $(this).hide();
                }
                if ($(this).prop('nodeName') == 'I') {
                    $(this).removeClass('fa-check').addClass('fa-close');
                    $(this).css('color', 'red');
                }
                if ($(this).prop('nodeName') == 'SELECT') {
                    $(this).val('').change();
                }
                $(this).find("input:required").addClass('trequired').removeAttr('required');
                $(this).find('select:required').addClass('trequired').removeAttr('required');
                $(this).find('textarea:required').addClass('trequired').removeAttr('required');
            });
        }
    });
});

window.deferAfterjQueryLoaded.push(function() {
    $('#has_modulo').on('change', function(event) {
        if ($(this).is(':checked')) {
            $("[data-checkbox-parent='has_modulo']").each(function() {
                if($(this).prop('nodeName') == 'DIV') {
                    $(this).show();
                }
                if ($(this).prop('nodeName') == 'I') {
                    $(this).removeClass('fa-close').addClass('fa-check');
                    $(this).css('color', 'green');
                }
                $(this).find('input.trequired').attr('required', 'required');
                $(this).find('select.trequired').attr('required', 'required');
                $(this).find('textarea.trequired').attr('required', 'required');
            });

        }
        else {
            $("[data-checkbox-parent='has_modulo']").each(function() {
                if($(this).prop('nodeName') == 'DIV') {
                    $(this).hide();
                }
                if ($(this).prop('nodeName') == 'I') {
                    $(this).removeClass('fa-check').addClass('fa-close');
                    $(this).css('color', 'red');
                }
                $(this).find("input:required").addClass('trequired').removeAttr('required');
                $(this).find('select:required').addClass('trequired').removeAttr('required');
                $(this).find('textarea:required').addClass('trequired').removeAttr('required');
            });
        }
    });
});