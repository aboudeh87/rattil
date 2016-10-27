@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h3>
                    {{ trans('messages.your_account_activated_success')}}
                </h3>
            </div>
        </div>
    </div>
@endsection
