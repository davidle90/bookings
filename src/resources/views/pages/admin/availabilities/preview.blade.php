<h3>Time Slots for Monday</h3>
@foreach ($timeSlots as $slot)
    <div>{{ $slot['start'] }} - {{ $slot['end'] }}</div>
@endforeach
