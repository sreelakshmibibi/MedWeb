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
                            <div class="flexslider">
                                {{-- <ul class="slides">

                                    <li>
                                        @php
                                            $xraysrc = 'x-rays/' . $request->patient_id . '/' . $request->tooth_id;
                                        @endphp
                                        <img src="{{ asset('storage/' . $xraysrc) }}" alt="slide" />
                                        <p class="flex-caption">Adventurer Cheesecake Brownie</p>
                                    </li>
                                    <li>
                                        <img src="{{ asset('images/avatar/2.jpg') }}" alt="slide" />
                                        <p class="flex-caption">Adventurer Lemon</p>
                                    </li>
                                    <li>
                                        <img src="{{ asset('images/avatar/3.jpg') }}" alt="slide" />
                                        <p class="flex-caption">Adventurer Donut</p>
                                    </li>
                                    <li>
                                        <img src="{{ asset('images/avatar/4.jpg') }}" alt="slide" />
                                        <p class="flex-caption">Adventurer Cheesecake Brownie</p>
                                    </li>
                                </ul> --}}
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
