<?php

$additionalTeethImages = [
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],

    // Add more teeth data as needed
];

$additionalNormalTeethImages = [
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],
    ['class' => 'pediatric molar', 'image' => 'images/teeths/top/t_fl.png'],

    // Add more teeth data as needed
];
$upper_ped_teethImages = [];
$upper_teethImages = [];
$lower_ped_teethImages = [];
$lower_teethImages = [];
$topItems_p = [];
$topItems_n = [];
$bottomItems_p = [];
$bottomItems_n = [];

$topItems_p = [];
$topItems_n = [];
$bottomItems_p = [];
$bottomItems_n = [];
$topItems_pr = [];
$topItems_nr = [];
$bottomItems_pr = [];
$bottomItems_nr = [];
$topItems_pl = [];
$topItems_nl = [];
$bottomItems_pl = [];
$bottomItems_nl = [];
?>

@foreach ($tooth as $item)
    @php
        if ($item->position === 'top') {
            if ($item->is_pediatric === 'Y') {
                $topItem_p = ['class' => 'pediatric', 'image' => $item->teeth_image, 'teeth_name' => $item->teeth_name];

                if ($item->direction === 'left') {
                    array_push($topItems_pl, $topItem_p);
                } else {
                    array_push($topItems_pr, $topItem_p);
                }
                // $topItem_p = ['class' => 'pediatric', 'image' => $item->teeth_image, 'teeth_name' => $item->teeth_name];
                // array_push($topItems_p, $topItem_p);
            } else {
                $topItem_n = ['class' => 'normal', 'image' => $item->teeth_image, 'teeth_name' => $item->teeth_name];

                if ($item->direction === 'left') {
                    array_push($topItems_nl, $topItem_n);
                } else {
                    array_push($topItems_nr, $topItem_n);
                }
                // $topItem_n = ['class' => 'normal', 'image' => $item->teeth_image, 'teeth_name' => $item->teeth_name];
                // array_push($topItems_n, $topItem_n);
            }
        } elseif ($item->position === 'bottom') {
            if ($item->is_pediatric === 'Y') {
                $bottomItem_p = [
                    'class' => 'pediatric',
                    'image' => $item->teeth_image,
                    'teeth_name' => $item->teeth_name,
                ];
                if ($item->direction === 'left') {
                    array_push($bottomItems_pl, $bottomItem_p);
                } else {
                    array_push($bottomItems_pr, $bottomItem_p);
                }
                // $bottomItem_p = [
                //     'class' => 'pediatric',
                //     'image' => $item->teeth_image,
                //     'teeth_name' => $item->teeth_name,
                // ];
                // array_push($bottomItems_p, $bottomItem_p);
            } else {
                $bottomItem_n = ['class' => 'normal', 'image' => $item->teeth_image, 'teeth_name' => $item->teeth_name];
                if ($item->direction === 'left') {
                    array_push($bottomItems_nl, $bottomItem_n);
                } else {
                    array_push($bottomItems_nr, $bottomItem_n);
                }
                // array_push($bottomItems_n, $bottomItem_n);
            }
        }
    @endphp
@endforeach

@php
    // array_push($topItems_p, $topItems_pl, $topItems_pr);
    // array_push($topItems_n, $topItems_nl, $topItems_pr);
    // array_push($bottomItems_p, $bottomItems_pl, $bottomItems_pr);
    // array_push($bottomItems_n, $bottomItems_nl, $bottomItems_nr);

    $topItems_plrev = array_reverse($topItems_pl);
    $topItems_nlrev = array_reverse($topItems_nl);
    $bottomItems_plrev = array_reverse($bottomItems_pl);
    $bottomItems_nlrev = array_reverse($bottomItems_nl);

    $topItems_p = array_merge($topItems_p, $topItems_plrev, $topItems_pr);
    $topItems_n = array_merge($topItems_n, $topItems_nlrev, $topItems_nr);
    $bottomItems_p = array_merge($bottomItems_p, $bottomItems_plrev, $bottomItems_pr);
    $bottomItems_n = array_merge($bottomItems_n, $bottomItems_nlrev, $bottomItems_nr);

    array_push($upper_ped_teethImages, $topItems_p);
    array_push($upper_teethImages, $topItems_n);
    array_push($lower_ped_teethImages, $bottomItems_p);
    array_push($lower_teethImages, $bottomItems_n);
@endphp



<div class=" row">
    <div class="box bg-white">
        <div class="box-body">
            <div class="tooth_body">
                <div class="dental-chart">
                    @foreach ($upper_ped_teethImages as $row)
                        <div class="row">
                            @foreach ($row as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img id="{{ $tooth['teeth_name'] }}" class="teeth_image"
                                        src="{{ asset($tooth['image']) }}" alt=""
                                        title="T{{ $tooth['teeth_name'] }}">
                                    <p class="image-text">{{ $tooth['teeth_name'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="row mb-2">
                            @foreach ($additionalTeethImages as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    @foreach ($upper_teethImages as $row)
                        <div class="row">
                            @foreach ($row as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img id="{{ $tooth['teeth_name'] }}" class="teeth_image"
                                        src="{{ asset($tooth['image']) }}" alt=""
                                        title="T{{ $tooth['teeth_name'] }}">
                                    <p class="image-text">{{ $tooth['teeth_name'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="row ">
                            @foreach ($additionalNormalTeethImages as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    @foreach ($lower_teethImages as $row)
                        <div class="row mt-4">
                            @foreach ($additionalNormalTeethImages as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            @foreach ($row as $tooth)
                                <div class="tooth {{ $tooth['class'] }}" style="direction: rtl;">
                                    <p class="image-text mb-0">{{ $tooth['teeth_name'] }}</p>
                                    <img id="{{ $tooth['teeth_name'] }}" class="teeth_image"
                                        src="{{ asset($tooth['image']) }}" alt=""
                                        title="T{{ $tooth['teeth_name'] }}">
                                    {{-- <p class="image-text mb-0">{{ $tooth['teeth_name'] }}</p> --}}
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    @foreach ($lower_ped_teethImages as $row)
                        <div class="row mt-2">
                            @foreach ($additionalTeethImages as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            @foreach ($row as $tooth)
                                <div class="tooth {{ $tooth['class'] }}" style="direction: rtl;">
                                    <p class="image-text mb-0">{{ $tooth['teeth_name'] }}</p>
                                    <img id="{{ $tooth['teeth_name'] }}" class="teeth_image"
                                        src="{{ asset($tooth['image']) }}" alt=""
                                        title="T{{ $tooth['teeth_name'] }}">
                                    {{-- <p class="image-text mb-0">{{ $tooth['teeth_name'] }}</p> --}}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
