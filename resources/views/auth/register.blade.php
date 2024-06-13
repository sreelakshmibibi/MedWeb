@extends('layouts.app')

@section('content')
    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="text-primary">Get started with Us</h2>
                                <p class="mb-0">Register a new membership</p>
                            </div>
                            <div class="p-40">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
                                            <input type="text" id="name"
                                                class="form-control ps-15 bg-transparent @error('name') is-invalid @enderror"
                                                placeholder="Full Name" name="name" value="{{ old('name') }}" required
                                                autofocus>

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-email"></i></span>
                                            <input type="email" id="email"
                                                class="form-control ps-15 bg-transparent @error('email') is-invalid @enderror"
                                                name="email" placeholder="Email" value="{{ old('email') }}" required>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
                                            <input type="password" id="password"
                                                class="form-control ps-15 bg-transparent @error('password') is-invalid @enderror"
                                                name="password" placeholder="Password" required>

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
                                            <input type="password" id="password-confirm"
                                                class="form-control ps-15 bg-transparent" placeholder="Retype Password"
                                                name="password_confirmation" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="checkbox">
                                                <input type="checkbox" id="basic_checkbox_1">
                                                <label for="basic_checkbox_1">I agree to the <a href="#"
                                                        class="text-warning"><b>Terms</b></a></label>
                                            </div>
                                        </div>

                                        <!-- /.col -->
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-info margin-top-10">SIGN IN</button>
                                        </div>

                                        <!-- /.col -->
                                    </div>
                                </form>
                                <div class="text-center">
                                    <p class="mt-15 mb-0">Already have an account?<a href="{{ route('login') }}"
                                            class="text-danger ms-5"> Sign In</a></p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <p class="mt-20 text-white">- Register With -</p>
                            <p class="gap-items-2 mb-20">
                                <a class="btn btn-social-icon btn-round btn-facebook" href="#"><i
                                        class="fa-brands fa-facebook"></i></a>
                                <a class="btn btn-social-icon btn-round btn-twitter" href="#"><i
                                        class="fa-brands fa-twitter"></i></a>
                                <a class="btn btn-social-icon btn-round btn-instagram" href="#"><i
                                        class="fa-brands fa-instagram"></i></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
