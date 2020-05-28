@extends('business.master')

@section('body')
	@include('business.dashboard.nav')

	@include('business.dashboard.content')
@endsection

@section('js')
	<script src="/app-assets/vendors/chartjs/chart.min.js"></script>
	<script src="/js/charts/transactions.js"></script>
@endsection
