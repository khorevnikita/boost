@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <div class="alert alert-success">
                    Order has been formed. You will be redirected to the payment system..
                </div>
                @if($is_new)
                    <div class="alert alert-info">
                        User has been registered. You will get a password and confirmation link on your email
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
<script>

</script>
