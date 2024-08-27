<form method="post" action="{{ route('report.collection') }}">
    @csrf
    <div class="container-fluid">
        <div class="box no-border mb-2">
            <div class="box-header p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="box-title ">
                        Audit Bill
                    </h4>
                </div>
            </div>
            <div class="box-body px-0 ">

                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="fromdate">From <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="fromdate" name="fromdate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="todate">To <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="todate" name="todate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="branch">Branch</label>
                            <select class="form-control " type="text" id="branch" name="branch">
                                <option value="">All</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="patient_id">Patient Id</label>
                            <input type="text" class="form-control " id="patient_id" name="patient_id">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="bill_no">Bill No.</label>
                            <input type="text" class="form-control " id="bill_no" name="bill_no">
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="branch">Bill Status</label>
                            <select class="form-control " type="text" id="branch" name="branch">
                                <option value="">All</option>
                               
                                <option value="{{ App\Models\PatientTreatmentBilling::BILL_GENERATED }}"> {{ App\Models\PatientTreatmentBilling::BILL_GENERATED_WORDS }}</option>
                                <option value="{{ App\Models\PatientTreatmentBilling::PAYMENT_DONE }}"> {{ App\Models\PatientTreatmentBilling::PAYMENT_DONE_WORDS }}</option>
                                <option value="{{ App\Models\PatientTreatmentBilling::BILL_CANCELLED }}"> {{ App\Models\PatientTreatmentBilling::BILL_CANCELLED_WORDS }}</option>
                                
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer p-3 px-0 text-end " style="border-radius: 0px;">
                <button type="submit" class="btn btn-success" id="searchauditbillbtn">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
    </div>
    <div class="auditbilldiv container" style="display: none;">
        <div class="table-responsive" style=" width: 100%;
    overflow-x: auto;">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                id="collectionTable" width="100%">
                <thead class="bg-primary-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Branch</th>
                        <th>Visit</th>
                        <th>Bill Type</th>
                        <th>Total</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Net</th>
                        <th>Cash</th>
                        <th>Gpay</th>
                        <th>Card</th>
                        <th>Total Paid</th>
                        <th>Balance Given</th>
                        <th>Outstanding</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr class="bt-3 border-primary">
                        {{-- <th colspan="7">Total:</th> --}}
                        <th colspan="6"></th>
                        <th>Total:</th>
                        <th id="total-sum"></th>
                        <th id="total-dis"></th>
                        <th id="total-tax"></th>
                        <th id="total-net"></th>
                        <th id="total-cash"></th>
                        <th id="total-gpay"></th>
                        <th id="total-card"></th>
                        <th id="total-paid"></th>
                        <th id="total-balance"></th>
                        <th id="total-due"></th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</form>
<script type="text/javascript">
    var table;
    jQuery(function($) {
        var clinicBasicDetails = @json($clinicBasicDetails);

        $('#searchauditbillbtn').click(function(e) {
            e.preventDefault(); // Prevent form submission

            $('.auditbilldiv').show();
        });

    });
</script>
