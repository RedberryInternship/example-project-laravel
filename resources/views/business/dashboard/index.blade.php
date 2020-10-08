@extends('business.master')

@section('body')
	@include('business.dashboard.nav')

	@include('business.dashboard.content')
@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="/css/business/dashboard.css">
@endsection

@section('js')
	<script src="/app-assets/vendors/chartjs/chart.min.js"></script>
	<script src="/js/dashboard.js"></script>
@endsection
