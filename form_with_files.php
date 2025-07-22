<?php
$errors = [];
$success = false;
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $formData['name'] = trim($_POST['name'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $formData['phone'] = trim($_POST['phone'] ?? '');
    $formData['portfolio'] = trim($_POST['portfolio'] ?? '');
    $formData['active_socials'] = $_POST['active_socials'] ?? [];
    $formData['affiliations'] = trim($_POST['affiliations'] ?? '');
    $formData['certificates'] = trim($_POST['certificates'] ?? '');
    $formData['bio'] = trim($_POST['bio'] ?? '');
    $formData['join_date'] = $_POST['join_date'] ?? '';
    $formData['contract_start'] = $_POST['contract_start'] ?? '';
    $formData['contract_end'] = $_POST['contract_end'] ?? '';
    $formData['password'] = $_POST['password'] ?? '';
    $formData['password_confirm'] = $_POST['password_confirm'] ?? '';
    $formData['gender'] = $_POST['gender'] ?? '';
    $formData['level'] = $_POST['level'] ?? '';
    $formData['contract_type'] = $_POST['contract_type'] ?? '';
    $formData['experience_years'] = $_POST['experience_years'] ?? '';
    $formData['expected_salary'] = $_POST['expected_salary'] ?? '';
    $formData['availability_time'] = $_POST['availability_time'] ?? '';
    $formData['remote_work'] = $_POST['remote_work'] ?? '';
    $formData['terms'] = isset($_POST['terms']);

    // Basic validation
    if (empty($formData['name'])) {
        $errors['name'] = 'Name is required';
    }

    if (empty($formData['email'])) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($formData['phone'])) {
        $errors['phone'] = 'Phone number is required';
    }

    if (empty($formData['portfolio'])) {
        $errors['portfolio'] = 'Portfolio URL is required';
    } elseif (!filter_var($formData['portfolio'], FILTER_VALIDATE_URL)) {
        $errors['portfolio'] = 'Invalid URL format';
    }

    if (empty($formData['password'])) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($formData['password']) < 8) {
        $errors['password'] = 'Password must be at least 8 characters';
    }

    if (empty($formData['password_confirm'])) {
        $errors['password_confirm'] = 'Password confirmation is required';
    } elseif ($formData['password'] !== $formData['password_confirm']) {
        $errors['password_confirm'] = 'Passwords do not match';
    }

    if (empty($formData['gender'])) {
        $errors['gender'] = 'Gender is required';
    }

    if (empty($formData['level'])) {
        $errors['level'] = 'Experience level is required';
    }

    if (empty($formData['contract_type'])) {
        $errors['contract_type'] = 'Contract type is required';
    }

    if (!$formData['terms']) {
        $errors['terms'] = 'You must accept the terms and conditions';
    }

    // File upload handling
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // CV file validation (PDF only)
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $cvFile = $_FILES['cv'];
        $cvExtension = strtolower(pathinfo($cvFile['name'], PATHINFO_EXTENSION));

        if ($cvExtension !== 'pdf') {
            $errors['cv'] = 'CV must be a PDF file';
        } else {
            $cvFileName = 'cv_' . time() . '_' . $cvFile['name'];
            $cvPath = $uploadDir . $cvFileName;
            if (move_uploaded_file($cvFile['tmp_name'], $cvPath)) {
                $formData['cv_path'] = $cvPath;
            } else {
                $errors['cv'] = 'Failed to upload CV file';
            }
        }
    }

    // Resume file validation (Markdown only)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $resumeFile = $_FILES['resume'];
        $resumeExtension = strtolower(pathinfo($resumeFile['name'], PATHINFO_EXTENSION));

        if ($resumeExtension !== 'md') {
            $errors['resume'] = 'Resume must be a Markdown (.md) file';
        } else {
            $resumeFileName = 'resume_' . time() . '_' . $resumeFile['name'];
            $resumePath = $uploadDir . $resumeFileName;
            if (move_uploaded_file($resumeFile['tmp_name'], $resumePath)) {
                $formData['resume_path'] = $resumePath;
                // Read first 50 characters for preview
                $resumeContent = file_get_contents($resumePath);
                $formData['resume_preview'] = substr($resumeContent, 0, 50) . '...';
            } else {
                $errors['resume'] = 'Failed to upload resume file';
            }
        }
    }

    // Date validations
    if (empty($formData['join_date'])) {
        $errors['join_date'] = 'Join date is required';
    } elseif (!validateDate($formData['join_date'])) {
        $errors['join_date'] = 'Invalid date format';
    }

    if (empty($formData['contract_start'])) {
        $errors['contract_start'] = 'Contract start date is required';
    } elseif (!validateDate($formData['contract_start'])) {
        $errors['contract_start'] = 'Invalid date format';
    }

    if (empty($formData['contract_end'])) {
        $errors['contract_end'] = 'Contract end date is required';
    } elseif (!validateDate($formData['contract_end'])) {
        $errors['contract_end'] = 'Invalid date format';
    }

    // Validate date range
    if (empty($errors['contract_start']) && empty($errors['contract_end'])) {
        if (strtotime($formData['contract_start']) >= strtotime($formData['contract_end'])) {
            $errors['contract_end'] = 'End date must be after start date';
        }
    }

    // If no errors, set success flag
    if (empty($errors)) {
        $success = true;
        // Here you would typically save to database
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
    <title>Technical Job Application Form</title>
    <link rel="stylesheet" href="form-style-uploads.css">
</head>

<body>
    <button class="theme-toggle" id="theme-toggle">☀️</button>

    <?php if ($success): ?>
        <div class="info-card">
            <h3>✅ Application Submitted Successfully</h3>
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value"><?= htmlspecialchars($formData['name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value"><?= htmlspecialchars($formData['email']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Phone:</div>
                <div class="info-value"><?= htmlspecialchars($formData['phone']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Portfolio:</div>
                <div class="info-value"><a href="<?= htmlspecialchars($formData['portfolio']) ?>" target="_blank"><?= htmlspecialchars($formData['portfolio']) ?></a></div>
            </div>
            <div class="info-row">
                <div class="info-label">Gender:</div>
                <div class="info-value"><?= htmlspecialchars($formData['gender']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Experience Level:</div>
                <div class="info-value"><?= htmlspecialchars($formData['level']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Contract Type:</div>
                <div class="info-value"><?= htmlspecialchars($formData['contract_type']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Years of Experience:</div>
                <div class="info-value"><?= htmlspecialchars($formData['experience_years']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Expected Salary:</div>
                <div class="info-value">$<?= number_format($formData['expected_salary']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Availability Time:</div>
                <div class="info-value"><?= htmlspecialchars($formData['availability_time']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Remote Work Preference:</div>
                <div class="info-value"><?= htmlspecialchars($formData['remote_work']) ?>%</div>
            </div>
            <?php if (isset($formData['cv_path'])): ?>
                <div class="info-row">
                    <div class="info-label">CV File:</div>
                    <div class="info-value">📄 <?= htmlspecialchars(basename($formData['cv_path'])) ?></div>
                </div>
            <?php endif; ?>
            <?php if (isset($formData['resume_preview'])): ?>
                <div class="info-row">
                    <div class="info-label">Resume Preview:</div>
                    <div class="info-value"><?= htmlspecialchars($formData['resume_preview']) ?></div>
                </div>
            <?php endif; ?>
            <div class="info-row">
                <div class="info-label">Contract Period:</div>
                <div class="info-value"><?= htmlspecialchars($formData['contract_start']) ?> to <?= htmlspecialchars($formData['contract_end']) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data" novalidate>
        <h2>Technical Job Application</h2>

        <!-- Personal Information -->
        <fieldset>
            <legend>Personal Information</legend>

            <div>
                <label for="name">Full Name:</label>
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

            <div>
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>"
                    class="<?= isset($errors['phone']) ? 'input-error' : '' ?>" required>
                <?php if (isset($errors['phone'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['phone']) ?></div>
                <?php endif; ?>
            </div>

            <div class="full-width">
                <label>Gender:</label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="gender" value="Male" <?= ($formData['gender'] ?? '') === 'Male' ? 'checked' : '' ?>>
                        Male
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="Female" <?= ($formData['gender'] ?? '') === 'Female' ? 'checked' : '' ?>>
                        Female
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="gender" value="Other" <?= ($formData['gender'] ?? '') === 'Other' ? 'checked' : '' ?>>
                        Other
                    </label>
                </div>
                <?php if (isset($errors['gender'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['gender']) ?></div>
                <?php endif; ?>
            </div>
        </fieldset>

        <!-- Professional Information -->
        <fieldset>
            <legend>Professional Information</legend>

            <div class="full-width">
                <label for="portfolio">Portfolio URL:</label>
                <input type="url" id="portfolio" name="portfolio" value="<?= htmlspecialchars($formData['portfolio'] ?? '') ?>"
                    class="<?= isset($errors['portfolio']) ? 'input-error' : '' ?>" required>
                <?php if (isset($errors['portfolio'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['portfolio']) ?></div>
                <?php endif; ?>
            </div>

            <div class="full-width">
                <label>Experience Level:</label>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="level" value="Junior" <?= ($formData['level'] ?? '') === 'Junior' ? 'checked' : '' ?>>
                        Junior (0-2 years)
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="level" value="Mid-Level" <?= ($formData['level'] ?? '') === 'Mid-Level' ? 'checked' : '' ?>>
                        Mid-Level (3-5 years)
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="level" value="Senior" <?= ($formData['level'] ?? '') === 'Senior' ? 'checked' : '' ?>>
                        Senior (6+ years)
                    </label>
                </div>
                <?php if (isset($errors['level'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['level']) ?></div>
                <?php endif; ?>
            </div>

            <div>
                <label for="experience_years">Years of Experience:</label>
                <input type="number" id="experience_years" name="experience_years" min="0" max="50"
                    value="<?= htmlspecialchars($formData['experience_years'] ?? '') ?>">
            </div>

            <div>
                <label for="expected_salary">Expected Salary ($):</label>
                <input type="number" id="expected_salary" name="expected_salary" min="0" step="1000"
                    value="<?= htmlspecialchars($formData['expected_salary'] ?? '') ?>">
            </div>

            <div class="full-width">
                <label for="remote_work">Remote Work Preference (%):</label>
                <div class="input-group" style="display: flex; align-items: center;">
                    <input type="range" id="remote_work" name="remote_work" min="0" max="100"
                        value="<?= htmlspecialchars($formData['remote_work'] ?? '50') ?>"
                        oninput="updateRangeValue('remote_work', this.value)">
                    <span id="remote_work_value">50%</span>
                </div>
            </div>

            <div>
                <label for="availability_time">Available Start Time:</label>
                <input type="time" id="availability_time" name="availability_time"
                    value="<?= htmlspecialchars($formData['availability_time'] ?? '') ?>">
            </div>

            <div class="full-width">
                <label for="contract_type">Contract Type:</label>
                <select name="contract_type" id="contract_type" class="<?= isset($errors['contract_type']) ? 'input-error' : '' ?>" required>
                    <option value="">Select Contract Type</option>
                    <option value="Renewable" <?= ($formData['contract_type'] ?? '') === 'Renewable' ? 'selected' : '' ?>>Renewable</option>
                    <option value="Fixed-term" <?= ($formData['contract_type'] ?? '') === 'Fixed-term' ? 'selected' : '' ?>>Fixed-term</option>
                    <option value="Periodic" <?= ($formData['contract_type'] ?? '') === 'Periodic' ? 'selected' : '' ?>>Periodic</option>
                    <option value="Conditioned" <?= ($formData['contract_type'] ?? '') === 'Conditioned' ? 'selected' : '' ?>>Conditioned</option>
                    <option value="Permanent" <?= ($formData['contract_type'] ?? '') === 'Permanent' ? 'selected' : '' ?>>Permanent</option>
                </select>
                <?php if (isset($errors['contract_type'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['contract_type']) ?></div>
                <?php endif; ?>
            </div>

            <div class="full-width">
                <label for="active-socials">Active Social Media:</label>
                <select name="active_socials[]" id="active-socials" multiple>
                    <?php
                    $socialOptions = ['LinkedIn', 'GitHub', 'Twitter', 'Stack Overflow', 'Discord'];
                    $selectedSocials = $formData['active_socials'] ?? [];
                    foreach ($socialOptions as $option):
                    ?>
                        <option value="<?= $option ?>" <?= in_array($option, $selectedSocials) ? 'selected' : '' ?>><?= $option ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="affiliations">Company Affiliations:</label>
                <input type="text" id="affiliations" name="affiliations" value="<?= htmlspecialchars($formData['affiliations'] ?? '') ?>">
            </div>

            <div>
                <label for="certificates">Certificates (comma-separated):</label>
                <input type="text" id="certificates" name="certificates" value="<?= htmlspecialchars($formData['certificates'] ?? '') ?>"
                    placeholder="e.g., AWS Certified, Google Cloud Professional, Azure Developer">
            </div>

            <div class="full-width">
                <label for="bio">Professional Bio:</label>
                <textarea id="bio" name="bio"><?= htmlspecialchars($formData['bio'] ?? '') ?></textarea>
            </div>
        </fieldset>

        <!-- File Uploads -->
        <fieldset>
            <legend>Documents</legend>

            <div class="full-width">
                <label for="cv">CV (PDF only):</label>
                <input type="file" id="cv" name="cv" accept=".pdf" class="file-input <?= isset($errors['cv']) ? 'input-error' : '' ?>">
                <div class="file-info">Only PDF files are accepted</div>
                <?php if (isset($errors['cv'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['cv']) ?></div>
                <?php endif; ?>
            </div>

            <div class="full-width">
                <label for="resume">Resume (Markdown only):</label>
                <input type="file" id="resume" name="resume" accept=".md" class="file-input <?= isset($errors['resume']) ? 'input-error' : '' ?>">
                <div class="file-info">Only Markdown (.md) files are accepted</div>
                <?php if (isset($errors['resume'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['resume']) ?></div>
                <?php endif; ?>
            </div>
        </fieldset>

        <!-- Login Credentials -->
        <fieldset>
            <legend>Account Credentials</legend>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password"
                    class="<?= isset($errors['password']) ? 'input-error' : '' ?>" required>
                <?php if (isset($errors['password'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['password']) ?></div>
                <?php endif; ?>
            </div>

            <div>
                <label for="password_confirm">Confirm Password:</label>
                <input type="password" id="password_confirm" name="password_confirm"
                    class="<?= isset($errors['password_confirm']) ? 'input-error' : '' ?>" required>
                <?php if (isset($errors['password_confirm'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['password_confirm']) ?></div>
                <?php endif; ?>
            </div>
        </fieldset>

        <!-- Dates -->
        <fieldset>
            <legend>Employment Dates</legend>

            <div>
                <label for="join_date">Preferred Join Date:</label>
                <input type="date" id="join_date" name="join_date" value="<?= htmlspecialchars($formData['join_date'] ?? '') ?>"
                    class="<?= isset($errors['join_date']) ? 'input-error' : '' ?>" required>
                <?php if (isset($errors['join_date'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['join_date']) ?></div>
                <?php endif; ?>
            </div>

            <div class="full-width">
                <label>Contract Period:</label>
                <div class="date-range-container">
                    <div>
                        <input type="date" id="contract_start" name="contract_start"
                            value="<?= htmlspecialchars($formData['contract_start'] ?? '') ?>"
                            class="<?= isset($errors['contract_start']) ? 'input-error' : '' ?>" required>
                        <?php if (isset($errors['contract_start'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['contract_start']) ?></div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <input type="date" id="contract_end" name="contract_end"
                            value="<?= htmlspecialchars($formData['contract_end'] ?? '') ?>"
                            class="<?= isset($errors['contract_end']) ? 'input-error' : '' ?>" required>
                        <?php if (isset($errors['contract_end'])): ?>
                            <div class="error"><?= htmlspecialchars($errors['contract_end']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- Terms and Conditions -->
        <div class="full-width">
            <label class="checkbox-label">
                <input type="checkbox" name="terms" value="1" <?= $formData['terms'] ?? false ? 'checked' : '' ?>
                    class="<?= isset($errors['terms']) ? 'input-error' : '' ?>">
                I agree to the <a href="#" target="_blank">Terms and Conditions</a>
            </label>
            <?php if (isset($errors['terms'])): ?>
                <div class="error"><?= htmlspecialchars($errors['terms']) ?></div>
            <?php endif; ?>
        </div>

        <button type="submit">Submit Application</button>
    </form>

    <script>
        // Set default dates
        let joinDate = document.getElementById("join_date");
        if (!joinDate.value) {
            joinDate.value = new Date().toISOString().slice(0, 10);
        }

        // Auto-sync contract start with join date
        joinDate.onchange = function() {
            let contractStart = document.getElementById("contract_start");
            contractStart.value = joinDate.value;
        }

        // Range slider update function
        function updateRangeValue(id, value) {
            document.getElementById(id + '_value').textContent = value + '%';
        }

        // Initialize range value
        updateRangeValue('remote_work', document.getElementById('remote_work').value);

        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        const htmlElement = document.documentElement;

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            htmlElement.setAttribute('data-theme', savedTheme);
            updateToggleButton(savedTheme);
        } else {
            const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const initialTheme = prefersDarkMode ? 'dark' : 'light';
            htmlElement.setAttribute('data-theme', initialTheme);
            updateToggleButton(initialTheme);
        }

        themeToggle.addEventListener('click', () => {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateToggleButton(newTheme);
        });

        function updateToggleButton(theme) {
            themeToggle.textContent = theme === 'light' ? '🌙' : '☀️';
        }
    </script>
</body>

</html>