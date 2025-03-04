<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>The Festival - Register</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="icon" type="image/png" href="/media/favicon.png">
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100 bg-light">
    <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <form class="form-signin" method="POST" action="/user/register">
                <h1 class="h3 mb-3 font-weight-normal text-center text-cyan">Create new account</h1>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="inputFirstName" class="form-label">First Name</label>
                    <input type="text" id="inputFirstName" name="firstName" class="form-control"
                        placeholder="First Name" required maxlength="49">
                </div>
                <div class="mb-3">
                    <label for="inputLastName" class="form-label">Last Name</label>
                    <input type="text" id="inputLastName" name="lastName" class="form-control" placeholder="Last Name"
                        required maxlength="49">
                </div>
                <div class="mb-3">
                    <label for="inputUsername" class="form-label">Username</label>
                    <input type="text" id="inputUsername" name="username" class="form-control" placeholder="Username"
                        required maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="inputEmail" class="form-label">Email address</label>
                    <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address"
                        required maxlength="49">
                </div>
                <div class="mb-3">
                    <label for="inputPhone" class="form-label">Phone Number</label>
                    <input type="text" id="inputPhone" name="phone" class="form-control" placeholder="Phone Number"
                        required maxlength="10" pattern="\d{10}" title="Please enter exactly 10 digits.">
                </div>
                <div class="mb-3">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" id="inputPassword" name="password" class="form-control"
                        placeholder="Password" required maxlength="50">
                </div>
                <div class="mb-3">
                    <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" id="inputConfirmPassword" name="confirmPassword" class="form-control"
                        placeholder="Confirm Password" required maxlength="50">
                </div>
                <button class="btn btn-lg btn-primary w-100" type="submit">Register</button>
                <p class="mt-3 text-center">
                    <a href="/user/login">Already have an account? Login instead</a>
                </p>
                <p class="mt-3 text-center">
                    <a href="/">Back to Home</a>
                </p>
            </form>
        </div>
    </div>
</body>

</html>