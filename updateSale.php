<?php

	require_once'../../inc/config/constants.php';
	require_once'../../inc/config/db.php';
	
	// Definir constante para la consulta SQL de actualización de stock
	define('UPDATE_ITEM_STOCK_SQL', 'UPDATE item SET stock = :stock WHERE itemNumber = :itemNumber');
	
	if(isset($_POST['saleDetailsSaleID'])){

		$saleDetailsItemNumber = htmlentities($_POST['saleDetailsItemNumber']);
		$saleDetailsSaleDate = htmlentities($_POST['saleDetailsSaleDate']);
		$saleDetailsItemName = htmlentities($_POST['saleDetailsItemName']);
		$saleDetailsQuantity = htmlentities($_POST['saleDetailsQuantity']);
		$saleDetailsUnitPrice = htmlentities($_POST['saleDetailsUnitPrice']);
		$saleDetailsSaleID = htmlentities($_POST['saleDetailsSaleID']);
		$saleDetailsCustomerName = htmlentities($_POST['saleDetailsCustomerName']);
		$saleDetailsDiscount = htmlentities($_POST['saleDetailsDiscount']);
		$saleDetailsCustomerID = htmlentities($_POST['saleDetailsCustomerID']);
		
		$quantityInOriginalOrder = 0;
		$quantityInNewOrder = 0;
		$originalStockInItemTable = 0;
		$newStock = 0;
		
		// Check if mandatory fields are not empty
		if(isset($saleDetailsItemNumber) && isset($saleDetailsSaleDate) && isset($saleDetailsQuantity) && isset($saleDetailsUnitPrice) && isset($saleDetailsCustomerID)){
			
			// Sanitize item number
			$saleDetailsItemNumber = filter_var($saleDetailsItemNumber, FILTER_SANITIZE_STRING);
			
			// Validate item quantity. It has to be an integer
			if(filter_var($saleDetailsQuantity, FILTER_VALIDATE_INT) === 0 || filter_var($saleDetailsQuantity, FILTER_VALIDATE_INT)){
				// Quantity is valid
			} else {
				// Quantity is not a valid number
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for Quantity.</div>';
				exit();
			}
			
			// Validate unit price. It has to be an integer or floating point value
			if(filter_var($saleDetailsUnitPrice, FILTER_VALIDATE_FLOAT) === 0.0 || filter_var($saleDetailsUnitPrice, FILTER_VALIDATE_FLOAT)){
				// Valid unit price
			} else {
				// Unit price is not a valid number
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for Unit Price.</div>';
				exit();
			}
			
			// Validate discount
			if($saleDetailsDiscount !== ''){
				if(filter_var($saleDetailsDiscount, FILTER_VALIDATE_FLOAT) === 0.0 || filter_var($saleDetailsDiscount, FILTER_VALIDATE_FLOAT)){
				// Valid discount
				} else {
					// Discount is not a valid number
					echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a valid number for Discount.</div>';
					exit();
				}
			}
			
			// Check if saleID is empty
			if($saleDetailsSaleID == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a Sale ID.</div>';
				exit();
			}
			
			// Check if customerID is empty
			if($saleDetailsCustomerID == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter a Customer ID.</div>';
				exit();
			}
			
			// Check if itemNumber is empty
			if($saleDetailsItemNumber == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Item Number.</div>';
				exit();
			}
			
			// Check if quantity is empty
			if($saleDetailsQuantity == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter quantity.</div>';
				exit();
			}
			
			// Check if unit price is empty
			if($saleDetailsUnitPrice == ''){ 
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Please enter Unit Price.</div>';
				exit();
			}
			
			// Get the quantity and itemNumber in original sale order
			$orginalSaleQuantitySql = 'SELECT * FROM sale WHERE saleID = :saleID';
			$originalSaleQuantityStatement = $conn->prepare($orginalSaleQuantitySql);
			$originalSaleQuantityStatement->execute(['saleID' => $saleDetailsSaleID]);
			
			// Get the customerID for the given customerName
			$customerIDsql = 'SELECT * FROM customer WHERE customerID = :customerID';
			$customerIDStatement = $conn->prepare($customerIDsql);
			$customerIDStatement->execute(['customerID' => $saleDetailsCustomerID]);
			
			if($customerIDStatement->rowCount() < 1){
				// Customer id is wrong
				echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Customer ID does not exist in DB. Please enter a valid Customer ID.</div>';
				exit();
			} else {
				$row = $customerIDStatement->fetch(PDO::FETCH_ASSOC);
				$customerID = $row['customerID'];
				$saleDetailsCustomerName = $row['fullName'];
			}
			
			if($originalSaleQuantityStatement->rowCount() > 0){
				
				// Sale details exist in DB. Hence proceed to calculate the stock
				$originalQtyRow = $originalSaleQuantityStatement->fetch(PDO::FETCH_ASSOC);
				$quantityInOriginalOrder = $originalQtyRow['quantity'];
				$originalOrderItemNumber = $originalQtyRow['itemNumber'];

				// Check if the user wants to update the itemNumber too. In that case,
				// we need to remove the quantity of the original order for that item and 
				// update the new item details in the item table.
				// Check if the original itemNumber is the same as the new itemNumber
				if($originalOrderItemNumber !== $saleDetailsItemNumber) {
					// Item numbers are different. That means the user wants to update a new item number too
					// in that case, need to update both items' stocks.
						
					// Get the stock of the new item from item table
					$newItemCurrentStockSql = 'SELECT * FROM item WHERE itemNumber = :itemNumber';
					$newItemCurrentStockStatement = $conn->prepare($newItemCurrentStockSql);
					$newItemCurrentStockStatement->execute(['itemNumber' => $saleDetailsItemNumber]);
					
					if($newItemCurrentStockStatement->rowCount() < 1){
						// Item number is not in DB. Hence abort.
						echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button>Item Number does not exist in DB. If you want to update this item, please add it to DB first.</div>';
						exit();
					}
					
					// Calculate the new stock value for new item using the existing stock in item table
					$newItemRow = $newItemCurrentStockStatement->fetch(PDO::FETCH_ASSOC);
					$originalQuantityForNewItem = $newItemRow['stock'];
					$enteredQuantityForNewItem = $saleDetailsQuantity;
					$newItemNewStock = $originalQuantityForNewItem - $enteredQuantityForNewItem;
					
					// UPDATE the stock for new item in item table
					$newItemStockUpdateStatement = $conn->prepare(UPDATE_ITEM_STOCK_SQL);
					$newItemStockUpdateStatement->execute(['stock' => $newItemNewStock, 'itemNumber' => $saleDetailsItemNumber]);
					
					// Get the current stock of the previous item
					$previousItemCurrentStockSql = 'SELECT * FROM item WHERE itemNumber=:itemNumber';
					$previousItemCurrentStockStatement = $conn->prepare($previousItemCurrentStockSql);
					$previousItemCurrentStockStatement->execute(['itemNumber' => $originalOrderItemNumber]);
					
					// Calculate the new stock value for the previous item using the existing stock in item table
					$previousItemRow = $previousItemCurrentStockStatement->fetch(PDO::FETCH_ASSOC);
					$currentQuantityForPreviousItem = $previousItemRow['stock'];
					$previousItemNewStock = $currentQuantityForPreviousItem + $quantityInOriginalOrder;
					
					// UPDATE the stock for previous item in item table
					$previousItemStockUpdateStatement = $conn->prepare(UPDATE_ITEM_STOCK_SQL);
					$previousItemStockUpdateStatement->execute(['stock' => $previousItemNewStock, 'itemNumber' => $originalOrderItemNumber]);
					
					// Finally UPDATE the sale table for new item
					$updateSaleDetailsSql = 'UPDATE sale SET itemName = :itemName, quantity = :quantity, unitPrice = :unitPrice, discount = :discount WHERE saleID = :saleID';
					$updateSaleDetailsStatement = $conn->prepare($updateSaleDetailsSql);
					$updateSaleDetailsStatement->execute([
						'itemName' => $saleDetailsItemName,
						'quantity' => $saleDetailsQuantity,
						'unitPrice' => $saleDetailsUnitPrice,
						'discount' => $saleDetailsDiscount,
						'saleID' => $saleDetailsSaleID
					]);

					// Send success message to user
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Sale Details have been successfully updated.</div>';

				} else {
					// The item number remains same in the update. Just update stock
					$originalStockInItemTable = $originalQtyRow['quantity'];
					$newStock = $originalStockInItemTable - $saleDetailsQuantity;
					
					// Update stock in item table
					$stockUpdateStatement = $conn->prepare(UPDATE_ITEM_STOCK_SQL);
					$stockUpdateStatement->execute(['stock' => $newStock, 'itemNumber' => $saleDetailsItemNumber]);
					
					// Update sale table with new details
					$updateSaleDetailsSql = 'UPDATE sale SET itemName = :itemName, quantity = :quantity, unitPrice = :unitPrice, discount = :discount WHERE saleID = :saleID';
					$updateSaleDetailsStatement = $conn->prepare($updateSaleDetailsSql);
					$updateSaleDetailsStatement->execute([
						'itemName' => $saleDetailsItemName,
						'quantity' => $saleDetailsQuantity,
						'unitPrice' => $saleDetailsUnitPrice,
						'discount' => $saleDetailsDiscount,
						'saleID' => $saleDetailsSaleID
					]);

					// Send success message to user
					echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Sale Details have been successfully updated.</div>';
				}
			}
		}
	}
?>
