<?php
// Include the database connection configuration
include('dbinfo.inc');

// Initialize variables
$name = "";
$room = "";

// Create a connection to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize user inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $room = mysqli_real_escape_string($conn, $_POST['room']);

    // Check for empty fields
    if (empty($name) || empty($room)) {
        // Handle validation errors, e.g., display an error message
        echo "Guest Name and Room Type are required.";
    } else {
        // Insert data into the database
        $insertQuery = "INSERT INTO reservations (guest_name, room_type) VALUES ('$name', '$room')";

        if ($conn->query($insertQuery) === TRUE) {
            // Data successfully inserted
            // Redirect to prevent form resubmission (PRG pattern)
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reservation System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h1 {
            background-color: #3498db;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .button {
            background-color: #3498db;
            color: #fff;
            padding: 1rem 2rem;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #2078a3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 1rem;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: #fff;
        }
    </style>
</head>
<body>
    <h1>Hotel Reservation System</h1>
    <div class="container">
        <h2>Make a Reservation</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="name">Guest Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>

            <div class="form-group">
                <label for="room">Room Type:</label>
                <input type="text" id="room" name="room" value="<?php echo $room; ?>" required>
            </div>

            <button class="button" type="submit" name="make_reservation">Make Reservation</button>
        </form>

        <h2>Reservation List</h2>
        <table>
            <tr>
                <th>Reservation ID</th>
                <th>Guest Name</th>
                <th>Room Type</th>
            </tr>
            <?php
            $query = "SELECT * FROM reservations";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["guest_name"] . "</td>";
                    echo "<td>" . $row["room_type"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No reservations found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
