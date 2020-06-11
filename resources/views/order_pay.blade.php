@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">

            </div>
        </div>
    </div>
    @push("scripts")
        <script src="https://widget.cloudpayments.ru/bundles/cloudpayments"></script>
        <script>
            var widget = new cp.CloudPayments({language: "en-US"});
            widget.charge({ // options
                    publicId: 'pk_9b1b8ca37fa37329548c6541f127f',  //id из личного кабинета
                    description: "Order #{{$order->id}}", //назначение
                    amount: parseInt("{{$order->amount}}"), //сумма
                    // amount: 10, //сумма
                    currency: 'RUB', //валюта
                    invoiceId: "{{$order->id}}", //номер заказа  (необязательно)
                    accountId: "{{$order->user->email}}", //идентификатор плательщика (необязательно)
                    skin: "mini", //дизайн виджета
                    data: {
                        myProp: 'myProp value' //произвольный набор параметров
                    }
                },
                function (options) { // success
                    axios.post("/api/orders/" + options.invoiceId + "/payed", {email: options.accountId}).then(r => {
                        if (r.data.status === 'success') {
                            window.location.href = "/order/success";
                        }
                    });
                    console.log(options);
                    //действие при успешной оплате
                },
                function (reason, options) { // fail
                    //действие при неуспешной оплате
                });
        </script>
    @endpush
@endsection
