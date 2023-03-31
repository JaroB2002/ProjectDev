
<?php
// Establish database connection
$conn = new PDO('mysql:host=localhost;dbname=demo', 'root', '');

// Retrieve data from database
$stmt = $conn->prepare('SELECT * FROM users');
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set headers for CSV file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="users.csv"');

// Create file pointer for output
$output = fopen('php://output', 'w');

// Write headers to CSV file
fputcsv($output, array_keys($results[0]));

// Write data to CSV file
foreach ($results as $row) {
    fputcsv($output, $row);
}

// Close file pointer
fclose($output);
?>
