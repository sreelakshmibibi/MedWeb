<section class="content ">
    <div class="box">
        {{-- <div class="box-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Users</h4>
                @can('create user')
                    <a href="{{ url('users/create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"> </i> Add
                        User</a>
                @endcan
            </div>
        </div> --}}

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped data-table" id="userstable" width="100%">
                    <thead>
                        <tr class="bg-primary-light text-center">
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th width="200px">
                                @can('staff create')
                                    {{-- <a href="{{ url('users/create') }}" class="btn btn-sm btn-primary"><i
                                            class="fa fa-plus"> </i> Add
                                        User</a> --}}

                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modal-createuser"> <i class="fa fa-plus"> </i>
                                        Add User</button>
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

@include('settings.user.create_modal')

<script>
    jQuery(function($) {

        var table1 = $('#userstable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    className: 'text-center max-w-10',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Row index starting from 1
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'roles',
                    name: 'roles',
                    className: 'text-center',

                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
