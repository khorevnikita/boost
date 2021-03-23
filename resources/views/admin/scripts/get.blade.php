@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Scripts management</div>
                    <div class="card-body">
                        @foreach($scripts as $script)
                            <form action="{{url("/admin/scripts/$script->id")}}" method="post">
                                @csrf
                                @method("PUT")
                                <div class="form-group">
                                    <label for="place">Placement</label>
                                    <select class="form-control" name="place" id="place">
                                        <option @if($script->place=="header") selected @endif value="header">Header</option>
                                        <option @if($script->place=="footer") selected @endif value="footer">Footer</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="value">Script</label>
                                    <textarea rows="3" name="value" class="form-control" id="value">{{$script->value}}</textarea>
                                </div>
                                <button class="btn btn-outline-secondary">Save script</button>

                            </form>
                            <form action="{{url("/admin/scripts/$script->id")}}" method="post">
                                @csrf
                                @method("DELETE")
                                <button class="btn btn-danger float-right" onclick="return confirm('Confirm the action')">Delete</button>
                            </form>
                            <div style="clear:both"></div>
                            <hr>
                        @endforeach
                        <form action="{{url("/admin/scripts")}}" method="post">
                            @csrf
                            <button class="btn btn-primary">Add new script</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
