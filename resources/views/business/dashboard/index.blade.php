@extends('business.layouts.master')

@section('body')
	@include('business.dashboard.nav')

	@include('business.dashboard.content')
@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="/css/business/dashboard.css">
@endsection

@section('js')
	<script src="/js/business/dashboard.js"></script>
@endsection
