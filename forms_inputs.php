<!DOCTYPE html>
<html lang="en">
<?php
print_r($_POST);

if (isset($_POST) && !empty($_POST)) {
    echo "<h1>data submitted via POST</h1>";
    echo "<pre>";
    print_r($_POST);
    print_r($_FILES);
    echo "</pre>";
    # code...
} else {
    echo "<h1>Data not submitted</h1>";
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forms and input types</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        input[type="number"] {
            width: 97%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="radio"],
        input[type="checkbox"] {
            margin-right: 10px;
        }

        input[type="file"] {
            margin-bottom: 15px;
        }

        textarea {
            width: 97%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        input[type="range"] {
            width: 100%;
            margin-bottom: 15px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="checkbox"] {
            margin-right: 10px;
        }

        .radio-group {
            margin-bottom: 15px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 5px;

        }

        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* filedset styles */
        fieldset {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
        }

        legend {
            font-weight: bold;
            padding: 0 10px;
        }

        button[type="reset"] {
            background-color: #6c757d;
        }

        button[type="reset"]:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <!-- forms -->
    <form method="post" enctype="multipart/form-data" autocomplete="off">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" disabled>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="should be a valid email service (gmail, yahoo, microsoft)" value="admin@gmail.com" readonly required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="password should be at least 8 characters" maxlength="15" required>

        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone" placeholder="place your phone number" pattern="0[567] [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}" title="written in the pattern :0[567] [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}" required>

        <!-- gender radio -->
        <fieldset style="display: flex; justify-content:flex-start; align-items: center;">
            <legend title=" gender fields">Gender:</legend>
            <div class="radio-group">
                <label for="male">Male:</label>
                <input type="radio" id="male" name="gender" value="male" required>
            </div>
            <div class="radio-group">
                <label for="female">Female:</label>
                <input type="radio" id="female" name="gender" value="female" required>
            </div>
        </fieldset>
        <!-- resume pdf -->
        <label for="resume">Resume:</label>
        <input type="file" id="resume" name="resume" accept=".pdf" multiple required>
        <!-- avatar picture -->
        <label for="avatar">Avatar:</label>
        <input type="file" id="avatar" name="avatar" accept=".jpg, .jpeg, .png" required>

        <!-- bio -->
        <label for="bio">Bio:</label>
        <textarea id="bio" name="bio" rows="20" cols="200" maxlength="200" placeholder="Tell us about yourself..." required></textarea>
        <!-- age range slider -->
        <label for="age">Age:</label>
        <input type="number" id="age" name="age" min="15" max="100" step="1" required>
        <!-- degree of expertism -->
        <label for="experism">Expertism</label>
        <input type="range" name="expertism" min="1" max="3" step="1" value="2" list="expertism">
        <datalist id="expertism">
            <option value="1" label="Beginner"></option>
            <option value="2" label="Intermediate"></option>
            <option value="3" label="Expert"></option>
        </datalist>

        <!-- select -->
        <label for="country">Country:</label>
        <select id="country" name="country" required>
            <option value="">Select your country</option>
            <option value="usa" selected>USA</option>
            <option value="canada">Canada</option>
            <option value="uk">UK</option>
            <option value="australia">Australia</option>
            <option value="other">Other</option>
        </select>


        <!-- multiple select of certificates of IT -->
        <label for="certificates">Certificates:</label>
        <select id="certificates" name="certificates[]" multiple required>
            <option value="ccna">CCNA</option>
            <option value="ccnp">CCNP</option>
            <option value="aws">AWS Certified Solutions Architect</option>
            <option value="azure">Microsoft Azure Fundamentals</option>
            <option value="gcp">Google Cloud Certified - Associate Cloud Engineer</option>
            <option value="other">Other</option>
        </select>
        <!-- join date -->
        <label for="join_date">Join Date:</label>
        <input type="date" id="join_date" name="join_date" required>


        <!-- work hours -->
        <label for="work_hours">Work Hours:</label>
        <input type="time" id="work_hours" name="start_work_hours" required>
        <input type="time" id="end_work_hours" name="end_work_hours" required>

        <!-- terms and conditions -->
        <label for="terms">
            <input type="checkbox" id="terms" name="terms" required>
            I agree to the terms and conditions
        </label>

        <button type="submit">Send informations</button>
        <button type="reset">Reset</button>
    </form>

</body>

</html>