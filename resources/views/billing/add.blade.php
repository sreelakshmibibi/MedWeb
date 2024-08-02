<?php

use Illuminate\Support\Facades\Session;
?>
@extends('layouts.dashboard')
@section('title', 'Billing')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <div class="content-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title">Billing : <?= Session::get('patientName') ?> ( <?= Session::get('patientId') ?>
                        )</h3>
                    <a type="button" class="waves-effect waves-light btn btn-primary" href="{{ route('billing') }}">
                        <i class="fa-solid fa-angles-left"></i> Back</a>
                </div>
                <div id="error-message-container">
                    <p id="error-message"
                        class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-danger alerttop fadeOut"
                        style="display: none;"></p>
                </div>
            </div>

            <section class="content">
                <div class="box">
                    <div class="box-body ">
                        <form method="post" id="billingform" action="{{ route('treatment.details.store') }}"
                            enctype="multipart/form-data">
                            @csrf



                        </form>
                    </div>

                    <!-- /.box-body -->
                </div>
            </section>
        </div>
    </div>





@endsection
