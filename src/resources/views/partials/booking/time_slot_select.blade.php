<p class="text-gray-500 dark:text-gray-400">{{ \DateTime::createFromFormat('Y-m-d', $date)->format('l j F') }}</p>
<ul class="grid w-full gap-2 py-8 mb-5">
    @foreach($time_slots as $time_slot)
        <li>
            <input type="radio" id="{{ $date }}_{{ $time_slot['start'] }}_{{ $time_slot['end'] }}" name="time_slot" value="{{ $date }}_{{ $time_slot['start'] }}_{{ $time_slot['end'] }}" class="hidden peer" />
            <label for="{{ $date }}_{{ $time_slot['start'] }}_{{ $time_slot['end'] }}" class="inline-flex items-center justify-between w-full p-2 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100">
                <div class="block">
                    <div class="w-full text-lg font-semibold">{{ $time_slot['start'] }} - {{ $time_slot['end'] }}</div>
                </div>
            </label>
        </li>
    @endforeach
</ul>