<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h1 class="mb-4">Employee Information</h1>
    <p class="mb-4">Please enter the new employee's information below.</p>
    <form id="addEmployeeForm">
      <div class="row mb-3">
        <label for="employeeFirstname" class="form-label">First Name</label>
        <input type="text" class="form-control" id="employeeFirstname" name="firstname" placeholder="Enter first name">
      </div>
      <div class="row mb-3">
        <label for="employeeLastname" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="employeeLastname" name="lastname" placeholder="Enter last name">
      </div>
      <div class="row mb-3">
        <label for="employeeTitle" class="form-label">Title</label>
        <input type="text" class="form-control" id="employeeTitle" name="title" placeholder="Enter employee title">
      </div>
      <button type="submit" class="btn btn-primary">Create Employee</button>
    </form>

    <form style="display: none" method="POST" id="ghostForm">
      <input id="ghostFirstname" name="firstname">
      <input id="ghostLastname" name="lastname">
      <input id="ghostTitle" name="title">
      <input id="ghostDelete" name="delete" value="false">
    </form>
<?php
include 'database.php';

// Connect to the workson database using mysqli and exception handling
$conn = connect("workson", "WorksOnDanger", "Bunratty+44");

// Determine max ID value in DB so far
$nextId = getNextID($conn);

// Do we have data submitted from the form?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the data from the form
  $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
  $lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
  $title = isset($_POST['title']) ? $_POST['title'] : '';
  $deleteID = isset($_POST['delete']) ? $_POST['delete'] : 'false';

  // If delete is true, delete the employee
  if ($deleteID != 'false') {
    deleteEmployee($conn, $deleteID);
  } else {
    // Output error if any of the fields are empty
    if (empty($firstname) || empty($lastname) || empty($title)) {
      ?>
      <div class="alert alert-danger mt-4" role="alert">
          <b>Insert Error:</b><br>
          All fields are required.
      </div>
      <?php
    } else {
      // Attempt to insert the new employee
      insertUnSafe($conn, $nextId, $firstname, $lastname, $title);
      // insertSafe($conn, $nextId, $firstname, $lastname, $title);
    }
  }
}

// Display all employees in a table
$result = getAllEmployees($conn);
?>
    <table class='table table-striped mt-4'>
      <thead>
        <tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Title</th><th style="text-align: right;">Delete</th></tr>
      </thead>
      <tbody>
<?php
if (isset($result) && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    ?>
    <tr>
      <td><?=$row["id"]?></td>
      <td><?=$row["firstname"]?></td>
      <td><?=$row["lastname"]?></td>
      <td><?=$row["title"]?></td>
      <td style="text-align: right;">
        <i
          class="bi bi-trash3-fill text-danger"
          style="cursor: pointer;"
          onclick="deleteEmployee('<?=$row['firstname']?>', '<?=$row['lastname']?>', <?=$row['id']?>)">
        </i>
      </td>
    </tr>
    <?php
  }
}
?>
      </tbody>
    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const form = document.getElementById('addEmployeeForm');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      const formData = new FormData(form);
      for (const pair of formData.entries()) {
        if (pair[1] === "") {
          window.alert("All fields must be filled out");
          return;
        }
      }

      if (window.confirm("Insert new employee?")) {
        document.getElementById('ghostFirstname').value = formData.get('firstname');
        document.getElementById('ghostLastname').value = formData.get('lastname');
        document.getElementById('ghostTitle').value = formData.get('title');
        document.getElementById('ghostDelete').value = 'false';
        document.getElementById('ghostForm').submit();
      }
    });

    function deleteEmployee(firstName, lastName, id) {
      if (window.confirm(`Delete "${firstName} ${lastName}" from the database?`)) {
        document.getElementById('ghostDelete').value = id;
        document.getElementById('ghostForm').submit();
      }
    }
  </script>
</body>
</html>
