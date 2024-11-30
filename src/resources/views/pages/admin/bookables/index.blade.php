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
            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Bookables</span>
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
            <li>
                <a href="{{ route('admin.bookings.bookables.create') }}" class="text-blue-600 hover:text-blue-800">Create</a>
            </li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="p-5">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-800 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">ID</th>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Active</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookables as $bookable)
                    <tr class="go-to-url cursor-pointer bg-white border-b" data-url="{{ route('admin.bookings.bookables.edit', ['id' => $bookable->id]) }}">
                        <td class="px-6 py-4">{{ $bookable->id }}</td>
                        <td class="px-6 py-4">{{ $bookable->name }}</td>
                        <td class="px-6 py-4">{{ $bookable->is_active }}</td>
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
