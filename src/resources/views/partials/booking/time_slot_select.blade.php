<div class="md:p-5">
    <ul class="grid w-full gap-6">
        @foreach($time_slots as $time_slot)
            <li>
                <input type="radio" id="{{ $time_slot['start'] }}_{{ $time_slot['end'] }}" name="time_slot" value="{{ $time_slot['start'] }}_{{ $time_slot['end'] }}" class="hidden peer" />
                <label for="time_slot" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100">
                    <div class="block">
                        <div class="w-full text-lg font-semibold">{{ $time_slot['start'] }} - {{ $time_slot['end'] }}</div>
                        <div class="w-full"></div>
                    </div>
                </label>
            </li>
        @endforeach
    </ul>
</div>