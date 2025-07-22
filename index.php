<?php
$errors = [];
$success = false;
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $formData['name'] = trim($_POST['name'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $formData['portfolio'] = trim($_POST['portfolio'] ?? '');
    $formData['active_socials'] = $_POST['active_socials'] ?? [];
    $formData['affiliations'] = trim($_POST['affiliations'] ?? '');
    $formData['certificates'] = trim($_POST['certificates'] ?? '');
    $formData['bio'] = trim($_POST['bio'] ?? '');
    $formData['join_date'] = $_POST['join_date'] ?? '';
    $formData['contact_date_range_start'] = $_POST['contact_date_range_start'] ?? '';
    $formData['contact_date_range_ending'] = $_POST['contact_date_range_ending'] ?? '';

    // Validation rules
    if (empty($formData['name'])) {
        $errors['name'] = 'Name is required';
    }

    if (empty($formData['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($formData['portfolio'])) {
        $errors['portfolio'] = 'Portfolio URL is required';
    } elseif (!filter_var($formData['portfolio'], FILTER_VALIDATE_URL)) {
        $errors['portfolio'] = 'Invalid URL format';
    }

    if (empty($formData['affiliations'])) {
        $errors['affiliations'] = 'Affiliations are required';
    }

    // Certificates validation - comma-separated values
    if (empty($formData['certificates'])) {
        $errors['certificates'] = 'Certificates are required';
    } else {
        $certificates = explode(',', $formData['certificates']);
        $validCertificates = true;
        foreach ($certificates as $cert) {
            if (empty(trim($cert))) {
                $validCertificates = false;
                break;
            }
        }
        if (!$validCertificates) {
            $errors['certificates'] = 'Certificates must be comma-separated values with no empty entries';
        }
    }

    if (empty($formData['bio'])) {
        $errors['bio'] = 'Bio is required';
    }

    if (empty($formData['join_date'])) {
        $errors['join_date'] = 'Join date is required';
    } elseif (!validateDate($formData['join_date'])) {
        $errors['join_date'] = 'Invalid date format';
    }

    if (empty($formData['contact_date_range_start'])) {
        $errors['contact_date_range_start'] = 'Contract start date is required';
    } elseif (!validateDate($formData['contact_date_range_start'])) {
        $errors['contact_date_range_start'] = 'Invalid date format';
    }

    if (empty($formData['contact_date_range_ending'])) {
        $errors['contact_date_range_ending'] = 'Contract end date is required';
    } elseif (!validateDate($formData['contact_date_range_ending'])) {
        $errors['contact_date_range_ending'] = 'Invalid date format';
    }

    // Validate date range
    if (empty($errors['contact_date_range_start']) && empty($errors['contact_date_range_ending'])) {
        if (strtotime($formData['contact_date_range_start']) >= strtotime($formData['contact_date_range_ending'])) {
            $errors['contact_date_range_ending'] = 'End date must be after start date';
        }
    }

    // If no errors, set success flag
    if (empty($errors)) {
        $success = true;
        // Here you would typically save to database
        // saveAuthorToDatabase($formData);
    }
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Registration Form</title>
    <link rel="stylesheet" href="form-style.css">
</head>

<body>
    <button class="theme-toggle" id="theme-toggle">☀️</button>

    <?php if ($success): ?>
        <div class="info-card">
            <h3>✅ Author Registration Successful</h3>
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value"><?= htmlspecialchars($formData['name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?= htmlspecialchars($formData['email']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Portfolio:</div>
                <div class="info-value"><a href="<?= htmlspecialchars($formData['portfolio']) ?>" target="_blank"><?= htmlspecialchars($formData['portfolio']) ?></a></div>
            </div>
            <div class="info-row">
                <div class="info-label">Active Social Media:</div>
                <div class="info-value"><?= !empty($formData['active_socials']) ? implode(', ', $formData['active_socials']) : 'None selected' ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Affiliations:</div>
                <div class="info-value"><?= htmlspecialchars($formData['affiliations']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Certificates:</div>
                <div class="info-value"><?= htmlspecialchars($formData['certificates']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Bio:</div>
                <div class="info-value"><?= nl2br(htmlspecialchars($formData['bio'])) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Join Date:</div>
                <div class="info-value"><?= htmlspecialchars($formData['join_date']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Contract Period:</div>
                <div class="info-value"><?= htmlspecialchars($formData['contact_date_range_start']) ?> to <?= htmlspecialchars($formData['contact_date_range_ending']) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
        <h2>New Author Information</h2>

        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
                class="<?= isset($errors['name']) ? 'input-error' : '' ?>" required>
            <?php if (isset($errors['name'])): ?>
                <div class="error"><?= htmlspecialchars($errors['name']) ?></div>
            <?php endif; ?>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '') ?>"
                class="<?= isset($errors['email']) ? 'input-error' : '' ?>" required>
            <?php if (isset($errors['email'])): ?>
                <div class="error"><?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="full-width">
            <label for="portfolio">Portfolio URL:</label>
            <input type="url" id="portfolio" name="portfolio" value="<?= htmlspecialchars($formData['portfolio'] ?? '') ?>"
                class="<?= isset($errors['portfolio']) ? 'input-error' : '' ?>" required>
            <?php if (isset($errors['portfolio'])): ?>
                <div class="error"><?= htmlspecialchars($errors['portfolio']) ?></div>
            <?php endif; ?>
        </div>

        <div class="full-width">
            <label for="active-socials">Active social media:</label>
            <select name="active_socials[]" id="active-socials" multiple>
                <?php
                $socialOptions = ['Facebook', 'Twitter', 'Instagram', 'Discord', 'Tiktok'];
                $selectedSocials = $formData['active_socials'] ?? [];
                foreach ($socialOptions as $option):
                ?>
                    <option value="<?= $option ?>" <?= in_array($option, $selectedSocials) ? 'selected' : '' ?>><?= $option ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="affiliations">Affiliations:</label>
            <input type="text" id="affiliations" name="affiliations" value="<?= htmlspecialchars($formData['affiliations'] ?? '') ?>"
                class="<?= isset($errors['affiliations']) ? 'input-error' : '' ?>" required>
            <?php if (isset($errors['affiliations'])): ?>
                <div class="error"><?= htmlspecialchars($errors['affiliations']) ?></div>
            <?php endif; ?>
        </div>

        <div>
            <label for="certificates">Certificates (comma-separated):</label>
            <input type="text" id="certificates" name="certificates" value="<?= htmlspecialchars($formData['certificates'] ?? '') ?>"
                class="<?= isset($errors['certificates']) ? 'input-error' : '' ?>"
                placeholder="e.g., PHP Certified, MySQL Expert, AWS Developer" required>
            <?php if (isset($errors['certificates'])): ?>
                <div class="error"><?= htmlspecialchars($errors['certificates']) ?></div>
            <?php endif; ?>
        </div>

        <div class="full-width">
            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" class="<?= isset($errors['bio']) ? 'input-error' : '' ?>" required><?= htmlspecialchars($formData['bio'] ?? '') ?></textarea>
            <?php if (isset($errors['bio'])): ?>
                <div class="error"><?= htmlspecialchars($errors['bio']) ?></div>
            <?php endif; ?>
        </div>

        <div class="full-width">
            <label for="join_date">Join Date:</label>
            <input type="date" id="join_date" name="join_date" value="<?= htmlspecialchars($formData['join_date'] ?? '') ?>"
                class="<?= isset($errors['join_date']) ? 'input-error' : '' ?>" required>
            <?php if (isset($errors['join_date'])): ?>
                <div class="error"><?= htmlspecialchars($errors['join_date']) ?></div>
            <?php endif; ?>
        </div>

        <div class="full-width">
            <label>Contact Date Range:</label>
            <div class="date-range-container">
                <div>
                    <input type="date" id="contact_date_range_start" name="contact_date_range_start"
                        value="<?= htmlspecialchars($formData['contact_date_range_start'] ?? '') ?>"
                        class="<?= isset($errors['contact_date_range_start']) ? 'input-error' : '' ?>" required>
                    <?php if (isset($errors['contact_date_range_start'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['contact_date_range_start']) ?></div>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="date" id="contact_date_range_end" name="contact_date_range_ending"
                        value="<?= htmlspecialchars($formData['contact_date_range_ending'] ?? '') ?>"
                        class="<?= isset($errors['contact_date_range_ending']) ? 'input-error' : '' ?>" required>
                    <?php if (isset($errors['contact_date_range_ending'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['contact_date_range_ending']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <button type="submit">Add Author</button>
    </form>

    <script>
        // set the join date as the date of today
        let joinDate = document.getElementById("join_date");
        if (!joinDate.value) {
            joinDate.value = new Date().toISOString().slice(0, 10);
        }
        // set the start contract date as the join date auto change
        joinDate.onchange = function() {
            let startContractDate = document.getElementById("contact_date_range_start");
            startContractDate.value = joinDate.value;
        }
    </script>
    <script>
        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        // Check for saved theme preference or use OS preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            htmlElement.setAttribute('data-theme', savedTheme);
            updateToggleButton(savedTheme);
        } else {
            // Check OS preference
            const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initialTheme = prefersDarkMode ? 'dark' : 'light';
            htmlElement.setAttribute('data-theme', initialTheme);
            updateToggleButton(initialTheme);
        }

        // Toggle theme
        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateToggleButton(newTheme);
        });

        // Update toggle button icon
        function updateToggleButton(theme) {
            themeToggle.textContent = theme === 'light' ? '🌙' : '☀️';
        }
    </script>
</body>

</html>