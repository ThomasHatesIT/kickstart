@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="container">
        <div class="alert alert-success" role="alert">
          <h4 class="alert-heading">It's Working!</h4>
          <p>Bootstrap has been successfully loaded into your KickStart project.</p>
        </div>
        
        <button type="button" class="btn btn-primary">This is a Bootstrap Button</button>
        <button type="button" class="btn btn-danger">Another Button</button>
    </div>
@endsection