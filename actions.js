function editGrade(button) {
    let row = button.closest("tr");
    let studentId = row.dataset.studentId;
    let subjectId = row.dataset.subjectId;
    let grade = row.cells[2].textContent.trim();
    let studentName = row.cells[0].textContent.trim();
    let subjectName = row.cells[1].textContent.trim();

    // Create the popup HTML with editable fields
    const popup = document.createElement("div");
    popup.classList.add("popup");
    popup.innerHTML = `
        <div class="popup-content">
            <h2>Edit Grade</h2>
            <form id="editGradeForm">
                <!-- We no longer need hidden inputs, we'll send the IDs in the JSON request -->
                <label for="editStudent">Student:</label>
                <input id="editStudent" type="text" value="${studentName}" required /><br>

                <label for="editSubject">Subject:</label>
                <input id="editSubject" type="text" value="${subjectName}" required /><br>

                <label for="editGrade">Grade:</label>
                <input id="editGrade" type="number" value="${grade}" min="1" max="10" required /><br><br>

                <button type="button" id="save">Save</button>
                <button type="button" id="cancel">Cancel</button>
            </form>
        </div>
    `;

    document.body.appendChild(popup);

    // Cancel button - remove popup
    document.getElementById("cancel").addEventListener("click", () => {
        document.body.removeChild(popup);
    });

    // Save button - send updated data to the server
    document.getElementById("save").addEventListener("click", () => {
        const newGrade = document.getElementById("editGrade").value;
        const newStudentName = document.getElementById("editStudent").value;
        const newSubjectName = document.getElementById("editSubject").value;

        if (newGrade === "" || isNaN(newGrade) || newGrade < 1 || newGrade > 10) {
            alert("The grade must be a number between 1 and 10.");
            return;
        }

        // Send updated data to the server
        fetch('update_grade.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                student_id: studentId,
                subject_id: subjectId,
                grade: newGrade,
                student_name: newStudentName,
                subject_name: newSubjectName
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.cells[2].textContent = newGrade;  // Update the grade in the table
                row.cells[0].textContent = newStudentName; // Update student name in the table
                row.cells[1].textContent = newSubjectName; // Update subject name in the table
                document.body.removeChild(popup);  // Close the popup
                alert("Grade updated successfully!");
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error: " + error.message);
        });
    });
}


// delete
function deleteGrade(button) {
    let row = button.closest("tr");
    let studentId = row.dataset.studentId;
    let subjectId = row.dataset.subjectId;

    if (confirm("Are you sure you want to delete this grade?")) {
        fetch('delete_grade.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ student_id: studentId, subject_id: subjectId })
        })
        .then(response => {
            // Check if the response is valid JSON
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error(data.error || 'Unknown error');
                }
                return data;
            });
        })
        .then(data => {
            if (data.success) {
                row.remove();
                alert("Grade deleted successfully!");
            } else {
                alert("Error: " + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("Error: " + error.message);
        });
    }
}

document.querySelectorAll(".delete").forEach(button => {
    // Ensure that the event listener is attached only once for each button
    button.addEventListener("click", function () {
        deleteGrade(button);
    });
});