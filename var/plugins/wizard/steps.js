function initializeWizard() {
    var form = $(".validation-wizard").not('.wizard');

    if(form.length) {

        form.validate({
            ignore: "input[type=hidden]"
            , errorClass: "text-danger"
            , successClass: "text-success"
            , highlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            }
            , unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            }
            , errorPlacement: function (error, element) {
                error.insertAfter(element)
            }
            , rules: {
                email: {
                    email: !0
                }
            }
        })

        $(".validation-wizard").not('.edit').steps({
            headerTag: "h6"
            , bodyTag: "section"
            , transitionEffect: "fade"
            , titleTemplate: '<span class="step">#index#</span> #title#'
            , labels: {
                finish: "Finalizar"
            }
            , onStepChanging: function (event, currentIndex, newIndex) {
                var events = $._data($(this), 'events');
                if(events && events.stepChanging) {
                    $(this).triggerHandler('stepChanging', [currentIndex, newIndex]);
                }   
                return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
                //form.validate().settings.ignore = ":disabled,:hidden";
                //return form.valid();
            }
            , onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            }
            , onFinished: function (event, currentIndex) {
                         form.submit();
            }
        });

        $(".validation-wizard.edit").steps({
            headerTag: "h6"
            , bodyTag: "section"
            , transitionEffect: "fade"
            , titleTemplate: '<span class="step">#index#</span> #title#'
            , labels: {
                finish: "Finalizar"
            }
            , onStepChanging: function (event, currentIndex, newIndex) {
                var events = $._data($(this), 'events');
                if(events && events.stepChanging) {
                    $(this).triggerHandler('stepChanging', [currentIndex, newIndex]);
                }   
                return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
                //form.validate().settings.ignore = ":disabled,:hidden";
                //return form.valid();
            }
            , onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            }
            , onFinished: function (event, currentIndex) {
                         form.submit();
            }
            , showFinishButtonAlways: true
            , enableAllSteps: true
        });
    }
}