@extends('layouts.appmasterLoggedIn') 

@section('content')

<div class="profile-card">

  <!-- Background color -->
  <div class="card-up indigo lighten-1"></div>

  <!-- Avatar -->
  <!-- <div class="avatar mx-auto white">
    <img src="https://mdbootstrap.com/img/Photos/Avatars/img%20%2810%29.jpg" class="rounded-circle"
      alt="woman avatar">
  </div> -->
  
    <!-- Content -->
  <div class="card-body">
    <!-- Name -->
    <h4 class="card-title">{{$user->getFirst_name()}} {{$user->getLast_name()}} </h4>
    <hr>
    
    <p>Location</p>
	<p><i>{{$user->getLocation()}}</i></p>
	<p>Summary</p>
    <p><i>{{$user->getSummary()}}</i></p>
    
    @if(Session::get('sp')->getUser_id() == $user->getId())
    <a href="getEditProfile">Edit Profile</a>
    @endif
  </div>

	<div class="user-resume">
    	<h3>Job History</h3>

		<a href="createUserJob">Add Job History</a>


    	<h3>Skills</h3>

		<a href="getEditSkills">Add Skills</a>
    	<h3>Education</h3>

		<a href="getEditEducation">Add Education</a>
	</div>


</div>
@endsection