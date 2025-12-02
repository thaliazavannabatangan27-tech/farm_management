<?php
session_start();
require_once "includes/db_connect.php";
if (!isset($_SESSION['id'])) {
    exit;
}

$res = mysqli_query($conn, "SELECT id, username, name, email, role FROM users ORDER BY id DESC");
?>

<style>
    h2 {
        margin-top: 0;
        font-family: Arial, sans-serif;
    }

    button {
        padding: 6px 12px;
        margin: 3px;
        cursor: pointer;
        font-family: Arial, sans-serif;
    }

    button.add {
        background-color: #0b4d1b;
        color: white;
        border: none;
        border-radius: 3px;
    }

    button.edit {
        background-color: #ffc107;
        color: black;
        border: none;
        border-radius: 3px;
    }

    button.delete {
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 3px;
    }

    .back-link {
        display: inline-block;
        margin-top: 5px;
        margin-bottom: 10px;
        color: #0b4d1b;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-family: Arial, sans-serif;
    }

    th,
    td {
        padding: 8px;
        border: 1px solid #ccc;
        text-align: left;
    }

    th {
        background-color: #f4f4f4;
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fff;
        margin: 80px auto;
        padding: 20px;
        border: 1px solid #888;
        width: 400px;
        border-radius: 5px;
        position: relative;
        font-family: Arial, sans-serif;
    }

    .modal .close {
        color: #aaa;
        float: right;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }

    .modal input[type="text"],
    .modal input[type="email"],
    .modal input[type="password"],
    .modal select {
        width: 100%;
        padding: 6px 8px;
        margin: 5px 0 10px 0;
        display: block;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-sizing: border-box;
    }

    .modal button.submit {
        background-color: #0b4d1b;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 3px;
        cursor: pointer;
    }

    .modal button.cancel {
        background-color: #ccc;
        color: black;
        border: none;
        padding: 8px 12px;
        border-radius: 3px;
        cursor: pointer;
    }
</style>

<h2>Users</h2>
<button class="add" id="openAddModal">Add User</button>

<table id="usersTable">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php while ($u = mysqli_fetch_assoc($res)): ?>
        <tr data-id="<?php echo $u['id']; ?>">
            <td><?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['username']); ?></td>
            <td><?php echo htmlspecialchars($u['name']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td><?php echo htmlspecialchars($u['role']); ?></td>
            <td>
                <button class="edit" data-id="<?php echo $u['id']; ?>">Edit</button>
                <button class="delete" data-id="<?php echo $u['id']; ?>">Delete</button>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close" data-modal="addModal">&times;</span>
        <h3>Add User</h3>
        <form id="addForm">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Name</label>
            <input type="text" name="name" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>Role</label>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit" class="submit">Add</button>
            <button type="button" class="cancel" data-modal="addModal">Cancel</button>
        </form>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" data-modal="editModal">&times;</span>
        <h3>Edit User</h3>
        <form id="editForm">
            <input type="hidden" name="id">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Name</label>
            <input type="text" name="name" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Role</label>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit" class="submit">Save Changes</button>
            <button type="button" class="cancel" data-modal="editModal">Cancel</button>
        </form>
    </div>
</div>


<div id="deleteModal" class="modal">
    <div class="modal-content">
        <span class="close" data-modal="deleteModal">&times;</span>
        <h3>Delete User</h3>
        <p>Are you sure you want to delete the user with ID: <strong id="deleteUserIdDisplay"></strong>?</p>
        <form id="deleteForm">
            <input type="hidden" name="id">
            <button type="submit" class="delete">Confirm Delete</button>
            <button type="button" class="cancel" data-modal="deleteModal">Cancel</button>
        </form>
    </div>
</div>

<script>
    
    function openModal(modalId, userId = null) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        modal.style.display = "block";

        if (userId) {
            if (modalId === 'editModal') {
                const row = document.querySelector(`table tr[data-id="${userId}"]`);
                if (row) {
                    const form = modal.querySelector('form');
                    form.id.value = userId;
                    form.username.value = row.cells[1].innerText.trim();
                    form.name.value = row.cells[2].innerText.trim();
                    form.email.value = row.cells[3].innerText.trim();
                    form.role.value = row.cells[4].innerText.toLowerCase().trim();
                }
            }
            if (modalId === 'deleteModal') {
                modal.querySelector('input[name="id"]').value = userId;
                document.getElementById('deleteUserIdDisplay').innerText = userId;
            }
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = "none";
    }

    document.addEventListener('click', function(e) {
        let userId;

        if (e.target.id === 'openAddModal') {
            openModal('addModal');
            e.preventDefault();
        }

        if (e.target.classList.contains('edit')) {
            userId = e.target.getAttribute('data-id');
            if (userId) openModal('editModal', userId);
        }

        if (e.target.classList.contains('delete') && e.target.closest('#usersTable')) {
            userId = e.target.getAttribute('data-id');
            if (userId) openModal('deleteModal', userId);
        }

        if (e.target.classList.contains('close') || e.target.classList.contains('cancel')) {
            const modalId = e.target.getAttribute('data-modal');
            if (modalId) closeModal(modalId);
        }
    });

    // Close modal if clicked outside
    window.addEventListener('click', function(e) {
        document.querySelectorAll('.modal').forEach(modal => {
            if (e.target == modal) modal.style.display = "none";
        });
    });
</script>