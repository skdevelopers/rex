import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';

document.addEventListener('DOMContentLoaded', function() {
    const dropzoneElement = document.getElementById('my-dropzone');

    if (dropzoneElement) {
        const dropzone = new Dropzone(dropzoneElement, {
            url: '/upload',
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            init: function() {
                this.on("addedfile", function(file) {
                    console.log("File added:", file);
                });
                this.on("thumbnail", function(file, dataUrl) {
                    console.log("Thumbnail generated:", dataUrl);
                });
                this.on("success", function(file, response) {
                    console.log("File uploaded successfully:", response);
                });
                this.on("error", function(file, errorMessage) {
                    console.error("Error uploading file:", errorMessage);
                });
            }
        });

        dropzone.on('sending', function(file, xhr, formData) {
            // Prevent the default form submission
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        });

        // Add event listener for delete icon
        dropzoneElement.addEventListener('click', function(event) {
            if (event.target.matches('.mgc_delete_2_line')) {
                const imageName = event.target.getAttribute('data-image-name');

                // Remove the image preview from Dropzone
                dropzone.files.forEach(function(file) {
                    if (file.name === imageName) {
                        dropzone.removeFile(file);
                    }
                });

                // Send Axios request to delete the image from the server
                axios.delete(`/delete-image/${imageName}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        console.log('Image deleted successfully.');
                    })
                    .catch(error => {
                        console.error('Error deleting image:', error);
                    });
            }
        });
    }
});
