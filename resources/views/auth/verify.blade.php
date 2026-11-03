<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>verify Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #141E30, #3F5E96);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }

        input[name="otp"] {
            text-align: center;
            letter-spacing: 5px;
            font-size: 20px;
            font-weight: bold;
        }

        .verify-box {
            background: #0f1a2e;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            color: #eaf0ff;
        }

        .form-control {
            background: #1b2a44;
            border: none;
            color: white;
        }

        .form-control:focus {
            background: #1b2a44;
            color: white;
            box-shadow: 0 0 0 2px #3F5E96;
        }

        .btn-theme {
            background: #3F5E96;
            border: none;
            width: 100%;
            padding: 10px;
            font-weight: 600;
        }

        .btn-theme:hover {
            background: #2f4c85;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <div class="verify-box text-center">

                    <h3 class="mb-3">Email Verification</h3>
                    <p class="mb-4">Enter the OTP sent to your email</p>

                    <form method="POST" action="{{ route('verify.otp') }}">

                        @csrf

                        <input type="hidden" name="id" value="{{ $id }}">

                        <div class="mb-3">
                            <input type="text" name="otp" class="form-control text-center" placeholder="Enter OTP">
                        </div>

                        <button type="submit" class="btn btn-theme">Verify OTP</button>

                    </form>

                </div>

            </div>
        </div>
    </div>

</body>

</html>