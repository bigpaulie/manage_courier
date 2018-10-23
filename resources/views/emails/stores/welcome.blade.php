<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome Store</title>
</head>
<body>
 Hello {{$user->profile->first_name}},

<p>
    Username: {{$user->email}}
</p>
 <p>
     password: {{$user->user_password}}
 </p>
 <p>
    <a href="{{url('/login')}}">Click to login</a>
 </p>
</body>
</html>