document.addEventListener("DOMContentLoaded", function () {

    const datepicker = document.querySelectorAll(".datepicker");

    if(datepicker.length > 0) {
        
        datepicker.forEach(function(datepicker) {

                flatpickr(datepicker, {
                dateFormat: 'Y-m-d',
                locale: "en",
                allowInput: false,
                static: true,
            });

        })
    }
});


