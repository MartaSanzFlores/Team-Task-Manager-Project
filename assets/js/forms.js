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

    const deleteButtons = document.querySelectorAll(".delete");
    const modal = document.getElementById("confirmDeleteModal");
    const confirmButton = document.getElementById("confirmDelete");
    const cancelButton = document.getElementById("cancelDelete");
    const deleteForm = document.getElementById("deleteForm");
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
    
            const formAction = this.closest('form').action;
    
            modal.style.display = 'block';
    
            confirmButton.addEventListener('click', function() {
                deleteForm.action = formAction;
                deleteForm.submit();
            });
    
            cancelButton.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        });
    });
    

});


