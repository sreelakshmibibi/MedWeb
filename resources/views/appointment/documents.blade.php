<form id="form-delete" method="POST">
    @csrf
    <!-- document modal-->

    <div class="modal fade" id="modal-documents" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Uploaded Documents</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="xapp_id" name="xapp_id" value="">
                    <input type="hidden" id="xpatient_id" name="xpatient_id" value="">
                    <input type="hidden" id="xteeth_id" name="xteeth_id" value="">
                    <input type="hidden" id="xtooth_exam_id" name="xtooth_exam_id" value="">
                    {{-- <div class="container-fluid"> --}}
                    <div class="box bg-transparent no-border">
                        <div class="box-body">
                            <div class="flexslider fx-element-overlay">
                                <ul id="imageSlides" class="slides">
                                    <!-- Images will be loaded dynamically here -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{-- </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</form>

<script>
    $(document).ready(function() {
        var url
        // Trigger Flexslider update when modal is shown
        $('#modal-documents').on('shown.bs.modal', function() {
            $('.flexslider').resize(); // Trigger Flexslider to resize or update
            //var dataId = $('#uploadedXrays').attr('data-id');
            var dataId = $('#xtooth_exam_id').val();
            var patientId = $('#xpatient_id').val();
            var toothId = $('#xteeth_id').val();
            // Construct the path to the images
            var xraysrc = 'storage/x-rays/' + patientId + '/' + toothId + '/';

            url = '/images/' + dataId;

            // Clear previous images
            // $('#imageSlides').empty();
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    //$('#imageSlides').empty();
                    var images = response.images;
                    // Iterate over each image and add it to the modal
                    images.forEach(function(image) {
                        var imageSrc = "{{ asset('storage/') }}/" +
                            image.xray;

                        var listItem = `<li class = "fx-card-item fx-element-overlay">
                                            <div class = "fx-card-avatar fx-overlay-1">
                                                <img src = "${imageSrc}" alt="document" class="bbsr-0 bber-0">
                                                <div class = "fx-overlay scrl-up">
                                                    <ul class = "fx-info">
                                                        <li>
                                                            <a class = "btn btn-outline image-popup-vertical-fit " href = "${imageSrc}">
                                                                <i class="fa-solid fa-magnifying-glass"></i></a>
                                                        </li>
                                                        <li>
                                                            <a class="btn btn-danger-outline delete-image" data-image="${image.id}">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <p class="flex-caption${image.id}"></p>
                                       </li>`;
                                         
                        $('#imageSlides').append(listItem);

                    });

                    //$('.flexslider').flexslider('destroy');
                    // Initialize or reload FlexSlider after appending images
                    $('.flexslider').flexslider({
                        animation: "slide",
                    });


                    $('.image-popup-vertical-fit').magnificPopup({
                        type: 'image',
                        closeOnContentClick: true,
                        mainClass: 'mfp-img-mobile mfp-fade',
                        image: {
                            verticalFit: true
                        },
                        callbacks: {
                            open: function() {
                                // Adjust z-index and CSS for Magnific Popup as needed
                                $('.mfp-bg').css('z-index',
                                    '1060'
                                ); // Ensure Magnific Popup overlay is above modal
                                $('.mfp-wrap').css('z-index',
                                    '1070'
                                ); // Ensure Magnific Popup container is above modal
                            }
                        },

                    });

                    // Bind click event for delete button
                    $('.delete-image').click(function(e) {
                        e.preventDefault();
                        var imageToDelete = $(this).data('image');

                        deleteImage(imageToDelete, patientId, toothId);
                    });

                },
                error: function(xhr, status, error) {
                    console.error('Error loading images:', error);
                }
            });

        });
        // Function to delete image via AJAX
        function deleteImage(imageName, patientId, toothId) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/delete-image', // Replace with your route to delete image
                method: 'DELETE', // Use appropriate HTTP method (DELETE, POST, etc.)
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Set CSRF token
                },
                data: {
                    image: imageName, // Send image name or identifier to server
                    patientId: patientId,
                    toothId: toothId
                },
                success: function(response) {
                    // Optionally, remove the deleted image from the UI
                    var successMessage = `<li class="success-message">Image ${imageName} deleted successfully.</li>`;
                    $('.flex-caption'+imageName).append(response.message);

                    // Remove the success message after a delay (optional)
                    setTimeout(function() {
                        $('.flex-caption'+imageName).remove();
                    }, 3000); // Remove after 3 seconds
                    // Reload images or update UI as needed
                    reloadImages();
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting image:', error);
                }
            });
        }

        // Function to reload images after deletion
        function reloadImages() {
            var url = '/images/' + $('#xtooth_exam_id').val();
            
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    var images = response.images;

                    // Clear previous images
                    // $('#imageSlides').empty();

                    if (images.length === 0) {
                        // Display a message indicating no images found
                        $('#imageSlides').append('<p>No images found.</p>');
                    } else {
                        $('#imageSlides').empty();
                            images.forEach(function(image) {
                            var imageSrc = "{{ asset('storage/') }}/" +
                                image.xray;

                            var listItem = `<li class = "fx-card-item fx-element-overlay">
                                            <div class = "fx-card-avatar fx-overlay-1">
                                                <img src = "${imageSrc}" alt="document" class="bbsr-0 bber-0">
                                                <div class = "fx-overlay scrl-up">
                                                    <ul class = "fx-info">
                                                        <li>
                                                            <a class = "btn btn-outline image-popup-vertical-fit " href = "${imageSrc}">
                                                                <i class="fa-solid fa-magnifying-glass"></i></a>
                                                        </li>
                                                        <li>
                                                            <a class="btn btn-danger-outline delete-image" data-image="${image.id}">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        
                                        </li>`;
                            $('#imageSlides').append(listItem);

                        });
                    }
                    // Initialize or reload FlexSlider after appending images
                    $('.flexslider').flexslider({
                        animation: "slide",
                    });

                    $('.image-popup-vertical-fit').magnificPopup({
                        type: 'image',
                        closeOnContentClick: true,
                        mainClass: 'mfp-img-mobile mfp-fade',
                        image: {
                            verticalFit: true
                        },
                        callbacks: {
                            open: function() {
                                // Adjust z-index and CSS for Magnific Popup as needed
                                $('.mfp-bg').css('z-index',
                                    '1060'
                                ); // Ensure Magnific Popup overlay is above modal
                                $('.mfp-wrap').css('z-index',
                                    '1070'
                                ); // Ensure Magnific Popup container is above modal
                            }
                        },

                    });

                    // Bind click event for delete button
                    $('.delete-image').click(function(e) {
                        e.preventDefault();
                        var imageToDelete = $(this).data('image');

                        deleteImage(imageToDelete, patientId, toothId);
                    });

                },
                error: function(xhr, status, error) {
                    console.error('Error reloading images after deletion:', error);
                }
            });
        }




    });
</script>
