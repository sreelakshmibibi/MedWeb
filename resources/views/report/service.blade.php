<form method="post" action="{{ route('report.service') }}">
    @csrf
    <div class="container-fluid">
        <div class="box no-border mb-2">
            <div class="box-header p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="box-title ">
                        Service Report
                    </h4>

                    <button type='button'
                        class='waves-effect waves-light btn btn-circle btn-secondary btn-treatment-pdf-generate btn-xs mt-0 mb-2'
                        title='Download & Print Treatment Summary'><i class='fa fa-download'></i></button>
                </div>
            </div>
            <div class="box-body px-0 ">
                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="serviceFromDate">From <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="serviceFromDate" name="serviceFromDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="serviceToDate">To <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="serviceToDate" name="serviceToDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="serviceBranch">Branch</label>
                            <select class="form-control " type="text" id="serviceBranch" name="serviceBranch">
                                <option value="">All</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="serviceCreatedBy">Done By</label>
                            <select class="form-control " type="text" id="serviceCreatedBy" name="serviceCreatedBy">
                                <option value="">All</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"> {{ str_replace('<br>', ' ', $doctor->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="serviceTreatment">Treatment</label>
                            <select class="form-control " type="text" id="serviceTreatment" name="serviceTreatment">
                                <option value="">All</option>
                                @foreach ($treatments as $treatment)
                                    <option value="{{ $treatment->id }}"> {{ $treatment->treat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="serviceTreatmentPlan">Treatment Plan</label>
                            <select class="form-control " type="text" id="serviceTreatmentPlan"
                                name="serviceTreatmentPlan">
                                <option value="">All</option>
                                @foreach ($treatmentPlans as $treatmentPlan)
                                    <option value="{{ $treatmentPlan->id }}"> {{ $treatmentPlan->plan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="serviceComboOffer">Combo Offer</label>
                            <select class="form-control " type="text" id="serviceComboOffer"
                                name="serviceComboOffer">
                                <option value="">All</option>
                                @foreach ($comboOffers as $comboOffer)
                                    <option value="{{ $comboOffer->id }}">
                                        {{ $comboOffer->treatments->pluck('treat_name')->implode(', ') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="serviceGender">Gender</label>
                            <select class="form-control " type="text" id="serviceGender" name="serviceGender">
                                <option value="">All</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="O">Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="age">Age</label>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="From" name="serviceAgeFrom"
                                    id="serviceAgeFrom" aria-label="From" min="0" max="99">
                                <span class="input-group-text">-</span>
                                <input type="number" class="form-control" placeholder="To" name="serviceAgeTo"
                                    id="serviceAgeTo" aria-label="To" min="0" max="99">
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="box-footer p-3 px-0 text-end " style="border-radius: 0px;">
                <button type="submit" class="btn btn-success" id="searchServiceBtn">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
    </div>
    <div class="servicediv container" style="display: none">
        <div class="table-responsive" style=" width: 100%; overflow-x: auto;">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                id="serviceTable" width="100%">
                <thead class="bg-primary-light">
                    <tr>
                        <th>No</th>
                        <th>Branch</th>
                        <th>Date</th>
                        <th>Phone Number</th>
                        <th>Service Name</th>
                        <th>Treatment Plan</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr class="bt-3 border-primary">
                        <th colspan="5"></th>
                        <th>Total:</th>
                        <th id="total-quantity"></th>
                        <th id="total-total"></th>
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
        $('#searchServiceBtn').click(function(e) {
            e.preventDefault(); // Prevent form submission

            if ($.fn.DataTable.isDataTable("#serviceTable")) {
                // Destroy existing DataTable instance
                // table.destroy();
                $('#serviceTable').DataTable().destroy();
            }

            // Initialize DataTable
            table = $('#serviceTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('report.service') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = $('input[name="_token"]').val();
                        d.serviceFromDate = $('#serviceFromDate').val();
                        d.serviceToDate = $('#serviceToDate').val();
                        d.serviceBranch = $('#serviceBranch').val();
                        d.serviceCreatedBy = $('#serviceCreatedBy').val();
                        d.serviceTreatment = $('#serviceTreatment').val();
                        d.serviceTreatmentPlan = $('#serviceTreatmentPlan').val();
                        d.serviceComboOffer = $('#serviceComboOffer').val();
                        d.serviceGender = $('#serviceGender').val();
                        d.serviceAgeFrom = $('#serviceAgeFrom').val();
                        d.serviceAgeTo = $('#serviceAgeTo').val();
                    },
                    dataSrc: function(json) {
                        return json.data; // Ensure `json.data` is correct
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'branch',
                        name: 'branch'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        className: 'min-w-60',
                        render: function(data, type, row) {
                            if (data) {
                                // Convert the date string to a JavaScript Date object
                                var date = new Date(data);

                                // Format the date as d-m-y
                                var day = ("0" + date.getDate()).slice(-2);
                                var month = ("0" + (date.getMonth() + 1)).slice(
                                    -2);
                                var year = date.getFullYear();

                                return day + '-' + month + '-' +
                                    year; // Return formatted date
                            } else {
                                return '-'; // Return dash if no data is present
                            }
                        }
                    },
                    {
                        data: 'phoneNumber',
                        name: 'phoneNumber'
                    },
                    {
                        data: 'serviceName',
                        name: 'serviceName'
                    },
                    {
                        data: 'treatmentPlan',
                        name: 'treatmentPlan'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    }
                ],
                dom: 'Bfrtlp',
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All']
                ],
                buttons: [{
                        extend: 'print',
                        text: 'Print',
                        title: clinicBasicDetails.clinic_name,
                        messageTop: 'Service Report',
                        orientation: 'portrait',
                        pageSize: 'A4',
                        footer: true,
                        filename: 'Service Report',
                        exportOptions: {
                            columns: ':visible'
                        },
                        customize: function(win) {
                            $(win.document.body).css('font-size', '10pt');
                            $(win.document.body).find('table').addClass('compact').css(
                                'font-size', 'inherit');
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: clinicBasicDetails.clinic_name,
                        messageTop: 'Service Report',
                        footer: true,
                        filename: 'Service Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        title: clinicBasicDetails.clinic_name,
                        messageTop: 'Service Report',
                        orientation: 'portrait',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        },
                        footer: true,
                        filename: 'Service Report',
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 10;
                        }
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var totalQuantity = api.column(6).data().reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                    var totalAmount = api.column(7).data().reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
                    $(api.column(6).footer()).html(totalQuantity.toFixed(2));
                    $(api.column(7).footer()).html(totalAmount.toFixed(2));
                }
            });

            $('.servicediv').show();
        });

    });
</script>
