<?php
session_start();
require_once "includes/db_connect.php";

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

function getCount($conn, $table)
{
    $allowed = ['crops', 'tasks', 'resources', 'users'];
    if (!in_array($table, $allowed)) return 0;
    $sql = "SELECT COUNT(*) AS total FROM `$table`";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    return (int)$row['total'];
}

$crops_count = getCount($conn, 'crops');
$tasks_count = getCount($conn, 'tasks');
$resources_count = getCount($conn, 'resources');
$users_count = getCount($conn, 'users');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Dashboard - FarmSys</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="./assets/assets/css/dashboard.css">

</head>

<body>

    <div class="sidebar">
        <div class="logo">🌾TANIMAN NG GULAY </div>
        <div class="subtitle">Smart Farm Management</div>

        <ul class="nav">
            <li class="active"><a href="#" data-page="dashboard_content.php">Dashboard</a></li>
            <li><a href="#" data-page="crops/index.php">Crops</a></li>
            <li><a href="#" data-page="tasks/index.php">Tasks</a></li>
            <li><a href="#" data-page="resources/index.php">Resources</a></li>
            <li><a href="#" data-page="users.php">Users</a></li>
        </ul>

        <div class="logout">
            <button onclick="logoutNow()">Logout</button>
        </div>
    </div>

    <div class="main">
        <div id="main-content">
            <header>
                <h2>Dashboard</h2>
                <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</p>
            </header>

            <div class="cards">
                <div class="card" data-page="./crops/view_crops">
                    <h3>Crops</h3>
                    <p class="count"><?php echo $crops_count; ?></p>
                    <a href="#" class="manage-link">Manage Crops</a>
                </div>

                <div class="card">
                    <h3>Tasks</h3>
                    <p class="count"><?php echo $tasks_count; ?></p>
                    <a href="#" data-page="tasks/index.php">Manage Tasks</a>
                </div>

                <div class="card">
                    <h3>Resources</h3>
                    <p class="count"><?php echo $resources_count; ?></p>
                    <a href="#" data-page="resources/index.php">Manage Resources</a>
                </div>

                <div class="card">
                    <h3>Users</h3>
                    <p class="count"><?php echo $users_count; ?></p>
                    <a href="#" data-page="users.php">Manage Users</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function logoutNow() {
            Swal.fire({
                title: "Logout?",
                text: "Do you really want to logout?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#0b4d1b",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, logout"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "logout.php";
                }
            });
        }

        function handleFormSubmission(e, action) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            formData.append('action', action);

            fetch('process_user.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return response.json();
                    } else {
                        console.error('Server returned non-JSON response.', response);
                        throw new Error('Server returned an unexpected response format.');
                    }
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            zIndex: 10000
                        }).then(() => {

                            form.reset();
                            closeModal(form.closest('.modal').id);

                            const usersLink = document.querySelector('a[data-page="users.php"]');
                            if (usersLink) usersLink.click();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Operation Failed',
                            text: data.message || 'An unknown error occurred.',
                            zIndex: 10000
                        });
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Could not reach the server or unexpected response from server.',
                        zIndex: 10000
                    });
                });
        }

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


        document.querySelectorAll('.nav a, .cards a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                if (this.closest('ul.nav')) {
                    document.querySelectorAll('.nav li').forEach(li => li.classList.remove('active'));
                    this.parentElement.classList.add('active');
                }

                const page = this.dataset.page;
                if (!page) return;

                fetch(page)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('main-content').innerHTML = html;

                        document.getElementById('addForm')?.addEventListener('submit', function(e) {
                            handleFormSubmission(e, 'add');
                        });
                        document.getElementById('editForm')?.addEventListener('submit', function(e) {
                            handleFormSubmission(e, 'edit');
                        });
                        document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
                            handleFormSubmission(e, 'delete');
                        });
                    })
                    .catch(err => console.error('Error loading page:', err));
            });
        });


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

        window.addEventListener('click', function(e) {
            document.querySelectorAll('.modal').forEach(modal => {
                if (e.target == modal) modal.style.display = "none";
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.manage-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // prevent default anchor jump
                    const card = this.closest('.card');
                    const page = card.dataset.page; // get target page

                    // Example: load page content into a container
                    fetch(page)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('main-content').innerHTML = html;
                        })
                        .catch(err => console.error(err));
                });
            });
        });
    </script>


</body>

</html>