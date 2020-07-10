@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create an order
                    </div>

                    <div class="card-body">
                        <form id="get-link-form" action="{{url("admin/orders")}}">
                            {{--<div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" name="name" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="surname">Surname</label>
                                <input id="surname" type="text" name="surname" value="" class="form-control">
                            </div>--}}
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" name="email" value="" class="form-control">
                                <p class="text-danger" data-key="email"></p>
                            </div>
                           {{-- <div class="form-group">
                                <label for="phone">Phone</label>
                                <input id="phone" type="text" name="phone" value="" class="form-control">
                            </div>--}}
                            <div class="form-group">
                                <label for="skype">Skype or discord</label>
                                <input id="skype" type="text" name="contact" value="" class="form-control">
                                <p class="text-danger" data-key="contact"></p>
                            </div>
                            {{--<div class="form-group">
                                <label for="discord">Discord</label>
                                <input id="discord" type="text" name="discord" value="" class="form-control">
                            </div>--}}
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input id="amount" type="text" name="amount" value="" class="form-control">
                                <p class="text-danger" data-key="amount"></p>
                            </div>
                            <div class="form-group">
                                <label for="currency">Currency</label>
                                <select id="currency" type="text" name="currency" class="form-control">
                                    <option value="EUR" selected>EUR</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="comment">Comment (Order name)</label>
                                <textarea id="comment" name="comment" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Get link</button>
                            </div>
                        </form>
                        <p class="d-none text-primary js-link"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push("scripts")
        <script>
            $("#get-link-form").submit(function (e) {
                $(".text-danger").text("");
                e.preventDefault();
                var data = {};
                for (var v of $(this).serializeArray()) {
                    data[v['name']] = v['value']
                }
                axios.post($(this).attr("action"), data).then(r => {
                    if (r.data.status === 'success') {
                        $(".js-link").removeClass("d-none").text(r.data.url);
                        $("form").addClass("d-none")
                    }
                }).catch(err => {
                    let errors = err.response.data.errors;
                    for (var key in errors) {
                        var msg = errors[key][0];
                        $("[data-key='" + key + "']").text(msg);
                    }
                });
                return false;
            })
        </script>
    @endpush
@endsection
