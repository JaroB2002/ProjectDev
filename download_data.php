
<?php
// Establish database connection
$conn = new PDO('mysql:host=localhost;dbname=demo', 'root', '');

// Retrieve data from database
$stmt = $conn->prepare('SELECT * FROM users');
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

