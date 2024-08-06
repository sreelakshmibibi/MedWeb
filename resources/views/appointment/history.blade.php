<div class="table-responsive">
    <!-- Main content -->
    <table class="table table-bordered table-hover table-striped mb-0 data-table text-center" width="100%">
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
                <th width="80px">Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <!-- /.content -->
</div>
<script type="text/javascript">
    jQuery(function($) {

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "",
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'treat_date',
                    name: 'treat_date',
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
                    name: 'doctor'
                },
                {
                    data: 'branch',
                    name: 'branch',
                    className: 'text-left',
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: true
                }
            ]
        });

    });
    $('#modal-download').on('hidden.bs.modal', function() {
        $('#pdfRequestForm')[0].reset(); // Reset form fields
        $('#toothSelection').addClass('d-none'); // Hide tooth selection
        $('#toothIdSelect').empty(); // Clear tooth options
    });
</script>
