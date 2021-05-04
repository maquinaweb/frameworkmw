window.deferAfterjQueryLoaded.push(
    function() {
        $(document).ready(function() {
            $('#change_password').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.password').show(250);
                }
                else {
                    $('.password').hide(150);
                }
            });
        });
    }
);