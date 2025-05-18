<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/modal.css">
  <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css">
  <title>Popup Modals</title>
</head>
<body>
  
  <!-- Success Modal -->
  <div id="successModal" class="modal success">
    <div class="modal-contents">
      <span class="modal-icon">&#x2714;</span> 
      <p id='successModalMessage' class="modal-title">Success</p>
      <button class="modal-close-btn" onclick="closeModal('successModal', 'success_page.php')">Close</button>
    </div>
  </div>

  <!-- Failure Modal -->
  <div id="failureModal" class="modal failure">
    <div class="modal-contents">
      <span class="modal-icon">&#x26A0;</span> 
      <p id="failureModalMessage">Failure</p>
      <button class="modal-close-btn" onclick="closeModal('failureModal', 'success_page.php')">Close</button>
    </div>
  </div>

  <!-- Error Modal -->
  <div id="errorModal" class="modal error">
    <div class="modal-contents">
      <span class="modal-icon">&#x274C;</span> 
      <p id="errorModalMessage-title">Error</p>
      <button class="modal-close-btn" onclick="closeModal('errorModal')">Close</button>
    </div>
  </div>


  <script src="../js/modal.js"></script>
</body>
</html>
