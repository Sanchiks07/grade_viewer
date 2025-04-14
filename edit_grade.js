document.addEventListener("DOMContentLoaded", function() {
    // Event delegation to handle the click on 'Edit' button dynamically
    document.querySelector('table').addEventListener('click', function(event) {
        // Check if the clicked element is the 'Edit' button
        if (event.target.classList.contains('edit')) {
            // Get the row that contains the clicked 'Edit' button
            const row = event.target.closest('tr');
            
            // Extract the current data (student, subject, and grade)
            const student = row.querySelector('td:nth-child(1)').textContent.trim();
            const subject = row.querySelector('td:nth-child(2)').textContent.trim();
            const grade = row.querySelector('td:nth-child(3)').textContent.trim();
            
            // Show the popup and pre-fill the form with current data
            showEditPopup(student, subject, grade);
        }
    });

    // Function to display the popup with pre-filled data
    function showEditPopup(student, subject, grade) {
        // Create a popup form dynamically
        const popup = document.createElement('div');
        popup.classList.add('popup');
        popup.innerHTML = `
            <div class="popup-content">
                <span class="close-btn">&times;</span>
                <h2>Edit Grade</h2>
                <form method="POST" action="update_grade.php">
                    <label for="edit_student">Student:</label>
                    <input id="edit_student" name="edit_student" value="${student}" required />

                    <label for="edit_subject">Subject:</label>
                    <input id="edit_subject" name="edit_subject" value="${subject}" required />

                    <label for="edit_grade">Grade:</label>
                    <input type="number" id="edit_grade" name="edit_grade" value="${grade}" required />

                    <button type="submit" class="save">Save</button>\
                    <button class="close-btn">Cancel</button>
                </form>
            </div>
        `;
        
        // Append the popup to the body
        document.body.appendChild(popup);

        // Close button functionality
        const closeBtn = popup.querySelector('.close-btn');
        closeBtn.addEventListener('click', function() {
            popup.remove();
        });
        
        // Prevent form submission for now, and you can handle form submission with AJAX if needed
        const form = popup.querySelector('form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const updatedStudent = form.querySelector('#edit_student').value;
            const updatedSubject = form.querySelector('#edit_subject').value;
            const updatedGrade = form.querySelector('#edit_grade').value;
            
            // Perform AJAX request here to send updated data to the server
            // Example: send updated data to the server (you need to write the update logic)
            fetch('update_grade.php', {
                method: 'POST',
                body: new URLSearchParams({
                    'edit_student': updatedStudent,
                    'edit_subject': updatedSubject,
                    'edit_grade': updatedGrade
                })
            })
            .then(response => response.json())
            .then(data => {
                // Handle response and update the table
                if (data.success) {
                    alert('Grade updated successfully!');
                    location.reload();  // Reload page to reflect the update
                } else {
                    alert('Error updating grade');
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });

            // Close the popup after submission (optional)
            popup.remove();
        });
    }
});