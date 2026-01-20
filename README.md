## Smart Chemical Inventory Management Portal (3D Animated PHP App)

This is a **role-based chemical inventory portal** for labs/industries, built with **PHP, MySQL, Bootstrap**, and **3D-styled UI** effects.

### 1. Features Overview

- **Authentication & Roles**
  - `Admin`: Full CRUD – add, edit, delete chemicals; manage expiry & safety.
  - `Guest`: Read-only – can only view inventory and alerts.
  - PHP sessions for login, backend checks to block unauthorized access.

- **Module 1 – Chemical Stock Logging**
  - Log: **chemical name, quantity, location**.
  - Auto-generated `chemical_id` (primary key).
  - Admin-only: Add / Edit / Delete.
  - Guests: View only.

- **Module 2 – Expiry & Safety Alerts**
  - Track **expiry dates** and **safety level (Low/Medium/High)**.
  - Dashboard shows:
    - Expired chemicals.
    - Expiring soon (≤ 30 days).
    - High-risk chemicals.
  - Color-coded badges + animated dashboard panels.

- **UI / UX**
  - Responsive **Bootstrap 5** layout.
  - 3D cards, neon/glassmorphism, hover + motion (`assets/css/style.css`, `assets/js/main.js`).
  - Clear navigation for Admin vs Guest.

### 2. File & Folder Structure

- `index.php` – Landing page with **Admin / Guest** login choices and 3D hero section.
- `login.php` – Login logic, validates user from `users` table.
- `logout.php` – Destroys session and redirects to landing.
- `dashboard.php` – Role-based dashboard with stats and recent alerts.
- `view_chemicals.php` – Shared inventory view (Admin: sees Edit/Delete; Guest: view-only).
- `add_chemical.php` – Admin-only form to create new chemicals (+ optional expiry & safety).
- `edit_chemical.php` – Admin-only edit for existing chemicals and safety info.
- `delete_chemical.php` – Admin-only delete (with FK-safe delete of safety record).
- `alerts.php` – Expiry & safety alerts center (expired, expiring soon, high risk).
- `db_config.php` – MySQL connection config.
- `auth_check.php` – Session check + `require_role('admin')` helper for backend RBAC.
- `partials/header.php` / `partials/footer.php` – Shared navbar, layout, scripts.
- `assets/css/style.css` – 3D cards, neon buttons, glassmorphism, badges, animations.
- `assets/js/main.js` – Simple 3D tilt effect + staggered fade-in animations.
- `schema.sql` – Database schema + sample data (to import in phpMyAdmin).

### 3. Database Setup (XAMPP / phpMyAdmin)

1. Start **Apache** and **MySQL** from the XAMPP Control Panel.
2. Open `http://localhost/phpmyadmin` in your browser.
3. Click **Import**.
4. Choose the `schema.sql` file from this project folder.
5. Click **Go** to import.

This will:
- Create database: `smart_chemical_inventory`
- Create tables: `users`, `chemicals`, `chemical_safety`
- Add sample data:
  - Users:
    - Admin → `admin` / `admin123`
    - Guest → `guest` / `guest123`
  - Example chemicals and safety/expiry info.

### 4. Configure Database Connection

Open `db_config.php` and confirm:

```php
$DB_HOST = 'localhost';
$DB_USER = 'root';      // default XAMPP user
$DB_PASS = '';          // change if you set a MySQL password
$DB_NAME = 'smart_chemical_inventory';
```

Update `$DB_USER` / `$DB_PASS` if your MySQL settings are different.

### 5. Running the Project on XAMPP

1. Copy the whole project folder (e.g. `alice`) into your XAMPP `htdocs` directory, e.g.:
   - `C:\xampp\htdocs\smart_chemical_inventory`
2. Ensure Apache + MySQL are running.
3. In your browser, visit:
   - `http://localhost/smart_chemical_inventory/index.php`
4. Login using:
   - **Admin**: `admin` / `admin123` → full control (Add/Edit/Delete, alerts, etc.).
   - **Guest**: `guest` / `guest123` → read-only inventory and alerts.

### 6. How RBAC (Role-Based Access Control) Works

- On login, PHP stores in `$_SESSION`:
  - `user_id`, `username`, `role` (`admin` or `guest`).
- `auth_check.php`:
  - Redirects to `login.php` if not logged in.
  - Provides `require_role('admin')` to block Guests from Admin pages.
- UI differences:
  - Admin sees **Add Chemical**, **Edit**, **Delete** buttons.
  - Guest does **not** see any modification controls.

### 7. Customization Ideas

- Replace plain text passwords in `schema.sql` with **hashed** passwords and use `password_hash()` / `password_verify()` in `login.php`.
- Tune colors, fonts, or animations in `assets/css/style.css`.
- Add more fields (e.g., CAS number, supplier, storage conditions) in `chemicals` and related forms.

