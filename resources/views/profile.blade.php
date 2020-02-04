@extends('layouts.appmaster') 

@section('content')

@if(Session::get('user_id'))
<div class="profile-card">

  <!-- Background color -->
  <div class="card-up indigo lighten-1"></div>

  <!-- Avatar -->
  <div class="avatar mx-auto white">
    <img src="https://mdbootstrap.com/img/Photos/Avatars/img%20%2810%29.jpg" class="rounded-circle"
      alt="woman avatar">
  </div>
  
    <!-- Content -->
  <div class="card-body">
    <!-- Name -->
    <h4 class="card-title">{{$user->getFirst_name()}} {{$user->getLast_name()}} </h4>
    <hr>
    <!-- Quotation -->
    <p><i>{{$user->getSummary()}}</i></p>
  </div>




</div>
@endif
@endsection