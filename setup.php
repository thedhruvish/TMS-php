<?php
require_once './config.php';

// Connect to MySQL server (without DB selection)
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}

$DB_exists = false;
$sql_file = './db_dump/tms-php-1.sql'; // ✅ Make sure this path and file exists

// Check if the database exists
$DB_check_query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'";
$DB_result = mysqli_query($conn, $DB_check_query);
if (mysqli_num_rows($DB_result) > 0) {
    $DB_exists = true;
}

// Handle confirmation POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {

    // Drop the existing database
    $drop_query = "DROP DATABASE `" . DB_NAME . "`";
    if (!mysqli_query($conn, $drop_query)) {
        die("❌ Error dropping database: " . mysqli_error($conn));
    }
    echo "<p>✅ Database '" . DB_NAME . "' dropped successfully.</p>";

    // Create a new database
    $create_query = "CREATE DATABASE `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!mysqli_query($conn, $create_query)) {
        die("❌ Error creating database: " . mysqli_error($conn));
    }
    echo "<p>✅ Database '" . DB_NAME . "' created successfully.</p>";

    mysqli_select_db($conn, DB_NAME);

    // Run SQL file
    if (file_exists($sql_file)) {
        $sql = file_get_contents($sql_file);


        if (mysqli_multi_query($conn, $sql)) {
            do {
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($conn));
            echo "<p>✅ SQL file executed successfully.</p>";
        } else {
            die("❌ Error executing SQL file: " . mysqli_error($conn));
        }
    } else {
        die("❌ SQL file not found at path: $sql_file");
    }

    mysqli_close($conn);
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Database Setup</title>
</head>

<body>
    <h2>Database Setup</h2>

    <?php if ($DB_exists) { ?>
        <p>⚠️ The database '<strong><?php echo DB_NAME; ?></strong>' already exists.</p>
        <form method="POST">
            <p>Do you want to <strong>drop and recreate</strong> it?</p>
            <button type="submit" name="confirm" value="yes">Yes, Drop and Recreate</button>
        </form>
    <?php } else { ?>
        <p>✅ The database '<strong><?php echo DB_NAME; ?></strong>' does not exist. Creating it...</p>
        <?php
        // Create database
        $create_query = "CREATE DATABASE `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        if (!mysqli_query($conn, $create_query)) {
            die("❌ Error creating database: " . mysqli_error($conn));
        }
        echo "<p>✅ Database created successfully.</p>";

        mysqli_select_db($conn, DB_NAME);

        // Run SQL
        if (file_exists($sql_file)) {
            $sql = file_get_contents($sql_file);

            // echo "<pre>" . $sql . "</pre>"; // Optional debug

            if (mysqli_multi_query($conn, $sql)) {
                do {
                    if ($result = mysqli_store_result($conn)) {
                        mysqli_free_result($result);
                    }
                } while (mysqli_next_result($conn));
                echo "<p>✅ SQL file executed successfully.</p>";
            } else {
                die("❌ Error executing SQL file: " . mysqli_error($conn));
            }
        } else {
            die("❌ SQL file not found at path: $sql_file");
        }

        mysqli_close($conn);
        ?>
    <?php } ?>
</body>

</html>