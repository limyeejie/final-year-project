// Function to show the appropriate modal based on type (Comment out later)
function showModal(type, message = null) {

  const modalId = `${type}Modal`; // Get the ID of the modal to show
  const modal = document.getElementById(modalId);
  if (modal) {
    const messageElement = modal.querySelector(`#${modalId}Message`);
    if (message && messageElement) {
      messageElement.textContent = message;
    }
    modal.style.display = 'flex';
  }
}
  
  // Function to close the specified modal
function closeModal(modalId, redirectUrl = null) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.style.display = 'none';
  }
  if (redirectUrl) {
    window.location.href = redirectUrl;
  }
}

function showSuccessModal(redirectUrl, message) {
  document.querySelector('#successModal .modal-close-btn')
      .setAttribute('onclick', `closeModal('successModal', '${redirectUrl}')`);
  showModal('success', message);
  
}

function showFailureModal(redirectUrl, message) {
  document.querySelector('#failureModal .modal-close-btn')
      .setAttribute('onclick', `closeModal('failureModal', '${redirectUrl}')`);
  showModal('failure', message);
  
}
  
  