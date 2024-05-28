document.addEventListener('DOMContentLoaded', function() {
    const viewNotesLinks = document.querySelectorAll('.view-notes-link');
    
    viewNotesLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const userId = this.getAttribute('data-user-id');
            
            fetch('../../view/note/fetch_notes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'user_id=' + userId
            })
            .then(response => response.json())
            .then(data => {
                const userNameElement = document.getElementById('user-name');
                userNameElement.textContent = `Notes of ${data.name}`;

                const notesTable = document.getElementById('notes-table');
                notesTable.innerHTML = `
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                `;
                
                if (data.notes.length > 0) {
                    data.notes.forEach(note => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${note.id}</td>
                            <td>${note.title}</td>
                            <td>${note.content}</td>
                            <td>${note.created_at}</td>
                            <td>
                                <button type="button" class="edit-button" data-id="${note.id}">Edit</button>
                                <form action="../../view/note/delete_note.php" method="post" style="display:inline;">
                                    <input type="hidden" name="note_id" value="${note.id}">
                                    <input type="hidden" name="dashboard" value="admin">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this note?');">Delete</button>
                                </form>
                            </td>
                        `;
                        notesTable.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.colSpan = 5;
                    cell.textContent = 'No notes created';
                    row.appendChild(cell);
                    notesTable.appendChild(row);
                }

                const editButtons = document.querySelectorAll('.edit-button');
                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const noteId = this.getAttribute('data-id');
                        window.location.href = `../note/edit_note.php?id=${noteId}`;
                    });
                });
            });
        });
    });
});
