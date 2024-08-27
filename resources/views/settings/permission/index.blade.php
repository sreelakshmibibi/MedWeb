<section class="content ">
    <div class="box">
        {{-- <div class="box-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Permissions</h4>
                @can('create permission')
                    <a href="{{ url('permissions/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"> </i> Add
                        Permission</a>
                @endcan
            </div>
        </div> --}}

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped " id="permissionstable" width="100%">
                    <thead>
                        <tr class="bg-primary-light text-center">
                            <th width="10%">Id</th>
                            <th width="60%">Name</th>
                            <th width="30%">
                                @can('create permission')
                                    {{-- <a href="{{ url('permissions/create') }}" class="btn btn-sm btn-primary"><i
                                            class="fa fa-plus"> </i> Add
                                        Permission</a> --}}
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-createpermission"> <i class="fa fa-plus"> </i>
                                        Add Permission</button>
                                @else
                                    Action
                                @endcan
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@include('settings.permission.create_modal')

<script>
    jQuery(function($) {
        var table = $('#permissionstable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('permissions.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: 'text-center ',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'name',
                    name: 'name',

                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                }
            ],
            columnDefs: [{
                    targets: 0,
                    width: '10%'
                },
                {
                    targets: 1,
                    width: '60%'
                },
                {
                    targets: 2,
                    width: '30%'
                }
            ],
        });
    });
</script>
