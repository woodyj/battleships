@extends('layouts.app')

@section('content')
	<div>You sank the enemy fleet in {{ $shotsFired }} shots!</div>
	<div><a href="{{ @route('reset') }}">Play again</a></div>
@endsection