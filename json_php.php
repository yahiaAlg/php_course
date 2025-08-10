<?php
// $student = [
//     'name' => 'Alice Johnson',
//     'age' => 22,
//     'grades' => [
//         [
//             "subject" => "math",
//             "score" => 10
//         ],
//         [
//             "subject" => "natural science",
//             "score" => 15
//         ],
//         [
//             "subject" => "social sciences",
//             "score" => 16
//         ],
//         [
//             "subject" => "physics",
//             "score" => 17
//         ],
//     ],
//     'is_honors' => true,
//     'advisor' => null
// ];

// // convert this associative array into JSON
// $data = json_encode($student, JSON_PRETTY_PRINT);
// echo "<pre>";
// print_r($data);
// echo "</pre>";
// mkdir("./data/", 0777);
// file_put_contents("data/student_semi_structured_data.json", $data);

// $extracted_data = file_get_contents("data/student_semi_structured_data.json");
// if (isset($extracted_data) && !empty($extracted_data)) {
//     echo "<h1>JSON DATA BEFORE CONVERSINO</h1>";
//     echo "<pre>";
//     print_r($extracted_data);
//     echo "</pre>";


//     // conversion into php associative array
//     $student_associative_data_array = json_decode($extracted_data, true);
//     echo "<h1>JSON DATA AFTER CONVERSION INTO ASSOCIATIVE ARRAY</h1>";
//     echo "<pre>";
//     print_r($student_associative_data_array);
//     echo "</pre>";
//     $student_associative_data_array["name"] = "yahia";
//     unset($student_semi_structured_data["advisor"]);

//     file_put_contents("data/student_semi_structured_data.json", json_encode($student_associative_data_array));
// }



// XML
// $productsData = [
//     ['id' => 1, 'name' => 'Laptop', 'price' => 999.99],
//     ['id' => 2, 'name' => 'Mouse', 'price' => 25.50],
//     ['id' => 3, 'name' => 'Keyboard', 'price' => 75.00]
// ];


// $productsXML = new SimpleXMLElement('<products></products>');
// foreach ($productsData as $productData) {
//     $productTag = $productsXML->addChild("product");
//     $productTag->addAttribute("id", $productData["id"]);
//     $productTag->addChild("name", $productData["name"]);
//     $productTag->addChild("price", $productData["price"]);
// }

// echo "<pre>";
// echo $productsXML->asXML();
// echo "</pre>";

// file_put_contents("data/student_semi_structured_data.xml",  $productsXML->asXML());
// $productsXML = file_get_contents("data/student_semi_structured_data.xml");

// $product_associative_array = simplexml_load_string($productsXML);
// echo "<h1>RAW XML PRODUCTS DATA</h1>";
// echo "<pre>";
// echo $productsXML;
// echo "</pre>";
// echo "<h1>AFTER CONVERSION TO PHP ASSOCIATIVE ARRAY</h1>";
// echo "<pre>";
// print_r($product_associative_array);
// echo "</pre>";


// $employeesCsvData = "name,age,city,job
// Alice,28,Boston,Developer
// Bob,32,Seattle,Designer
// Charlie,29,Austin,Manager
// Diana,31,Denver,Analyst";

// file_put_contents('data/employees.csv', $employeesCsvData);

// $employees = [];
// if (($file = fopen("data/employees.csv", "r")) !== false) {
//     $headers = fgetcsv($file);

//     while (($dataRow = fgetcsv($file)) !== false) {
//         $employees[] = array_combine($headers, $dataRow);
//     }
//     echo "<pre>";
//     print_r($employees);
//     echo "</pre>";
// }

// $products = [
//     ['Product A', 29.99, 15, 'Electronics'],
//     ['Product B', 45.50, 8, 'Home'],
//     ['Product C', 12.25, 25, 'Books'],
//     ['Product D', 89.99, 5, 'Electronics']
// ];

// $headers = ["name", "price", "quantity", "category"];
// $productCsvFile = fopen("data/products.csv", "w");
// $header_line = implode(",", $headers) . "\n"; // "name,price,category"
// fwrite($productCsvFile, $header_line);
// foreach ($products as $product) {
//     $product_line = implode(",", $product) . "\n";
//     fwrite($productCsvFile, $product_line);
// }
// fclose($productCsvFile);


// serialisation


// $userData = [
//     'username' => 'john_doe',
//     'email' => 'john@example.com',
//     'preferences' => [
//         'theme' => 'dark',
//         'language' => 'en'
//     ],
//     'last_login' => new DateTime('2024-01-15 10:30:00'),
//     'login_count' => 42
// ];

// $json_user_data = serialize($userData);

// echo "<pre>";
// echo $json_user_data;
// echo "</pre>";
// file_put_contents("data/user_data_ser.ser", $json_user_data);


$loadedData = file_get_contents('data/user_data_ser.ser');
$restored = unserialize($loadedData);
echo "Username: " . $restored['username'] . "\n";
echo "Last login: " . $restored['last_login']->format('Y-m-d') . "\n";
