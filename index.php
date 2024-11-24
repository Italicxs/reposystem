<?php
// Define las constantes para evitar duplicación de literales
define('STATUS_LIST_FILE', 'inc/statusList.html');
define('DISTRICT_LIST_FILE', 'inc/districtList.html');

require 'inc/header.php';
?>

<div class="container">
  <div class="row">
    <div class="col-3">
      <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</a>
        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
      </div>
    </div>
    <div class="col-9">
      <div class="tab-content" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
          <div class="card card-outline-secondary my-4">
            <div class="card-header">Vendor Details</div>
            <div class="card-body">
              <form>
                <div class="form-group">
                  <label for="vendorDetailsVendorName">Name<span class="requiredIcon">*</span></label>
                  <input type="text" class="form-control" id="vendorDetailsVendorName" name="vendorDetailsVendorName">
                </div>
                <div class="form-group">
                  <label for="vendorDetailsVendorEmail">Email</label>
                  <input type="email" class="form-control" id="vendorDetailsVendorEmail" name="vendorDetailsVendorEmail">
                </div>
                <div class="form-group">
                  <label for="vendorDetailsVendorAddress">Address<span class="requiredIcon">*</span></label>
                  <input type="text" class="form-control" id="vendorDetailsVendorAddress" name="vendorDetailsVendorAddress">
                </div>
                <div class="form-group">
                  <label for="vendorDetailsVendorAddress2">Address 2</label>
                  <input type="text" class="form-control" id="vendorDetailsVendorAddress2" name="vendorDetailsVendorAddress2">
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="vendorDetailsVendorCity">City</label>
                    <input type="text" class="form-control" id="vendorDetailsVendorCity" name="vendorDetailsVendorCity">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="vendorDetailsVendorDistrict">District</label>
                    <select id="vendorDetailsVendorDistrict" name="vendorDetailsVendorDistrict" class="form-control chosenSelect">
                      <?php include(DISTRICT_LIST_FILE); ?>
                    </select>
                  </div>
                </div>
                <button type="button" id="addVendor" name="addVendor" class="btn btn-success">Add Vendor</button>
                <button type="button" id="updateVendorDetailsButton" class="btn btn-primary">Update</button>
                <button type="button" id="deleteVendorButton" class="btn btn-danger">Delete</button>
                <button type="reset" class="btn">Clear</button>
              </form>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="v-pills-sale" role="tabpanel" aria-labelledby="v-pills-sale-tab">
          <div class="card card-outline-secondary my-4">
            <div class="card-header">Sale Details</div>
            <div class="card-body">
              <div id="saleDetailsMessage"></div>
              <form>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label for="saleDetailsItemNumber">Item Number<span class="requiredIcon">*</span></label>
                    <input type="text" class="form-control" id="saleDetailsItemNumber" name="saleDetailsItemNumber" autocomplete="off">
                    <div id="saleDetailsItemNumberSuggestionsDiv" class="customListDivWidth"></div>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="saleDetailsCustomerID">Customer ID<span class="requiredIcon">*</span></label>
                    <input type="text" class="form-control" id="saleDetailsCustomerID" name="saleDetailsCustomerID" autocomplete="off">
                    <div id="saleDetailsCustomerIDSuggestionsDiv" class="customListDivWidth"></div>
                  </div>
                  <div class="form-group col-md-4">
                    <label for="saleDetailsCustomerName">Customer Name</label>
                    <input type="text" class="form-control" id="saleDetailsCustomerName" name="saleDetailsCustomerName" readonly>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="saleDetailsSaleID">Sale ID</label>
                    <input type="text" class="form-control invTooltip" id="saleDetailsSaleID" name="saleDetailsSaleID" title="This will be auto-generated when you add a new record" autocomplete="off">
                    <div id="saleDetailsSaleIDSuggestionsDiv" class="customListDivWidth"></div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-5">
                    <label for="saleDetailsItemName">Item Name</label>
                    <input type="text" class="form-control" id="saleDetailsItemName" name="saleDetailsItemName" readonly>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="saleDetailsAvailableQuantity">Available Quantity</label>
                    <input type="text" class="form-control" id="saleDetailsAvailableQuantity" name="saleDetailsAvailableQuantity" readonly>
                  </div>
                  <div class="form-group col-md-2">
                    <label for="saleDetailsQuantity">Quantity<span class="requiredIcon">*</span></label>
                    <input type="number" class="form-control" id="saleDetailsQuantity" name="saleDetailsQuantity" value="0">
                  </div>
                  <div class="form-group col-md-2">
                    <label for="saleDetailsUnitPrice">Unit Price</label>
                    <input type="text" class="form-control" id="saleDetailsUnitPrice" name="saleDetailsUnitPrice" readonly>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label for="saleDetailsTotal">Total</label>
                    <input type="text" class="form-control" id="saleDetailsTotal" name="saleDetailsTotal" readonly>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="saleDetailsDiscount">Discount %</label>
                    <input type="number" class="form-control" id="saleDetailsDiscount" name="saleDetailsDiscount" value="0">
                  </div>
                  <div class="form-group col-md-3">
                    <label for="saleDetailsGrandTotal">Grand Total</label>
                    <input type="text" class="form-control" id="saleDetailsGrandTotal" name="saleDetailsGrandTotal" readonly>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="saleDetailsSaleDate">Sale Date</label>
                    <input type="text" class="form-control datepicker" id="saleDetailsSaleDate" name="saleDetailsSaleDate" value="">
                  </div>
                </div>
                <button type="button" id="addSaleButton" name="addSale" class="btn btn-success">Add Sale</button>
                <button type="reset" class="btn">Clear</button>
              </form>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
          <div class="card card-outline-secondary my-4">
            <div class="card-header">Profile</div>
            <div class="card-body">
              <p>This is your profile tab content.</p>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
          <div class="card card-outline-secondary my-4">
            <div class="card-header">Messages</div>
            <div class="card-body">
              <p>This is your messages tab content.</p>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
          <div class="card card-outline-secondary my-4">
            <div class="card-header">Settings</div>
            <div class="card-body">
              <p>This is your settings tab content.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="js/main.js"></script>
<script>
  // Aquí puedes incluir código adicional relacionado a tus interacciones
</script>

<?php require 'inc/footer.php'; ?>
