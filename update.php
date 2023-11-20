<?php
include 'database.php';

// Define sanitize_input function
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if ISBN is set in the URL
$isbn = isset($_GET["isbn"]) ? sanitize_input($_GET["isbn"]) : "";

if (!empty($isbn)) {
    $sql = "SELECT * FROM books WHERE ISBN='$isbn'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        // Handle the case when ISBN is not found
        echo "Book not found.";
        exit();
    }
} else {
    // Handle the case when ISBN is not set
    echo "ISBN is not set.";
    exit();
}

// Check if the form is submitted for updating
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    // Process the update here
    $newTitle = sanitize_input($_POST["title"]);
    $newAuthor = sanitize_input($_POST["author"]);
    $newEdition = intval($_POST["edition"]);
    $newYear = intval($_POST["year"]);
    $newCategoryID = sanitize_input($_POST["categoryID"]);
    $newReserved = sanitize_input($_POST["reserved"]);

    $updateSql = "UPDATE books 
                  SET title='$newTitle', author='$newAuthor', edition=$newEdition, year=$newYear, 
                      categoryID='$newCategoryID', reserved='$newReserved' 
                  WHERE ISBN='$isbn'";
    
    if ($conn->query($updateSql) === TRUE) {
        // Redirect to admin page after successful update
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating book: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Book</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<h2>Update Book</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?isbn=" . $row["ISBN"]); ?>">
    ISBN: <input type="text" name="isbn" value="<?php echo $row["ISBN"]; ?>" readonly><br>
    Title: <input type="text" name="title" value="<?php echo $row["title"]; ?>" required><br>
    Author: <input type="text" name="author" value="<?php echo $row["author"]; ?>" required><br>
    Edition: <input type="number" name="edition" value="<?php echo $row["edition"]; ?>" required><br>
    Year: <input type="number" name="year" value="<?php echo $row["year"]; ?>" required><br>
    Category ID: <input type="text" name="categoryID" value="<?php echo $row["categoryID"]; ?>"><br>
    Reserved: <input type="text" name="reserved" value="<?php echo $row["reserved"]; ?>" required><br>
    <input type="submit" name="update" value="Update Book">
</form>

</body>
</html>