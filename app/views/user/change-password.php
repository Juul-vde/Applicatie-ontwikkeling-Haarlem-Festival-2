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
            <form class="form-signin" method="POST" action="/user/resetPassword">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>" />
                <h1 class="h3 mb-3 font-weight-normal text-center text-cyan">Reset Password</h1>
                <!-- Display success message -->
                <?php if ($error): ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-10">
                        <div class="mb-3">
                            <label for="password" class="form-label text-cyan">New Password</label>
                            <input type="password" id="password" name="password" required class="form-control"
                                placeholder="Enter new password" required autofocus>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-10">
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label text-cyan">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                placeholder="Confirm password" required autofocus>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-10">
                        <div class="d-grid gap-2">
                            <button class="btn btn-signup btn-lg mb-4" type="submit">Change password</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>