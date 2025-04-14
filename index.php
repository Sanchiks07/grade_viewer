<?php 
session_start();

include 'fetch_grades.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Grade Viewer</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>📚 Student Grade Viewer</h1>

<form method="get" style="text-align: center; margin-bottom: 30px;">
  <label for="student">👤 Student:</label>
  <select name="student" id="student">
    <option value="">All Students</option>
    <?php foreach ($students as $student): ?>
      <option value="<?= htmlspecialchars($student) ?>" <?= ($student === $studentFilter) ? 'selected' : '' ?>>
        <?= htmlspecialchars($student) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label for="subject">📘 Subject:</label>
  <select name="subject" id="subject">
    <option value="">All Subjects</option>
    <?php foreach ($subjects as $subject): ?>
      <option value="<?= htmlspecialchars($subject) ?>" <?= ($subject === $subjectFilter) ? 'selected' : '' ?>>
        <?= htmlspecialchars($subject) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <button class="btn" type="submit">🔍 Filter</button>
  <a href="index.php" class="btn">🔄 Reset</a>
</form>

<h2>Add New Grade</h2>

<!-- Display error message if available -->
<?php if (isset($_SESSION['error'])): ?>
  <div class="error-message" style="color: red; text-align: center; margin-bottom: 20px;">
    <?= htmlspecialchars($_SESSION['error']); ?>
  </div>
  <?php unset($_SESSION['error']); ?> <!-- Clear the error after displaying it -->
<?php endif; ?>

<form method="POST" action="insert_grade.php" style="text-align: center; margin-bottom: 30px;">
  <label for="new_student">👤 Student:</label>
  <input name="new_student" id="new_student" required/>

  <label for="new_subject">📘 Subject:</label>
  <input name="new_subject" id="new_subject" required/>

  <label for="new_grade">📝 Grade:</label>
  <input name="new_grade" id="new_grade" type="number" required/>

  <button type="submit" class="btn">Add grade</button>
</form>

<table>
  <thead>
    <tr>
      <th>Student</th>
      <th>Subject</th>
      <th>Grade</th>
      <th>-</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($grades) > 0): ?>
      <?php foreach ($grades as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['student_name']) ?></td>
          <td><?= htmlspecialchars($row['subject_name']) ?></td>
          <td><?= htmlspecialchars($row['grade']) ?></td>
          <td>
            <button class="edit">Edit</button>
            <button class="delete">Delete</button>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="3" style="text-align:center;">No data found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<script src="edit_grade.js"></script>
</body>
</html>