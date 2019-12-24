@extends('layouts.master')

@section('title', 'Categories')

@section('app')
<categories-data-table></categories-data-table>
@endsection

@section('scripts')
<script>
	const controller_url = '{!! route('categories') !!}';
</script>
@endsection