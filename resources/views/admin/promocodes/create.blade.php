@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Create a promo code
                    </div>

                    <div class="card-body">
                        <form action="{{url("/admin/promocodes")}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{old("title")}}">
                                @error("title")
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <div class="col">
                                    <label for="value">Value</label>
                                    <input id="value" type="text" class="form-control" name="value" value="{{old("value")}}">
                                    @error('value')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="currency">Currency</label>
                                    <select id="currency" name="currency" class="form-control">
                                        <option value="usd">USD</option>
                                        <option value="eur">EUR</option>
                                        <option value="%">%</option>
                                    </select>
                                    @error('currency')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="end_at">Ends at</label>
                                <input id="end_at" type="datetime-local" class="form-control" name="end_at" value="{{old("end_at")}}">
                                @error("end_at")
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input id="code" type="text" class="form-control" name="code" value="{{old("code")}}">
                                @error("code")
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
