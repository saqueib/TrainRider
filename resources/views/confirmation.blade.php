@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-12 col-sm-offset-0">

                <div class="panel panel-default">
                    <div class="panel-body text-center">

                        <h1><small>Hi</small> <br> <span class="text-primary">{{ Auth::user()->name }}</span></h1>

                        <h3>Do you really want to cancel your subscription?</h3>

                        <hr>

                        <form action="{{ route('subscriptionCancel') }}" method="post">
                            {{ csrf_field() }}
                            <p><a href="{{ route('home') }}" class="btn btn-link">No, I wanted to Stay</a></p>
                            <button type="submit" class="btn btn-lg btn-danger">Please Cancel It</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection