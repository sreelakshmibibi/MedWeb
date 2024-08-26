<form method="post" action="{{ route('report.disease') }}">
    @csrf
    <div class="container-fluid">
        <div class="box no-border mb-2">
            <div class="box-header p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="box-title ">
                        Disease Report
                    </h4>
                </div>
            </div>
            <div class="box-body px-0 ">
                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="diseaseFromDate">From <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="diseaseFromDate" name="diseaseFromDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="diseaseToDate">To <span class="text-danger">
                                    *</span></label>
                            <input type="date" class="form-control" id="diseaseToDate" name="diseaseToDate"
                                value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="diseaseBranch">Branch</label>
                            <select class="form-control " type="text" id="diseaseBranch" name="diseaseBranch">
                                <option value="">All</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch['id'] }}"> {{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="disease">Disease</label>
                            <select class="form-control " type="text" id="disease" name="disease">
                                <option value="">All</option>
                                @foreach ($diseases as $disease)
                                    <option value="{{ $disease->id }}"> {{ $disease->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="diseaseGender">Gender</label>
                            <select class="form-control " type="text" id="diseaseGender" name="diseaseGender">
                                <option value="">All</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="O">Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <div class="form-group">
                            <label class="form-label" for="diseaseAge">Age</label>
                            <div class="input-group">
                                <input type="number" class="form-control" placeholder="From" aria-label="From"
                                    name="diseaseAgeFrom" id="diseaseAgeFrom" min="0" max="110">
                                <span class="input-group-text">-</span>
                                <input type="number" class="form-control" placeholder="To" aria-label="To"
                                    name="diseaseAgeTo" id="diseaseAgeTo" min="0" max="110">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer p-3 px-0 text-end">
            <button type="submit" class="btn btn-success" id="searchDiseaseBtn">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </div>
    </div>
    <div class="diseasediv container" style="display: none">
        <div class="table-responsive" style=" width: 100%; overflow-x: auto;">
            <table class="table table-bordered table-hover table-striped mb-0 data-table text-center" id="diseaseTable"
                width="100%">
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

        $('#searchDiseaseBtn').click(function(e) {
            e.preventDefault(); // Prevent form submission

            if ($.fn.DataTable.isDataTable("#diseaseTable")) {
                $('#diseaseTable').DataTable().destroy();
            }

            // Initialize DataTable
            table = $('#diseaseTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('report.disease') }}",
                    type: 'POST',
                    data: function(d) {
                        d._token = $('input[name="_token"]').val();
                        d.diseaseFromDate = $('#diseaseFromDate').val();
                        d.diseaseToDate = $('#diseaseToDate').val();
                        d.diseaseBranch = $('#diseaseBranch').val();
                        d.disease = $('#disease').val();
                        d.diseaseGender = $('#diseaseGender').val();
                        d.diseaseAgeFrom = $('#diseaseAgeFrom').val();
                        d.diseaseAgeTo = $('#diseaseAgeTo').val();
                    },
                    dataSrc: function(json) {
                        return json.data;
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
                                var date = new Date(data);

                                // Format the date as d-m-y
                                var day = ("0" + date.getDate()).slice(-2);
                                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                                var year = date.getFullYear();

                                return day + '-' + month + '-' + year;
                            } else {
                                return '-';
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
                        messageTop: 'Disease Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        footer: true,
                        filename: 'Disease Report',
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
                        messageTop: 'Disease Report',
                        footer: true,
                        filename: 'Disease Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        title: clinicBasicDetails.clinic_name,
                        messageTop: 'Disease Report',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        },
                        footer: true,
                        filename: 'Disease Report',
                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 10;
                        }
                    }
                ],
            });

            $('.diseasediv').show();
        });
    });
</script>
