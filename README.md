# budget_planner_final

A lightweight PHP web application that helps users track personal budgets, categories, and expenses. It includes an admin panel for managing users and categories, as well as a simple front‑end for regular users to add, edit, and view their financial data.

---

## Overview

`budget_planner_final` provides a complete CRUD interface for budgeting:

* **Users** can register, log in, and manage their profile.  
* **Budgets** can be created, edited, and listed.  
* **Categories** (e.g., Food, Transport) are managed by both admins and regular users.  
* **Expenses** are recorded against a budget and category, with the ability to edit or delete entries.  
* **Admin panel** offers user management, category oversight, and secure authentication.

All data is stored in a MySQL database (`Database/budget_db.sql`).

---

## Features

- **User authentication** – registration, login, logout, and profile updates.  
- **Admin dashboard** – view users, manage categories, and secure admin login.  
- **Budget management** – create, edit, view, and delete budgets.  
- **Category handling** – add, edit, view, and delete categories (admin & user level).  
- **Expense tracking** – record expenses linked to budgets and categories.  
- **Responsive UI** – simple, clean design using `css/style.css`.  
- **Modular PHP code** – separate config, navigation, and business logic files for maintainability.

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 7.4+ |
| Database | MySQL |
| Front‑end | HTML5, CSS3 |
| Server | Apache (or any PHP‑compatible web server) |
| Development | Composer (optional) |

Key files:

| Directory | Important Files |
|-----------|-----------------|
| Root | `index.php`, `login.php`, `register.php`, `home.php`, `navbar.php`, `config.php` |
| Admin | `admin/admin_home.php`, `admin/admin_login.php`, `admin/view_users.php`, `admin/config.php` |
| CRUD | `add_budget.php`, `edit_budget.php`, `view_budgets.php`, `add_categories.php`, `edit_category.php`, `view_categories.php`, `add_expense.php`, `edit_expense.php`, `view_expenses.php` |
| Database | `Database/budget_db.sql` |
| Styles | `css/style.css` |

---

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/yourusername/budget_planner_final.git
   cd budget_planner_final
   ```

2. **Set up the database**

   - Create a new MySQL database (e.g., `budget_planner`).
   - Import the schema:

     ```bash
     mysql -u YOUR_DB_USER -p YOUR_DB_NAME < Database/budget_db.sql
     ```

3. **Configure the application**

   - Copy `config.sample.php` to `config.php` (or edit the existing `config.php`).
   - Update the database credentials:

     ```php
     // config.php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'budget_planner');
     define('DB_USER', 'YOUR_DB_USER');
     define('DB_PASS', 'YOUR_DB_PASSWORD');
     ```

4. **Serve the project**

   - Using **XAMPP / MAMP / WAMP**: place the project folder inside `htdocs` (or the equivalent web root) and start Apache & MySQL.
   - Or start the built‑in PHP server:

     ```bash
     php -S localhost:8000
     ```

5. **Access the