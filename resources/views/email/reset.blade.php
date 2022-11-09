<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://fonts.cdnfonts.com/css/helvetica-neue-9" rel="stylesheet">
</head>
<body style="background: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%);">
<div style="color: #fff">

    <div style="text-align: center;">
        <div style="max-width: 22px; height: 20px; margin: 70px auto 0; padding: 10px">
            <img style="width: 100%; height: 100%" src="{{ url('https://res.cloudinary.com/dt5wsfrex/image/upload/v1667211421/Vector_oxko4i.png') }}" />
        </div>
        <h3 style="color: #DDCCAA; margin: 10px" >MOVIE QUOTES</h3>
    </div>

    <div style="margin-left: 15%; max-width: 70%; font-family: 'Helvetica 65 Medium', sans-serif; line-height: 1.3">
        <p>Hola</p>

        <p style="margin: 30px 0">Please click the button below to reset your password:</p>

        <button style="
                background: #E31221;
                border-radius: 4px;
                width: 128px;
                height: 36px;
                cursor: pointer;
                border: none;"
        >
            <a href="{{ $url }}" style="display: inline-block; width: 100%; color: #fff; text-decoration: none">
                Reset password
            </a>
        </button>

        <p style="margin: 30px 0">If clicking doesn't work, you can try copying and pasting it to your browser:</p>

        <a href="{{ $url }}" target="_blank" style="color: #DDCCAA; text-decoration: none">
            <div style="word-wrap: break-word;">
                {{ $url }}
            </div>
        </a>

        <p style="margin: 30px 0">If you have any problems, please contact us: support@moviequotes.ge

        <p style="margin-bottom: 100px">MovieQuotes Crew</p>
    </div>

</div>
</body>
</html>
