<form action="{{ url('users') }}" method="POST">
    @csrf
    <div class="modal fade modal-right slideInRight" id="modal-createuser" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable h-p100">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-user"></i> Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                        <div class="form-group">
                            <label class="form-label" for="name">Name <span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="text" id="name" name="name" placeholder="Name">
                            <div id="nameError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email ID<span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="email" id="email" name="email"
                                placeholder="Email ID">
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Password<span class="text-danger">
                                    *</span></label>
                            <input class="form-control" type="password" id="password" name="password"
                                placeholder="password">
                            <div id="passwordError" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="roles">Roles<span class="text-danger">
                                    *</span></label>
                            <select name="roles[]" id="roles" class="form-control" multiple>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>
                </div>
                <div class="modal-footer modal-footer-uniform">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success float-end">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
