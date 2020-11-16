@extends('business.layouts.master')

@section('body')
	<div class="row">
		<div class="col s12">
			<div class="card card-tabs">
				<div class="card-content">
					<div class="row mb-2">
						<div class="col s12">
	                        <h4 class="card-title bpg-arial">დამტენები</h4>
	                    </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            @if ($chargers -> count())
                                <table class="responsive-table bpg-arial">
                                    <thead>
                                        <tr style="color: black">
                                            <th>სახელი</th>
                                            <th>კოდი</th>
                                            <th class="center">საჯარო</th>
                                            <th class="center">სტატუსი</th>
                                            <th class="center">რედაქტირება</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($chargers as $charger)
                                            <tr>
                                                <td>{{ $charger -> name }}</td>
                                                <td>{{ $charger -> code }}</td>
                                                <td class="center">
                                                    <i class="material-icons dp48" style="{{ $charger -> public ? 'color: green' : 'color: red' }}">{{ $charger -> public ? 'check' : 'close' }}</i>
                                                </td>
                                                <td class="center">
                                                    <i class="material-icons dp48" style="font-size: 1em; font-family: arial">{{ $charger -> status}}</i>
                                                </td>
                                                <td class="center">
                                                    <a href="/business/chargers/{{ $charger -> id }}/edit" class="btn waves-effect waves-light btn-small">
                                                        <i class="material-icons">edit</i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="mt-4 flex">
                                    <i class="material-icons red color-white round mr-1">priority_high</i>
                                    <p>დამტენები არ მოიძებნა</p>
                                </div>
                            @endif
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
@endsection
