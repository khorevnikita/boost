<div>
    <h4>Hello, {{$user->name}}!</h4>
    <p>Thank you for registration on {{config("app.name")}}</p>
    <p>
        Your login: <strong>{{$user->email}}</strong>
    </p>
    <p>
        Your password: <strong>{{$password}}</strong>
    </p>
    <p>
        <a href="{{url("confirm-email/$user->confirmation_token")}}">Confirm e-mail</a>
    </p>
</div>
