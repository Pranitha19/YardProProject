<?php
session_start();
require_once '../../controllers/AdminController.php';
require_once '../../helpers/flash.php';

$controller = new AdminController();
$centers    = $controller->getAllCenters();

$daysOfWeek = [
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday',
    7 => 'Sunday',
];

// Handle form submit
if (isset($_POST['update'])) {
    $centerData = [
        'center_id'   => $_POST['center_id'],
        'name'        => $_POST['name'],
        'email'       => $_POST['email'] ?? null,
        'phone_no'    => $_POST['phone_no'] ?? null,
        'description' => $_POST['description'] ?? null,
        'address'     => $_POST['address'],
        'base_price'  => $_POST['base_price'] ?? 0.00,
        'image_url'   => $_POST['image_url'] ?? null,
    ];

    $timings = $_POST['timings'] ?? [];

    if ($controller->updateServiceCenter($centerData, $timings)) {
        setFlash('success', 'Service Center updated successfully!');
        header('Location: editServiceCenter.php');
        exit();
    } else {
        setFlash('danger', 'Failed to update Service Center.');
    }
}

// AJAX: return JSON with center + timings
if (isset($_GET['center_id']) && !empty($_GET['center_id'])) {
    $center_id = (int)$_GET['center_id'];
    $center    = null;

    foreach ($centers as $c) {
        if ((int)$c['center_id'] === $center_id) {
            $center = $c;
            break;
        }
    }

    if ($center) {
        $timings = $controller->getCenterTimings($center_id);
        header('Content-Type: application/json');
        echo json_encode([
            'center'  => $center,
            'timings' => $timings,
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Service Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
    <div class="container mt-5" style="max-width: 900px;">
        <h3 class="text-success mb-4">Edit Service Center</h3>

        <?php showFlash(); ?>

        <form method="POST" id="editForm">
            <label class="form-label">Select Service Center</label>
            <select name="center_id" id="center_id" class="form-select mb-3" required>
                <option value="">Choose...</option>
                <?php foreach ($centers as $c): ?>
                <option value="<?= $c['center_id'] ?>">
                    <?= htmlspecialchars($c['name']) ?> (ID: <?= $c['center_id'] ?>)
                </option>
                <?php endforeach; ?>
            </select>

            <input name="name" id="name" class="form-control mb-2" placeholder="Name">
            <input name="email" id="email" class="form-control mb-2" placeholder="Email">
            <input name="phone_no" id="phone_no" class="form-control mb-2" placeholder="Phone">
            <textarea name="description" id="description" class="form-control mb-2" placeholder="Description"></textarea>
            <textarea name="address" id="address" class="form-control mb-2" placeholder="Address"></textarea>
            <input name="base_price" id="base_price" class="form-control mb-2" placeholder="Base Price (e.g. 49.99)">
            <input name="image_url" id="image_url" class="form-control mb-3" placeholder="Image URL">

            <h5 class="mt-4 mb-2 text-success">Weekly Timings</h5>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle">
                    <thead class="table-success">
                        <tr>
                            <th style="width: 20%;">Day</th>
                            <th style="width: 10%;">Open?</th>
                            <th style="width: 35%;">Start Time</th>
                            <th style="width: 35%;">End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daysOfWeek as $day => $label): ?>
                        <tr>
                            <td><?= htmlspecialchars($label) ?></td>
                            <td class="text-center">
                                <input type="checkbox"
                                       class="form-check-input day-open"
                                       id="day<?= $day ?>_open"
                                       name="timings[<?= $day ?>][open]"
                                       value="1">
                            </td>
                            <td>
                                <input type="time"
                                       id="day<?= $day ?>_start"
                                       name="timings[<?= $day ?>][start]"
                                       class="form-control">
                            </td>
                            <td>
                                <input type="time"
                                       id="day<?= $day ?>_end"
                                       name="timings[<?= $day ?>][end]"
                                       class="form-control">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button name="update" class="btn btn-success">Update</button>
            <a href="home.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script>
    // Fetch center + timings when dropdown changes
    $(document).ready(function() {

        function syncCheckboxRow(cb) {
            const row = cb.closest('tr');
            const times = row.querySelectorAll('input[type="time"]');
            const enable = cb.checked;
            times.forEach(t => t.disabled = !enable);
        }

        $('.day-open').each(function() {
            syncCheckboxRow(this);
            this.addEventListener('change', () => syncCheckboxRow(this));
        });

        $('#center_id').on('change', function() {
            const id = $(this).val();
            if (!id) return;

            $.ajax({
                url: 'editServiceCenter.php',
                method: 'GET',
                data: { center_id: id },
                dataType: 'json',
                success: function(resp) {
                    const c  = resp.center;
                    const ts = resp.timings || {};

                    $('#name').val(c.name);
                    $('#email').val(c.email);
                    $('#phone_no').val(c.phone_no);
                    $('#description').val(c.description);
                    $('#address').val(c.address);
                    $('#base_price').val(c.base_price);
                    $('#image_url').val(c.image_url);

                    // Reset all days to closed
                    for (let d = 1; d <= 7; d++) {
                        const cb  = document.getElementById('day'+d+'_open');
                        const st  = document.getElementById('day'+d+'_start');
                        const en  = document.getElementById('day'+d+'_end');

                        cb.checked = false;
                        st.value   = '';
                        en.value   = '';
                        st.disabled = true;
                        en.disabled = true;
                    }

                    // Apply timings from DB
                    for (const day in ts) {
                        if (!ts.hasOwnProperty(day)) continue;
                        const d = parseInt(day);

                        const startDb = ts[day].start; // 'HH:MM:SS' or null
                        const endDb   = ts[day].end;

                        const cb  = document.getElementById('day'+d+'_open');
                        const st  = document.getElementById('day'+d+'_start');
                        const en  = document.getElementById('day'+d+'_end');

                        if (startDb && endDb) {
                            cb.checked = true;
                            st.disabled = false;
                            en.disabled = false;
                            st.value = startDb.substring(0,5);
                            en.value = endDb.substring(0,5);
                        } else {
                            cb.checked = false;
                            st.disabled = true;
                            en.disabled = true;
                            st.value = '';
                            en.value = '';
                        }
                    }
                }
            });
        });
    });

    setTimeout(() => {
        const msg = document.querySelector('.flash-message');
        if (msg) {
            msg.style.transition = "opacity 0.5s";
            msg.style.opacity = "0";
            setTimeout(() => msg.remove(), 500);
        }
    }, 3000);
    </script>
</body>

</html>
