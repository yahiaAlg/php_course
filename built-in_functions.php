<?php

// // unsetting variables 
// $current_user = "adam";



// echo "Before deleting the user: " . $current_user . "<br>";

// //after a changing the value of the variable
// $current_user = "john";
// echo "After changing the user: " . $current_user . "<br>";

// // // Unsetting the variable
// // unset($current_user);
// // echo "After deleting the user: " . $current_user . "<br>";



// // now for table of users
// $users = [
//     "Adam Smith",
//     "John Doe",
//     "Jane Doe",
//     "Alice Johnson",
//     "Bob Brown"
// ];
// for ($i = 0; $i < count($users); $i++) {
//     echo "User " . $i . ": " . $users[$i] . "<br>";
// }

// // deleting the last user   
// unset($users[count($users) - 1]);

// echo "<h1>After deleting the last user:</h1> <br>";
// for ($i = 0; $i < count($users); $i++) {
//     echo "User " . $i . ": " . $users[$i] . "<br>";
// }



// // checking if the 3rd user exists
// if (isset($users[2])) {
//     echo "The 3rd user is: " . $users[2] . "<br>";
// } else {
//     echo "The 3rd user does not exist.<br>";
// }


// // unsetting the 3rd user
// unset($users[2]);
// echo "<h1>After unsetting the 3rd user:</h1> <br>";
// // checking if the 3rd user exists and his value is not null 
// if (isset($users[2])) {
//     echo "The 3rd user is: " . $users[2] . "<br>";
// } else {
//     echo "The 3rd user does not exist after unsetting.<br>";
//     echo "The 3rd user is now null." .  $users[2] . "<br>";
// }



// // checking if a user named alice exists
// $user_to_check = "Alice Johnson";
// if (in_array($user_to_check, $users)) {
//     echo "User " . $user_to_check . " exists in the users list.<br>";
// } else {
//     echo "User " . $user_to_check . " does not exist in the users list.<br>";
// }


// // getting the index of a user named alice 
// $user_index = array_search($user_to_check, $users);
// if ($user_index !== false) {
//     echo "User " . $user_to_check . " is found at index: " . $user_index . "<br>";
// } else {
//     echo "User " . $user_to_check . " is not found in the users list.<br>";
// }

// // getting jane's index
// $user_to_check = "Jane Doe";
// $user_index = array_search($user_to_check, $users);

// if ($user_index !== false) {
//     echo "User " . $user_to_check . " is found at index: " . $user_index . "<br>";
// } else {
//     echo "User " . $user_to_check . " is not found in the users list.<br>";
// }

// function testFunction()
// {
//     echo "This is a test function.<br>";
//     echo "Current function name: " . __FUNCTION__ . "<br>";
// }

// // MAGIC CONSTANTS
// echo "Current file name: " . __FILE__ . "<br>";
// echo "Current directory: " . __DIR__ . "<br>";
// echo "Current line number: " . __LINE__ . "<br>";
// echo "Current function name: " . __FUNCTION__ . "<br>";
// testFunction();


// // speaking about classes and objects 
// // classes are blueprints for creating objects, they are like complex types  
// // $username = "User"; // primitive type
// // $email = "user@example.com"; // primitive type
// // $age = 25; // primitive type
// // $balance = 100.50; // primitive type
// // $days_active = 30; // primitive type

// // creating new type of data named user via associative (old way)
// $user = [
//     "username" => "User",
//     "email" => "user@example.com",
//     "age" => 25,
//     "balance" => 100.50,
//     "days_active" => 30
// ];
// second way of creating a user is via class
class User
{
    // properties
    private string $username;
    public string $email;
    private int $age = 27;
    private float $balance = 100;
    private int $days_active = 0;

    // constructor
    public function __construct(string $given_username, string $given_email, int $given_age, float $given_balance, int $given_days_active)
    {
        $this->username =  is_string($given_username) ? $given_username : "anonymous"; // if not string then set to unknown
        $this->email = filter_var($given_email, FILTER_VALIDATE_EMAIL) ? $given_email : "user@example.com";

        if ($given_age < 0) {
            $this->age = 18;
        } else {
            $this->age = $given_age;
        }
        if ($given_balance < 0) {
            $this->balance = 0;
        } else {
            $this->balance = $given_balance;
        }

        $this->days_active = $given_days_active < 0 ? 0 : $given_days_active;
    }

    // methods
    function login()
    {
        echo "User " . $this->username . " logged in.<br>";
        echo "User email: " . $this->email . "<br>";
        // added days active
        $this->days_active++;
    }

    function logout()
    {
        echo "User" . $this->username . " logged out.<br>";
    }
    function getAge(): int
    {
        return $this->age;
    }
    function setAge(int $age)
    {
        if ($age > 0) {
            $this->age = $age;
        } else {
            echo "Age must be a positive number.<br>";
        }
    }



    function getBalance(): float
    {
        return $this->balance;
    }

    function addToBalance(int $addedbalance)
    {
        if ($addedbalance >= 0) {
            $this->balance += $addedbalance;
        } else {
            echo "Balance cannot be negative.<br>";
        }
    }
    // deducting from balance
    function deductFromBalance(int $deductedbalance)
    {
        if ($this->balance < $deductedbalance) {
            echo "Insufficient balance to deduct " . $deductedbalance . ".<br>";
            return;
        }
        if ($deductedbalance >= 0) {
            $this->balance -= $deductedbalance;
            echo "Deducted " . $deductedbalance . " from balance. New balance: " . $this->balance . "<br>";
        } else {
            echo "Deducted balance cannot be negative.<br>";
        }
    }
    function getDaysActive(): int
    {
        return $this->days_active;
    }

    function setUsername(string $new_username)
    {
        if (is_string($new_username) && (strlen($new_username) > 0 && strlen($new_username) <= 35)) {
            $this->username = $new_username;
        } elseif (strlen($new_username) > 35) {
            echo "Username must be between 1 and 35 characters long.<br>";
        } elseif (!is_string($new_username)) {
            echo "Username must be alphabetic.<br>";
        }
    }

    function getFirstName(): string
    {
        $username_serparated = explode(" ", $this->username);
        if (count($username_serparated) > 2) {
            return $username_serparated[0] . " " . $username_serparated[1];
        } elseif (count($username_serparated) == 2) {
            return $username_serparated[0];
        } else {
            return $username_serparated[0];
        }
    }
}




$user_2 =  new User(
    "Jane Doe",
    "jane@example.com",
    25,
    1000,
    10
);

$user_2->login(); // this will cause an error because the user is not logged

// add balance to user_2
$user_2->addToBalance(1000);
// deduct balance from user_2
$user_2->deductFromBalance(500);


echo "{$user_2->getFirstName()} current balance: " . $user_2->getBalance() . "<br>";


// changed her name due to typing error and changed to Maria Doe
$user_2->setUsername(356);

echo "the new username is " . $user_2->getFirstName();
