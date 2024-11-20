<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'marks' and 'difficulty' are set in the POST data and are not empty
    if(isset($_POST['marks']) && isset($_POST['difficulty']) && !empty($_POST['marks']) && !empty($_POST['difficulty'])) {
        // Retrieve form data
        $marks = $_POST['marks'];
        $difficulty = $_POST['difficulty'];

        // Define CSV files for each unit
        $csv_files_30 = array(
            'unit1.csv',
            'unit2.csv'
        );

        $csv_files_70 = array(
            'unit3.csv',
            'unit4.csv',
            'unit5.csv',
            'unit6.csv'
        );

        // Determine which set of CSV files to use based on desired marks
        $csv_files = ($marks == 30) ? $csv_files_30 : $csv_files_70;

        // Initialize variables to store total marks and selected questions
        $total_marks = 0;
        $selected_questions = array();

        // Iterate through CSV files and select questions
        foreach ($csv_files as $csv_file) {
            // Read CSV file
            $rows = array_map('str_getcsv', file($csv_file));
            // Remove header row
            $header = array_shift($rows);

            // Iterate through rows to select questions based on difficulty
            foreach ($rows as $row) {
                // Assume each row contains: question, marks, difficulty
                $question = $row[0];
                $question_marks = intval($row[1]);
                $question_difficulty = $row[2];

                // Check if question matches selected difficulty
                if ($question_difficulty == $difficulty) {
                    // Check if adding this question exceeds total marks needed
                    if ($total_marks + $question_marks <= $marks) {
                        // Add question to selected questions
                        $selected_questions[] = $question;
                        // Update total marks
                        $total_marks += $question_marks;
                    }
                }

                // Check if total marks reached desired marks
                if ($total_marks >= $marks) {
                    break 2; // Break out of both foreach loops
                }
            }
        }

        // Output selected questions
        echo "<h2>Generated Question Paper</h2>";
        echo "<p>Total Marks: $marks</p>";
        echo "<p>Difficulty: $difficulty</p>";
        echo "<p>Questions:</p>";
        echo "<ul>";
        foreach ($selected_questions as $question) {
            echo "<li>$question</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Form data missing or empty!</p>";
    }
} else {
    // If form is not submitted, display error message
    echo "<p>Form not submitted!</p>";
}
?>