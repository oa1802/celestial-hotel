<?php 
	session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
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
		<h2>Escape to our luxury Miami Beach hotel</h2>
		<p>Come to the Celestial Hotel located in South Beach Miami. 
		Our hotel offers beautiful standard and luxury rooms and 
		access to ameneties including pools, dining and spa services. 
		Book with us today and experience South Beach.</p>
		<form action="booking.php" method="post">
			<label>Check-in Date:</label>
			<input type="date" name="checkin" value="2020-05-01" required><br><br>
			<label>Check-out Date:</label>
			<input type="date" name="checkout" value="2020-05-01" required><br><br>
			<label>Rooms:</label>
			<select name="rooms" required>
				<option value="">select</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select><br><br>
			<label>Adults:</label>
			<select name="adults" required>
				<option value="">select</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
			</select><br><br>
			<label>Children:</label>
			<select name="children" required>
				<option value="">select</option>
				<option value="0">0</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
			</select><br><br>
		  	<input type="submit" value="Submit">
		</form>
		<br>
		<p style="color: orange;">
			<strong>
				<?php 
					if(isset($_SESSION["search_message"])) {
						echo $_SESSION["search_message"];
					}
					$_SESSION["search_message"] = "";
				?>
			</strong>
		</p>
		<br>
		<img src="img\pool.jpg" height=300></img>
		<br>
		<br>
		<p>1020 Ocean Drive, Miami Beach, FL 33139</p>
		<a href="mailto:info@celestialhotel.com"><p>info@celestialhotel.com</p></a>
		<p>(000) 000-0000</p>
		<p>Directions: Located in South Beach's Art Deco Historic District on Ocean Drive between 10th and 11th Streets</p>
		<br>
		<br>
	</body>
</html>
