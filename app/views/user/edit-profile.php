<?php
// Start the session before any output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] === false) {
    header('Location: /user/login');
    exit;
}

include_once __DIR__ . '/../header.php';

// Split the full name into first and last name if available
$firstName = '';
$lastName = '';
if (!empty($user->fullname)) {
    $nameParts = explode(' ', $user->fullname, 2);
    $firstName = $nameParts[0] ?? '';
    $lastName = $nameParts[1] ?? '';
}
?>

<main class="container mt-5 mb-5">
    <div class="page-container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4 text-cyan">Edit Your Profile</h2>
                        
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
                        
                        <form id="editProfileForm" method="POST" action="/user/updateProfile" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="firstName" class="form-label text-start d-block">First Name</label>
                                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?= htmlspecialchars($firstName) ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="lastName" class="form-label text-start d-block">Last Name</label>
                                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?= htmlspecialchars($lastName) ?>" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="username" class="form-label text-start d-block">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user->username ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="password" class="form-label text-start d-block">Password</label>
                                            <button type="button" class="passwd-button">Change Password</button>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="email" class="form-label text-start d-block">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user->email ?? '') ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label text-start d-block">Phone Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user->phone ?? '') ?>" required>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-start mt-4 gap-3">
                                        <a href="/user/dashboard" class="btn btn-signup btn-smaller">Cancel</a>
                                        <button type="submit" id="saveChangesBtn" class="btn btn-signup btn-smaller" disabled>Save Changes</button>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="profile-picture-container mb-3">
                                            <?php if (!empty($user->image)): ?>
                                                <img src="<?= htmlspecialchars($user->image) ?>" alt="Profile Picture" class="profile-picture">
                                            <?php else: ?>
                                                <img src="/media/Profile_avatar_placeholder.png" alt="Profile Picture" class="profile-picture">
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex flex-column align-items-center gap-2">
                                            <div class="input-group">
                                                <input type="file" class="form-control form-control-sm" id="image" name="image" accept="image/jpeg,image/png">
                                            </div>
                                            <small class="text-muted">Allowed formats: JPG, JPEG, PNG</small>
                                            <small class="text-muted">Image will be cropped to a square</small>
                                            <?php if (!empty($user->image)): ?>
                                                <button type="submit" class="passwd-button mt-2" name="deleteProfilePicture" value="1">Delete Profile Picture</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden field to store the combined full name -->
                            <input type="hidden" id="fullname" name="fullname" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editProfileForm');
        const saveButton = document.getElementById('saveChangesBtn');
        const formElements = form.querySelectorAll('input, select, textarea');
        const firstNameInput = document.getElementById('firstName');
        const lastNameInput = document.getElementById('lastName');
        const fullNameInput = document.getElementById('fullname');
        
        // Function to update the hidden fullname field
        function updateFullName() {
            fullNameInput.value = firstNameInput.value + ' ' + lastNameInput.value;
        }
        
        // Update fullname when first or last name changes
        firstNameInput.addEventListener('input', updateFullName);
        lastNameInput.addEventListener('input', updateFullName);
        
        // Set initial value
        updateFullName();
        
        formElements.forEach(element => {
            element.addEventListener('input', function() {
                saveButton.removeAttribute('disabled');
            });
        });
    });
</script>

<?php include_once __DIR__ . '/../footer.php'; ?>