@extends('errors.layout')
@section('code', '404')
@section('title', 'We couldn’t find that page')
@section('message', 'It may have moved, or the address has a typo.')
@section('links')
<a href="/products">All devices</a><a href="/search">Search</a>
@endsection
