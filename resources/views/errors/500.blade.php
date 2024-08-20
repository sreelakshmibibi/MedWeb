@extends('layouts.dashboard')
@section('title', 'Server Error')
@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <section class="error-page h-p100">
                <div class="container h-p100">
                    <div class="row h-p100 align-items-center justify-content-center text-center">
                        <div class="col-lg-7 col-md-10 col-12">
                            <div class="rounded10 p-50">
                                <img src="{{ asset('images/auth-bg/500.png') }}" class="max-w-200" alt="" />
                                <h1>500 - Internal Server Error!</h1>
                                <h3>Something went wrong on our end. Please try again later.</h3>
                                @if (config('app.debug'))
                                    <pre>{{ $exception->getMessage() }}</pre>
                                @endif
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
