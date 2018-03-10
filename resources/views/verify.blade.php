<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<div>
    Hi {{ $name }},
    <br>
    Thank you for creating an account with us. Don't forget to complete your registration!
    <br>
    Here yours six digits verification number : <strong style="color:blue;">{{$verification_six_digits}} </strong> verify in this link : <a href="http://sobatteknologi.xyz/custome/snippets/pages/user/verification.html">Verify by Code</a> ,
    or Please click on the link below or copy it into the address bar of your browser to confirm your email address:
    <br>
    <a href="{{ url('user/verify', $verification_code)}}">Confirm my email address </a>

    <br/>
</div>

</body>
</html>
