<div class="table-responsive">
    <!-- Main content -->
    <table class="table table-bordered table-hover table-striped mb-0 border-2 data-table text-center" width="100%">
        <thead class="bg-primary-light">
            <tr>
                <th>No</th>
                <th>Date</th>
                <th>Teeth</th>
                <th>Problem</th>
                <th>Treatment</th>
                <th>Consulted Doctor</th>
                <th>Branch</th>
                <th>Status</th>
                <th>Remarks</th>
                {{-- <th width="150px">Action</th> --}}
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
            // ajax: "",

            ajax: {
                url: "",
                type: 'GET',
                // data: {
                //     selectedDate: selectedDate
                // }
                // data: function(d) {
                //     d.selectedDate = selectedDate; // Add selectedDate as a query parameter
                // }
            },
            columns: [

                {
                    data: 'treat_date',
                    name: 'treat_date'
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
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: true
                },
                {
                    data: 'nursing_remark',
                    name: 'nursing_remark'
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