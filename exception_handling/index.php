<?php
// echo "Starting user profile system...<br>";
// echo "Loading user data...<br>";
// echo "User: John Doe, Email: john@example.com<br>";

// // This single line will KILL the entire program
// try {
//     $profilePicture = fopen('profile_pictures/john.jpg', "r"); // File doesn't exist!
//     echo fread($profilePicture, filesize('profile_pictures/john.jpg')) ? $profilePicture : "File not found";
//     fclose($profilePicture);
// } catch (Error $file_exception) {
//     echo "<br>" . $file_exception->getCode() . "<br>";
//     echo $file_exception->getMessage() . "<br>";
//     echo "the error happened at the line: " . $file_exception->getLine() . "<br>";
// }

// // NONE of these lines will execute - the program is DEAD
// echo "Displaying user dashboard...<br>";
// echo "Loading recent activities...<br>";
// echo "Showing friend list...<br>";
// echo "Profile system ready!<br>";

$result = "";
function divide($a, $b)
{
    if ($b == 0) {
        throw new Exception("Division by zero is not allowed", 1);
    }
    return $a / $b;
}

function validateAge($age)
{
    if ($age < 18) {
        throw new Exception("you are not old enough to get a driving liscence", 18);
    }
    return $age;
}
if (strtolower($_SERVER["REQUEST_METHOD"]) === "post" && isset($_POST['submit']) && isset($_POST['age'])) {
    try {
        if (is_numeric($_POST['age'])) {

            $result = validateAge($_POST['age']);
        } else {
            echo "Error: age values must be number.";
        }
    } catch (Exception $e) {
        $result = $e->getMessage() . "the code error number " . $e->getCode();
    } finally {
        echo "you entered this program at" . date("H:i:s");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>division exception example</title>
    <style>
        /* dracula theme  */
        body {
            background-color: #1d1f23;
            color: #f8f8f2;
            font-family: 'Fira Code', monospace;

            height: 100vh;

        }

        form {
            display: flex;
            gap: 1%;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1%;

        }

        input[type="number"] {
            background-color: #2f3436;
            width: 49%;
            color: #f8f8f2;
            border: none;
            padding: 10px;
            font-size: x-large;

        }

        input[type="number"]:hover {
            cursor: pointer;
        }

        input[type="number"]:focus {
            background-color: #3b3f4e;
            outline: greenyellow;

        }


        button[type="submit"] {
            background-color: #2f3436;
            color: #f8f8f2;
            border: none;
            padding: 10px;
            font-size: x-large;
        }

        button[type="submit"]:hover {
            outline: greenyellow 1px solid;
            cursor: pointer;
        }

        .division-result {
            display: flex;
            justify-content: center;
            align-items: center;
            color: #f8f8f2;
            font-size: larger;
            font-weight: bold;
            width: 100%;
            height: 50vh;
            border: greenyellow 1px solid;
            border-radius: 5px;
            box-shadow: #f8f8f2 0px 0px 10px;
        }

        h1 {
            font-size: 96px;
        }
    </style>
</head>

<body>
    <div class="container">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <input type="number" name="age" placeholder="Enter your age">
            <!-- <input type="number" name="b" placeholder="Enter the divisor"> -->
            <button type="submit" name="submit">Verify Liscence conditions</button>

        </form>
        <div class="division-result">
            <?php if (isset($result) && is_numeric($result)) {
                echo "condition fullfilled to get the driving license";
            } elseif (!is_numeric($result)) {
                # code...
                echo $result;
            } ?>
        </div>
    </div>
</body>

</html>