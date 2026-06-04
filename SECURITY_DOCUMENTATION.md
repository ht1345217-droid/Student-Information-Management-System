# Security Hardening & Validation Documentation

This document explains the security improvements, input validation design, and team contributions for the Student Information Management System (SIMS).

---

## 1. Input Validation Rules

### Name Validation
*   **Rule**: Must be 3 to 50 characters, containing only letters and spaces.
*   **Why**: Protects the database from containing garbage data, prevents layout breaks from overly long names, and defends against command injection/HTML payloads hidden in the name field.

### Username Validation
*   **Reserved Usernames Check**: Reserved words like `admin`, `administrator`, `root`, `superadmin`, and `system` are blocked.
*   **Format Rule**: Must start with an alphabet, contain no special characters, but alphanumeric combinations are allowed.
*   **Why**: Blocking reserved usernames prevents users from impersonating administrators or system services. Ensuring usernames start with a letter and are strictly alphanumeric avoids injection of path traversal sequences (e.g. `../`) or sql fragments in queries that lookup users by username.

### Country and Gender Fields
*   **Rule**: Dropdown list selection with mandatory requirement.
*   **Why**: Restricting inputs to predefined lists prevents malicious attackers from injecting arbitrary text payloads, ensuring data integrity and consistency.

### Email Validation
*   **Rule**: Syntax checking using standard regular expressions (client-side) and `FILTER_VALIDATE_EMAIL` (server-side), plus database lookup check to ensure uniqueness.
*   **Why**: Verifying format ensures messaging services can deliver to the address. The uniqueness check prevents account overlap, unauthorized duplicate accounts, and database confusion.

---

## 2. Authentication & Session Security

### Session Starting Security
*   **Rule**: Enforced `cookie_httponly = true`, `cookie_secure = true` (over HTTPS), and `cookie_samesite = Strict`.
*   **Why**: 
    *   `HttpOnly` blocks client-side scripts (like Javascript) from reading session cookies, neutralizing session stealing via Cross-Site Scripting (XSS).
    *   `Secure` guarantees that cookies are only sent over encrypted SSL (HTTPS) connections, preventing interception in transit.
    *   `SameSite=Strict` ensures cookies are not sent on cross-site requests, mitigating Cross-Site Request Forgery (CSRF).

### Session Fixation Protection
*   **Rule**: Regenerate the session ID via `session_regenerate_id(true)` immediately after a user logs in.
*   **Why**: This generates a new session key and destroys the old pre-authentication key, preventing attackers from forcing a known session ID onto a victim's browser and hijacking the session after they log in.

### Session Destruction
*   **Rule**: On logout, the `$_SESSION` array is cleared, the session cookie is deleted from the client browser, and `session_destroy()` is invoked.
*   **Why**: Assures that the session token is fully invalidated on both client and server sides, preventing back-button or replay access.

---

## 3. Database Security

### Prepared Statements Everywhere
*   **Rule**: Every SQL query involving dynamic parameter insertion utilizes parameterized prepared statements (`mysqli::prepare` and `mysqli_stmt::bind_param`).
*   **Why**: Separates the SQL query structure from the user-provided data. The database treats user inputs strictly as literal values rather than executable code, entirely eliminating SQL Injection (SQLi) vulnerabilities.

### Friendly Database Error Reporting
*   **Rule**: Database connection and query failures return generalized friendly messages without showing SQL syntax, schema details, or system paths.
*   **Why**: Exposing raw database errors helps attackers maps the table layout and find attack vectors. General errors mask the system's inner workings.

---

## 4. File Upload Security

We implemented file uploads (for Student Profiles and Admin Avatars) with a multi-layered security grid:
1.  **Size Validation**: Restricts uploads to 2MB to prevent denial-of-service (DoS) attacks that fill up server disk space.
2.  **Extension Whitelisting**: Only permits `jpg`, `jpeg`, `png`, and `gif` extensions.
3.  **MIME-Type Verification**: Employs PHP's `finfo` class to read the binary signature of the file, preventing users from renaming a script (like `shell.php`) to `shell.png` to bypass extension checks.
4.  **Secure Cryptographic Renaming**: Replaces the original filename with a secure random hash (e.g. `bin2hex(random_bytes(16))`) combined with the verified extension. This prevents directory traversal attacks, overwriting existing files, and execution of malicious code.
5.  **Execution Prevention (.htaccess)**: The `uploads/` directory contains an `.htaccess` file that explicitly disables CGI, option index browsing, and forces all web servers to serve uploaded files as plain text rather than executing them.

---

## 5. Web Attack Protections

*   **XSS Protection**: All user-supplied inputs displayed in HTML pages are wrapped in `xss_clean()` (using `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`). This encodes tags like `<script>`, preventing browser execution of injected scripts.
*   **CSRF Protection**: All POST forms contain a cryptographically secure random token generated per session (`generate_csrf_token()`) and verified upon postback (`verify_csrf_token()`).
*   **Clickjacking Protection**: Headers `X-Frame-Options: DENY` and `Content-Security-Policy: frame-ancestors 'none'` are sent on every request to prevent the site from being embedded inside malicious frames or iframes.
*   **Brute Force Protection**: Track login failures by IP address in the `login_attempts` table. Exceeding 5 failures blocks the IP from logging in for 15 minutes.
*   **Directory Traversal Protection**: Renaming file uploads and avoiding direct inclusion of user-supplied paths in `include()` or `require()` statements ensures attackers cannot read arbitrary files on the hosting system.

---

## 6. Team Roles & Contributions

*   **Lead Security Engineer**: Designed the database-level security policy, integrated prepared statements across all CRUD features, and set up HTTP security headers.
*   **Full Stack Developer**: Developed the frontend registration validations, designed country and gender dropdown lists, and built the client-side/server-side validation checks.
*   **System Architect**: Implemented the session management architecture, secure cookie configuration, brute force lockout mechanism, and secure file upload systems.
*   **QA & Integration Engineer**: Verified responsive navigation menus, performed cross-browser testing for responsive layout, and verified CSV ledger reporting.
