<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: /');
    exit;
}

include_once __DIR__ . '/../header.php';
?>

<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">Manage Users</h1>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <div class="user-list">
                <?php foreach ($users as $managedUser): ?>
                    <div class="user-block">
                        <div class="user-info">
                            <div class="user-avatar">
                                <img src="<?= !empty($managedUser->image) ? htmlspecialchars($managedUser->image) : '/media/Profile_avatar_placeholder.png' ?>" 
                                     alt="Profile Picture">
                            </div>
                            <div class="user-details">
                                <h3><?= htmlspecialchars($managedUser->fullname) ?></h3>
                                <p>Role: <?= $managedUser->role === \App\Enums\Role::ADMIN ? 'Administrator' : 'User' ?></p>
                            </div>
                        </div>
                        <div class="user-actions">
                            <button type="button" class="btn btn-smaller" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#changeRoleModal<?= $managedUser->id ?>">
                                Change Role
                            </button>
                            <button type="button" class="btn btn-delete" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteUserModal<?= $managedUser->id ?>">
                                Delete User
                            </button>
                        </div>
                    </div>

                    <!-- Change Role Modal -->
                    <div class="modal fade" id="changeRoleModal<?= $managedUser->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Change User Role</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Change role for <?= htmlspecialchars($managedUser->fullname) ?>?</p>
                                    <form action="/user/changeRole" method="POST">
                                        <input type="hidden" name="userId" value="<?= $managedUser->id ?>">
                                        <select name="role" class="form-select mb-3">
                                            <option value="1" <?= $managedUser->role === \App\Enums\Role::USER ? 'selected' : '' ?>>User</option>
                                            <option value="0" <?= $managedUser->role === \App\Enums\Role::ADMIN ? 'selected' : '' ?>>Administrator</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete User Modal -->
                    <div class="modal fade" id="deleteUserModal<?= $managedUser->id ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete User</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete <?= htmlspecialchars($managedUser->fullname) ?>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="/user/deleteUser" method="POST" style="display: inline;">
                                        <input type="hidden" name="userId" value="<?= $managedUser->id ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once __DIR__ . '/../footer.php'; ?>