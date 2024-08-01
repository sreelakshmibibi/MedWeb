<?php
$teethData = [
    [['class' => 'pediatric inccan', 'image' => 'images/teeths/pediatric/top/t52.png', 'text' => '52'], ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t51.png', 'text' => '51'], ['class' => 'pediatric molar', 'image' => 'images/teeths/pediatric/top/t61.png', 'text' => '61'], ['class' => 'pediatric inccan', 'image' => 'images/teeths/pediatric/top/t62.png', 'text' => '62']],
    // Add more rows as per your data structure
    [['class' => 'normal inccan', 'image' => 'images/teeths/top/t18.png', 'text' => '52'], ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '51'], ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '61'], ['class' => 'normal inccan', 'image' => 'images/teeths/top/t18.png', 'text' => '62'], ['class' => 'normal inccan', 'image' => 'images/teeths/top/t18.png', 'text' => '52'], ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '51'], ['class' => 'normal molar', 'image' => 'images/teeths/top/t18.png', 'text' => '61'], ['class' => 'normal inccan', 'image' => 'images/teeths/top/t18.png', 'text' => '62']],
];

?>

<div class="row">
    <div class="box bg-white">
        <div class="box-body">
            <div class="tooth_body">
                <div class="dental-chart">
                    @foreach ($teethData as $row)
                        <div class="row">
                            @foreach ($row as $tooth)
                                <div class="tooth {{ $tooth['class'] }}" style="{{ $tooth['style'] ?? '' }}">
                                    <img src="{{ asset($tooth['image']) }}" alt="">
                                    <p class="image-text">{{ $tooth['text'] }}</p>
                                </div>
                            @endforeach
                            {{-- Fill the row with empty columns if necessary --}}
                            @for ($i = count($row); $i < 8; $i++)
                                <div class="tooth"></div>
                            @endfor
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
