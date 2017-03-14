<?php
/**
 * Created by PhpStorm.
 * User: Matic
 * Date: 2. 03. 2017
 * Time: 20:36
 */




//echo "Hello World<br>";

$id_show = "";
$name_show = "";
$surname_show = "";
$position_show = "";
$show_values = ["", "", "", "", "", "", "", "", "", "", ""];

$connection = mysqli_connect("test.winteh.pro", "root", "root123") or die(mysqli_error());
/*
if ($connection) echo "Connection established!";
else echo "Connection failed!";
*/
$database = mysqli_select_db($connection, "work");
/*
if ($database) echo "<br>Database selected!";
else echo "Database NOT selected!";
*/


$numberOfRowsQuery = "SELECT COUNT(*) FROM information_schema.columns WHERE table_name =\"employee\";";
$numberOfRows = mysqli_fetch_array(mysqli_query($connection, $numberOfRowsQuery))[0];
$DEFAULT_ROWS = 4;

$namesQuery = "SELECT Name FROM names;";
$namesResult = mysqli_query($connection, $namesQuery);

//$names = mysqli_fetch_array($namesResult);

//print_r(array_values($names));
$names = array();


//$totalrecords = mysqli_num_rows($result);
while($row = mysqli_fetch_array($namesResult)) {
    $names[] = $row;
}


/*
while ($row = )
$namesArray["names"] = array();

*/


$namesQuery = "SELECT Name FROM names;";
$namesResult = mysqli_query($connection, $namesQuery);


$namesArray = array();

$namesArray["names"] = array();

while ($row = mysqli_fetch_array($namesResult))
{
    $qwe = array();
    $qwe["name"] = $row["Name"];

    array_push($namesArray["names"], $qwe);
}


/*
print_r(array_values(mysqli_fetch_all($namesResult)));

$namesArray = array();

while($result = mysqli_fetch_array($namesResult))
{
    $namesArray[] = $result;
}

*/

$insert_error = false;

if (isset($_REQUEST["action"])) {
    if ($_REQUEST["action"] == "Write") {
        //echo $numberOfRows;

        $status=true;
        if (isset($_POST["name"]) && isset($_POST["surname"]) && isset($_POST["position"]) && !empty($_POST["name"]) && !empty($_POST["surname"]) && !empty($_POST["position"]) )
        {
            for ($i = 0; $i < ($numberOfRows - $DEFAULT_ROWS); $i++)
            {
                if (!isset($_POST[$names[4+$i][0]]) || empty($_POST[$names[4+$i][0]])) $status = false;
            }

            if ($status ) {

                $name = $_POST["name"];
                $surname = $_POST["surname"];
                $position = $_POST["position"];

                $query = "INSERT INTO employee VALUES (null, '" . $name . "' , '" . $surname . "' , '" . $position . "' ";
                for ($i = 0; $i < ($numberOfRows - $DEFAULT_ROWS); $i++)
                {
                    $query .= ", '".$_POST[$names[4+$i][0]]."' ";
                }
                $query .= ");";


                $result = mysqli_query($connection, $query);
            }
            else echo "<br>Premalo vnesenih polj!";
/*
            echo "<br>" . $query;

            if ($result) echo "<br>Vnos uspešen!";
            else echo "<br>Vnos ni uspešen!";
*/
        }
        else $insert_error=true;
    }
    else if ($_REQUEST["action"] == "Read") {
        $query = "SELECT * FROM employee ORDER BY id DESC LIMIT 1;";

        $result = mysqli_query($connection, $query);

        if (!empty($result))
        {
            $array = mysqli_fetch_array($result);
            $id_show = $array[0];
            $name_show = $array[1];
            $surname_show = $array[2];
            $position_show = $array[3];



            for ($i = 0; $i < ($numberOfRows - $DEFAULT_ROWS); $i++)
            {
                $show_values[$i] = $array[4+$i];
            }
        }

    }
    else if ($_REQUEST["action"] == "Create")
    {

        if (isset($_POST["newname"]) && !empty($_POST["newname"]))
        {
            $name = $_POST["newname"];
            $type = $_POST["type"];

            $query = "ALTER TABLE employee ADD ".$name." ".$type.";";


            $result = mysqli_query($connection, $query);

            if ($result) mysqli_query($connection, "INSERT INTO names VALUES (null, '".$name."');");

            header("Location: http://test.winteh.pro/db_connect.php");
            die();
        }
    }
    else if ($_REQUEST["action"] == "Delete")
    {
        if ($numberOfRows > 4) {
            $deleteName = array_pop(array_slice($names, -1)[0]);

            $deleteQuery = "ALTER TABLE employee DROP ".$deleteName.";";
            mysqli_query($connection, $deleteQuery);


            $deleteQuery = "DELETE FROM names WHERE Name LIKE \"".$deleteName."\";";
            mysqli_query($connection, $deleteQuery);

            echo $deleteName;

            header("Location: http://test.winteh.pro/db_connect.php");
            die();
        }

    }
}





echo "
<fieldset style=\"width: 300px; display: inline-block; float: left; \">
    <legend>Write</legend>
<form method=\"post\" action=\"db_connect.php\" >
    ID: <input type=\"number\" name=\"id\" /> <br>
    Name: <input type=\"text\" name=\"name\" /> <br>
    Surname: <input type=\"text\" name=\"surname\" /> <br>
    Position: <select name=\"position\" >
    <option value=\"CEO\" >CEO </option>
    <option value=\"Manager\" >Manager </option>
        </select> ";
if ($numberOfRows > $DEFAULT_ROWS)
{
    for ($i = 0; $i < ($numberOfRows - $DEFAULT_ROWS); $i++)
    {
        echo "<br>".$names[4+$i][0].": <input type=\"text\" name=\"".$names[4+$i][0]."\" />";
    }
}

echo "
    <br> <br>
    <input type=\"submit\" value=\"Write\" name=\"action\">
</form>
</fieldset>

<fieldset style=\"width: 300px; display: inline-block;\">
    <legend>Read</legend>
    <form method=\"post\" action=\"db_connect.php\" >
        ID: <input type=\"number\" name=\"id\" value=\"$id_show\" /> <br>
        Name: <input type=\"text\" name=\"name\" value=\"$name_show\" /> <br>
        Surname: <input type=\"text\" name=\"surname\" value=\"$surname_show\" /> <br>
        Position: <input type=\"text\" name=\"position\" value=\"$position_show\"> ";
if ($numberOfRows > $DEFAULT_ROWS)
{
    for ($i = 0; $i < ($numberOfRows - $DEFAULT_ROWS); $i++)
    {
        $show_val = $show_values[$i];
        echo "<br>".$names[4+$i][0].": <input type=\"text\" name=\"position\" value=\"$show_val\" />";
    }
}

echo "<br><br>
        <input type=\"submit\" value=\"Read\" name=\"action\">
    </form>
</fieldset>
<fieldset style=\"width: 300px; display: inline-block; \" >
    <legend>Create</legend>
    <form method=\"post\" action=\"db_connect.php\" >
        Name: <input type=\"text\" name=\"newname\" /> <br>
        Type: <select name=\"type\" >
        <option value=\"Varchar(100)\">Text</option>
        <option value=\"Integer\">Integer</option>
    </select> <br><br>
        <input type=\"submit\" value=\"Create\" name=\"action\">
        <input type=\"submit\" value=\"Delete\" name=\"action\">
    </form>
</fieldset>

";


if ($connection) echo "<br>Connection established!";
else echo "<br>Connection failed!";

if ($database) echo "<br>Database selected!";
else echo "<br>Database NOT selected!";


if (isset($result) && $result) { echo "<br>Vnos uspešen!"; echo $query; }
else echo "<br>Vnos ni uspešen!";

if ($insert_error) echo "<br>Premalo vnesenih polj!<br>";


echo json_encode($namesArray);

mysqli_close($connection);

?>