<?php include '../db_connect.php'; ?>

<?php 
if (isset($_GET['class_id']) && is_numeric($_GET['class_id'])) {
    $class_id = intval($_GET['class_id']); // Get the class_id safely
} else {
    echo "Invalid class ID specified.";
    exit;
}
?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
     
        <div class="card-body">
            <form action="admin/upload_excel.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">Upload Excel File</label>
                    <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx, .xls" required>
                </div>
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>"> <!-- Hidden field for class_id -->
                <div class="form-group">
           
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card-header {
        background-color: #f8f9fa;
        border-bottom: none;
    }
    .btn-primary {
        background-color: #007bff;
        color: white;
    }
</style>
