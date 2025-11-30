// ===================================
// Main JavaScript Entry Point
// ===================================

// Initialize the application when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  console.log('SOFTNETIX website loaded');
  
  // Set dynamic year in footer copyright
  setDynamicYear();
  
  // Initialize components
  // Additional initialization code will be added in subsequent tasks
});

// ===================================
// Dynamic Year for Footer Copyright
// ===================================

function setDynamicYear() {
  const yearElement = document.getElementById('currentYear');
  if (yearElement) {
    const currentYear = new Date().getFullYear();
    yearElement.textContent = currentYear;
  }
}
