@extends('dashboard.layouts.index')
@php
    use App\Models\UserBusinessProfile;
    $user_id = Auth::user()->id;
    $businsee_profile = UserBusinessProfile::where('user_id', $user_id)->first();
@endphp
@section('title')
    Error
@endsection
@section('header')
    @include('dashboard.layouts.header')
@endsection
@section('sidebar')
    @include('dashboard.layouts.sidebar')
@endsection
@section('page-content')
    <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center" style="">
        <div class="row">
            <div class="col-xl-4" style="text-align: end;">
                 <img src="{{asset('dashboard/img/sub.png')}}" class="img-fluid" alt="Page Not Found">
            </div>
            <div class="col-xl-8">
                <h1 style="font-size: 40px;line-height: normal;">{{ $data['heading'] }}</h1>
                <h2>{{ $data['message'] }}</h2>
                <a class="btn" href="{{ $data['link'] }}">{{ $data['button'] }}</a>
            </div>
        </div>

      

        <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
        </div>
    </section>
@endsection
