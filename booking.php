<?php
	session_start();
	if(isset($_POST["checkin"])) {
		$_SESSION["checkin"] = date("Y/m/d", strtotime($_POST["checkin"]));
		$_SESSION["checkout"] = date("Y/m/d", strtotime($_POST["checkout"]));
		$_SESSION["true_checkout"] = date("Y/m/d", (strtotime($_POST["checkout"]) - 1));
		$_SESSION["days"] = (strtotime($_POST["checkout"]) - strtotime($_POST["checkin"])) / 86400;
		$_SESSION["rooms"] = $_POST["rooms"];
		$_SESSION["adults"] = $_POST["adults"];
		$_SESSION["children"] = $_POST["children"];
		$_SESSION["guests"] = $_SESSION["adults"] + $_SESSION["children"];
	}
	if($_SESSION["checkin"] > $_SESSION["checkout"]) {
		$_SESSION["search_message"] = "Invalid selection - Check-in date can not be after the check-out date.";
		header("Location: index.php");
	}
	if($_SESSION["checkin"] == $_SESSION["checkout"]) {
		$_SESSION["search_message"] = "Invalid selection - Check-in date and check-out date cannot be the same.";
		header("Location: index.php");
	}
	if($_SESSION["guests"] > 4 * $_SESSION["rooms"]) {
		$_SESSION["search_message"] = "Invalid selection - you may have input too many guests and too few rooms. Rooms should not have more than 4 persons.";
		header("Location: index.php");
	}
	$db = new PDO("mysql:host=localhost;dbname=omaralkhalilidatabase;port=3306", "omaralkhalili", "");
	$available_rooms_query =
	"SELECT r.id, r.room_type, rt.beds, rt.room_size, rt.price, rs.type, rs.bed_type 
	FROM rooms r JOIN room_types rt ON r.room_type = rt.id JOIN room_sizes rs on rt.room_size = rs.type 
	WHERE r.id NOT IN (SELECT DISTINCT room_id FROM inventory 
	WHERE date >= \"" . $_SESSION["checkin"] . "\" AND date <= \"" . $_SESSION["true_checkout"] . "\" AND booked = 1) 
	AND r.id IN (SELECT DISTINCT room_id FROM inventory WHERE date >= \"" . $_SESSION["checkin"] . "\" AND date <= \"" . $_SESSION["true_checkout"] . "\" AND booked = 0);"; 
	$query_execution = $db->prepare($available_rooms_query);
	$query_execution->execute();
	$_SESSION["available_rooms"] = $query_execution->fetchall();
	$query_execution->closeCursor();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/booking.css">
	</head>
	<body style="background-color: CornflowerBlue;">
		<a href="./"><h1>Celestial Hotel</h1></a>
		<nav class="nav navbar-nav">
			<li><a href="amenities">Amenities</a></li>
			<li><a href="attractions">Attractions</a></li>
			<li><a href="services">Services</a></li>
		</nav>
		<br>
		<br>
		<br>
		<p style="color: orange;">
			<strong>
				<?php
					if(isset($_SESSION["booking_message"])) {
						echo $_SESSION["booking_message"];
					}
					$_SESSION["booking_message"] = "";
				?>
			</strong>
		</p>
		<form action="confirmation.php" method="post">
			<table>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><input type="submit" value="Continue"></td>
				</tr>
				<tr>
					<th>Preview</th>
					<th>Room Type</th>
					<th>Bed Type</th>
					<th>Beds</th>
					<th>Price</th>
					<th>Select</th>
				</tr>
				<?php
					if(sizeof($_SESSION["available_rooms"]) > 0) {
						for($i = 0; $i < sizeof($_SESSION["available_rooms"]); $i++) {
							echo "<tr>" .
									"<td><img src=\"img/" . $_SESSION["available_rooms"][$i]["type"] . ".jpg\" height=100/></td>" .
									"<td>" . $_SESSION["available_rooms"][$i]["type"] . "</td>" .
									"<td>" . $_SESSION["available_rooms"][$i]["bed_type"] . "</td>" .
									"<td>" . $_SESSION["available_rooms"][$i]["beds"] . "</td>" .
									"<td>$" . $_SESSION["available_rooms"][$i]["price"] . ".00 X " . $_SESSION["days"] . " = $" . $_SESSION["available_rooms"][$i]["price"] * $_SESSION["days"] . ".00</td>" .
									"<td><input type=\"checkbox\" name=\"" . $_SESSION["available_rooms"][$i]["id"] . "\" value=\"" . $_SESSION["available_rooms"][$i]["id"] . "\"></td>
								</tr>";	
						}
					} else {
						echo "<tr>
								<td colspan=6>No rooms available in that date range.</td>
							</tr>";	
					}
				?>
			</table>
		</form>
		<br>
		<br>
	</body>
</html>
