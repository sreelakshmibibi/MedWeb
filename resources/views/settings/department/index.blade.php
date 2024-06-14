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
                            <table class="table table-bordered table-hover table-striped mb-0 border-2 data-table" id="y_dataTables">
                                <thead class="bg-primary-light">
                                    <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /.content-wrapper -->

   @include('settings.department.create')
    {{-- </div> --}}

    <!-- ./wrapper -->
    <script type="text/javascript">
        
        jQuery(function($){
        
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('settings.department') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'department', name: 'department'},
                    {data: 'action', name: 'action', orderable: false, searchable: true},
                ]
            });
                
        });
    </script>
@endsection
