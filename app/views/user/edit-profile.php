<?php include_once __DIR__ . '/../header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check for success or error messages
$successMessage = $_SESSION['success_message'] ?? null;
$errorMessage = $_SESSION['error_message'] ?? null;

// Clear the messages after displaying them
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<main class="container mt-5">
    <div class="page-container">
        <h1 class="mb-4">Edit Your Profile</h1>
        
        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>
        
        <div class="card p-4 shadow-sm">
            <form method="POST" action="/user/updateProfile" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user->username ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user->email ?? '') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user->phone ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="fullname" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" value="<?= htmlspecialchars($user->fullname ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Profile Picture</label>
                    <?php if (!empty($user->image)): ?>
                        <div class="mb-2">
                            <img src="<?= htmlspecialchars($user->image) ?>" alt="Current Profile Picture" class="img-thumbnail" style="max-width: 150px;">
                            <p class="text-muted">Current profile picture</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <small class="text-muted">Leave empty to keep current image</small>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="/user/dashboard" class="btn btn-secondary btn-smaller">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-smaller">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include_once __DIR__ . '/../footer.php'; ?>