@extends('business.layouts.master')

@section('css')
<link rel="stylesheet" href="/css/business/groups.css" />
@endsection

@section('js')
    <script src="/js/business/groups-listing.js"></script>
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
                        <form action="{{ url('/business/groups') }}" method="POST" class="flex align-center">
                            @csrf

                            <div class="input-field col s8">
                                <input type="text" for="name" name="name" required>
                                <label for="name">დამტენების ახალი ჯგუფი</label>
                            </div>

                            <div class="col offset-s2 s2">
                                <button class="btn waves-effect waves-light green width-100">
                                    დამატება
                                </button>
                            </div>
                        </form>

                        @error('name')
                            <div class="card col s3 warning-alert" style="padding: 0 0rem">
                                <div class="card-content red white-text">
                                    ამ სახელით უკვე არსებობს ჯგუფი! 
                                    <i class="material-icons right">add_alert</i>
                                </div>
                            </div>
                        @enderror
                    </div>

                    @if ($groups -> count())
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
                                            @foreach($groups as $group)
                                                <tr>
                                                    <td>{{ $group -> id }}</td>
                                                    <td>{{ $group -> name }}</td>
                                                    <td class="center">{{ $group -> chargers -> count() }}</td>
                                                    <td class="center">
                                                        <a href="/business/groups/{{ $group -> id }}/edit" class="btn waves-effect waves-light btn-small">
                                                            <i class="material-icons">edit</i>
                                                        </a>
                                                    </td>
                                                    <td class="center">
                                                        <form action="{{ url('/business/groups/' . $group -> id) }}" method="POST" class="removable-groups" data-group-name="{{ $group -> name }}">
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
