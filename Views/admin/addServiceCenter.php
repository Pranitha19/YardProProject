<?php

// Controllers and helpers already loaded by index.php

$controller = new AdminController();

$daysOfWeek = [
    1 => 'Monday',
    2 => 'Tuesday',
    3 => 'Wednesday',
    4 => 'Thursday',
    5 => 'Friday',
    6 => 'Saturday',
    7 => 'Sunday',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Center fields
    $centerData = [
        'name'        => $_POST['name'],
        'email'       => $_POST['email'] ?? null,
        'phone_no'    => $_POST['phone_no'] ?? null,
        'description' => $_POST['description'] ?? null,
        'address'     => $_POST['address'],
        'base_price'  => $_POST['base_price'] ?? 0.00,
        'image_url'   => $_POST['image_url'] ?? null,
    ];

    // timings
    $timings = $_POST['timings'] ?? [];

    if ($controller->addServiceCenter($centerData, $timings)) {
        setFlash('success', 'Service Center added successfully!');
    } else {
        setFlash('danger', 'Error adding Service Center.');
    }

    // PRG
    header('Location: /YardProProject/?route=admin/add-service-center');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Service Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5" style="max-width: 900px;">
        <h3 class="text-success mb-4">Add Service Center</h3>

        <?php showFlash(); ?>

        <form method="POST">
            <input name="name" class="form-control mb-2" placeholder="Center Name *" required>
            <input name="email" class="form-control mb-2" placeholder="Email">
            <input name="phone_no" class="form-control mb-2" placeholder="Phone">
            <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
            <textarea name="address" class="form-control mb-2" placeholder="Address *" required></textarea>
            <input type="number" step="0.01" name="base_price" class="form-control mb-2"
            placeholder="Base Price (e.g., 49.99)">
            <input name="image_url" class="form-control mb-3" placeholder="Image URL (optional)">

            <h5 class="mt-4 mb-2 text-success">Weekly Timings</h5>
            <p class="text-muted" style="font-size: 0.9rem;">
                Tick <strong>Open</strong> and choose start/end time for days you work. Leave unchecked to mark as
                <strong>Closed</strong>.
            </p>

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
                                name="timings[<?= $day ?>][open]"
                                value="1"
                                <?= ($day <= 5) ? 'checked' : '' ?>>
                            </td>
                            <td>
<input type="time" name="timings[<?= $day ?>][start]"class="form-control"value="<?= ($day <= 5) ? '09:00' : '' ?>">
                            </td>
                            <td>
        <input type="time"
            name="timings[<?= $day ?>][end]"
            class="form-control"
            value="<?= ($day <= 5) ? '17:00' : '' ?>">
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <button class="btn btn-success">Add Center</button>
            <a href="/YardProProject/?route=admin/home" class="btn btn-secondary">Back</a>
        </form>
                        </div>
    <script>
    document.querySelectorAll('.day-open').forEach(cb => {
        const row = cb.closest('tr');
        const times = row.querySelectorAll('input[type="time"]');

        function sync() {
            const enabled = cb.checked;
            times.forEach(t => t.disabled = !enabled);
        }

        cb.addEventListener('change', sync);
        sync();
    });
    <script>
    setTimeout(() => {
        const msg = document.querySelector('.flash-message');
        if (msg) {
            msg.style.transition = "opacity 0.5s";
            msg.style.opacity = "0";
            setTimeout(() => msg.remove(), 500);
        }
    }, 2000);
    </script>
</body>
</html>
