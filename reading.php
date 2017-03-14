<?php
/**
 * Created by PhpStorm.
 * User: Matic
 * Date: 4. 03. 2017
 * Time: 18:08
 */


$connection = mysqli_connect("test.winteh.pro", "root", "root123") or die(mysqli_error());

$database = mysqli_select_db($connection, "work");

$query = "SELECT * FROM employee ORDER BY id DESC LIMIT 1;";

$result = mysqli_query($connection, $query);


if (!empty($result))
{

    if (mysqli_num_rows($result) > 0)
    {
        $result = mysqli_fetch_array($result);

        $employee = array();

        $employee["id"] = $result["id"];
        $employee["name"] = $result["name"];
        $employee["surname"] = $result["surname"];
        $employee["position"] = $result["position"];

        $response["success"] = 1;

        $response["employee"] = array();

        array_push($response["employee"], $employee);


        $namesQuery = "SELECT Name FROM names;";
        $namesResult = mysqli_query($connection, $namesQuery);

        $response["names"] = array();

        while ($row = mysqli_fetch_array($namesResult))
        {
            $qwe = array();
            $qwe["name"] = $row["Name"];

            array_push($response["names"], $qwe);
        }


        echo json_encode($response);
    }
    else {
        $response["success"] = 0;
        $response["message"] = "Ni vnosov!";

        echo json_encode($response);
    }
}
else {
    $response["success"] = 0;
    $response["message"] = "Ni vnosov!";

    echo json_encode($response);
}

mysqli_close($connection);



?>