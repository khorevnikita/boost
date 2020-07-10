@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6 offset-md-3 mt-5">
                <div class="card">
                    <div class="card-body text-center">
                        <form id="paymentModal">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label text-center">
                                    <input class="form-check-input" type="radio" name="type" value="bitcoin">
                                    <img src="/images/bitcoint.png" class="img-fluid">
                                </label>
                                <label class="form-check-label text-center active">
                                    <input class="form-check-input" type="radio" name="type" value="default" checked>
                                    <img src="/images/ecommpay.png" class="img-fluid">
                                </label>
                            </div>
                            <div class="form-group mt-5 ">
                                <button type="submit" class="btn btn-primary">Pay</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="coinModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order has been formed</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Bitcoin wallet hash:</p>
                    <div class="form-group">
                        <input type="text" id="coinhash" class="form-control" value="">
                    </div>
                    <p>Please be prepared to present the transaction number to the manager</p>
                </div>
            </div>
        </div>
    </div>
    @push("scripts")
        <script src="https://widget.cloudpayments.ru/bundles/cloudpayments"></script>
        <script>
            $("form").submit(function (e) {
                e.preventDefault();
                let raw = $(this).serializeArray();
                let type = raw[0]['value'];
                if (type === 'default') {
                    pay();
                } else {
                    let modal = $("#coinModal");
                    $('#coinModal').on('shown.bs.modal', function (e) {
                        // console.log("shown")
                        var input = modal.find("#coinhash");
                        //   console.log(input)
                        input.val("{{config('services.bitcoin.hash')}}");
                        document.querySelector("#coinhash").select();
                        document.execCommand("copy");
                        input.prop("disabled", true)
                    });
                    modal.modal("show");
                }
            });

            function pay() {
                let data = {
                    email:"{{$order->user->email}}",
                    contact:"{{$order->user->skype}}",
                    type:"default"
                };
                axios.post("{{url("/api/orders/$order->id/form")}}", data).then(r => {
                    if (r.data.status === 'success') {
                        window.location.href = r.data.data.url;
                        //window.open(r.data.data.url, '_blank');
                    }
                }).catch(err => {
                    let errors = err.response.data.errors;
                    for (var key in errors) {
                        var msg = errors[key][0];
                        $("[data-key='" + key + "']").text(msg);
                    }
                    $("#paymentModal").modal("hide")
                });
                return;
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
            }
        </script>
    @endpush
@endsection
