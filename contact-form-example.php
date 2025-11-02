<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Example - Secured with CSRF Protection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        button {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: none;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .security-info {
            background: #e7f3ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .security-info strong {
            color: #007bff;
        }
    </style>
</head>
<body>
    <?php
    // Start session for CSRF token
    session_start();
    
    // Load security functions
    require_once 'email-templates/security-functions.php';
    
    // Generate CSRF token
    $csrf_token = generateCsrfToken();
    ?>

    <div class="form-container">
        <h2>Secure Contact Form Example</h2>
        
        <div class="security-info">
            <strong>🔒 Security Features:</strong>
            <ul>
                <li>CSRF Token Protection</li>
                <li>Input Validation & Sanitization</li>
                <li>Email Header Injection Prevention</li>
                <li>Rate Limiting (5 submissions/hour)</li>
            </ul>
        </div>

        <div id="alertMessage" class="alert"></div>

        <form id="contactForm" method="POST" action="email-templates/contact-form.php">
            <!-- CSRF Token - REQUIRED for security -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            
            <div class="form-group">
                <label for="name">Name *</label>
                <input type="text" id="name" name="name" required maxlength="100">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" placeholder="+62 812-3456-7890">
            </div>

            <div class="form-group">
                <label for="comment">Message *</label>
                <textarea id="comment" name="comment" required maxlength="5000"></textarea>
            </div>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <script>
        // Handle form submission with AJAX
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            var form = this;
            var formData = new FormData(form);
            var alertDiv = document.getElementById('alertMessage');
            
            // Show loading state
            var submitBtn = form.querySelector('button[type="submit"]');
            var originalText = submitBtn.textContent;
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Show alert message
                alertDiv.className = 'alert ' + data.alert;
                alertDiv.textContent = data.message;
                alertDiv.style.display = 'block';
                
                // Reset form if success
                if (data.alert === 'alert-success') {
                    form.reset();
                }
                
                // Restore button
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                
                // Scroll to alert
                alertDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                
                // Hide alert after 5 seconds
                setTimeout(function() {
                    alertDiv.style.display = 'none';
                }, 5000);
            })
            .catch(error => {
                console.error('Error:', error);
                alertDiv.className = 'alert alert-danger';
                alertDiv.textContent = 'An error occurred. Please try again.';
                alertDiv.style.display = 'block';
                
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
</body>
</html>
