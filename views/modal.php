<?php
function addModal($title)
{ ?>
  <!-- Trigger/Open The Modal -->
  <!-- <button id="myBtn">Open Modal</button> -->
  <!-- The Modal -->
  <link rel="stylesheet" type="text/css" href="/styles/modal.css" />
  <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header" id="mHeader">
        <span class="close" id="close-span">&times;</span>
        <h2><?php echo $title; ?></h2>
      </div>
      <div class="modal-body" id="mBody">
        <p></p>
      </div>
      <div class="modal-footer" id="mFooter">
        <h3></h3>
      </div>
    </div>
  </div>
  <script src="/scripts/modal.js"></script>
<?php }
