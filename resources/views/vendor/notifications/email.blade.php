<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
@if (str_contains($line, 'commented on your post:'))
<div class="comment-card">
<div class="comment-author">
<span class="author-name">{{ Str::before($line, ' commented on your post: ') }}</span> commented on your post:
</div>
<div class="comment-post-title">
{{ Str::after($line, ' commented on your post: ') }}
</div>
</div>
@elseif (trim($line) === 'Comment:')
{{-- Skip "Comment:" label for a cleaner UI --}}
@elseif (Str::startsWith(trim($line), '"') && Str::endsWith(trim($line), '"'))
<x-mail::panel>
<div class="comment-text">
{{ trim($line, '"') }}
</div>
</x-mail::panel>
@else
{{ $line }}
@endif
@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards,')<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
"If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
'into your web browser:',
[
'actionText' => $actionText,
]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>