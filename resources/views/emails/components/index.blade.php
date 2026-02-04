{{--
    Email Components - Reusable Elements
    Usage: @include('emails.components.button', ['url' => '...', 'text' => '...'])
--}}

{{-- Primary Button --}}
@component('emails.components.button-primary')
    @slot('url', $url ?? '#')
    @slot('text', $text ?? 'Click Here')
@endcomponent
