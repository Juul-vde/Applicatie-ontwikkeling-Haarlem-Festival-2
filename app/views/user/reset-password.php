<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" type="image/png" href="/media/favicon.png">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-sm" style="max-width: 600px; width: 100%;">
        <div class="card-body">
            <form class="form-signin" method="POST" action="/user/forgotpassword">
                <h1 class="h3 mb-3 font-weight-normal text-center text-cyan">Reset Password</h1>
                <!-- Display success message -->
                <?php if ($successMessage): ?>
                    <div class="alert alert-success text-center">
                        <?= htmlspecialchars($successMessage) ?>
                    </div>
                <?php endif; ?>
                <!-- Info Text -->
                <p class="text-center text-muted small mb-4">
                    Enter your email, and we will send you a link to reset your password.
                </p>

                <!-- Use Bootstrap grid and sizing classes -->
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-10">
                        <div class="mb-3">
                            <label for="email" class="form-label text-cyan">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control"
                                placeholder="Enter your email" required autofocus>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-10">
                        <div class="d-grid gap-2">
                            <button class="btn btn-signup btn-lg" type="submit">Send Reset Link</button>
                        </div>
                    </div>
                </div>

                <p class="mt-3 text-center">
                    <a href="/user/login" class="link">Back to login</a>
                </p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>