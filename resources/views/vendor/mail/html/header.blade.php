@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                {{-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo"> --}}
                <img src="{{ asset('images/logo/logo-1.jpg') }}" class="logo" alt="Logo">
            @else
                {{ $slot }}
                <img src="{{ asset('images/logo/logo-1.jpg') }}" class="logo" alt="Logo">
            @endif

        </a>
    </td>
</tr>
