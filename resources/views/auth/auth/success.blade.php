@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <p class="text-success text-center">
                    {{ trans('messages.your_account_activated_success') }}
                </p>
            </div>
        </div>
    </div>
@endsection
