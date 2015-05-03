<?php
require_once("../includes/config.php");

switch($_GET["type"])
{
	case "priceWatch":
		InsertPriceWatch($_GET["desiredPrice"], $_GET["email"], $_GET["asin"]);
		break;
	default:
		echo "denied";
}

function InsertPriceWatch($desiredPrice, $email, $asin)
{
	global $DBO;

	/*
	
	***person table***
	person_id
	person_first_name
	person_last_name

	**email table***
	email_id
	email_address
	person_id

	***person_wish_list_item***
	person_wish_list_item_id
	person_wish_list_item_desired_price
	person_wish_list_item_is_tracking
	amazon_product_asin
	person_id

	*/

	$personId = -1;
	$firstName = "";
	$lastName = "";

	if($statement = $DBO->prepare("SELECT person_id FROM email WHERE email_address = ?"))
	{

		echo "Checking if person_id exists...<br>";

		$statement->bind_param("s", $email);

		$statement->execute();

		$statement->bind_result($resultPersonId);

		$statement->fetch();

		if($resultPersonId != null)
		{
			$personId = $resultPersonId;
		}

		$statement->close();

		echo "personId: " . $personId . "<br>";
	}

	if($personId <= 0)
	{
		// create new person record
		echo "Creating new person<br>";
		if($statement = $DBO->prepare("INSERT INTO person (person_first_name, person_last_name) VALUES (?, ?)"))
		{
			$statement->bind_param("ss", $firstName, $lastName);

			$statement->execute();

			$personId = $DBO->insert_id;

			$statement->close();

			echo "personId: " . $personId . "<br>";
		}

		// create new email record
		echo "Creating new email record<br>";
		if($statement = $DBO->prepare("INSERT INTO email (email_address, person_id) VALUES (?, ?)"))
		{
			$statement->bind_param("ss", $email, $personId);

			$statement->execute();

			$statement->close();
		}
	}

	// insert new wish list item for tracking
	if($statement = $DBO->prepare("INSERT INTO person_wish_list_item (person_wish_list_item_desired_price, person_wish_list_item_is_tracking, amazon_product_asin, person_id) VALUES(?, b'1', ?, ?)"))
	{
		$statement->bind_param("sss", $desiredPrice, $asin, $personId);

		$statement->execute();

		$statement->close();

		echo "Inserted new person_wish_list_item";
	}
}