<?php
include 'database.php';

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Create book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create"])) {
    $isbn = sanitize_input($_POST["isbn"]);
    $title = sanitize_input($_POST["title"]);
    $author = sanitize_input($_POST["author"]);
    $edition = intval($_POST["edition"]);
    $year = intval($_POST["year"]);
    $categoryID = sanitize_input($_POST["categoryID"]);
    $reserved = sanitize_input($_POST["reserved"]);

    $sql = "INSERT INTO books (ISBN, title, author, edition, year, categoryID, reserved) 
            VALUES ('$isbn', '$title', '$author', $edition, $year, '$categoryID', '$reserved')";
    $conn->query($sql);
}

// View all books
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

// Update book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $isbn = sanitize_input($_POST["isbn"]);
    $title = sanitize_input($_POST["title"]);
    $author = sanitize_input($_POST["author"]);
    $edition = intval($_POST["edition"]);
    $year = intval($_POST["year"]);
    $categoryID = sanitize_input($_POST["categoryID"]);
    $reserved = sanitize_input($_POST["reserved"]);

    $sql = "UPDATE books 
            SET title='$title', author='$author', edition=$edition, year=$year, categoryID='$categoryID', reserved='$reserved' 
            WHERE ISBN='$isbn'";
    $conn->query($sql);
}

// Delete book
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["delete"])) {
    $isbn = sanitize_input($_GET["delete"]);

    $sql = "DELETE FROM books WHERE ISBN='$isbn'";
    $conn->query($sql);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<div class="navbar">
    <a href="index.php" style="float:right;">Logout</a>
</div>

<h2>Create Book</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    ISBN: <input type="text" name="isbn" required><br>
    Title: <input type="text" name="title" required><br>
    Author: <input type="text" name="author" required><br>
    Edition: <input type="number" name="edition" required><br>
    Year: <input type="number" name="year" required><br>
    Category ID: <input type="text" name="categoryID"><br>
    Reserved: <input type="text" name="reserved" required><br>
    <input type="submit" name="create" value="Create Book">
</form>

<h2>View All Books</h2>
<table>
    <tr>
        <th>ISBN</th>
        <th>Title</th>
        <th>Author</th>
        <th>Edition</th>
        <th>Year</th>
        <th>Category ID</th>
        <th>Reserved</th>
        <th>Action</th>
    </tr>
    <?php
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["ISBN"] . "</td>";
        echo "<td>" . $row["title"] . "</td>";
        echo "<td>" . $row["author"] . "</td>";
        echo "<td>" . $row["edition"] . "</td>";
        echo "<td>" . $row["year"] . "</td>";
        echo "<td>" . $row["categoryID"] . "</td>";
        echo "<td>" . $row["reserved"] . "</td>";
        echo "<td><a href='?delete=" . $row["ISBN"] . "'>Delete</a> | <a href='update.php?isbn=" . $row["ISBN"] . "'>Update</a></td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>