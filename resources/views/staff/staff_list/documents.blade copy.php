{{-- <form id="form-delete" method="POST"> --}}
{{-- @csrf --}}
<!-- document modal-->

<div class="modal fade" id="modal-documents" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Documents</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="box-body">
                        <div class="text-center py-10 bb-1 bb-dashed ">
                            <h4>Upload</h4>
                            <form action="#" class="dropzone" id="dropzone-form">

                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form>

                        </div>
                        <div class="text-center py-10">
                            <h4>Uploaded Documents</h4>
                            {{-- <div class="box-body">
                                <div class="owl-carousel owl-theme b-1">
                                    <div class="box mb-0 h-100 w-100">
                                        <img class="card-img-top img-responsive"
                                            src="{{ asset('images/card/img1.jpg') }}" alt="Card image cap">
                                    </div>
                                    <div class="box mb-0 h-100 w-100">
                                        <img class="card-img-top img-responsive"
                                            src="{{ asset('images/card/img2.jpg') }}" alt="Card image cap">
                                    </div>
                                    <div class="box mb-0 h-100 w-100">
                                        <img class="card-img-top img-responsive"
                                            src="{{ asset('images/card/img3.jpg') }}" alt="Card image cap">
                                    </div>
                                    <div class="box mb-0 h-100 w-100">
                                        <img class="card-img-top img-responsive"
                                            src="{{ asset('images/card/img4.jpg') }}" alt="Card image cap">
                                    </div>
                                    <div class="box mb-0 h-100 w-100">
                                        <img class="card-img-top img-responsive"
                                            src="{{ asset('images/card/img1.jpg') }}" alt="Card image cap">
                                    </div>
                                    <div class="box mb-0 h-100 w-100">
                                        <img class="card-img-top img-responsive"
                                            src="{{ asset('images/card/img2.jpg') }}" alt="Card image cap">
                                    </div>
                                </div>
                            </div> --}}
                            <div class="box bg-transparent no-border">
                                <div class="box-body">
                                    <div class="flexslider fx-element-overlay">
                                        <ul id="imageSlides" class="slides">
                                            <li class = "fx-card-item fx-element-overlay">
                                                <div class = "fx-card-avatar fx-overlay-1">
                                                    <img src = "{{ asset('images/card/img1.jpg') }}" alt="document"
                                                        class="bbsr-0 bber-0">
                                                    <div class = "fx-overlay scrl-up">
                                                        <ul class = "fx-info">
                                                            <li>
                                                                <a class = "btn btn-outline image-popup-vertical-fit "
                                                                    href = "{{ asset('images/card/img1.jpg') }}">
                                                                    <i class="fa-solid fa-magnifying-glass"></i></a>
                                                            </li>
                                                            <li>
                                                                <a class="btn btn-danger-outline delete-image"
                                                                    data-image="1">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <p class="flex-caption1"></p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

{{-- </form> --}}





<script>
    $(document).ready(function() {
        Dropzone.autoDiscover = false;

        $('#modal-documents').on('shown.bs.modal', function() {
            $('.flexslider').resize();
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
            var dropzone = new Dropzone('#dropzone-form', {
                url: '/upload',
                maxFilesize: 2,
                acceptedFiles: 'image/*,application/pdf',
                addRemoveLinks: true,
                dictDefaultMessage: 'Drag files here or click to upload',
                dictRemoveFile: 'Remove',
                init: function() {
                    this.on('thumbnail', function(file) {
                        // Remove default tooltip
                        $(file.previewElement).find('.dz-details').removeAttr(
                            'title');

                        // Add custom tooltip content if needed
                        var tooltipContent = `File Name: ${file.name}`;
                        $(file.previewElement).find('.dz-details').attr(
                            'data-original-title',
                            tooltipContent);

                        // Ensure tooltips are initialized
                        $(file.previewElement).find('.dz-details').tooltip();
                    });
                }
            });
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });
    });
</script>
