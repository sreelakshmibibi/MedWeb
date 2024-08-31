<div class="table-responsive">
    <!-- Main content -->
    <table class="table table-bordered table-hover table-striped mb-0 data-table text-center" width="100%"
        id="history_table">
        <thead class="bg-primary-light">
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Teeth</th>
                <th>Problem</th>
                <th>Disease</th>
                <th>Treatment</th>
                <th>Consulted Doctor</th>
                <!-- <th>Branch</th> -->
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

        table = $('#history_table').DataTable({
            deferRender: true,
            fixedColumns: true,
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
                // {
                //     data: 'branch',
                //     name: 'branch',
                //     className: 'text-left w-120',
                // },
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

        // Debug column widths after table draw
        $('#history_table').on('draw.dt', function() {
            console.log('Table drawn with column widths:', table.columns().header().toArray().map(
                function(header) {
                    return $(header).css('width');
                }));
        });

    });
    $('#modal-download').on('hidden.bs.modal', function() {
        $('#pdfRequestForm')[0].reset(); // Reset form fields
        $('#toothSelection').addClass('d-none'); // Hide tooth selection
        $('#toothIdSelect').empty(); // Clear tooth options
    });
</script>
