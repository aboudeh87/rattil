@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h3>
                    {{ trans('messages.invalid_activation_token')}}
                </h3>
            </div>
        </div>
    </div>
@endsection
