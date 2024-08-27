<form method="post" action="{{ route('report.patient') }}">
    @csrf
    <div class="container-fluid">
        <div class="box no-border mb-2">
            <div class="box-header p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="box-title ">
                        Patients Report
                    </h4>
                </div>
            </div>
            <div class="box-body px-0 ">
                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="patientFromDate">From <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="patientFromDate" name="patientFromDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="patientToDate">To <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="patientToDate" name="patientToDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="patientBranch">Branch</label>
                            <select class="form-control " type="text" id="patientBranch" name="patientBranch">
                                <option value="">All</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="patientDoctor">Doctor</label>
                            <select class="form-control " type="text" id="patientDoctor" name="patientDoctor">
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
                            <label class="form-label" for="patientTreatment">Treatment</label>
                            <select class="form-control " type="text" id="patientTreatment" name="patientTreatment">
                                <option value="">All</option>
                                @foreach ($treatments as $treatment)
                                    <option value="{{ $treatment->id }}"> {{ $treatment->treat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="patientDisease">Disease</label>
                            <select class="form-control " type="text" id="patientDisease" name="patientDisease">
                                <option value="">All</option>
                                @foreach ($diseases as $disease)
                                    <option value="{{ $disease->id }}"> {{ $disease->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="patientGender">Gender</label>
                            <select class="form-control " type="text" id="patientGender" name="patientGender">
                                <option value="">All</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="O">Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="patientAge">Age</label>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="From" aria-label="From"
                                    name="patientAgeFrom" id="patientAgeFrom" min="0" max="110">
                                <span class="input-group-text">-</span>
                                <input type="number" class="form-control" placeholder="To" aria-label="To"
                                    name="patientAgeTo" id="patientAgeTo" min="0" max="110">
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="box-footer p-3 px-0 text-end">
                <button type="submit" class="btn btn-success" id="searchPatientsBtn">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
    </div>
    <div class="patientsdiv container" style="display: none">
        <div class="table-responsive" style="width: 100%; overflow-x: auto;">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center"
                id="patientsTable" width="100%">
                <thead class="bg-primary-light">
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Phone Number</th>
                        <th>Diseases</th>
                        <th>Treatments</th>
                        <th>Doctor</th>
                        <th>Branch</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</form>
<script type="text/javascript">
    var table;
    jQuery(function($) {
        var clinicBasicDetails = @json($clinicBasicDetails);

        $('#searchPatientsBtn').click(function(e) {
            e.preventDefault(); // Prevent form submission

            if ($.fn.DataTable.isDataTable("#patientsTable")) {
                $('#patientsTable').DataTable().destroy();
            }

            // Initialize DataTable
            table = $('#patientsTable').DataTable({
                processing: true,
                serverSide: true,
                // responsive: true,
                ajax: {
                    url: "{{ route('report.patient') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = $('input[name="_token"]').val();
                        d.patientFromDate = $('#patientFromDate').val();
                        d.patientToDate = $('#patientToDate').val();
                        d.patientBranch = $('#patientBranch').val();
                        d.patientDoctor = $('#patientDoctor').val();
                        d.patientTreatment = $('#patientTreatment').val();
                        d.patientDisease = $('#patientDisease').val();
                        d.patientGender = $('#patientGender').val();
                        d.patientAgeFrom = $('#patientAgeFrom').val();
                        d.patientAgeTo = $('#patientAgeTo').val();
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
                        data: 'date',
                        name: 'date',
                        className: 'min-w-60',
                        render: function(data, type, row) {
                            if (data) {
                                // Convert the date string to a JavaScript Date object
                                var date = new Date(data);

                                // Format the date as d-m-y
                                var day = ("0" + date.getDate()).slice(-2);
                                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                                var year = date.getFullYear();

                                return day + '-' + month + '-' +
                                    year; // Return formatted date
                            } else {
                                return '-'; // Return dash if no data is present
                            }
                        }
                    },
                    {
                        data: 'patientId',
                        name: 'patientId'
                    },
                    {
                        data: 'patientName',
                        name: 'patientName'
                    },

                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'age',
                        name: 'age'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'disease',
                        name: 'disease'
                    },
                    {
                        data: 'treatment',
                        name: 'treatment'
                    },
                    {
                        data: 'doctor',
                        name: 'doctor'
                    },
                    {
                        data: 'branch',
                        name: 'branch'
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
                        messageTop: 'Patients Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        footer: true,
                        filename: 'Patients Report',
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
                        messageTop: 'Patients Report',
                        footer: true,
                        filename: 'Patients Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        title: clinicBasicDetails.clinic_name,
                        messageTop: 'Patients Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        },
                        footer: true,
                        filename: 'Patients Report',
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 10;
                        }
                    }
                ],

            });

            $('.patientsdiv').show();
        });

    });
</script>
