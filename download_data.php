<?php
// Establish database connection
try {
  $conn = new PDO('mysql:host=localhost;dbname=demo', 'root', '');
} catch (PDOException $e) {
  // handle connection error
  die('Connection failed: ' . $e->getMessage());
}

// Retrieve data from database
try {
  $stmt = $conn->prepare('SELECT * FROM users');
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  // handle query error
  die('Query failed: ' . $e->getMessage());
}

// If no results found, display error message and exit
if (count($results) == 0) {
  die('No results found.');
}

// Set headers for CSV file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="users.csv"');
header('Pragma: no-cache');
header('Expires: 0');

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

// End script execution
exit;
?>
