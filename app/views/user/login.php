<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>ElectroCore - Login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <form class="form-signin" method="POST" action="/user/login">
                <h1 class="h3 mb-3 font-weight-normal text-center text-cyan">Please sign in</h1>
                <!-- Display error message -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email address</label>
                    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address"
                        required autofocus>
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" id="inputPassword" name="password" class="form-control"
                        placeholder="Password" required>
                </div>
                <button class="btn btn-lg btn-signup w-100" type="submit">Sign in</button>
                <p class="mt-3 text-center">
                    <a href="/user/register" class="link">Don't have an account? Register here</a>
                </p>
                <p class="mt-3 text-center">
                    <a href="/" class="link">Back to Home</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>