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
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs " role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#uploadtabcontent" role="tab"
                                id="uploadtabtitle">
                                <span class="hidden-sm-up"><i class="fa-solid fa-upload"></i></span>
                                <span class="hidden-xs-down">Upload</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#viewtabcontent" role="tab"
                                id="viewtabtitle">
                                <span class="hidden-sm-up"><i class="fa-solid fa-eye"></i></span>
                                <span class="hidden-xs-down">View</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="uploadtabcontent" role="tabpanel">
                            <div class="py-10">
                                <section class="content px-2">
                                    <div class="box-body">
                                        <form action="#" class="dropzone" id="dropzone-form">
                                            <div class="fallback">
                                                <input name="file" type="file" multiple />
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="tab-pane" id="viewtabcontent" role="tabpanel">
                            <div class="py-10">
                                <section class="content px-2">
                                    <div class="box-body py-0 px-2">
                                        <div class="inner-user-div33">
                                            <ul class="nav d-block nav-stacked py-1 ">

                                                <li
                                                    class="nav-item d-flex justify-content-between align-items-center bb-dashed border-bottom">
                                                    <div class="min-w-200">
                                                        Photo
                                                    </div>
                                                    <div class="text-muted">{{ $staffProfile->date_of_joining }}</div>
                                                    <a class="min-w-50 btn" title="download"><i
                                                            class="fa fa-download"></i></a>
                                                </li>

                                                <li
                                                    class="nav-item d-flex justify-content-between align-items-center bb-dashed border-bottom">
                                                    <div class="min-w-200">
                                                        Passport
                                                    </div>
                                                    <div class="text-muted">{{ $staffProfile->date_of_joining }}</div>
                                                    <a class="btn min-w-50" title="download"><i
                                                            class="fa fa-download"></i></a>
                                                </li>
                                                <li
                                                    class="nav-item d-flex justify-content-between align-items-center bb-dashed border-bottom">
                                                    <div class="min-w-200">
                                                        Photo
                                                    </div>
                                                    <div class="text-muted">{{ $staffProfile->date_of_joining }}</div>
                                                    <a class="min-w-50 btn" title="download"><i
                                                            class="fa fa-download"></i></a>
                                                </li>
                                                <li
                                                    class="nav-item d-flex justify-content-between align-items-center bb-dashed border-bottom">
                                                    <div class="min-w-200">
                                                        Photo
                                                    </div>
                                                    <div class="text-muted">{{ $staffProfile->date_of_joining }}</div>
                                                    <a class="min-w-50 btn" title="download"><i
                                                            class="fa fa-download"></i></a>
                                                </li>
                                                <li
                                                    class="nav-item d-flex justify-content-between align-items-center bb-dashed border-bottom">
                                                    <div class="min-w-200">
                                                        Photo
                                                    </div>
                                                    <div class="text-muted">{{ $staffProfile->date_of_joining }}</div>
                                                    <a class="min-w-50 btn" title="download"><i
                                                            class="fa fa-download"></i></a>
                                                </li>
                                                <li
                                                    class="nav-item d-flex justify-content-between align-items-center bb-dashed border-bottom">
                                                    <div class="min-w-200">
                                                        Photo
                                                    </div>
                                                    <div class="text-muted">{{ $staffProfile->date_of_joining }}</div>
                                                    <a class="min-w-50 btn" title="download"><i
                                                            class="fa fa-download"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
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
