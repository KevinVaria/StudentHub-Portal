
<?php
require_once __DIR__ . '/../auth.php';

require_admin();

$uploadDir = __DIR__ . '/uploads';

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
        exit('Could not create the uploads folder.');
    }
}

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$row = [
    'title' => '',
    'category' => 'Technology',
    'event_date' => '',
    'location' => '',
    'description' => '',
    'poster' => null
];

$errors = [];

/* Load an existing event when editing */
if ($id > 0) {
    $stmt = $conn->prepare('SELECT * FROM events WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    $existingEvent = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$existingEvent) {
        set_flash('Event not found.', 'error');
        redirect('index.php');
    }

    $row = $existingEvent;
}

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $row['title'] = trim($_POST['title'] ?? '');
    $row['category'] = trim($_POST['category'] ?? '');
    $row['event_date'] = trim($_POST['event_date'] ?? '');
    $row['location'] = trim($_POST['location'] ?? '');
    $row['description'] = trim($_POST['description'] ?? '');

    /* Validate required fields */
    if ($row['title'] === '') {
        $errors[] = 'Title is required.';
    }

    if ($row['category'] === '') {
        $errors[] = 'Category is required.';
    }

    if ($row['location'] === '') {
        $errors[] = 'Location is required.';
    }

    $date = DateTime::createFromFormat('!Y-m-d', $row['event_date']);

    if (
        !$date ||
        $date->format('Y-m-d') !== $row['event_date']
    ) {
        $errors[] = 'Enter a valid event date.';
    }

    /* Validate an optional poster upload */
    $newPoster = null;

    if (
        isset($_FILES['poster']) &&
        $_FILES['poster']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        $file = $_FILES['poster'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Poster upload failed. Error code: ' . $file['error'];
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Poster must be 2 MB or smaller.';
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file['tmp_name']);

            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp'
            ];

            if (!isset($allowedTypes[$mime])) {
                $errors[] = 'Only JPEG, PNG, and WebP images are allowed.';
            } else {
                $newPoster = bin2hex(random_bytes(16))
                    . '.'
                    . $allowedTypes[$mime];
            }
        }
    }

    /* Save the event if validation passed */
    if (empty($errors)) {
        $posterMoved = false;

        try {
            if ($newPoster !== null) {
                if (
                    !move_uploaded_file(
                        $_FILES['poster']['tmp_name'],
                        $uploadDir . '/' . $newPoster
                    )
                ) {
                    throw new RuntimeException(
                        'Could not save the poster to the uploads folder.'
                    );
                }

                $posterMoved = true;
            }

            if ($id > 0) {
                /* Update an existing event */
                if ($newPoster !== null) {
                    $stmt = $conn->prepare(
                        'UPDATE events
                         SET title = ?, category = ?, event_date = ?,
                             location = ?, description = ?, poster = ?
                         WHERE id = ?'
                    );

                    $stmt->bind_param(
                        'ssssssi',
                        $row['title'],
                        $row['category'],
                        $row['event_date'],
                        $row['location'],
                        $row['description'],
                        $newPoster,
                        $id
                    );
                } else {
                    $stmt = $conn->prepare(
                        'UPDATE events
                         SET title = ?, category = ?, event_date = ?,
                             location = ?, description = ?
                         WHERE id = ?'
                    );

                    $stmt->bind_param(
                        'sssssi',
                        $row['title'],
                        $row['category'],
                        $row['event_date'],
                        $row['location'],
                        $row['description'],
                        $id
                    );
                }
            } else {
                /* Insert a new event */
                $stmt = $conn->prepare(
                    'INSERT INTO events
                     (title, category, event_date, location, description, poster)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );

                $stmt->bind_param(
                    'ssssss',
                    $row['title'],
                    $row['category'],
                    $row['event_date'],
                    $row['location'],
                    $row['description'],
                    $newPoster
                );
            }

            $stmt->execute();
            $stmt->close();

            /* Delete the previous poster after a successful update */
            if (
                $id > 0 &&
                $newPoster !== null &&
                !empty($row['poster'])
            ) {
                $oldPosterPath = $uploadDir . '/' . $row['poster'];

                if (is_file($oldPosterPath)) {
                    unlink($oldPosterPath);
                }
            }

            /* Log activity separately so logging failure doesn't undo the save */
            try {
                log_action(
                    $conn,
                    (int)($_SESSION['student_id'] ?? 0),
                    $id > 0 ? 'event_update' : 'event_create',
                    $row['title']
                );
            } catch (Throwable $logError) {
                error_log(
                    'StudentHub activity log error: '
                    . $logError->getMessage()
                );
            }

            set_flash('Event saved successfully.');
            redirect('index.php');
            exit;

        } catch (Throwable $ex) {
            error_log(
                'StudentHub event save error: '
                . $ex->getMessage()
            );

            if (
                isset($stmt) &&
                $stmt instanceof mysqli_stmt
            ) {
                $stmt->close();
            }

            if (
                $posterMoved &&
                $newPoster !== null &&
                is_file($uploadDir . '/' . $newPoster)
            ) {
                unlink($uploadDir . '/' . $newPoster);
            }

            $errors[] = 'Could not save event: ' . $ex->getMessage();
        }
    }
}

page_start($id > 0 ? 'Edit Event' : 'Add Event');

if (!empty($errors)) {
    echo '<ul class="flash error">';

    foreach ($errors as $error) {
        echo '<li>' . e($error) . '</li>';
    }

    echo '</ul>';
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= (int)$id ?>">

    <label for="title">Title</label>
    <input
        id="title"
        name="title"
        required
        maxlength="150"
        value="<?= e($row['title']) ?>"
    >

    <label for="category">Category</label>
    <select id="category" name="category" required>
        <?php foreach (
            ['Technology', 'Sports', 'Cultural', 'Career', 'Workshop', 'Other']
            as $category
        ): ?>
            <option
                value="<?= e($category) ?>"
                <?= $row['category'] === $category ? 'selected' : '' ?>
            >
                <?= e($category) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="event_date">Event date</label>
    <input
        id="event_date"
        type="date"
        name="event_date"
        required
        value="<?= e($row['event_date']) ?>"
    >

    <label for="location">Location</label>
    <input
        id="location"
        name="location"
        required
        maxlength="150"
        value="<?= e($row['location']) ?>"
    >

    <label for="description">Description</label>
    <textarea
        id="description"
        name="description"
        rows="4"
    ><?= e($row['description'] ?? '') ?></textarea>

    <label for="poster">Poster (JPEG/PNG/WebP, max 2 MB)</label>
    <input
        id="poster"
        type="file"
        name="poster"
        accept="image/jpeg,image/png,image/webp"
    >

    <?php if (!empty($row['poster'])): ?>
        <p>Current poster: <?= e($row['poster']) ?></p>
    <?php endif; ?>

    <button type="submit">Save event</button>
</form>

<?php page_end(); ?>
