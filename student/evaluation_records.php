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

<div class="col-lg-12">
    <div class="callout callout-info">
        <span style="color: #dc143c"><h3 class="text-center" style="font-weight: bold;">List of teachers you've evaluated</h3></span>

        <!-- Search Bar -->
        <div class="input-group mb-3" style="max-width: 20%; margin-left: auto;">
            <input type="text" class="form-control" id="search-input" placeholder="Search..." aria-label="Search">
            <div class="input-group-append">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
            </div>
        </div>

        <div id="evaluation-cards" class="row">
            <?php 
            $student_id = $_SESSION['login_id'];

            // Fetch unique evaluations for the logged-in student
            $evaluations = $conn->query("SELECT DISTINCT 
                CONCAT(f.lastname, ', ', f.firstname) AS faculty_name,
                sl.subject,
                a.year AS academic_year,
                CONCAT(cl.level, ' - ', cl.section) AS class_details,
                cl.curriculum,
                r.faculty_id,
                f.avatar
            FROM evaluation_list r
            LEFT JOIN subject_list sl ON r.subject_id = sl.id
            LEFT JOIN faculty_list f ON r.faculty_id = f.id
            LEFT JOIN class_list cl ON r.class_id = cl.id
            LEFT JOIN academic_list a ON r.academic_id = a.id
            WHERE r.student_id = '$student_id'
            GROUP BY r.student_id, f.id, sl.subject, a.year, cl.id
            ORDER BY f.lastname ASC");

            while ($row = $evaluations->fetch_assoc()): 
                $avatar = !empty($row['avatar']) ? 'assets/uploads/' . $row['avatar'] : 'assets/uploads/default_avatar.png';
            ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="user-icon bg-gradient-secondary d-flex justify-content-center align-items-center" style="width: 100px; height: 100px; border-radius: 50%;">
                                <img src="<?php echo $avatar; ?>" alt="Avatar" class="user-img border" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            </div>
                            <div class="ml-3">
                                <h5 class="card-title mb-0"><?php echo ucwords($row['faculty_name']); ?></h5>
                                <p class="card-text mb-1">Subject: <?php echo $row['subject']; ?></p>
                                <p class="card-text mb-1">Academic Year: <?php echo $row['academic_year'] . ' ' . ordinal_suffix($_SESSION['academic']['semester']) . ' Semester'; ?></p>
                                <p class="card-text mb-0">Class: <?php echo $row['curriculum'] . ' (' . $row['class_details'] . ')'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Rows per page selector and pagination controls here -->
        <div class="mb-3" style="width: 100px; margin-left: auto; float: left;">
            <select id="rows-per-page" class="form-control">
                <option value="5">5 rows</option>
                <option value="10">10 rows</option>
                <option value="15">15 rows</option>
                <option value="20">20 rows</option>
            </select>
        </div>
        <div id="pagination-controls" class="mt-3 d-flex justify-content-end"></div>
    </div>
</div>

<style>
    .bg-gradient-secondary {
        background: #B31B1C linear-gradient(182deg, #b31b1b, #dc3545) repeat-x !important;
        color: #fff;
    }
    .user-icon {
        background-color: #6c757d;
        border-radius: 50%;
    }
    .input-group, #rows-per-page {
        margin-bottom: 10px;
    }
    #pagination-controls {
        display: flex;
        align-items: center;
    }
    #pagination-controls button {
        margin: 0 5px;
        border: none;
        padding: 5px 10px;
        background-color: #007bff;
        color: #fff;
        border-radius: 3px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    #pagination-controls button.active {
        background-color: #007bff;
    }
    #pagination-controls button:disabled {
        background-color: #d6d6d6;
        cursor: not-allowed;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden; /* To keep corners rounded */
        transition: box-shadow 0.3s ease;
        background-color: #fff; /* Modern card background */
    }
    .card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .callout.callout-info{
        border-left-color: #dc143c !important;
    }

    .card-body{
        background-color: #dc143c !important;
    }

    .ml-3{
        color: white;
    }

    .card-body{
        display: flex;
        align-items: center;
    }

</style>

<script>
    $(document).ready(function() {
        let rowsPerPage = 5;
        let currentPage = 1;

        $('#search-input').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            filterCards(value);
        });

        $('#rows-per-page').on('change', function() {
            rowsPerPage = parseInt($(this).val());
            currentPage = 1;
            paginateCards();
        });

        function filterCards(query) {
            $('#evaluation-cards .card').each(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(query) > -1);
            });
            paginateCards();
        }

        function paginateCards() {
            const cards = $('#evaluation-cards .card');
            const totalCards = cards.length;
            const totalPages = Math.ceil(totalCards / rowsPerPage);
            cards.hide();

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            cards.slice(start, end).show();

            renderPaginationControls(totalPages);
        }

        function renderPaginationControls(totalPages) {
            $('#pagination-controls').empty();

            const prevButton = $('<button></button>')
                .text('Previous')
                .prop('disabled', currentPage === 1)
                .on('click', function() {
                    if (currentPage > 1) {
                        currentPage--;
                        paginateCards();
                    }
                });

            const nextButton = $('<button></button>')
                .text('Next')
                .prop('disabled', currentPage === totalPages)
                .on('click', function() {
                    if (currentPage < totalPages) {
                        currentPage++;
                        paginateCards();
                    }
                });

            $('#pagination-controls').append(prevButton);

            for (let i = 1; i <= totalPages; i++) {
                const btn = $('<button></button>')
                    .text(i)
                    .addClass(i === currentPage ? 'active' : '')
                    .on('click', function() {
                        currentPage = i;
                        paginateCards();
                    });
                $('#pagination-controls').append(btn);
            }

            $('#pagination-controls').append(nextButton);
        }

        paginateCards();
    });
</script>
