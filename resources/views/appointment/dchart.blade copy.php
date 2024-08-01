<?php
// Example data structure
$teethImages = [
    [
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t55.png', 'text' => '55'],
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t54.png', 'text' => '54'],
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t53.png', 'text' => '53'],
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t52.png', 'text' => '52'],
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t51.png', 'text' => '51'],
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t61.png', 'text' => '61'],
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t62.png', 'text' => '62'],
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t63.png', 'text' => '63'],
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t64.png', 'text' => '64'],
        ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t65.png', 'text' => '65'],
        // Add more teeth data as needed
    ],
    [
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '18'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t17.png', 'text' => '17'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '16'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t17.png', 'text' => '15'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '14'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t17.png', 'text' => '13'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '12'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t17.png', 'text' => '11'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '21'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t17.png', 'text' => '22'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '23'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t17.png', 'text' => '24'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '25'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t17.png', 'text' => '26'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '27'],
        ['class' => 'normal molar', 'image' => 'images/teeths/top/t17.png', 'text' => '28'],
        // Add more teeth data as needed
    ],
    // Add more rows as needed
];

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
?>

<div class="row">
    <div class="box bg-white">
        <div class="box-body">
            <div class="tooth_body">
                <div class="dental-chart">
                    @foreach ($teethImages as $row)
                        <div class="row">
                            @foreach ($row as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                    <p class="image-text">{{ $tooth['text'] }}</p>
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

                    @foreach ($teethImages as $row)
                        <div class="row mt-2">
                            @foreach ($additionalTeethImages as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            @foreach ($row as $tooth)
                                <div class="tooth {{ $tooth['class'] }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                    <p class="image-text">{{ $tooth['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
