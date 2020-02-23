<!-- Displays a form for a creating new user education -->
@extends('layouts.appmasterLoggedIn')

@section('content')

<h2>New User Education</h2>

<form action="processCreateUserEducation" method="POST">
{{ csrf_field() }}

<!-- <div class="form-group">
<img src="https://mdbootstrap.com/img/Photos/Avatars/img%20%2810%29.jpg" class="rounded-circle">
</div> -->

<div class="form-group">
<label for="school">School</label>
<input style="width: 30%" type="text" class="form-control" id="school" placeholder="School" name="school">
{{$errors->first('title')}}
</div>

<div class="form-group">
<label for="degree">Degree</label>
<input style="width: 30%" type="text" class="form-control" id="degree" placeholder="Degree" name="degree">
{{$errors->first('company')}}
</div>

<div class="form-group">
<label for="years">Years</label>
<input style="width: 30%" type="text" class="form-control" id="years" placeholder="Years" name="years">
{{$errors->first('years')}}
</div>

<button type="submit" class="btn btn-dark">Save</button>

</form>
@endsection