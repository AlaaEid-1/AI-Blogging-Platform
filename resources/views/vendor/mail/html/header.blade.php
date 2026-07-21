@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo-v2.1.png" class="logo" alt="Laravel Logo">
@else
<div style="background-color: #18181b; color: #ffffff; padding: 10px 20px; border-radius: 8px; font-weight: 800; font-size: 24px; letter-spacing: 1px; display: inline-block;">
<span style="color: #60a5fa; margin-right: 2px;">Write</span>AI
</div>
@endif
</a>
</td>
</tr>
