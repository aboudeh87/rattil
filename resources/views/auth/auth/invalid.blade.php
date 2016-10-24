@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <p class="text-danger text-center">
                    {{ trans('messages.invalid_activation_token') }}
                </p>
            </div>
        </div>
    </div>
@endsection
