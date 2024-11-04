<div class="nav-bar">
    <a href="./index.php?page=category" class="nav-link nav-category <?php echo (isset($_GET['page']) && $_GET['page'] == 'category') ? 'active' : ''; ?>">Category</a>
    <a href="./index.php?page=semester" class="nav-link nav-semester <?php echo (isset($_GET['page']) && $_GET['page'] == 'semester') ? 'active' : ''; ?>">Semester</a>
    <a href="#" class="nav-link nav-questionnaire <?php echo (isset($_GET['page']) && $_GET['page'] == 'questionnaire') ? 'active' : ''; ?>" id="questionnaireLink">Questionnaire</a>
</div>

<div class="center">
    <div class="card">
        <h3>Select a Semester</h3>
        <select id="semesterDropdown">
            <option value="">Select a Semester</option>
            <?php
            $qry = $conn->query("SELECT * FROM academic_list ORDER BY ABS(year) DESC, ABS(semester) DESC");
            while ($row = $qry->fetch_assoc()):
            ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['year'] . " - Semester " . $row['semester']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button id="manageQuestionnaireButton" disabled>Manage Questionnaire</button>
    </div>
</div>

<style>
    /* Center container styling */
    .center {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 60vh;
        background-color: #f0f2f5;
        font-family: 'Arial', sans-serif;
    }

    /* Card styling */
    .card {
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 400px;
        width: 100%;
        text-align: center;
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    /* Heading styling */
    .card h3 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 20px;
    }

    /* Dropdown styling */
    #semesterDropdown {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ddd;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 1rem;
        background: #fafafa;
        transition: border-color 0.3s;
    }

    #semesterDropdown:focus {
        border-color: #007bff;
        outline: none;
    }

    /* Button styling */
    #manageQuestionnaireButton {
        background-color: #dc143c;
        color: #ffffff;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s, transform 0.2s;
        width: 100%;
    }

    #manageQuestionnaireButton:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }

    #manageQuestionnaireButton:not(:disabled):hover {
        background-color: #0056b3;
        transform: translateY(-3px);
    }

    #manageQuestionnaireButton:not(:disabled):active {
        transform: translateY(-1px);
    }
    .card-header {
    background-color: transparent;
    border-bottom: none;
}
    /*.nav-bar {
        display: flex;
        justify-content: space-around;
        background: #f4f4f4;
        padding: 10px 0;
        
    }

    .nav-bar a {
        text-decoration: none;
        padding: 10px 15px;
        color: #333;
      
    }

    .nav-bar a.active {
        font-weight: bold;
        border-bottom: 2px solid blue;
        font-family: tahoma;
    } */
    .nav-bar {
        display: flex; 
    }

    .nav-link {
        margin: 0 10px;
        text-decoration: none;
        color: black;
    }

    .nav-link.active {
        font-weight: bold;
        color: #dc143c;
        border-bottom: 2px solid #007bff;
        margin-bottom: 10px; 
    }

    .content-container {
        margin-top: 20px;
        padding: 20px;
    }

    .card button {
        padding: 10px 15px;
        background-color: #dc143c;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .card button:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    .card button:not(:disabled):hover {
        background-color: darkblue;
    }
    .bg-gradient-primary {
    background: #dc143c linear-gradient(180deg, #dc143c, #dc143c) repeat-x !important;
    color: #fff;
    
}
.bg-gradient-secondary {
    background: #000000 linear-gradient(180deg, #000000, #6c757d) repeat-x !important;
    color: #fff;
}
</style>

<script>
    const semesterDropdown = document.getElementById("semesterDropdown");
    const manageButton = document.getElementById("manageQuestionnaireButton");
    const questionnaireLink = document.getElementById("questionnaireLink");

    semesterDropdown.addEventListener("change", function() {
        manageButton.disabled = !semesterDropdown.value;
        // Enable the questionnaire link if a semester is selected
        if (semesterDropdown.value) {
            questionnaireLink.href = `index.php?page=manage_questionnaire&id=${semesterDropdown.value}`;
            questionnaireLink.style.pointerEvents = 'auto'; // Allow click
            questionnaireLink.style.color = ''; // Reset link color
        } else {
            questionnaireLink.href = '#'; // Disable link
            questionnaireLink.style.pointerEvents = 'none'; // Prevent click
            questionnaireLink.style.color = '#ccc'; // Change color to indicate disabled
        }
    });

    manageButton.addEventListener("click", function() {
        const selectedSemester = semesterDropdown.value;
        if (selectedSemester) {
            window.location.href = `index.php?page=manage_questionnaire&id=${selectedSemester}`;
        } else {
            alert('Please select a semester first.');
        }
    });
</script>
