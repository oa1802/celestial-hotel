<?php
	session_start();
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$address = $_POST["address"];
	$city = $_POST["city"];
	$state = $_POST["state"];
	$zip = $_POST["zip"];
	$phone = $_POST["phone"];
	$email = $_POST["email"];
	$confirmation_number = rand(1000000,9999999);
	$db = new PDO("mysql:host=localhost;dbname=omaralkhalilidatabase;port=3306", "omaralkhalili", "");
	$customer_exists_query = "SELECT id FROM customers WHERE first_name = :first_name AND last_name = :last_name AND email = :email;";
	$query_execution = $db->prepare($customer_exists_query);
	$query_execution->bindValue(':first_name', $first_name);
	$query_execution->bindValue(':last_name', $last_name);
	$query_execution->bindValue(':email', $email);
	$query_execution->execute();
	$customer_id = $query_execution->fetch();
	if($customer_id == false) {
		$create_new_customer =
		"INSERT INTO customers
			(id, first_name, last_name, address, city, state, zip, phone, email)
		VALUES
			(NULL, :first_name, :last_name, :address, :city, :state, :zip, :phone, :email);";
		$query_execution = $db->prepare($create_new_customer);
		$query_execution->bindValue(':first_name', $first_name);
		$query_execution->bindValue(':last_name', $last_name);
		$query_execution->bindValue(':address', $address);
		$query_execution->bindValue(':city', $city);
		$query_execution->bindValue(':state', $state);
		$query_execution->bindValue(':zip', $zip);
		$query_execution->bindValue(':phone', $phone);
		$query_execution->bindValue(':email', $email);
		$query_execution->execute();
		$select_customer_id = "
		SELECT id FROM customers
		WHERE first_name = :first_name
		AND last_name = :last_name
		AND address = :address
		AND city = :city
		AND state = :state
		AND zip = :zip
		AND phone = :phone
		AND email = :email;";
		$query_execution = $db->prepare($select_customer_id);
		$query_execution->bindValue(':first_name', $first_name);
		$query_execution->bindValue(':last_name', $last_name);
		$query_execution->bindValue(':address', $address);
		$query_execution->bindValue(':city', $city);
		$query_execution->bindValue(':state', $state);
		$query_execution->bindValue(':zip', $zip);
		$query_execution->bindValue(':phone', $phone);
		$query_execution->bindValue(':email', $email);
		$query_execution->execute();
		$customer_id = $query_execution->fetch();
		$customer_id = $customer_id["id"];
	} else {
		$customer_id = $customer_id["id"];
		$update_customer_record =
		"UPDATE customers 
		SET address = :address, city = :city, state = :state, zip = :zip, phone = :phone
		WHERE id = :customer_id AND first_name = :first_name AND last_name = :last_name AND email = :email;
			(NULL, :first_name, :last_name, :address, :city, :state, :zip, :phone, :email);";
		$query_execution = $db->prepare($update_customer_record);
		$query_execution->bindValue(':customer_id', $customer_id);
		$query_execution->bindValue(':first_name', $first_name);
		$query_execution->bindValue(':last_name', $last_name);
		$query_execution->bindValue(':address', $address);
		$query_execution->bindValue(':city', $city);
		$query_execution->bindValue(':state', $state);
		$query_execution->bindValue(':zip', $zip);
		$query_execution->bindValue(':phone', $phone);
		$query_execution->bindValue(':email', $email);
		$query_execution->execute();
	}
	$create_new_booking =
	"INSERT INTO bookings (id, customer_id, confirmation_number, price)
	VALUES (NULL, :customer_id, :confirmation_number, :price);";
	$query_execution = $db->prepare($create_new_booking);
	$query_execution->bindValue(':customer_id', $customer_id);
	$query_execution->bindValue(':confirmation_number', $confirmation_number);
	$query_execution->bindValue(':price', $_SESSION["total"]);
	$query_execution->execute();
	$select_booking_id = "SELECT id FROM bookings WHERE customer_id = :customer_id AND confirmation_number = :confirmation_number;";
	$query_execution = $db->prepare($select_booking_id);
	$query_execution->bindValue(':customer_id', $customer_id);
	$query_execution->bindValue(':confirmation_number', $confirmation_number);
	$query_execution->execute();
	$booking_id = $query_execution->fetch();
	$booking_id = $booking_id["id"];
	$update_inventory =
	"UPDATE inventory SET booked=1, booking_id = :booking_id WHERE date >= :checkin AND date <= :checkout AND room_id IN (";
	for($i = 0; $i < sizeof($_SESSION["selected_rooms"]); $i++) {
		$update_inventory = $update_inventory . $_SESSION["selected_rooms"][$i]["id"];
		if($i < sizeof($_SESSION["selected_rooms"]) - 1) {
			$update_inventory = $update_inventory . ", ";
		}
	}
	$update_inventory = $update_inventory . ");";
	$query_execution = $db->prepare($update_inventory);
	$query_execution->bindValue(':booking_id', $booking_id);
	$query_execution->bindValue(':checkin', $_SESSION["checkin"]);
	$query_execution->bindValue(':checkout', $_SESSION["true_checkout"]);
	$query_execution->execute();
	$query_execution->closeCursor();
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
		<br>
		<h2>Thank you <?php echo $first_name . " " . $last_name?>. You're booked! Your confirmation number is <?php echo $confirmation_number ?>.</h2><br>
		<img src="img/booked.jpg" height=300>
	</body>
</html>
