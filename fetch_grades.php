<?php
require_once "config.php";

$studentFilter = isset($_GET["student"]) ? $_GET["student"] : "";   // ja ir izvēlēts kkāds specifisks students, tad tas tiek saglabāts šeit
$subjectFilter = isset($_GET["subject"]) ? $_GET["subject"] : "";   // ja ir izvēlēts kkāds specifisks priekšmets, tad tas tiek saglabāts šeit

$sql = "
    SELECT
        students.name AS student_name,
        subjects.subject_name,
        grades.grade
    FROM grades
        JOIN students ON grades.student_id = students.id    /* apvieno grades tabulu ar studentu tabulu */
        JOIN subjects ON grades.subject_id = subjects.id    /* apvieno grades tabulu ar priekšmeta tabulu */
";

$conditions = [];   // saglabā visus mūsu "noteikumus"
$params = [];   // saglabā visus studenta vārdus un priekšmetus, kurus lietotājs ir izvēlējies

// ja ir izvēlēts viens specifisks students, tad "display" tikai to audzēkni
if (!empty($studentFilter)) {
    $conditions[] = "students.name = :student";
    $params[":student"] = $studentFilter;
}

// ja ir izvēlēts viens specifisks priekšmets, tad "display" tikai to priekšmetu
if (!empty($subjectFilter)) {
    $conditions[] = "subjects.subject_name = :subject";
    $params[":subject"] = $subjectFilter;
}

// ja ir izvēlēti kkādi noteikumi (audzēknis, priekšmets)
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);  // noteikumi tiek padoti lai tie tiktu attēloti (attiecīgi audzēkņi/priekšmeti) 
}

$sql .= " ORDER BY students.name, subjects.subject_name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

$students = $pdo ->query("SELECT name FROM students ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);   // visi audzēkņi tiek attēloti drop-down
$subjects = $pdo->query("SELECT subject_name FROM subjects ORDER BY subject_name")->fetchAll(PDO::FETCH_COLUMN);    // visi priekšmeti tiek attēloti drop-down
?>