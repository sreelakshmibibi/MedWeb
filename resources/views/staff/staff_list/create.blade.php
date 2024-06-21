<form method="post" action="">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-right" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-user-nurse"> </i> Staff Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">
                    <div class="container-fluid">

                        <ul class="nav nav-tabs customtab justify-content-start" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#doctor"
                                    role="tab">Doctor </a> </li>
                            <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#nurse"
                                    role="tab">Nurse</a> </li>
                            <li class="nav-item"> <a class="nav-link" data-bs-toggle="tab" href="#others"
                                    role="tab">Others</a> </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="doctor" role="tabpanel">
                                <div class="p-15">
                                    @include('staff.staff_list.doctor')
                                </div>
                            </div>
                            <div class="tab-pane" id="nurse" role="tabpanel">
                                <div class="p-15">
                                    @include('staff.staff_list.nurse')
                                </div>
                            </div>
                            <div class="tab-pane" id="others" role="tabpanel">
                                <div class="p-15">
                                    @include('staff.staff_list.others')
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end" id="buttonalert">Save</button>
                </div>

            </div>

        </div>
    </div>
</form>
