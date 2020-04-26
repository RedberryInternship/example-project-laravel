@extends('business.master')

@section('css')
	<link rel="stylesheet" type="text/css" href="../../../app-assets/css/custom.css">
@endsection

@section('js')
@endsection

@section('body')
	<div class="row">
		<div class="col s12">
			<div class="card card-tabs">
				<div class="card-content">
					<div class="row">
						<div class="col s12">
	                        <h4 class="card-title">დამტენების ჯგუფები</h4>
	                    </div>
                    </div>

                    <div class="row mb-2">
                        <form action="{{ url('/business/charger-groups') }}" method="POST" class="flex align-center">
                            @csrf

                            <div class="input-field col s8">
                                <input type="text" for="name" name="name">
                                <label for="name">დამტენების ახალი ჯგუფი</label>
                            </div>

                            <div class="col offset-s2 s2">
                                <button class="btn waves-effect waves-light green width-100">
                                    დამატება
                                </button>
                            </div>
                        </form>
                    </div>

                    @if ($chargerGroups -> count())
                        <div class="row">
                            <div class="col s12">
                                <div id="view-borderless-table" class="active">
                                    <table class="responsive-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>სახელი</th>
                                                <th class="center">დამტენების რაოდენობა</th>
                                                <th class="center">რედაქტირება</th>                                                
                                                <th class="center">წაშლა</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($chargerGroups as $chargerGroup)
                                                <tr>
                                                    <td>{{ $chargerGroup -> id }}</td>
                                                    <td>{{ $chargerGroup -> name }}</td>
                                                    <td class="center">{{ $chargerGroup -> chargers -> count() }}</td>
                                                    <td class="center">
                                                        <a href="/business/charger-groups/{{ $chargerGroup -> id }}/edit" class="btn waves-effect waves-light btn-small">
                                                            <i class="material-icons">edit</i>
                                                        </a>
                                                    </td>
                                                    <td class="center">
                                                        <form action="{{ url('/business/charger-groups/' . $chargerGroup -> id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="_method" value="delete">

                                                            <button class="btn waves-effect waves-light btn-small red">
                                                                <i class="material-icons">cancel</i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
				</div>
			</div>
		</div>
	</div>
@endsection
