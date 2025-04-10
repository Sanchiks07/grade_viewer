CREATE DATABASE gradeviewer;
USE gradeviewer;

-- Students
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)
);

-- Subjects
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100)
);

-- Grades
CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject_id INT,
    grade INT,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Insert 15 students
INSERT INTO students (name) VALUES
('Emma Johnson'), ('Liam Smith'), ('Olivia Brown'), ('Noah Davis'), ('Ava Wilson'),
('Elijah Thomas'), ('Sophia Moore'), ('James Lee'), ('Isabella White'), ('Benjamin Hall'),
('Mia Clark'), ('Lucas Young'), ('Charlotte King'), ('Henry Scott'), ('Amelia Green');

-- Subjects: Math & Chemistry
INSERT INTO subjects (subject_name) VALUES ('Math'), ('Chemistry');

-- Insert random grades from 1 to 10 for each student
INSERT INTO grades (student_id, subject_id, grade) VALUES
(1, 1, 9), (1, 2, 8),
(2, 1, 6), (2, 2, 7),
(3, 1, 10), (3, 2, 9),
(4, 1, 5), (4, 2, 6),
(5, 1, 7), (5, 2, 5),
(6, 1, 8), (6, 2, 8),
(7, 1, 4), (7, 2, 7),
(8, 1, 9), (8, 2, 9),
(9, 1, 10), (9, 2, 6),
(10, 1, 3), (10, 2, 4),
(11, 1, 7), (11, 2, 6),
(12, 1, 6), (12, 2, 5),
(13, 1, 8), (13, 2, 9),
(14, 1, 5), (14, 2, 4),
(15, 1, 9), (15, 2, 10);