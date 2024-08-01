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
                    <button type="button" class="btn btn-danger float-end" data-bs-dismiss="modal"
                        id="btn-confirm-status">Change</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

</form>

<script>
    $(document).ready(function() {
        // Trigger Flexslider update when modal is shown
        $('#modal-documents').on('shown.bs.modal', function() {
            $('.flexslider').resize(); // Trigger Flexslider to resize or update
            var patientId = $('#xpatient_id').val();
            var toothId = $('#xteeth_id').val();

            // Construct the path to the images
            var xraysrc = 'storage/x-rays/' + patientId + '/' + toothId + '/';

            var url = '/images/' + patientId + '/' + toothId;

            // Clear previous images
            // $('#imageSlides').empty();
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    var images = response.images;

                    // Iterate over each image and add it to the modal
                    images.forEach(function(image) {
                        var imageSrc = "{{ asset('storage/x-rays') }}/" +
                            patientId + "/" + toothId + "/" + image;

                        // var listItem = '<li><a class="image-link" href="' +
                        //     imageSrc +
                        //     '"><img src="' +
                        //     imageSrc +
                        //     '" alt="slide" /><p class="flex-caption" >' + image +
                        //     '</p></a></li>';
                        // $('#imageSlides').append(listItem);
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
                                                            <a class = "btn btn-outline" >
                                                                <i class = "fa fa-trash"></i></a>
                                                        </li>
                                                        // <li>
                                                        //     <a class = "btn btn-outline" href = "javascript:void(0);">
                                                        //         <i class = "fa-solid fa-gear"></i></a>
                                                        // </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <p class="flex-caption">"${image}"</p>
                                        </li>`;
                        $('#imageSlides').append(listItem);

                    });

                    // Initialize or reload FlexSlider after appending images
                    $('.flexslider').flexslider({
                        animation: "slide",
                    });


                    $('.image-popup-vertical-fit').magnificPopup({
                        type: 'image',
                        closeOnContentClick: true,
                        mainClass: 'mfp-img-mobile',
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

                    $('.zoom-gallery').magnificPopup({
                        delegate: 'a',
                        type: 'image',
                        closeOnContentClick: false,
                        closeBtnInside: false,
                        mainClass: 'mfp-with-zoom mfp-img-mobile',
                        image: {
                            verticalFit: true,
                            titleSrc: function(item) {
                                return item.el.attr('title') +
                                    ' &middot; <a class="image-source-link" href="' +
                                    item.el.attr('data-source') +
                                    '" target="_blank">image source</a>';
                            }
                        },
                        gallery: {
                            enabled: true
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
                        zoom: {
                            enabled: true,
                            duration: 300, // don't foget to change the duration also in CSS
                            opener: function(element) {
                                return element.find('img');
                            }
                        }

                    });


                    // $('.image-popup-vertical-fit').magnificPopup({
                    //     type: 'image',
                    //     gallery: {
                    //         enabled: true
                    //     },
                    //     removalDelay: 300, // Delay in milliseconds before popup is removed
                    //     mainClass: 'mfp-fade', // Class that hides the popup while the animation is running
                    //     callbacks: {
                    //         open: function() {
                    //             // Adjust z-index and CSS for Magnific Popup as needed
                    //             $('.mfp-bg').css('z-index',
                    //                 '1060'
                    //             ); // Ensure Magnific Popup overlay is above modal
                    //             $('.mfp-wrap').css('z-index',
                    //                 '1070'
                    //             ); // Ensure Magnific Popup container is above modal
                    //         }
                    //     },
                    //     zoom: {
                    //         enabled: true, // Enable zoom in and out
                    //         duration: 300, // Duration of the zoom animation
                    //         easing: 'ease-in-out' // Easing function for the zoom animation
                    //     }
                    //     // Other options as needed
                    // });

                },
                error: function(xhr, status, error) {
                    console.error('Error loading images:', error);
                }
            });

        });
    });
</script>
