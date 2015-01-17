<?php

// include configuration file
include('config.php');
	
// connect to the database
$db = mysqli_connect ($db_host, $db_user, $db_password, $db_name) OR die ('Could not connect to MySQL: ' . mysqli_connect_error());

// continue session
session_start();

if(isset($_POST['submit']))
{
	// create an empty error array
	$error = array();

	// check for a firstname
	if(empty($_POST['isbn']))
	{
		$error['isbn'] = 'Required field';
	}
    
    if(empty($_POST['price']))
	{
		$error['price'] = 'Required field';
	}
    
    if(empty($_POST['haggle']))
	{
		$_POST['haggle'] = 'no';
	}
	
	// if there are no errors
	if(sizeof($error) == 0)
	{
        $book = file_get_contents("https://www.googleapis.com/books/v1/volumes?q={$_POST['isbn']}");
        $json = json_decode($book, true);

        $title = $json['items'][0]['volumeInfo']['title'];
        $authors = @implode(",", $json['items'][0]['volumeInfo']['authors']);
        $subject = $json['items'][0]['volumeInfo']['categories'][0];
        $seller = $_SESSION['user_id'];
		// insert user into the users table
		$sql = "INSERT INTO `selling`(`list`, `isbn`, `title`, `author`, `subject`, `price`, `seller_id`, `lon`, `lat`, `haggle`) VALUES (null,'{$_POST['isbn']}','$title','$authors','$subject','{$_POST['price']}','$seller','{$_POST['lon']}','{$_POST['lat']}','{$_POST['haggle']}')";
		$result = mysqli_query($db, $sql);
    
    // redirect user to profile page
    header("Location: index.php");
    exit();
    }
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Shoutbox</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- jQuery -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

		<!-- bootstrap -->
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="css.css">			
	</head>
    <script>
        var x = document.getElementById("demo");
        
        function getLocation() {
            if (navigator.geolocation) {
		        navigator.geolocation.getCurrentPosition(getPosition);
		    } else { 
		        document.getElementById("demo").innerHTML = "Geolocation is not supported by this browser.";
		    }
        }
        
        function getPosition(position) {
            var lat = position.coords.latitude;
            var lon = position.coords.longitude;
            $("#passlon").html('<input name="lon" type="text" value="'+lon+'" class="form-control" />');
            $("#passlat").html('<input name="lat" type="text" value="'+lat+'" class="form-control" />');
        }
        
        function showModal(isbn){
            $("#modal").toggle(200);
            $("#passisbn").html('<input name="isbn" type="text" value="'+isbn+'" class="form-control" />');
        }
        
    </script>
	<body onload="getLocation();">
		
		<!-- content -->	
		
            
            <?php
            
            if($_GET['type'] == "isbn"){
                $page = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:{$_GET['data']}");
                $data = json_decode($page);
                foreach($data->items as $mydata)
                {
                    echo' <div id="'. $_GET['data'] .' style="margin-top:65px;background-color:white;border-radius:5px;padding:10px;" onclick="showModal(this.id);">';
                    echo "<img src=". $mydata->volumeInfo->imageLinks->smallThumbnail .">";
                    echo "<h3>". $mydata->volumeInfo->title ."</h3>";
//                    echo "<p>". @implode(", ", $mydata->volumeInfo->authors ."</p>";
                    echo "</div>";
                }   
            }else if($_GET['type'] == "title"){
                $page = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=intitle:{$_GET['data']}");
                $data = json_decode($page);
                foreach($data->items as $mydata)
                {
                    echo' <div id="'. $_GET['data'] .' style="margin-top:65px;background-color:white;border-radius:5px;padding:10px;" onclick="showModal(this.id);">';
                    echo "<img src=". $mydata->volumeInfo->imageLinks->smallThumbnail .">";
                    echo "<h3>". $mydata->volumeInfo->title ."</h3>";
//                    echo "<p>". @implode(", ", $mydata->volumeInfo->authors ."</p>";
                    echo "</div>";
                } 
            }else if($_GET['type'] == "author"){
                $page = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=inauthor:{$_GET['data']}");
                $data = json_decode($page);
                foreach($data->items as $mydata)
                {
                    echo' <div id="'. $_GET['data'] .' style="margin-top:65px;background-color:white;border-radius:5px;padding:10px;" onclick="showModal(this.id);">';
                    echo "<img src=". $mydata->volumeInfo->imageLinks->smallThumbnail .">";
                    echo "<h3>". $mydata->volumeInfo->title ."</h3>";
//                    echo "<p>". @implode(", ", $mydata->volumeInfo->authors ."</p>";
                    echo "</div>";
                }   
            }

            ?>
<!--
            <img src="<?php echo $imgurllist; ?>">
            
            <h3><?php echo $titlelist; ?></h3>
            
            <p><?php echo $authorslist; ?></p>
-->
            
            <div id="demo"></div>
        
         <form id="modal" method="post" action="sellablebooks.php" style="margin:20px;">

            <h1 style="text-align:center;margin-bottom:10px;">Select Price</h1>


            <!-- first name -->
            <div class="form-group">
                <label>Price</label>
                <input name="price" type="text" class="form-control" />
                <span class="text-danger"><?php echo $error['price']; ?></span>
            </div>

            <div class="form-group">
                <label>Negotiable?</label>
                <input type="checkbox" name="haggle" value="haggle"><br>
                <span class="text-danger"><?php echo $error['haggle']; ?></span>
            </div>

            <div id="passisbn" class="form-group" style="visibility:hidden;">
            </div>
             
            <div id="passlon" class="form-group" style="visibility:hidden;">
            </div>
             
            <div id="passlat" class="form-group" style="visibility:hidden;">
            </div>

            <!-- submit button -->
            <div class="form-group">
                <input name="submit" type="submit" value="Sell Book" class="btn btn-primary" />
                <button type="button" class="btn btn-danger">Cancel</button>
            </div>

        </form>

	</body>
</html>