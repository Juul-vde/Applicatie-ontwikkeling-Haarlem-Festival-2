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
                    </div>
                    <div class="col-md-4">
                        <div class="profile-picture-container mb-3">
                            <?php if (!empty($user->image)): ?>
                                <img src="<?= htmlspecialchars($user->image) ?>" alt="Profile Picture" class="profile-picture">
                            <?php else: ?>
                                <img src="/media/Profile_avatar_placeholder.png" alt="Profile Picture" class="profile-picture">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-actions d-flex justify-content-center gap-3">
                <a href="/user/editProfile" class="btn btn-smaller">Edit Profile</a>
                <button type="button" class="btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You are about to delete your account. Are you sure you want to continue?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="/user/deleteAccount" method="POST" style="display: inline;">
                        <button type="submit" class="btn btn-danger">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once __DIR__ . '/../footer.php'; ?>