<?php
	session_start();
	$_SESSION["selected_rooms"] = [];
	for($i = 0; $i < sizeof($_SESSION["available_rooms"]); $i++) {
		for($j = 1; $j <= 20; $j++) {
			if(isset($_POST[$j]) && $_POST[$j] == $_SESSION["available_rooms"][$i]["id"]) {
				array_push($_SESSION["selected_rooms"], $_SESSION["available_rooms"][$i]);
			}
		}
	};	
	if(sizeof($_SESSION["selected_rooms"]) < $_SESSION["rooms"]) {
		$_SESSION["booking_message"] = "Invalid selection - you must select at least " . $_SESSION["rooms"] . " room(s).";
		header("Location: booking.php");
	}
	$_SESSION["beds"] = 0;
	for($i = 0; $i < sizeof($_SESSION["selected_rooms"]); $i++) {
		$_SESSION["beds"] = $_SESSION["beds"] + $_SESSION["selected_rooms"][$i]["beds"];
	}
	if($_SESSION["guests"] > $_SESSION["beds"] * 2 && $_SESSION["booking_message"] == "") {
		$_SESSION["booking_message"] = "Invalid selection - you must select rooms with enough beds for " . $_SESSION["guests"] . " persons.";
		header("Location: booking.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/confirmation.css">
	</head>
	<body>
		<a href="./"><h1>Celestial Hotel</h1></a>
		<nav class="nav navbar-nav">
			<li><a href="amenities">Amenities</a></li>
			<li><a href="attractions">Attractions</a></li>
			<li><a href="services">Services</a></li>
		</nav>
		<br>
		<br>
		<br>
		<form action="confirmed.php" method="post">
			<table>
				<tr>
					<td><label>First Name:</label></td>
					<td><input type="text" name="first_name" required><br><br></td>
				</tr>
				<tr>
					<td><label>Last Name:</label></td>
					<td><input type="text" name="last_name" required><br><br></td>
				</tr>
				<tr>
					<td><label>Address:</label></td>
					<td><input type="text" name="address" required><br><br></td>
				</tr>
				<tr>
					<td><label>City:</label></td>
					<td><input type="text" name="city" required><br><br></td>
				</tr>
				<tr>
					<td><label>State:</label></td>
					<td><input type="text" name="state" required><br><br></td>
				</tr>
				<tr>
					<td><label>ZIP:</label></td>
					<td><input type="text" pattern="[0-9]{5}" name="zip" required><br><br></td>
				</tr>
				<tr>
					<td><label>Phone:</label></td>
					<td><input type="tel" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" size="20" placeholder="###-###-####" required><br><br></td>
				</tr>
				<tr>
					<td><label>Email:</label></td>
					<td><input type="email" name="email" required><br><br></td>
				</tr>
			</table>
			<div>
				<table>
					<tr>
						<th>Preview</th>
						<th>Room Type</th>
						<th>Bed Type</th>
						<th>Beds</th>
						<th>Check-in</th>
						<th>Check-out</th>
						<th>Price</th>
					</tr>
					<?php
						for($i = 0; $i < sizeof($_SESSION["selected_rooms"]); $i++) {
							echo "<tr>" .
									"<td><img src=\"img/" . $_SESSION["selected_rooms"][$i]["type"] . ".jpg\" height=100/></td>" .
									"<td>" . $_SESSION["selected_rooms"][$i]["type"] . "</td>" .
									"<td>" . $_SESSION["selected_rooms"][$i]["bed_type"] . "</td>" .
									"<td>" . $_SESSION["selected_rooms"][$i]["beds"] . "</td>" .
									"<td>" . date("m/d/Y", strtotime($_SESSION["checkin"])) . "</td>" .
									"<td>" . date("m/d/Y", strtotime($_SESSION["checkout"])) . "</td>" .
									"<td>$" . $_SESSION["selected_rooms"][$i]["price"] . ".00 X " . $_SESSION["days"] . "</td>" .
								"</tr>";	
						}
						$_SESSION["total"] = 0;
						for($i = 0; $i < sizeof($_SESSION["selected_rooms"]); $i++) {
							$_SESSION["total"] += ($_SESSION["selected_rooms"][$i]["price"] * $_SESSION["days"]);
						}
						echo "<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><strong>Total</strong></td>
								<td><strong>$" . $_SESSION["total"] . ".00</strong></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><input type=\"submit\" value=\"Confirm\"></td>
							</tr>";
					?>
				</table>
				<br>
				<br>
			</div>
		</form>
	</body>
</html>