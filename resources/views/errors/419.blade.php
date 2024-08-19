@extends('layouts.dashboard')
@section('title', 'Page Expired')
@section('content')
    <div class="content-wrapper">
        <div class="container-full">
            <section class="error-page h-p100">
                <div class="container h-p100">
                    <div class="row h-p100 align-items-center justify-content-center text-center">
                        <div class="col-lg-7 col-md-10 col-12">
                            <div class="rounded10 p-50">
                                <img src="{{ asset('images/auth-bg/419.png') }}" class="max-w-200" alt="" />
                                <h1>419 - Page Expired!</h1>
                                <h3>Sorry, the page you are looking for is expired.</h3>
                                <div class="my-30">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="btn btn-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        this.closest('form').submit();">Back
                                            to
                                            Login</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
