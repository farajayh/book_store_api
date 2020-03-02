<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    require_once "../config/db_connection.php";
    require_once "../objects/books.php";
 
    // instantiate database and book object
    $database = new Database();
    $db = $database->db_conn();
 
    // initialise object
    $book = new Book($db);

    // query books
    $stmt = $book->read();
    $num = $stmt->rowCount();
 
    // check if more than 0 record found
    if($num>0){
 
    // initialise books array
    $books_arr=array();
    $books_arr["records"]=array();
 
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $book_item=array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description),
            "author" => $author,
            "avg_rating" => round($book->get_avg_rating($id), 1)
        );
 
        array_push($books_arr["records"], $book_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show books data in json format
    echo json_encode($books_arr);
   }else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // display message
    echo json_encode(
        array("message" => "No book found.")
    );
}
?>