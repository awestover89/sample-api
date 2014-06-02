<?php

// Connect to MySQL
$mysqli = new mysqli("mysql1.000webhost.com","a6916718_admin","a6916718","a6916718_maindb");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connection to MySQL failed: %s\n", mysqli_connect_error());
    exit();
}

$url_pieces = explode('/', $_SERVER['REQUEST_URI']);
$call = $url_pieces[1];
$url_pieces = array_slice($url_pieces, 2);
$params = array();
foreach ($url_pieces as $index => $val)
{
    if ($index % 2 == 0)
        $params[$val] = $url_pieces[$index+1];
}

if (!empty($call) && function_exists($call) )
    call_user_func($call, $params);
else
    die("That is not a valid API call");

function get_db()
{
    static $db;

    if (!$db)
        $db = new mysqli("mysql1.000webhost.com","a6916718_admin","a6916718","a6916718_maindb");

    return $db;
}

function branding_lookup($params)
{
    if (empty($params['url']))
        return null;
    $db = get_db();
    $url = $db->real_escape_string($params['url']);
    $sql = "SELECT name, logo, url FROM `urlbrands` WHERE `url` LIKE '%$url%'";
    $result = $db->query($sql);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    
    //Send the Result
    header('HTTP/1.1 200 OK');  
    header('Content-type: application/json');
    echo json_encode($row);
    exit;
}

?>		
