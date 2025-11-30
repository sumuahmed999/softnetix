// ===================================
// Contact Form Validation & Submission
// ===================================

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contactForm');
  
  if (!form) return;
  
  const nameInput = document.getElementById('name');
  const emailInput = document.getElementById('email');
  const phoneInput = document.getElementById('phone');
  const messageInput = document.getElementById('message');
  
  // Validation functions
  const validators = {
    name: function(value) {
      if (!value || value.trim() === '') {
        return 'Name is required';
      }
      return null;
    },
    
    email: function(value) {
      if (!value || value.trim() === '') {
        return 'Email is required';
      }
      // Email regex pattern
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) {
        return 'Please enter a valid email address';
      }
      return null;
    },
    
    phone: function(value) {
      // Phone is optional, but if provided, validate format
      if (value && value.trim() !== '') {
        // Remove all non-digit characters for validation
        const digitsOnly = value.replace(/\D/g, '');
        if (digitsOnly.length < 10 || digitsOnly.length > 15) {
          return 'Phone number must be between 10 and 15 digits';
        }
      }
      return null;
    },
    
    message: function(value) {
      if (!value || value.trim() === '') {
        return 'Message is required';
      }
      if (value.trim().length < 10) {
        return 'Message must be at least 10 characters long';
      }
      return null;
    }
  };
  
  // Show error message
  function showError(input, message) {
    const errorElement = document.getElementById(input.id + '-error');
    if (errorElement) {
      errorElement.textContent = message;
      errorElement.classList.add('show');
    }
    input.classList.add('error');
    input.setAttribute('aria-invalid', 'true');
  }
  
  // Clear error message
  function clearError(input) {
    const errorElement = document.getElementById(input.id + '-error');
    if (errorElement) {
      errorElement.textContent = '';
      errorElement.classList.remove('show');
    }
    input.classList.remove('error');
    input.setAttribute('aria-invalid', 'false');
  }
  
  // Validate single field
  function validateField(input) {
    const validator = validators[input.name];
    if (!validator) return true;
    
    const error = validator(input.value);
    if (error) {
      showError(input, error);
      return false;
    } else {
      clearError(input);
      return true;
    }
  }
  
  // Validate all fields
  function validateForm() {
    let isValid = true;
    
    if (!validateField(nameInput)) isValid = false;
    if (!validateField(emailInput)) isValid = false;
    if (!validateField(phoneInput)) isValid = false;
    if (!validateField(messageInput)) isValid = false;
    
    return isValid;
  }
  
  // Real-time validation on blur
  nameInput.addEventListener('blur', function() {
    validateField(this);
  });
  
  emailInput.addEventListener('blur', function() {
    validateField(this);
  });
  
  phoneInput.addEventListener('blur', function() {
    validateField(this);
  });
  
  messageInput.addEventListener('blur', function() {
    validateField(this);
  });
  
  // Clear error on input
  nameInput.addEventListener('input', function() {
    if (this.classList.contains('error')) {
      clearError(this);
    }
  });
  
  emailInput.addEventListener('input', function() {
    if (this.classList.contains('error')) {
      clearError(this);
    }
  });
  
  phoneInput.addEventListener('input', function() {
    if (this.classList.contains('error')) {
      clearError(this);
    }
  });
  
  messageInput.addEventListener('input', function() {
    if (this.classList.contains('error')) {
      clearError(this);
    }
  });
  
  // Show premium success modal
  function showSuccessModal(message) {
    // Create modal HTML
    const modalHTML = `
      <div class="success-modal-overlay" id="successModal">
        <div class="success-modal">
          <div class="confetti"></div>
          <div class="confetti"></div>
          <div class="confetti"></div>
          <div class="confetti"></div>
          <div class="confetti"></div>
          <div class="confetti"></div>
          <div class="confetti"></div>
          <div class="confetti"></div>
          <div class="confetti"></div>
          
          <div class="sparkle"></div>
          <div class="sparkle"></div>
          <div class="sparkle"></div>
          <div class="sparkle"></div>
          
          <div class="success-modal-content">
            <div class="success-icon">
              <div class="success-icon-circle">
                <svg class="success-icon-checkmark" viewBox="0 0 52 52">
                  <path d="M14 27l7 7 16-16"/>
                </svg>
              </div>
            </div>
            
            <h2 class="success-title">Message Sent Successfully!</h2>
            <p class="success-message">${message}</p>
            
            <button class="success-close-btn" onclick="closeSuccessModal()">
              Awesome!
            </button>
          </div>
        </div>
      </div>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Show modal with animation
    setTimeout(() => {
      document.getElementById('successModal').classList.add('show');
    }, 10);
  }
  
  // Show error modal
  function showErrorModal(message) {
    const modalHTML = `
      <div class="success-modal-overlay error-modal" id="errorModal">
        <div class="success-modal">
          <div class="success-modal-content">
            <div class="success-icon">
              <div class="success-icon-circle">
                <svg class="success-icon-checkmark" viewBox="0 0 52 52" style="stroke-dasharray: 0;">
                  <path d="M16 16 L36 36 M36 16 L16 36"/>
                </svg>
              </div>
            </div>
            
            <h2 class="success-title">Oops!</h2>
            <p class="success-message">${message}</p>
            
            <button class="success-close-btn" onclick="closeSuccessModal()">
              Try Again
            </button>
          </div>
        </div>
      </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    setTimeout(() => {
      const modal = document.getElementById('errorModal');
      if (modal) modal.classList.add('show');
    }, 10);
  }
  
  // Close modal function (global)
  window.closeSuccessModal = function() {
    const modals = document.querySelectorAll('.success-modal-overlay');
    modals.forEach(modal => {
      modal.classList.remove('show');
      setTimeout(() => {
        modal.remove();
      }, 300);
    });
  };
  
  // Show form message (fallback for inline messages)
  function showFormMessage(type, message) {
    if (type === 'success') {
      showSuccessModal(message);
    } else {
      showErrorModal(message);
    }
  }
  
  // Submit form function - Connected to PHP backend
  async function submitForm(formData) {
    try {
      const response = await fetch('api/submit-contact.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
      });
      
      const result = await response.json();
      
      if (!response.ok || !result.success) {
        throw new Error(result.message || 'Submission failed');
      }
      
      return result;
    } catch (error) {
      console.error('Submission error:', error);
      throw error;
    }
  }
  
  // Form submission handling
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Validate form
    if (!validateForm()) {
      return;
    }
    
    // Get form data
    const formData = {
      name: nameInput.value.trim(),
      email: emailInput.value.trim(),
      phone: phoneInput.value.trim(),
      message: messageInput.value.trim()
    };
    
    // Get submit button and message container
    const submitButton = form.querySelector('button[type="submit"]');
    const btnText = submitButton.querySelector('.btn-text');
    const btnLoading = submitButton.querySelector('.btn-loading');
    const formMessage = document.getElementById('formMessage');
    
    // Show loading state
    submitButton.disabled = true;
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline';
    formMessage.classList.remove('show', 'success', 'error');
    
    try {
      // Set timeout for 5 seconds
      const timeoutPromise = new Promise((_, reject) => {
        setTimeout(() => reject(new Error('Request timeout')), 5000);
      });
      
      // Simulate form submission (replace with actual EmailJS or API call)
      const submitPromise = submitForm(formData);
      
      // Race between submission and timeout
      await Promise.race([submitPromise, timeoutPromise]);
      
      // Success
      showFormMessage('success', 'Thank you! We\'ll get back to you soon.');
      form.reset();
      
      // Clear any remaining error states
      clearError(nameInput);
      clearError(emailInput);
      clearError(phoneInput);
      clearError(messageInput);
      
    } catch (error) {
      // Error
      console.error('Form submission error:', error);
      showFormMessage('error', 'Something went wrong. Please try again.');
    } finally {
      // Reset button state
      submitButton.disabled = false;
      btnText.style.display = 'inline';
      btnLoading.style.display = 'none';
    }
  });
  
  // Handle form reset
  form.addEventListener('reset', function() {
    // Clear all error messages
    clearError(nameInput);
    clearError(emailInput);
    clearError(phoneInput);
    clearError(messageInput);
    
    // Hide form message
    const formMessage = document.getElementById('formMessage');
    formMessage.classList.remove('show', 'success', 'error');
  });
  
  // Export validation function for use in form submission
  window.validateContactForm = validateForm;
});
