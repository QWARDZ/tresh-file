
A secure PHP login and registration system that implements encryption, 
password hashing, input sanitization, and protection against SQL injection.
All user data is encrypted in the database for maximum security.

FILES EXPLANATION:
------------------

1. config.php
   - Contains security functions used throughout the application
   - encryptData() - Encrypts sensitive data using AES-256-CBC encryption
   - decryptData() - Decrypts encrypted data to retrieve original values
   - sanitizeInput() - Cleans user input to prevent XSS attacks
   - Uses OpenSSL for strong encryption with random initialization vectors
   - Defines encryption key and method as constants

2. db.php
   - Establishes secure connection to MySQL database using PDO
   - Configured with localhost server, test_db database, root user
   - Sets PDO error mode to throw exceptions for better error handling
   - Disables emulated prepared statements for enhanced security
   - Uses UTF-8 character encoding to support special characters
   - Displays generic error message on connection failure

3. register.php
   - User registration page with HTML form
   - Sanitizes username input using sanitizeInput()
   - Encrypts username before storing in database
   - Hashes password using password_hash() with bcrypt algorithm
   - Checks for duplicate usernames by decrypting all stored usernames
   - Uses parameterized queries to prevent SQL injection
   - Displays success message on successful registration
   - Shows generic error messages to avoid information disclosure
   - Links to login page

4. login.php
   - User login page with HTML form
   - Sanitizes all user input before processing
   - Fetches all users from database and decrypts usernames to find match
   - Verifies password using password_verify() against stored hash
   - Displays generic error "Invalid username or password" for security
   - Does not reveal whether username or password was incorrect
   - Uses try-catch to handle database errors gracefully
   - Links to registration page

5. database.sql
   - SQL script to create the database structure
   - Creates test_db database if it doesn't exist
   - Creates users table with three columns:
     * id - Auto-incrementing primary key
     * username - VARCHAR(500) to store encrypted username (NOT NULL)
     * password - VARCHAR(255) to store hashed password (NOT NULL)
   - Username field is larger to accommodate encrypted data

