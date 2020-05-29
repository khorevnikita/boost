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
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" name="name" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="surname">Surname</label>
                                <input id="surname" type="text" name="surname" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" type="email" name="email" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input id="phone" type="text" name="phone" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input id="amount" type="text" name="amount" value="" class="form-control">
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
                });
                return false;
            })
        </script>
    @endpush
@endsection
