<?php
function connect($dbName, $dbUser, $dbPassword) {
    $conn = null;
    try {
        $conn = new mysqli("database", $dbUser, $dbPassword, $dbName);
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
    } catch (Exception $e) {
        ?>
        <div class="alert alert-danger mt-4" role="alert">
            <b>Connection Failed:</b><br>
            <?php echo $e->getMessage(); ?>
        </div></body></html>
        <?php
        exit();
    }

    return $conn;
}

function getNextID($conn) {
    try {
        $sql = "SELECT MAX(id) AS max_id FROM employee";
        $result = $conn->query($sql);
        if ($result->num_rows != 1) {
            throw new Exception("Next ID Could not be found: " . $conn->connect_error);
        }

        $row = $result->fetch_assoc();
        return $row["max_id"] + 1;
    } catch (Exception $e) {
        ?>
        <div class="alert alert-danger mt-4" role="alert">
            <b>Database Error</b><br>
            <?php echo $e->getMessage(); ?>
        </div></body></html>
        <?php
        return -1;
    }
}

function getAllEmployees($conn) {
    // Setup select query
    $sql =<<<EOQ
    SELECT id, firstname, lastname, title FROM employee
    ORDER BY id DESC LIMIT 50;
    EOQ;

    // Retrieve all employees from the employee table using a prepared query
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    } catch (Exception $e) {
        ?>
        <div class="alert alert-danger mt-4" role="alert">
            <b>Database Error:</b><br>
            <?php echo $e->getMessage(); ?>
        </div>
        <?php
    }
}

function deleteEmployee($conn, $id) {
    // Setup delete query
    $sql = "DELETE FROM employee WHERE id = ?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("d", $id);
        $stmt->execute();
        ?>
        <div class="alert alert-success mt-4" role="alert">
            Employee deleted successfully.
        </div>
        <?php
    } catch (Exception $e) {
        ?>
        <div class="alert alert-danger mt-4" role="alert">
            <b>Error deleting employee:</b><br>
            <?php echo $e->getMessage(); ?>
        </div>
        <?
    } finally {
        if (isset($stmt)) $stmt->close();
    }
}

function insertSafe($conn, &$nextId, &$firstname, &$lastname, &$title) {
    try {
        $sql = "INSERT INTO employee (id, firstname, lastname, title) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsss", $nextId, $firstname, $lastname, $title);
        $stmt->execute();
        ?>
        <div class="alert alert-success mt-4" role="alert">
            Employee created successfully.
        </div>
        <?php
    } catch (Exception $e) {
        ?>
        <div class="alert alert-danger mt-4" role="alert">
            <b>Error creating employee:</b><br>
            <?php echo $e->getMessage(); ?>
        </div>
        <?php
    } finally {
        if (isset($stmt)) $stmt->close();
    }
}

function insertUnSafe($conn, $nextId, $firstname, $lastname, $title) {
    try {
        // Use multi query to allow for SQL injection (yes, hence the "unsafe")
        $sql = "INSERT INTO employee (id, title, lastname, firstname) VALUES ($nextId, '$title', '$lastname', '$firstname')";
        if ($conn->multi_query($sql) === TRUE) {
            ?>
            <div class="alert alert-success mt-4" role="alert">
                Employee created successfully.
                <i class="bi bi-filetype-sql float-end" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#sqlCollapse" aria-expanded="false" aria-controls="sqlCollapse"></i>
                <div class="collapse mt-2" id="sqlCollapse">
                    <div class="card card-body">
                        <pre><code><?=$sql?></code></pre>
                    </div>
                </div>
            </div>
            <?php

            // Clear the results from any extra commands
            while ($conn->more_results()) {
                $conn->next_result();
            }
        } else {
            throw new Exception("Query error");
        }
    } catch (Exception $e) {
        ?>
        <div class="alert alert-danger mt-4" role="alert">
            <b>Error with query:</b></br>
            <?php echo $e->getMessage(); ?>
            <i class="bi bi-filetype-sql float-end" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#sqlCollapse2" aria-expanded="false" aria-controls="sqlCollapse2"></i>
            <div class="collapse mt-2" id="sqlCollapse2">
                <div class="card card-body">
                    <pre><code><?=$sql?></code></pre>
                </div>
            </div>
        </div>
        <?php

        // Clear the results from any extra commands
        while ($conn->more_results()) {
            $conn->next_result();
        }
    }
}
