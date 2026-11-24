@extends('errors.layout')
@section('code', '419')
@section('title', 'This page expired')
@section('message', 'It was open for a while, so the security token ran out. Go back, reload the page and try again.')
@section('links')
<a href="javascript:history.back()">Go back</a>
@endsection
