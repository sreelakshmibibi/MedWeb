
<div class="dental-chart">
    @php
        // Define arrays for tooth data
        $teethData = [
            'pediatricTop' => ['t51.png', 't61.png'],
            'pediatricIncisors' => array_fill(0, 2, 't_fl.png'),
            'normalTop' => ['t13.png', 't12.png', 't11.png', 't21.png', 't22.png', 't23.png'],
            'normalMiddle' => array_fill(0, 6, 't_fl.png'),
            'normalBottom' => array_fill(0, 6, 't_fl.png'),
            'normalReverseBottom' => ['b43.png', 'b42.png', 'b41.png', 'b31.png', 'b32.png', 'b33.png'],
            'pediatricIncisorsBottom' => array_fill(0, 2, 't_fl.png'),
            'pediatricBottom' => ['b81.png', 'b71.png'],
        ];
    @endphp

    {{-- Loop through each section --}}
    @foreach ($teethData as $section => $images)
        <div class="row">
            {{-- Loop through each tooth --}}
            @foreach ($images as $image)
                @php
                    $toothClass = '';
                    $text = '';
                    $direction = '';
                    
                    // Determine classes and texts based on section
                    switch ($section) {
                        case 'pediatricTop':
                            $toothClass = 'pediatric molar';
                            $text = substr($image, 1, 2);
                            break;
                        case 'pediatricIncisors':
                            $toothClass = 'pediatric inccan';
                            break;
                        case 'normalTop':
                            $toothClass = 'normal inccan';
                            $text = substr($image, 1, 2);
                            break;
                        case 'normalMiddle':
                        case 'normalBottom':
                            $toothClass = 'normal molar';
                            break;
                        case 'normalReverseBottom':
                            $toothClass = 'normal inccan';
                            $text = substr($image, 1, 2);
                            $direction = 'rtl';
                            break;
                        case 'pediatricIncisorsBottom':
                            $toothClass = 'pediatric inccan';
                            break;
                        case 'pediatricBottom':
                            $toothClass = 'pediatric molar';
                            $text = substr($image, 1, 2);
                            $direction = 'rtl';
                            break;
                    }
                @endphp

                <div class="tooth {{ $toothClass }}" style="direction: {{ $direction }}">
                    @if (!empty($text))
                        <p class="image-text mb-0">{{ $text }}</p>
                    @endif
                    <img src="{{ asset('images/teeths/' . ($section == 'normalReverseBottom' || $section == 'pediatricBottom' ? 'pediatric/bottom/' : ($section == 'pediatricIncisors' || $section == 'pediatricIncisorsBottom' ? 'top/' : 'top/')) . $image }}" alt="">
                </div>
            @endforeach
        </div>
        @if (!$loop->last)
            <br />
        @endif
    @endforeach
</div>
