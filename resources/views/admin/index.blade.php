@extends('layouts.admin')

@section('content')
    <div class="bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
        </div>

        <div class="p-6">
            <div class="bg-blue-50 border-l-4 border-blue-500 px-4 py-3 text-gray-700">
                <span class="font-semibold">Hello, </span>
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                <span> (If not {{ Auth::user()->name ?? 'Admin' }}! <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-blue-600 underline">Logout</a>)</span>
            </div>

            <p class="mt-6 text-gray-600 leading-7">
                From your account dashboard, you can easily check &amp; view your recent orders,
                manage your shipping and billing addresses, and edit your password and account details.
            </p>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
@endsection
