@extends('layouts.cashtrack')

@php
    $pageTitle = 'Profile';
    $pageSubtitle = 'Kelola informasi akun dan keamanan login.';
@endphp

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold">Profile</h1>
        <p class="text-slate-500">
            Kelola informasi akun, password, dan pengaturan akun.
        </p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div
                        class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 text-2xl font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="text-xl font-bold">
                            {{ auth()->user()->name }}
                        </h2>
                        <p class="text-sm text-slate-500">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-t pt-3">
                        <span class="text-slate-500">User ID</span>
                        <span class="font-semibold">#{{ auth()->id() }}</span>
                    </div>

                    <div class="flex justify-between border-t pt-3">
                        <span class="text-slate-500">Bergabung</span>
                        <span class="font-semibold">
                            {{ auth()->user()->created_at->format('d M Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between border-t pt-3">
                        <span class="text-slate-500">Status</span>
                        <span class="font-semibold text-emerald-700">
                            Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection