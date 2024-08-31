<div class="table-responsive">
    <!-- Main content -->
    <table class="table table-bordered table-hover table-striped mb-0 data-table text-center" width="100%"
        id="history_table">
        <thead class="bg-primary-light">
            <tr>
                <th>No</th>
                <th>Date</th>
                <th class="text-center">Teeth</th>
                <th class="text-center">Problem</th>
                <th class="text-center">Disease</th>
                <th class="text-center">Treatment</th>
                <th>Consulted Doctor</th>
                <!-- <th>Branch</th> -->
                <th class="text-center">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <!-- /.content -->
</div>
<script type="text/javascript">
    // var table;
    // jQuery(function($) {
    //     if (historyStepAdded == true) {

    //         if ($.fn.DataTable.isDataTable("#history_table")) {
    //             $('#history_table').DataTable().destroy();
    //         }

    //         table = $('#history_table').DataTable({
    //             // deferRender: true,
    //             // fixedColumns: true,
    //             responsive: true,
    //             processing: true,
    //             serverSide: true,
    //             ajax: {
    //                 url: "",
    //                 type: 'GET',
    //             },
    //             columns: [{
    //                     data: 'DT_RowIndex',
    //                     name: 'DT_RowIndex',
    //                     className: 'max-w-10',
    //                     orderable: false,
    //                     searchable: false
    //                 },
    //                 {
    //                     data: 'treat_date',
    //                     name: 'treat_date',
    //                     className: 'w-20',
    //                     render: function(data, type, row) {
    //                         return moment(data).format('DD-MM-YYYY');
    //                     }
    //                 },
    //                 {
    //                     data: 'teeth',
    //                     name: 'teeth',
    //                     className: 'text-start align-top',
    //                 },
    //                 {
    //                     data: 'problem',
    //                     name: 'problem',
    //                     className: 'text-start align-top',
    //                 },
    //                 {
    //                     data: 'disease',
    //                     name: 'disease',
    //                     className: 'text-start align-top',
    //                 },
    //                 {
    //                     data: 'treatment',
    //                     name: 'treatment',
    //                     className: 'text-start align-top',
    //                 },
    //                 {
    //                     data: 'doctor',
    //                     name: 'doctor',
    //                     className: 'text-left w-30',
    //                 },
    //                 // {
    //                 //     data: 'branch',
    //                 //     name: 'branch',
    //                 //     className: 'text-left w-120',
    //                 // },
    //                 {
    //                     data: 'status',
    //                     name: 'status',
    //                     className: 'w-10 text-start align-top',
    //                     orderable: false,
    //                     searchable: true
    //                 },
    //                 {
    //                     data: 'action',
    //                     name: 'action',
    //                     className: 'w-20',
    //                     orderable: false,
    //                     searchable: true
    //                 }
    //             ]
    //         });

    //         // Debug column widths after table draw
    //         // $('#history_table').on('draw.dt', function() {
    //         //     console.log('Table drawn with column widths:', table.columns().header().toArray().map(
    //         //         function(header) {
    //         //             return $(header).css('width');
    //         //         }));
    //         // });
    //     }
    // });
    $('#modal-download').on('hidden.bs.modal', function() {
        $('#pdfRequestForm')[0].reset(); // Reset form fields
        $('#toothSelection').addClass('d-none'); // Hide tooth selection
        $('#toothIdSelect').empty(); // Clear tooth options
    });
</script>
