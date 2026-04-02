# 🪐 ORION DATABASE — PHP CRUD System (Space Theme)
## Files Included
```
crud-space/
├── index.php       ← Main app (form + table + all CRUD)
├── config.php      ← DB connection & auto-setup
└── README.md       ← This file
```
## Setup Instructions
### Step 1 — Start XAMPP
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**
### Step 2 — Copy Files
- Copy the `crud-space/` folder into:
  - **Windows:** `C:\xampp\htdocs\`
  - **Mac:**     `/Applications/XAMPP/htdocs/`
### Step 3 — Configure DB (optional)
Open `config.php` and update if needed:
```php
define('DB_USER', 'root');
define('DB_PASS', '');
```
> The database `orion_db` and table `crew_members` are created **automatically** on first run.
### Step 4 — Open in Browser
```
http://localhost/crud-space/
```
---
## CRUD Operations
| Operation | How |
|-----------|-----|
| **Insert** | Fill the form → click **⬆ Launch Record** |
| **Read**   | All records shown in the table below |
| **Update** | Click **✦ Patch** on any row → edit → save |
| **Delete** | Click **✕ Destroy** on any row → confirm |
| **Search** | Use the scan bar to filter by name/role/email |
## Database Schema
```sql
CREATE TABLE crew_members (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    role       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    stardate   DATE NOT NULL,
    status     ENUM('ACTIVE','OFFLINE') DEFAULT 'ACTIVE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```
