@extends('layouts.dashboard')
@section('title', 'Page Not Found')
@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <section class="error-page h-p100">
                <div class="container h-p100">
                    <div class="row h-p100 align-items-center justify-content-center text-center">
                        <div class="col-lg-7 col-md-10 col-12">
                            <div class="rounded10 p-50">
                                <img src="{{ asset('images/auth-bg/404.png') }}" class="max-w-200" alt="" />
                                <h1>404 - Page Not Found!</h1>
                                <h3>Sorry, the page you are looking for does not exist.</h3>
                                <div class="my-30"><a href="{{ url('/') }}" class="btn btn-danger">Back to
                                        dashboard</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
