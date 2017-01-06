@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-12 col-sm-offset-0">

            @if (session('status'))
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ session('status') }}
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-body text-center">
                    <h1>Welcome <span class="text-primary">{{ Auth::user()->name }}</span></h1>

                    @if( $is_subscribed )

                        <img width="180" src="{{ asset('img/train-active.png') }}" alt="Train Active">

                        <h1 class="pulse"><span>Choo</span> <span>Choo...</span></h1>

                        <h3 class="text-success">
                            🚂 Who whoo! your train is running  <br>
                            <small>
                                it has <span class="text-primary">{{ $subscription->stripe_plan }}</span> plan.
                            </small>
                        </h3>

                        @if( $subscription->onGracePeriod() )

                            <div class="alert alert-warning">
                                <h3 class="modal-title">Subscription expiring at {{ $subscription->ends_at->toFormattedDateString() }}</h3>
                            </div>

                            <form method="post" action="{{ route('subscriptionResume') }}">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-success">Resume Subscription</button>
                            </form>
                            <br>

                        @else
                            <a href="{{ route('confirmCancellation') }}" class="btn btn-danger">Cancel Subscription</a>
                        @endif

                    @else

                        <img width="180" src="{{ asset('img/train.png') }}" alt="Train Disabled">
                        <h3 class="text-danger">Your train is out of fuel. <br>
                            <small>You need to a coal delivery subscription to keep your train running!</small>
                        </h3>

                    @endif

                </div>
            </div>

            <div class="row text-center">
                <div class="col-md-12">
                    @if( !empty($plans) )

                        @if($is_subscribed)
                            <p class="lead">You can always change your plan.</p>
                        @else
                            <p class="lead">Please choose any of given plan to activate your train.</p>
                        @endif

                        @foreach($plans as $plan)
                            <div class="col-sm-4">
                                <div class="panel {{ ( $is_subscribed && $subscription->stripe_plan ==  $plan->id ) ? 'panel-success' :  'panel-primary' }}">
                                    <div class="panel-heading text-uppercase">{{ $plan->id }}</div>
                                    <div class="panel-body text-center">
                                        <h3 class="modal-title">
                                            {{ $plan->name }}
                                        </h3>
                                        <img class="img-responsive" src="{{ asset('img/coal.png') }}" alt="{{ $plan->name }} Coal">

                                        <p>{{ $plan->currency }} {{ $plan->amount / 100 }} / {{ $plan->interval }}</p>
                                    </div>
                                    <div class="panel-footer">
                                        @if( $is_subscribed &&  ( $subscription->stripe_plan ==  $plan->id ) )
                                            <a href="#" class="btn btn-default btn-block">
                                                Current Plan
                                            </a>
                                        @else
                                            <a href="{{ route('plan', $plan->id) }}" class="btn btn-success btn-block">
                                                Subscribe
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    @else
                        <div class="alert alert-warning">
                            <strong>No Plan found on Stripe Account!</strong> <br>
                            <p>It could be Network error or you don't have plans defined in Stripe Panel.</p>
                        </div>
                    @endif
                </div>
            </div>

            <p class="text-center">
                <small><a href='http://www.freepik.com/free-vector/steam-train-design_951856.htm'>Train Image by Freepik</a></small>
            </p>
        </div>
    </div>
</div>
@endsection
