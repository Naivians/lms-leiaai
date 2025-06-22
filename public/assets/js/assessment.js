$(document).ready(function () {
    $('#assessment_time').on('input change', function () {
        let value = parseInt($(this).val(), 10);
        if (value < 1 || isNaN(value)) {
            $(this).val('');
        }
        console.log($(this).val());
    });
});
