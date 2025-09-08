@extends('layouts.app') {{-- pakai layout bootstrap kamu, bukan x-app-layout --}}

@section('content')
<div class="container mt-4">

    {{-- Update Profile --}}
    <div class="card mb-3">
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- Update Password --}}
    <div class="card mb-3">
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>
</div>
@endsection