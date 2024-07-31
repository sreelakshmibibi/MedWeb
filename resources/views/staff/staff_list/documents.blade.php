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
                        <form action="#" class="dropzone" id="dropzone-form">
                            <div class="fallback">
                                <input name="file" type="file" multiple />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success float-end">Save</button>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function() {
        Dropzone.autoDiscover = false;

        $('#modal-documents').on('shown.bs.modal', function() {

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
