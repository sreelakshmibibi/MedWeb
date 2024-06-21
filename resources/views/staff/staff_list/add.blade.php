@extends('layouts.dashboard')
@section('title', 'Staff')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                @if (session('success'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-success alerttop fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('success') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                @if (session('error'))
                    <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fade fadeOut"
                        style="display: block;">
                        <i class="ti-check"></i> {{ session('error') }} <a href="#" class="closed">×</a>
                    </div>
                @endif
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Add Staff Member</h3>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body wizard-content">
                        <form action="#" class="tab-wizard wizard-circle">
                            <!-- Step 1 -->
                            <h6>Personal Info</h6>
                            <section>
                                <!--name and gender-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fname">First Name</label>
                                            <input type="text" class="form-control" id="fname" name="fname"
                                                placeholder="First Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="lname">Last Name</label>
                                            <input type="text" class="form-control" id="lname" name="lname"
                                                placeholder="Last Name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Gender</label>
                                            <div class="c-inputs-stacked">
                                                <input name="group123" type="radio" id="radio_123" value="M">
                                                <label for="radio_123" class="me-30">Male</label>
                                                <input name="group456" type="radio" id="radio_456" value="F">
                                                <label for="radio_456" class="me-30">Female</label>
                                                <input name="group789" type="radio" id="radio_789" value="O">
                                                <label for="radio_789" class="me-30">Other</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--dob, email, mob-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="dob">Date of Birth</label>
                                            <input type="date" class="form-control" id="dob" name="dob">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="email">E-mail Address</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="E-mail">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="phone">Contact
                                                Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                placeholder="Phone Number">
                                        </div>
                                    </div>
                                </div>
                                {{-- <h4 class="box-title text-info mb-0 mt-2"><i class="fa fa-address-card me-15"></i> Address
                                </h4> --}}
                                {{-- <hr class="my-15"> --}}
                                <!--address-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="address">Address Line
                                                1</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="Adress line 1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="address">Address Line
                                                2</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                placeholder="Adress line 2">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="location3">City</label>
                                            <select class="form-select" id="location3" name="location">
                                                <option value="">Select City</option>
                                                <option value="Hyderabad">Hyderabad</option>
                                                <option value="Dubai">Dubai</option>
                                                <option value="Delhi">Delhi</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="location3">State</label>
                                            <select class="form-select" id="location3" name="location">
                                                <option value="">Select State</option>
                                                <option value="Kerala">Kerala</option>
                                                <option value="Karnataka">Karnataka</option>
                                                <option value="Tamil Nadu">Tamil Nadu</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="location3">Country</label>
                                            <select class="form-select" id="location3" name="location">
                                                <option value="">Select Country</option>
                                                <option value="dept1">dept1</option>
                                                <option value="dept2">dept2</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="pincode">Pin Code</label>
                                            <input class="form-control" type="text" id="pincode" name="pincode"
                                                placeholder="XXX XXX">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Role</label>
                                            <div class="c-inputs-stacked">
                                                <input name="occupation" type="radio" id="radio_od" value="doctor">
                                                <label for="radio_od" class="me-30">Doctor</label>
                                                <input name="occupation" type="radio" id="radio_on" value="nurse">
                                                <label for="radio_on" class="me-30">Nurse</label>
                                                <input name="occupation" type="radio" id="radio_oo" value="other">
                                                <label for="radio_00" class="me-30">Other Staff</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="logo">Photo</label>
                                            <input class="form-control @error('clinic_logo') is-invalid @enderror"
                                                type="file" id="clinic_logo" name="clinic_logo" placeholder="logo">
                                            @error('clinic_logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <canvas id="logoCanvas" style="height: 64px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!--Education-->
                            <h6>Experience</h6>
                            <section>
                                <!--qualification-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="qualification">Qualification</label>
                                            <input type="text" class="form-control" id="qualification"
                                                name="qualification" placeholder="qualification" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="experience">Experience</label>
                                            <input type="text" class="form-control" id="experience" name="experience"
                                                placeholder="experience" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="department">Department</label>
                                            <select class="form-select" id="dept3" name="dept">
                                                <option value="">Select Country</option>
                                                <option value="India">India</option>
                                                <option value="UAE">UAE</option>
                                                <option value="USA">USA</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="designation">Designation</label>
                                            <input type="text" class="form-control" id="designation"
                                                name="designation" placeholder="Designation" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="join_date">Date of Joining</label>
                                            <input class="form-control" type="date" id="join_date" name="join_date">

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="join_date">Date of Relieving</label>
                                            <input class="form-control" type="date" id="join_date" name="join_date"
                                                disabled>

                                        </div>
                                    </div>
                                </div>

                                <!--for doctors-->
                                <div class="row doctorFields" style="display: none;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="specialization">Specialization</label>
                                            <input type="text" class="form-control" id="specialization"
                                                name="specialization" placeholder="Specialization" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="subspecial">Subspeciality</label>
                                            <input type="text" class="form-control" id="subspecial" name="subspecial"
                                                placeholder="Subspeciality" required>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="box-title text-info mb-0 mt-2 doctorFields" style="display: none;"><i
                                        class="fa fa-clock me-15"></i> Availability
                                </h4>
                                <hr class="my-15 doctorFields" style="display: none;">

                                <!--availability-->
                                <div class="row doctorFields" style="display: none;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="designation">Sunday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="join_date">Monday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="join_date">Tuesday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="join_date">Wednesday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row doctorFields" style="display: none;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="designation">Thursday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="join_date">Friday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="join_date">Saturday</label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="from">
                                                <span class="input-group-text col-md-1">-</span>
                                                <input type="time" class="form-control" id="specialization"
                                                    name="specialization" placeholder="to">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $(".tab-wizard .actions ul li:last-child a").addClass("bg-success btn btn-success");

            $('input[type=radio][name=occupation]').change(function() {
                if (this.value === 'doctor') {
                    $('.doctorFields').show();
                } else {
                    $('.doctorFields').hide();
                }
            });

        });
    </script>
@endsection
