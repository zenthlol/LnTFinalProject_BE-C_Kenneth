@extends('layouts.app')

@section('title', 'Dashboard | Barang')

@section('content')
<h1>Halo {{ Auth::user()->name }}</h1>
<h2>Kamu adalah {{ Auth::user()->role }}</h2>
@endsection
