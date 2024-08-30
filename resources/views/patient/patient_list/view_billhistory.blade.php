<div class="table-responsive">
    <!-- Main content -->
    <table class="table table-bordered table-hover table-striped mb-0 data-table text-center" width="100%"
        id="billhistory_table">
        <thead class="bg-primary-light">
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Teeth</th>
                <th>Problem</th>
                <th>Disease</th>
                <th>Treatment</th>
                <th>Consulted Doctor</th>
                <th>Branch</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <!-- /.content -->
</div>
<script type="text/javascript">
    var table;
    jQuery(function($) {
        table = $('#billhistory_table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "",
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: 'max-w-10',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'treat_date',
                    name: 'treat_date',
                    className: 'w-20',
                    render: function(data, type, row) {
                        return moment(data).format('DD-MM-YYYY');
                    }
                },
                {
                    data: 'teeth',
                    name: 'teeth'
                },
                {
                    data: 'problem',
                    name: 'problem'
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
                    name: 'doctor',
                    className: 'text-left w-30',
                },
                {
                    data: 'branch',
                    name: 'branch',
                    className: 'text-left w-120',
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'w-10',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'w-20',
                    orderable: false,
                    searchable: true
                }
            ]
        });
    });
</script>
