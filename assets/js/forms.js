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

    const editProfile = document.querySelector("#editProfile");

    if(editProfile) {
        editProfile.addEventListener('click', function() {
            const fileInput = document.querySelector("#formFile");
            const file = fileInput.files[0];

            if (file) {
                const formData = new FormData();
                formData.append('profileImage', file);

                fetch('api/profile/upload', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert("Image uploaded successfully!");
                        location.reload();
                    } else {
                        alert("Failed to upload image.");
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                alert("Please select an image first.");
            }
        });
    }

});


