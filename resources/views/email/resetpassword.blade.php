<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password Email</title>
</head>

<body>
    <p>Hello, {{$formData['user']->name}}</p>
    <h1>You Have Requested To Change Password:</h1>
    <p>Please Click The Link Given Below To Reset Password.</p>
     <a href="{{route('frontend.resetPassword',$formData['token'])}}">Click Here</a>
     <p>Thanks</p>
</body>

</html>
