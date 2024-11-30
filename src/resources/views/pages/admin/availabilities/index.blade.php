@extends('layouts.admin.main')

@section('styles')
@endsection

@section('modals')
@endsection

@section('breadcrumbs')
    <li aria-current="page">
        <div class="flex items-center">
            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Bookings</span>

            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Availabilities</span>
        </div>
    </li>
@endsection

@section('sidebar')
    <div class="w-1/6 p-5 border-r">
        <ul class="mx-2 flex flex-col gap-2">
            <li>
                <a href="{{ route('admin.bookings.index') }}" class="text-blue-600 hover:text-blue-800">
                    Back
                </a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="p-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-800 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Bookable</th>
                    <th scope="col" class="px-6 py-4">Duration</th>
                    <th scope="col" class="px-6 py-3">Monday</th>
                    <th scope="col" class="px-6 py-3">Tuesday</th>
                    <th scope="col" class="px-6 py-3">Wednesday</th>
                    <th scope="col" class="px-6 py-3">Thursday</th>
                    <th scope="col" class="px-6 py-3">Friday</th>
                    <th scope="col" class="px-6 py-3">Saturday</th>
                    <th scope="col" class="px-6 py-3">Sunday</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookables as $bookable)
                    <tr class="go-to-url cursor-pointer bg-white border-b" data-url="{{ route('admin.bookings.availabilities.edit', ['bookable_id' => $bookable->id]) }}">
                        <td class="px-6 py-4">{{ $bookable->name }}</td>
                        <td class="px-6 py-4">{{ $bookable->availabilities && $bookable->availabilities->first() ? $bookable->availabilities->first()->slot_duration : null }}</td>
                        <td class="px-6 py-4">{{ $bookable->availabilities && $bookable->availabilities->where('day_of_week', 'monday')->first() ? $bookable->availabilities->where('day_of_week', 'monday')->first()->start_time . ' - ' . $bookable->availabilities->where('day_of_week', 'monday')->first()->end_time : null }}</td>
                        <td class="px-6 py-4">{{ $bookable->availabilities && $bookable->availabilities->where('day_of_week', 'tuesday')->first() ? $bookable->availabilities->where('day_of_week', 'tuesday')->first()->start_time . ' - ' . $bookable->availabilities->where('day_of_week', 'tuesday')->first()->end_time : null }}</td>
                        <td class="px-6 py-4">{{ $bookable->availabilities && $bookable->availabilities->where('day_of_week', 'wednesday')->first() ? $bookable->availabilities->where('day_of_week', 'wednesday')->first()->start_time . ' - ' . $bookable->availabilities->where('day_of_week', 'wednesday')->first()->end_time : null }}</td>
                        <td class="px-6 py-4">{{ $bookable->availabilities && $bookable->availabilities->where('day_of_week', 'thursday')->first() ? $bookable->availabilities->where('day_of_week', 'thursday')->first()->start_time . ' - ' . $bookable->availabilities->where('day_of_week', 'thursday')->first()->end_time : null }}</td>
                        <td class="px-6 py-4">{{ $bookable->availabilities && $bookable->availabilities->where('day_of_week', 'friday')->first() ? $bookable->availabilities->where('day_of_week', 'friday')->first()->start_time . ' - ' . $bookable->availabilities->where('day_of_week', 'friday')->first()->end_time : null }}</td>
                        <td class="px-6 py-4">{{ $bookable->availabilities && $bookable->availabilities->where('day_of_week', 'saturday')->first() ? $bookable->availabilities->where('day_of_week', 'saturday')->first()->start_time . ' - ' . $bookable->availabilities->where('day_of_week', 'saturday')->first()->end_time : null }}</td>
                        <td class="px-6 py-4">{{ $bookable->availabilities && $bookable->availabilities->where('day_of_week', 'sunday')->first() ? $bookable->availabilities->where('day_of_week', 'sunday')->first()->start_time . ' - ' . $bookable->availabilities->where('day_of_week', 'sunday')->first()->end_time : null }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-5">
            {{ $bookables->links() }}
        </div>
    </div>
@endsection

@section('scripts')
    @include('bookings::includes.scripts.go-to-url')
@endsection
