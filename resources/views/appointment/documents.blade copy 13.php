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

                    <input type="hidden" id="xapp_id" name="xapp_id" value="">
                    <input type="hidden" id="xpatient_id" name="xpatient_id" value="">
                    <input type="hidden" id="xteeth_id" name="xteeth_id" value="">
                    <input type="hidden" id="xtooth_exam_id" name="xtooth_exam_id" value="">
                    {{-- <div class="container-fluid"> --}}
                    <div class="box bg-transparent no-border">
                        <div class="box-body">
                            <div class="flexslider fx-element-overlay" id="sliderdiv">
                                {{-- <ul id="imageSlides" class="slides"> --}}
                                <!-- Images will be loaded dynamically here -->
                                {{-- </ul> --}}
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

        $('#modal-documents').on('shown.bs.modal', function() {
            loadSlider();
        });

        function loadSlider() {
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
                success: function(response) {
                    console.log('Response from server:', response); // Debug response
                    var images = response.images;

                    if (images.length === 0) {
                        $('.flexslider').html('<p>No images found</p>');
                    } else {

                        var listul = document.createElement('ul');
                        listul.className = 'slides';
                        listul.id = 'imageSlides';

                        images.forEach(function(image) {
                            var imageSrc = "{{ asset('storage/') }}/" + image.xray;
                            var filename = image.xray.split('/').pop();
                            var listItem = document.createElement('li');
                            listItem.className = 'fx-card-item fx-element-overlay listItem';

                            listItem.innerHTML = `<div class="fx-card-avatar fx-overlay-1">
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
                                                <p class="flex-caption${image.id}">${filename}</p>`;

                            listul.appendChild(listItem);
                        });

                        var slideselement = document.querySelector('.flexslider');
                        slideselement.appendChild(listul);

                        initializeSlider();

                        $('.delete-image').click(function(e) {
                            e.preventDefault();
                            var imageToDelete = $(this).data('image');
                            deleteImage(imageToDelete);
                        });
                    }
                },

                error: function(xhr, status, error) {
                    console.error('Error loading images:', error);
                }
            });
        }

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
                    // alert('deleted');


                },
                error: function(xhr, status, error) {
                    console.error('Error deleting image:', error);
                }
            });
        }

        function reloadImages() {
            destroySlider();
            loadSlider();
        }

        $('#modal-documents').on('hide.bs.modal', function() {
            destroySlider();
        });
    });
</script>
