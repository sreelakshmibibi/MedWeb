@extends('layouts.dashboard')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Department Details</h3>
                    <button type="button" class="waves-effect waves-light btn btn-primary mb-5" data-bs-toggle="modal"
                        data-bs-target="#modal-center"> <i class="fa fa-add"></i> Add New</button>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    {{-- <div class="box-body p-0"> --}}
                    <div class="box-body">
                        <div class="table-responsive">
                            <!-- Main content -->
                            <table class="table table-bordered table-hover table-striped mb-0 border-2" id="y_dataTables">
                                <thead class="bg-primary-light">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Created at</th>
                                        <th>Manage</th>
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    <tr>
                                        <td>Id</td>
                                        <td>Name</td>
                                        <td>Email</td>
                                        <td>Created at</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="#"
                                                    class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                                        class="fa fa-pencil"></i></a>
                                                <a href="#"
                                                    class="waves-effect waves-circle btn btn-circle btn-danger btn-xs"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody> --}}
                            </table>
                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- modal -->
    <div class="modal center-modal fade" id="modal-center" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Department Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="box">
                            <!-- /.box-header -->
                            <form class="form">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Department</label>
                                        <input class="form-control" type="text" id="name" name="name"
                                            placeholder="Department Name">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="name">Status</label>
                                        <input class="form-control" type="text" id="status" name="status"
                                            placeholder="Status">
                                    </div>

                                    <div class="form-group mt-2">
                                        <label class="form-label col-md-6" for="branch">Active</label>
                                        <input name="branch" type="radio" class="form-control with-gap" id="yes">
                                        <label for="yes">Yes</label>
                                        <input name="branch" type="radio" class="form-control with-gap" id="no">
                                        <label for="no">No</label>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success float-end">Save</button>
                </div>
            </div>
        </div>
    </div>

    {{-- </div> --}}

    <!-- ./wrapper -->
    <script>
        $(document).ready(function() {
            $('#y_dataTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('list') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
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
                        data: 'created_at',
                        name: 'created_at'
                    }
                ]
            });
        });
    </script>
@endsection
