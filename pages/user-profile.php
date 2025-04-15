<?php

ob_start();

include(__DIR__ . '/../includes/header.php');
include(__DIR__ . '/../templates/breadcrumb.php');

// Generate CSRF token
if (empty($_SESSION['csrf_token_for_profile'])) {
  $_SESSION['csrf_token_for_profile'] = bin2hex(random_bytes(32));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_update_form'])) {
  // Validate CSRF token
  if (!isset($_POST['csrf_token_for_profile']) || $_POST['csrf_token_for_profile'] !== $_SESSION['csrf_token_for_profile']) {
    $_SESSION['error_message'] = "Security Validation Failed.";
    header("Location: " . BASE_URL . "user-profile");
    exit;
  }

  // Basic input sanitization
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $address = trim($_POST['address'] ?? '');
  $country = trim($_POST['country'] ?? '');
  $state = trim($_POST['state'] ?? '');
  $city = trim($_POST['city'] ?? '');
  $zip_code = trim($_POST['zip_code'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirmPassword = $_POST['confirm_password'] ?? '';

  try {
    if (empty($name)) {
      throw new Exception("Name is required.");
    }

    if (empty($email)) {
      throw new Exception("Email is required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("Invalid email format.");
    }

    // Upload photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
      $fileTmpPath = $_FILES['photo']['tmp_name'];
      $fileName = $_FILES['photo']['name'];
      $fileSize = $_FILES['photo']['size'];
      $fileType = mime_content_type($fileTmpPath);
      $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

      // Allowed types
      $allowedExtensions = ['jpg', 'jpeg', 'png'];
      $allowedMimeTypes = ['image/jpeg', 'image/png'];

      if (!in_array($fileExtension, $allowedExtensions) || !in_array($fileType, $allowedMimeTypes)) {
        throw new Exception("Invalid image format. Only JPG and PNG are allowed.");
      }

      // Optional: limit image size (e.g., 2MB)
      if ($fileSize > 2 * 1024 * 1024) {
        throw new Exception("Image size should not exceed 2MB.");
      }

      // Create uploads directory if it doesn't exist
      if (!is_dir('uploads')) {
        mkdir('../uploads', 0755, true);
      }

      // Remove old photo
      if (!empty($_SESSION['user']['photo'])) {
        @unlink('../uploads/' . $_SESSION['user']['photo']);
      }

      // Unique filename
      $newFileName = uniqid('photo_', true) . '.' . $fileExtension;

      // Move to upload folder
      if (!move_uploaded_file($fileTmpPath, '../uploads/' . $newFileName)) {
        throw new Exception("Failed to upload photo.");
      }

      // Update in database
      $stmt = $pdo->prepare("UPDATE users SET photo = :photo WHERE id = :id");
      $stmt->execute([
        ':photo' => $newFileName,
        ':id' => $userId
      ]);

      // Update session photo
      $_SESSION['user']['photo'] = $newFileName;

      // Set the new filename to $photo for the next update statement
      $photo = $newFileName;
    }


    // Password validation - only if a new password is provided
    if (!empty($password)) {
      // Check if password and confirm password match
      if ($password !== $confirmPassword) {
        throw new Exception("Password and Confirm Password do not match.");
      }

      // Password strength validation
      if (strlen($password) < 8) {
        throw new Exception("Password must be at least 8 characters long.");
      }

      if (
        !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)
      ) {
        throw new Exception("Password must include at least one uppercase letter, one lowercase letter, one number, and one special character.");
      }
    }

    // Check for duplicate email
    $statement = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $statement->execute([$email, $_SESSION['user']['id']]);
    if ($statement->rowCount() > 0) {
      throw new Exception("Email is already registered.");
    }

    // Update user profile
    if (!empty($password)) {
      // Update with new password
      $statement = $pdo->prepare("UPDATE users SET photo = ?, name = ?, email = ?, phone = ?, address = ?, country = ?, state = ?, city = ?, zip_code = ?, password = ? WHERE id = ?");
      $statement->execute([$photo, $name, $email, $phone, $address, $country, $state, $city, $zip_code, password_hash($password, PASSWORD_DEFAULT), $_SESSION['user']['id']]);
    } else {
      // Update without changing password
      $statement = $pdo->prepare("UPDATE users SET photo = ?, name = ?, email = ?, phone = ?, address = ?, country = ?, state = ?, city = ?, zip_code = ? WHERE id = ?");
      $statement->execute([$photo, $name, $email, $phone, $address, $country, $state, $city, $zip_code, $_SESSION['user']['id']]);
    }

    // Update session data
    $_SESSION['user']['photo'] = $photo;
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['email'] = $email;
    $_SESSION['user']['phone'] = $phone;
    $_SESSION['user']['address'] = $address;
    $_SESSION['user']['country'] = $country;
    $_SESSION['user']['state'] = $state;
    $_SESSION['user']['city'] = $city;
    $_SESSION['user']['zip_code'] = $zip_code;

    $_SESSION['success_message'] = "Profile updated successfully.";
    header("Location: " . BASE_URL . "user-profile");
    exit;

  } catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header("Location: " . BASE_URL . "user-profile");
    exit;
  }
}
// Renerate CSRF token
$_SESSION['csrf_token_for_profile'] = bin2hex(random_bytes(32));
?>

<div class="user-section pt_70 pb_70">
  <div class="container">
    <div class="row">

      <div class="col-lg-3">
        <?php include(__DIR__ . '/../templates/user-sidebar.php'); ?>
      </div>

      <div class="col-lg-9">

        <?php if (!empty($_SESSION['success_message'])): ?>
          <div class="alert alert-success">
            <?php echo $_SESSION['success_message'];
            unset($_SESSION['success_message']); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error_message'])): ?>
          <div class="alert alert-danger">
            <?php echo $_SESSION['error_message'];
            unset($_SESSION['error_message']); ?>
          </div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">

          <!-- CSRF Protection -->
          <input type="hidden" name="csrf_token_for_profile" value="<?php echo $_SESSION['csrf_token_for_profile']; ?>">

          <div class="form-group">
            <label for="">Existing Photo:</label>
            <div>
              <?php
              $defaultPhotoUrl = BASE_URL . 'dist/images/attendee.jpg';

              $photoFileName = $_SESSION['user']['photo'] ?? '';
              $photoFilePath = __DIR__ . '/../uploads/' . $photoFileName; // For file_exists
              $photoUrl = BASE_URL . 'uploads/' . $photoFileName; // For browser
              
              if (!empty($photoFileName) && file_exists($photoFilePath)) {
                $photoToShow = htmlspecialchars($photoUrl);
              } else {
                $photoToShow = $defaultPhotoUrl;
              }
              ?>
              <img src="<?php echo $photoToShow; ?>" alt="" class="w_150" />
            </div>
          </div>
          <div class="form-group">
            <label for="">Change Photo:</label>
            <div>
              <input type="file" name="photo" />
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Name *</label>
                <input type="text" class="form-control" name="name" required
                  value="<?php echo htmlspecialchars($_SESSION['user']['name'] ?? ''); ?>" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Email *</label>
                <input type="email" class="form-control" name="email" required
                  value="<?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Phone </label>
                <input type="text" class="form-control" name="phone"
                  value="<?php echo htmlspecialchars($_SESSION['user']['phone'] ?? ''); ?>" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Address </label>
                <input type="text" class="form-control" name="address"
                  value="<?php echo htmlspecialchars($_SESSION['user']['address'] ?? ''); ?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Country </label>
                <input type="text" class="form-control" name="country"
                  value="<?php echo htmlspecialchars($_SESSION['user']['country'] ?? ''); ?>" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">State </label>
                <input type="text" class="form-control" name="state"
                  value="<?php echo htmlspecialchars($_SESSION['user']['state'] ?? ''); ?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">City </label>
                <input type="text" class="form-control" name="city"
                  value="<?php echo htmlspecialchars($_SESSION['user']['city'] ?? ''); ?>" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Zip Code </label>
                <input type="text" class="form-control" name="zip_code"
                  value="<?php echo htmlspecialchars($_SESSION['user']['zip_code'] ?? ''); ?>" />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Password</label>
                <input type="password" class="form-control" name="password" />
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="">Confirm Password</label>
                <input type="password" class="form-control" name="confrim_password" />
              </div>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary" name="profile_update_form">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include(__DIR__ . '/../includes/footer.php'); ?>