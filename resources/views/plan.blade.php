@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-12 col-sm-offset-0">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <h1>Hi <span class="text-primary">{{ Auth::user()->name }}</span>, your plan details.</h1>
                        <hr>
                        <div class="panel panel-primary">
                            <div class="panel-heading text-uppercase">{{ $plan['id'] }}</div>
                            <div class="panel-body text-center">
                                <h3 class="modal-title">{{ $plan['name'] }}</h3>
                                <img src="{{ asset('img/coal.png') }}" alt="{{ $plan['name'] }} Coal">

                                <h4>{{ $plan->currency }} {{ $plan->amount / 100 }} / {{ $plan->interval }}</h4>

                            </div>
                            <div class="panel-footer text-left">
                                <form action="{{ route('subscribe') }}" method="POST" id="payment-form">
                                    {{ csrf_field() }}

                                    <h3 class="text-center">
                                        <span class="payment-errors label label-danger"></span>
                                    </h3>

                                    <div class="row">
                                        <div class='form-row'>
                                            <div class='col-xs-12 form-group card required'>
                                                <label class='control-label'>Card Number</label>
                                                <input autocomplete='off' value="4242 4242 4242 4242" class='form-control card-number' data-stripe="number" size='20' type='text' required>
                                            </div>
                                        </div>
                                        <div class='form-row'>
                                            <div class='col-xs-4 form-group cvc required'>
                                                <label class='control-label'>CVC</label>
                                                <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' data-stripe="cvc" size='4' type='text' required>
                                            </div>
                                            <div class='col-xs-4 form-group expiration required'>
                                                <label class='control-label'>Expiration Month</label>
                                                <input class='form-control card-expiry-month' placeholder='MM' value="{{ date('d') }}" data-stripe="exp_month" size='2' type='text' required>
                                            </div>
                                            <div class='col-xs-4 form-group expiration required'>
                                                <label class='control-label'> Year</label>
                                                <input class='form-control card-expiry-year' placeholder='YY' data-stripe="exp_year" size='2'  value="{{ date( 'y', strtotime('+ 4 year')) }}" type='text' required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-4">
                                                <div class='form-group cvc required'>
                                                    <label class='control-label'>Coupon Code</label>
                                                    <input autocomplete='off' class='form-control' placeholder='Coupon code' name="coupon" type='text'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="plan" value="{{ $plan['id'] }}">
                                    <input type="submit" class="submit btn btn-success btn-lg btn-block" value="Make $ {{ $plan['amount'] / 100 }} Payment">
                                </form>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript">

        Stripe.setPublishableKey('pk_test_frD0Nvi72TXM84hcpFi8RF5d');

        $(function() {
            var $form = $('#payment-form');
            $form.submit(function(event) {
                // Disable the submit button to prevent repeated clicks:
                $form.find('.submit').prop('disabled', true);

                // Request a token from Stripe:
                Stripe.card.createToken($form, stripeResponseHandler);

                // Prevent the form from being submitted:
                return false;
            });
        });

        function stripeResponseHandler(status, response) {
            // Grab the form:
            var $form = $('#payment-form');

            if (response.error) { // Problem!

                // Show the errors on the form:
                $form.find('.payment-errors').text(response.error.message);
                $form.find('.submit').prop('disabled', false); // Re-enable submission

            } else { // Token was created!

                // Get the token ID:
                var token = response.id;

                // Insert the token ID into the form so it gets submitted to the server:
                $form.append($('<input type="hidden" name="stripeToken">').val(token));

                // Submit the form:
                $form.get(0).submit();
            }
        };
    </script>
@endsection