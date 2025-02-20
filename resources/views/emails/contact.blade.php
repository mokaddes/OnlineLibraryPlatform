<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submission</title>
    <style>
        /* Add your custom email styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header, footer {
            background: #F3F3F3;
            color: #111111;
            padding: 10px;
            text-align: center;
        }

        p {
            margin: 0 0 20px;
        }

        strong {
            color: #333;
        }
    </style>
</head>
<body>
<header>
    <h1 style="text-align: center">{{ env('APP_NAME') }}</h1>
</header>

<div class="container">
    <h1 style="text-align: center">Contact Message Submission</h1>
    <p>Dear Admin,</p>
    <br>
    <p>An user send a contact message to your system,</p>
    <br>
    <p><strong>Name:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $data['message'] }}</p>

    <br>
    <p>Please login to admin dashboard for details</p>
    <br>
    <p><strong>Best regards,</strong><br>{{ env('APP_NAME') }}</p>
</div>

<footer>
    <p>&copy; 2024 {{ env('APP_NAME') }}. All rights reserved.</p>
</footer>
</body>
</html>
