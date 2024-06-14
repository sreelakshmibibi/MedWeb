@extends('layouts.dashboard')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <table class="table table-bordered" id="y_dataTables">
               <thead>
                  <tr>
                     <th>Id</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Created at</th>
                  </tr>
               </thead>
            </table>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->

    {{-- </div> --}}

    <!-- ./wrapper -->
    <script>
   $(document).ready( function () {
    $('#y_dataTables').DataTable({
           processing: true,
           serverSide: true,
           ajax: "{{ url('list') }}",
           columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'created_at', name: 'created_at' }
                 ]
        });
     });
  </script>
@endsection
