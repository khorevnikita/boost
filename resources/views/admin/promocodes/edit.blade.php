@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Edit a promo code ({{$promocode->code}})
                    </div>

                    <div class="card-body">
                        <form action="{{url("/admin/promocodes/$promocode->id")}}" method="post">
                            @csrf
                             @method("PUT")
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{$promocode->title}}">
                                @error("title")
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <label for="value">Value</label>
                                    <input id="value" type="text" class="form-control" name="value" value="{{$promocode->value}}">
                                    @error('value')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="currency">Currency</label>
                                    <select id="currency" name="currency" class="form-control">
                                        <option @if($promocode->currency == "usd") selected @endif value="usd">USD</option>
                                        <option @if($promocode->currency == "eur") selected @endif value="eur">EUR</option>
                                        <option @if($promocode->currency == "%") selected @endif value="%">%</option>
                                    </select>
                                    @error('currency')
                                    <p class="text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="end_at">Ends at</label>
                                <input id="end_at" type="datetime-local" class="form-control" name="end_at" value="{{str_replace(" ","T",$promocode->end_at)}}">
                                @error("end_at")
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input id="code" type="text" class="form-control" name="code" value="{{$promocode->code}}">
                                @error("code")
                                <p class="text-danger">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                        <form action="{{url("/admin/promocodes/$promocode->id")}}" method="POST">
                            @method("DELETE")
                            @csrf
                            <button type="submit" class="btn btn-danger float-right" onclick="return confirm('Confirm the action')">
                                Delete
                            </button>
                            <div style="clear:both"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
