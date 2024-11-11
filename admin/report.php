<?php
include 'db_connect.php';

function ordinal_suffix($num) {
    $num = $num % 100;
    if ($num < 11 || $num > 13) {
        switch ($num % 10) {
            case 1: return $num . 'st';
            case 2: return $num . 'nd';
            case 3: return $num . 'rd';
        }
    }
    return $num . 'th';
}
?>
<style>
.bg-gradient-secondary {
    background: #B31B1C linear-gradient(182deg, #b31b1b, #dc3545) repeat-x !important;
    color: #fff;
}
.user-icon {
    display: flex;
    align-items: center;
    margin: 15px 0;
}
.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    border: 1px solid rgba(0, 0, 0, .2);
    border-radius: .3rem;
    box-shadow: 0 .25rem .5rem rgba(0, 0, 0, .5);
}
.card-body {
    display: flex;
    align-items: center;
}
.font-weight-bold {
    font-weight: bold;
}
.card-text {
    margin: 0;
    font-size: 22px;
}
.card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.btn-info {
    margin-top: 15px;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn-info:hover {
    background-color: black;
    border-color: black;
}
.callout.callout-info{
    border-left-color: #dc143c;
}
.card{
    background-color: #dc143c;
}
.ml-3{
    color: white;
}
.ml-3 h5{
    font-size: 24px;
}
</style>
<div class="col-lg-12">
    <div class="callout callout-info">
        <div class="input-group mb-3" style="max-width: 40%; margin-left: auto;">
            <input type="text" id="search-input" class="form-control" placeholder="Search...">
            <div class="input-group-append">
                <span class="input-group-text">Search</span>
            </div>
        </div>
        <div class="text-right mb-3 d-flex align-items-center justify-content-end">
            <span class="mr-2 font-weight-bold">View evaluation result for teacher:</span>
            <button id="toggle-status-btn" class="btn btn-primary">View Status</button>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Faculty Name</th>
                    <th>Academic Year</th>
                    <th>Subject</th>
                    <th>Student Evaluated</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="evaluation-table">
                <?php 
                $faculty = $conn->query("SELECT f.id, 
                                                CONCAT(f.firstname, ' ', f.lastname) AS faculty_name, 
                                                f.avatar,
                                                r.academic_id, 
                                                a.year AS academic_year, 
                                                r.class_id, 
                                                cl.curriculum, 
                                                CONCAT(cl.level, ' - ', cl.section) AS class_details, 
                                                r.subject_id, 
                                                sl.subject,
                                                CONCAT(st.firstname, ' ', st.lastname) AS student_name,
                                                r.status
                                        FROM faculty_list f
                                        LEFT JOIN evaluation_list r ON r.faculty_id = f.id
                                        LEFT JOIN class_list cl ON r.class_id = cl.id
                                        LEFT JOIN subject_list sl ON r.subject_id = sl.id
                                        LEFT JOIN student_list st ON r.student_id = st.id
                                        LEFT JOIN academic_list a ON r.academic_id = a.id
                                        WHERE r.academic_id = {$_SESSION['academic']['id']}
                                        ORDER BY CONCAT(f.firstname, ' ', f.lastname) ASC");

                while ($row = $faculty->fetch_assoc()): 
                    $avatar = !empty($row['avatar']) ? 'assets/uploads/' . $row['avatar'] : 'assets/uploads/default_avatar.png';
                ?>
                <tr>
                    <td><?php echo ucwords($row['faculty_name']); ?></td>
                    <td><?php echo $row['academic_year'] . ' ' . ordinal_suffix($_SESSION['academic']['semester']) . ' Semester'; ?></td>
                    <td><?php echo $row['subject']; ?></td>
                    <td><?php echo ucwords($row['student_name']); ?></td>
                    <td><span class="evaluation-status"><?php echo ucwords($row['status']); ?></span></td>
                    <td>
                        <button class="btn btn-info view-report" data-id="<?php echo $row['id']; ?>" data-subject-id="<?php echo $row['subject_id']; ?>" data-class-id="<?php echo $row['class_id']; ?>">
                            <i class="fa fa-eye"></i> 
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div id="pagination-controls" class="text-center mt-3">
            <button id="prev-page" class="btn btn-secondary">Previous</button>
            <button id="next-page" class="btn btn-secondary">Next</button>
        </div>
    </div>
</div>

<div class="modal fade" id="report-modal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Evaluation Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="report-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const itemsPerPage = 6;
        let currentPage = 1;

        function showPage(page) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            $('#evaluation-table tr').hide();
            $('#evaluation-table tr').slice(start, end).show();
            $('#prev-page').toggle(page > 1);
            $('#next-page').toggle($('#evaluation-table tr:visible').length === itemsPerPage);
        }

        showPage(currentPage);

        $('#prev-page').click(function() {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
            }
        });

        $('#next-page').click(function() {
            currentPage++;
            showPage(currentPage);
        });

        $('#search-input').on('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            $('#evaluation-table tr').each(function() {
                const facultyName = $(this).find('td').eq(1).text().toLowerCase();
                $(this).toggle(facultyName.includes(searchTerm));
            });
            currentPage = 1;
            showPage(currentPage);
        });

        $('#toggle-status-btn').click(function() {
            const newStatus = $(this).text() === 'Activate All' ? 'active' : 'pending';
            $.ajax({
                url: 'ajax.php?action=toggle_status',
                method: 'POST',
                data: { status: newStatus },
                success: function(response) {
                    $('.evaluation-status').text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
                    $('#toggle-status-btn').text(newStatus === 'active' ? 'Deactivate All' : 'Activate All');
                },
                error: function(err) {
                    console.error('Error toggling status:', err);
                }
            });
        });

        $('.view-report').click(function() {
            var faculty_id = $(this).data('id');
            var subject_id = $(this).data('subject-id');
            var class_id = $(this).data('class-id');

            $.ajax({
                url: 'ajax.php?action=view_report',
                method: 'POST',
                data: {
                    faculty_id: faculty_id,
                    subject_id: subject_id,
                    class_id: class_id
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    displayReport(data);
                },
                error: function(err) {
                    console.error('Error fetching report:', err);
                }
            });
        });

        function displayReport(data) {
            var reportHtml = `<h6>Total Students Evaluated: ${data.tse}</h6>`;
            reportHtml += `<h3>Overall Average Rating: ${data.averageRating}/5</h3>`;
            $('#report-content').html(reportHtml);
            $('#report-modal').modal('show');
        }
    });
</script>
