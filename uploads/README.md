# Secure Uploads Directory

This directory stores uploaded files (e.g., hotel images, tour packages) outside the public web accessibility path (where possible) or protects them against direct PHP script execution.

Files inside this directory:
1. Are named with cryptographically secure random values (`bin2hex(random_bytes(16))`).
2. Are served exclusively via the `uploads/serve.php` proxy script, which validates session authentication and content MIME types.
3. Access from the web is restricted. If using Nginx/Apache, configure it to deny direct request execution of `.php` files in this directory.
