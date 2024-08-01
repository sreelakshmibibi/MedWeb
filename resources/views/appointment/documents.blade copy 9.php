<form id="form-document" method="POST">
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

                    <input type="text" id="xapp_id" name="xapp_id" value="">
                    <input type="text" id="xpatient_id" name="xpatient_id" value="">
                    <input type="text" id="xteeth_id" name="xteeth_id" value="">
                    <input type="text" id="xtooth_exam_id" name="xtooth_exam_id" value="">
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
        var url;

        function loadSlider() {
            // $('#imageSlides').empty();
            var dataId = $('#xtooth_exam_id').val();
            var patientId = $('#xpatient_id').val();
            var toothId = $('#xteeth_id').val();
            url = '/images/' + dataId;

            console.log('Loading images from URL:', url); // Debug URL

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                cache: false,
                // success: function(response) {
                //     console.log('Response from server:', response); // Debug response
                //     var images = response.images;
                //     // $('#imageSlides').empty(); // Clear previous images
                //     // $('.slides').empty();
                //     var listul = document.createElement('ul');
                //     listul.className = 'slides';
                //     listul.id = 'imageSlides';
                //     images.forEach(function(image) {
                //         var imageSrc = "{{ asset('storage/') }}/" + image.xray;
                //         var listItem = `<li class="fx-card-item fx-element-overlay">
                //             <div class="fx-card-avatar fx-overlay-1">
                //                 <img src="${imageSrc}" alt="document" class="bbsr-0 bber-0">
                //                 <div class="fx-overlay scrl-up">
                //                     <ul class="fx-info">
                //                         <li>
                //                             <a class="btn btn-outline image-popup-vertical-fit" href="${imageSrc}">
                //                                 <i class="fa-solid fa-magnifying-glass"></i>
                //                             </a>
                //                         </li>
                //                         <li>
                //                             <a class="btn btn-danger-outline delete-image" data-image="${image.id}">
                //                                 <i class="fa fa-trash"></i>
                //                             </a>
                //                         </li>
                //                     </ul>
                //                 </div>
                //             </div>
                //             <p class="flex-caption${image.id}"></p>
                //        </li>`;
                //         listul.appendChild(listItem);
                //         // $('.slides').append(listItem);
                //         // $('#imageSlides').appendChild(listItem);
                //         alert('appended')
                //     });
                //     var flexsliderelement = document.querySelector('flexslider');
                //     flexsliderelement.appendChild(listul);
                //     // Initialize Flexslider and Magnific Popup after images are appended
                //     // $('.flexslider').flexslider('destroy');
                //     $('.flexslider').flexslider({
                //         animation: "slide",
                //         controlNav: false,
                //         directionNav: true
                //     });

                //     $('.image-popup-vertical-fit').magnificPopup({
                //         type: 'image',
                //         closeOnContentClick: true,
                //         mainClass: 'mfp-img-mobile mfp-fade',
                //         image: {
                //             verticalFit: true
                //         },
                //         callbacks: {
                //             open: function() {
                //                 $('.mfp-bg').css('z-index', '1060');
                //                 $('.mfp-wrap').css('z-index', '1070');
                //             }
                //         }
                //     });

                //     $('.delete-image').click(function(e) {
                //         e.preventDefault();
                //         var imageToDelete = $(this).data('image');
                //         deleteImage(imageToDelete);
                //     });
                // },
                success: function(response) {
                    // $('.flexslider').empty();
                    // $('#imageSlides').remove();

                    console.log('Response from server:', response); // Debug response
                    var images = response.images;

                    // Clear previous images if needed
                    // $('#imageSlides').empty(); // Uncomment if you need to clear existing content

                    // Create the list element
                    // var listul = document.createElement('ul');
                    // listul.className = 'slides';
                    // listul.id = 'imageSlides';

                    images.forEach(function(image) {
                        var imageSrc = "{{ asset('storage/') }}/" + image.xray;

                        // Create list item element
                        var listItem = document.createElement('li');
                        listItem.className = 'fx-card-item fx-element-overlay listItem';

                        // Create inner HTML for the list item
                        listItem.innerHTML = `
            <div class="fx-card-avatar fx-overlay-1">
                <img src="${imageSrc}" alt="document" class="bbsr-0 bber-0">
                <div class="fx-overlay scrl-up">
                    <ul class="fx-info">
                        <li>
                            <a class="btn btn-outline image-popup-vertical-fit" href="${imageSrc}">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </a>
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
        `;

                        // Append list item to the list
                        // listul.appendChild(listItem);
                        var slideselement = document.querySelector('.slides');

                        slideselement.appendChild(listItem);
                    });
                    $('.flexslider').flexslider({
                        animation: "slide",
                        controlNav: false,
                        directionNav: true
                    });
                    // Ensure `flexsliderelement` is correctly selected flex-viewport
                    // var flexsliderelement = document.querySelector('.flexslider');
                    // if (flexsliderelement) {
                    //     flexsliderelement.appendChild(listul);
                    // $('.flexslider').flexslider('destroy');
                    // Initialize Flexslider and Magnific Popup after images are appended
                    // $('.flexslider').flexslider({
                    //     animation: "slide",
                    //     controlNav: false,
                    //     directionNav: true
                    // });

                    $('.image-popup-vertical-fit').magnificPopup({
                        type: 'image',
                        closeOnContentClick: true,
                        mainClass: 'mfp-img-mobile mfp-fade',
                        image: {
                            verticalFit: true
                        },
                        callbacks: {
                            open: function() {
                                $('.mfp-bg').css('z-index', '1060');
                                $('.mfp-wrap').css('z-index', '1070');
                            }
                        }
                    });

                    $('.delete-image').click(function(e) {
                        e.preventDefault();
                        var imageToDelete = $(this).data('image');
                        deleteImage(imageToDelete);
                    });

                },

                error: function(xhr, status, error) {
                    console.error('Error loading images:', error);
                }
            });
        }

        $('#modal-documents').on('shown.bs.modal', function() {
            // $('#imageSlides').children().remove();
            $('.flexslider').flexslider('destroy');
            loadSlider();
        });

        $('#modal-documents').on('hide.bs.modal', function() {
            // Reset text inputs
            // $('#xapp_id').val('');
            // $('#xpatient_id').val('');
            // $('#xteeth_id').val('');
            // $('#xtooth_exam_id').val('');

            // Clear image slides
            // $('#imageSlides').empty();
            // $('.flexslider').empty();
            $('.listItem').remove();
            // Clean up Flexslider and Magnific Popup
            $('.flexslider').flexslider('destroy'); // Destroy existing instance
            $.magnificPopup.close(); // Close Magnific Popup
        });

        function deleteImage(imageId, patientId, toothId) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '/delete-image',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    image: imageId,
                    patientId: patientId,
                    toothId: toothId
                },
                success: function(response) {
                    reloadImages(); // Reload images after deletion
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting image:', error);
                }
            });
        }

        function reloadImages() {
            loadSlider(); // Re-fetch and load images
        }
    });
</script>
