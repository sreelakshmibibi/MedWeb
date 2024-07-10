<div class="row doctor-section" style="display: none;">
    {{-- <div class="col-xl-8 col-12"> --}}
    <div class="col-xl-12 col-12">
        <div class="box">
            <div class="box-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Patients</h4>
                </div>
            </div>
            <div class="box-body p-15">
                <div class="table-responsive">
                    <!-- Main content -->
                    <table class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center"
                        width="100%">
                        <thead class="bg-primary-light">
                            <tr>
                                <th>No</th>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Phone number</th>
                                <th>Branch</th>
                                <th width="150px">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <!-- /.content -->
                </div>

            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    jQuery(function($) {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "",
                type: 'GET',
                data: function(d) {
                    // d.userId = userId; // Add selectedDate as a query parameter
                    d.userId = '{{ $userDetails->id }}'
                }
            },
            // ajax: "",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        // Return the row index (starts from 0)
                        return meta.row + 1; // Adding 1 to start counting from 1
                    }
                },
                {
                    data: 'patient_id',
                    name: 'patient_id'
                },

                {
                    data: 'name',
                    name: 'name'
                },

                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'branch',
                    name: 'branch'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: true
                },
            ]
        });

    });
</script>
