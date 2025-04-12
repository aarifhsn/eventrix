<?php
session_start();
include("layouts/header.php");
include("layouts/navbar.php");
include("layouts/sidebar.php");

// Initialize messages
$success_message = '';
$error_message = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_update'])) {
  try {
    // Sanitize user input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];

    $userId = $_SESSION['admin']['id'];

    // Full name validation
    if (empty($name)) {
      throw new Exception("Name cannot be empty.");
    }

    // Email validation
    if (empty($email)) {
      throw new Exception("Email cannot be empty.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      throw new Exception("Invalid email format.");
    }

    // === UPDATE NAME AND EMAIL ===
    $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
    $stmt->execute([
      ':name' => $name,
      ':email' => $email,
      ':id' => $userId
    ]);

    // === UPDATE PASSWORD IF PROVIDED ===
    if (!empty($password) || !empty($retype_password)) {
      if ($password !== $retype_password) {
        throw new Exception("Passwords do not match.");
      }

      // Hash the new password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Update password
      $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
      $stmt->execute([
        ':password' => $hashedPassword,
        ':id' => $userId
      ]);
    }
    // === HANDLE PHOTO UPLOAD ===
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

      // Remove old photo
      if (!empty($_SESSION['admin']['photo'])) {
        @unlink('../uploads/' . $_SESSION['admin']['photo']);
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
      $_SESSION['admin']['photo'] = $newFileName;
    }

    // Update session info
    $_SESSION['admin']['full_name'] = $full_name;
    $_SESSION['admin']['email'] = $email;

    $success_message = "Profile updated successfully!";

  } catch (Exception $e) {
    $error_message = $e->getMessage();
  }
}

?>


<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1>Edit Profile</h1>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">

              <?php if (!empty($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Success!</strong> <?= $success_message; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Error!</strong> <?= $error_message; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              <?php endif; ?>

              <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="col-md-3">
                      <?php
                      $photo = $_SESSION['admin']['photo'] ?? '';
                      $photo_url = !empty($photo) ? BASE_URL . "uploads/$photo" : BASE_URL . "uploads/default.png";
                      ?>
                      <img src="<?php echo $photo_url; ?>" alt="Profile Photo" class="profile-photo w_100_p">
                      <input type="file" class="mt_10" name="photo" accept="image/*">
                    </div>
                  </div>
                  <div class="col-md-9">
                    <div class="mb-4">
                      <label class="form-label">Name *</label>
                      <input type="text" class="form-control" name="name"
                        value="<?php echo htmlspecialchars($_SESSION['admin']['name']); ?>" required>
                    </div>
                    <div class="mb-4">
                      <label class="form-label">Email *</label>
                      <input type="email" class="form-control" name="email"
                        value="<?php echo htmlspecialchars($_SESSION['admin']['email']); ?>" required>
                    </div>
                    <div class="mb-4">
                      <label class="form-label">Password (leave blank if unchanged)</label>
                      <input type="password" class="form-control" name="password" />
                    </div>
                    <div class="mb-4">
                      <label class="form-label">Retype Password</label>
                      <input type="password" class="form-control" name="retype_password" />
                    </div>
                    <div class="mb-4">
                      <label class="form-label"></label>
                      <button type="submit" name="form_update" class="btn btn-primary">
                        Update
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php include("layouts/footer.php"); ?>