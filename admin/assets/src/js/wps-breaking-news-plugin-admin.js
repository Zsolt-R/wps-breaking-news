jQuery(document).ready(function ($) {

    const utility_enabler = $('#wps-bn-is-breaking-news');
    const utility_wrapper = $('#wps-bn-expire-utility');
    const timeFiled = $('#wps-bn-expire-date');

    // If we have mark set show the datepicker
    if($(utility_enabler).is(':checked')){
        utility_wrapper.toggleClass('show');
    }

    // Show the datepicker on check
    $(utility_enabler).on('click', function(){
        utility_wrapper.toggleClass('show');
    });

    // time field
    $(timeFiled).datetimepicker({
        minDate: 0,
        timeFormat: "HH:mm",
        dateFormat : 'yy-mm-dd',
        autoClose: true
    });
});