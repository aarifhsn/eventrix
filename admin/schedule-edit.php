<?php include 'layouts/top.php'; ?>

<?php
if(isset($_POST['form_update'])) {
    try {
        if($_POST['name'] == '') {
            throw new Exception("Name can not be empty");
        }
        if($_POST['title'] == '') {
            throw new Exception("Title can not be empty");
        }
        if($_POST['description'] == '') {
            throw new Exception("Description can not be empty");
        }
        if($_POST['location'] == '') {
            throw new Exception("Location can not be empty");
        }
        if($_POST['time'] == '') {
            throw new Exception("Time can not be empty");
        }
        if($_POST['item_order'] == '') {
            throw new Exception("Order can not be empty");
        }
        if(!filter_var($_POST['item_order'], FILTER_VALIDATE_INT)) {
            throw new Exception("Order must be integer value");
        }

        $path = $_FILES['photo']['name'];
        $path_tmp = $_FILES['photo']['tmp_name'];
        if($path=='') {
            $filename = $_POST['current_photo'];
        } else {
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $filename = "schedule_".time().".".$extension;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $path_tmp);
            if($mime != 'image/jpeg' && $mime != 'image/png') {
                throw new Exception("Please upload a valid photo");
            }
            unlink('../uploads/'.$_POST['current_photo']);
            move_uploaded_file($path_tmp, '../uploads/'.$filename);
        }
        
        $statement = $pdo->prepare("UPDATE schedules SET 
                                schedule_day_id=?,
                                name=?,
                                title=?,
                                description=?,
                                location=?,
                                time=?,
                                photo=?,
                                item_order=?
                                WHERE id=?");
        $statement->execute([
                            $_POST['schedule_day_id'],
                            $_POST['name'],
                            $_POST['title'],
                            $_POST['description'],
                            $_POST['location'],
                            $_POST['time'],
                            $filename,
                            $_POST['item_order'],
                            $_REQUEST['id']
                            ]);

        $_SESSION['success_message'] = "Data update is successful";
        header("location: ".ADMIN_URL."schedule.php");
        exit;

    } catch(Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error_message'] = $error_message;
        header("location: ".ADMIN_URL."schedule-edit.php?id=".$_REQUEST['id']);
        exit;
    }
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM schedules WHERE id=?");
$statement->execute([$_REQUEST['id']]);
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Schedule</h1>
            <div class="ml-auto">
                <a href="<?php echo ADMIN_URL; ?>schedule.php" class="btn btn-primary"><i class="fas fa-eye"></i> View All</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="current_photo" value="<?php echo $result[0]['photo']; ?>">
                                <div class="form-group mb-3">
                                    <label>Existing Photo</label>
                                    <div>
                                        <img src="<?php echo BASE_URL; ?>uploads/<?php echo $result[0]['photo']; ?>" alt="" class="w_200">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Change Photo</label>
                                    <div>
                                        <input type="file" name="photo">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Name *</label>
                                            <input type="text" name="name" class="form-control" value="<?php echo $result[0]['name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control" value="<?php echo $result[0]['title']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Select Schedule Day *</label>
                                            <select name="schedule_day_id" class="form-select">
                                                <?php
                                                $q = $pdo->prepare("SELECT * FROM schedule_days ORDER BY order1 ASC");
                                                $q->execute();
                                                $result1 = $q->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($result1 as $row1) {
                                                    ?>
                                                    <option value="<?php echo $row1['id']; ?>" <?php echo ($row1['id'] == $result[0]['schedule_day_id']) ? 'selected' : ''; ?>><?php echo $row1['day']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Description *</label>
                                    <textarea name="description" class="form-control h_200" cols="30" rows="10"><?php echo $result[0]['description']; ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Location *</label>
                                            <input type="text" name="location" class="form-control" value="<?php echo $result[0]['location']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Time *</label>
                                            <input type="text" name="time" class="form-control" value="<?php echo $result[0]['time']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label>Order *</label>
                                            <input type="text" name="item_order" class="form-control" value="<?php echo $result[0]['item_order']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="form_update">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>