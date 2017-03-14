<?php
/**
 * Created by PhpStorm.
 * User: Matic
 * Date: 4. 03. 2017
 * Time: 18:01
 */


$response = array();

if (isset($_POST["id"]) && isset($_POST["name"]) && isset($_POST["surname"]) && isset($_POST["position"]))
{
    $id = $_POST["id"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $position = $_POST["position"];

    require_once __DIR__."/database_connect.php";

    $database = new database_connect();

    $query = "INSERT INTO employee VALUES (null, '" . mysqli_real_escape_string($name) . "' , '" . mysqli_real_escape_string($surname) . "' , '" . mysqli_real_escape_string($position) . "');";

    $result = mysqli_query($connection, $query);

    if ($result)
    {
        $response["success"] = 1;
        $response["message"] = "Vnašanje uspešno!";

        echo json_encode($response);
    }
    else
    {
        $response["success"] = 0;
        $response["message"] = "Vnašanje ni uspešno!";

        echo json_encode($response);
    }

}
else
{
    $response["success"] = 0;
    $response["message"] = "Manjkajoča polja!";

    echo json_encode($response);
}

?>