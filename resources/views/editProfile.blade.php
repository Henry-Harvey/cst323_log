@extends('layouts.appmaster') 

@section('content')

@if(Session::get('user_id'))
<form action="editProfile" method="POST">
	{{ csrf_field() }}
	
    <!-- <div class="form-group">
    <img src="https://mdbootstrap.com/img/Photos/Avatars/img%20%2810%29.jpg" class="rounded-circle">
    </div> -->
    
    <div class="form-group">
		<input type="hidden"
			class="form-control" id="id" placeholder="ID" value="{{$user->getId()}}"
			name="id">
	</div>
    
	<div class="form-group">
		<label for="firstname">First Name</label> <input style="width: 30%" type="text"
			class="form-control" id="firstname" placeholder="First Name" value="{{$user->getFirst_name()}}"
			name="firstname">
	</div>
	
	<div class="form-group">
		<label for="lastname">Last Name</label> <input style="width: 30%" type="text"
			class="form-control" id="lastname" placeholder="Last Name" value="{{$user->getLast_name()}}"
			name="lastname">
	</div>
	
	<div class="form-group">
		<label for="location">Location</label> <input style="width: 30%" type="text"
			class="form-control" id="location" placeholder="Location" value="{{$user->getLocation()}}"
			name="location">
	</div>
	
	<div class="form-group">
		<label for="summary">Summary</label> <input style="width: 30%" type="text"
			class="form-control" id="summary" placeholder="Summary" value="{{$user->getSummary()}}"
			name="summary">
	</div>



	<button type="submit" class="btn btn-dark">Save</button>

</form>

@endif
@endsection