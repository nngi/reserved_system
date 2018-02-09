@extends('layouts.app')

@section('content')

<form method="post" action="{{route('user.store')}}">
	{{ csrf_field() }}
	<input type="submit" value="test登録">
</form>

@endsection
