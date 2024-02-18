@extends('dashboard.layouts.index')
@section('title')
  Trip | Places
@endsection
@section('header')
@include('dashboard.layouts.header')
@endsection
@section('sidebar')
@include('dashboard.layouts.sidebar')
@endsection
@section('page-content')
@include('dashboard.layouts.sweetAlert')
@include('dashboard.places.content')
@endsection
