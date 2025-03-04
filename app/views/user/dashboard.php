<?php include_once __DIR__ . '/../header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Welcome, <?= htmlspecialchars($user->fullname ?? 'User') ?>!</h1>
            
            <div class="card p-4 shadow-sm">
                <div class="row">
                    <div class="col-md-8">
                        <h3>Your Profile</h3>
                        <p><strong>Username:</strong> <?= htmlspecialchars($user->username ?? 'N/A') ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($user->email ?? 'N/A') ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($user->phone ?? 'N/A') ?></p>
                        <p><strong>Role:</strong> <?= $isAdmin ? 'Administrator' : 'User' ?></p>
                        <p><strong>Registered On:</strong> <?= $user->registration_date ? $user->registration_date->format('Y-m-d') : 'N/A' ?></p>
                        
                        <div class="d-flex justify-content-start mt-4">
                            <a href="/user/editProfile" class="btn btn-primary btn-smaller">Edit Profile</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-picture-container mb-3">
                            <?php if (!empty($user->image)): ?>
                                <img src="<?= htmlspecialchars($user->image) ?>" alt="Profile Picture" class="profile-picture">
                            <?php else: ?>
                                <img src="/media/Profile_avatar_placeholder.png" alt="Profile Picture" class="profile-picture">
                            <?php endif; ?>
                        </div>
                        <a href="#" class="btn btn-smaller btn-delete-account">Delete Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once __DIR__ . '/../footer.php'; ?>