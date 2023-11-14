<x-mail::message>
# Laravel Nova {{ $version }}

<div>
{{ $notes }}
</div>
<br><br>
<ul>
@foreach($comparison as $action => $count)
<li>{{ $count }} {{ trans_choice('File|Files', $count) }} {{ $action }}</li>
@endforeach
</ul>
<br><br>
<x-mail::button :url="$url">
Compare with v{{ $previousVersion }}
</x-mail::button>

<br>
{{ config('app.name') }}
</x-mail::message>
