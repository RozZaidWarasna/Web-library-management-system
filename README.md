# 📚 Library Management System

A web-based Library Management System built using **PHP**, **MySQL**, **HTML**, and **CSS**.  
The system helps manage library resources such as books, authors, publishers, borrowers, loans, sales, and reports through a clean dashboard interface.

---

## ✨ Features

- User authentication system
  - Sign in
  - Sign up
  - Logout
  - Role-based user accounts: Admin, Staff, Student

- Dashboard interface
  - Sidebar navigation
  - Search functionality
  - Table-based data display
  - Add, edit, and delete records

- Book management
  - Add new books
  - Edit book information
  - Delete books
  - Track book availability

- Author management
  - Add authors
  - Edit author details
  - Delete authors

- Publisher management
  - Add publishers
  - Edit publisher details
  - Delete publishers

- Borrower management
  - Add library members
  - Edit borrower information
  - Delete borrowers

- Loan management
  - Record book loans
  - Track loan date and return date
  - View active loans

- Sales management
  - Record book sales
  - Edit sale records
  - Delete sale records

- Reports and analytics
  - Total book value
  - Books by author
  - Borrower activity
  - Current loans
  - Books by publisher country
  - Inactive borrowers
  - Multi-author books
  - Sold books
  - Available books
  - Borrower loan history

---

## 🛠️ Technologies Used

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- phpMyAdmin
- XAMPP / WAMP / Local Server

---

## 📁 Project Structure

```text
Library-Management-System/
│
├── index.php
├── signup.php
├── logout.php
├── dashboard.php
├── db.php
├── header.php
├── footer.php
├── style.css
├── libr.sql
│
├── add_author.php
├── add_book.php
├── add_borrower.php
├── add_loan.php
├── add_publisher.php
├── add_sale.php
│
├── edit_author.php
├── edit_book.php
├── edit_borrower.php
├── edit_loan.php
├── edit_publisher.php
├── edit_sale.php
│
├── delete_author.php
├── delete_book.php
├── delete_borrower.php
├── delete_loan.php
├── delete_publisher.php
└── delete_sale.php
```

---

## 🗄️ Database

The project uses a MySQL database named:

```sql
libr
```

The database file included in the project is:

```text
libr.sql
```

Main database tables include:

- `users`
- `book`
- `author`
- `publisher`
- `borrower`
- `borrowertype`
- `loan`
- `loanperiod`
- `sale`
- `bookauthor`

---

## ⚙️ Installation and Setup

### 1. Clone the repository

```bash
git clone https://github.com/your-username/your-repository-name.git
```

Or download the project as a ZIP file.

---

### 2. Move the project to the server folder

If you are using XAMPP, move the project folder to:

```text
C:/xampp/htdocs/
```

Example:

```text
C:/xampp/htdocs/library-management-system/
```

---

### 3. Start Apache and MySQL

Open XAMPP Control Panel and start:

```text
Apache
MySQL
```

---

### 4. Import the database

Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Create a new database named:

```text
libr
```

Then import the database file:

```text
libr.sql
```

---

### 5. Configure database connection

Open `db.php` and make sure the database settings match your local server:

```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "libr";
```

If your MySQL password is different, update `$pass`.

---

### 6. Run the project

Open the project in your browser:

```text
http://localhost/library-management-system/
```

---

## 👤 User Roles

The system supports three account roles:

| Role | Description |
|---|---|
| Admin | Can manage records and access full dashboard actions |
| Staff | Can access the system based on assigned permissions |
| Student | Can use the system with limited access |

---

## 🔐 Authentication

The system includes:

- Login page
- Signup page
- Session-based authentication
- Password hashing using PHP `password_hash()`
- Password verification using PHP `password_verify()`

---

## 📊 Reports

The system provides several built-in reports:

1. Total Book Value
2. Books by Author
3. Borrower Activity
4. Current Loans
5. Books by Country
6. Inactive Borrowers
7. Multi-Author Books
8. Sold Books
9. Available Books
10. Borrower Loan History

---

## 🎨 Interface Design

The system uses a modern dashboard layout with:

- Sidebar navigation
- Warm color palette
- Responsive layout
- Styled forms
- Styled tables
- Report cards
- Buttons, badges, and alerts

---

## 📸 Screenshots

You can add screenshots here after uploading them to your GitHub repository.

```md
![Login Page](screenshots/login.png)
![Dashboard](screenshots/dashboard.png)
![Reports](screenshots/reports.png)
```

---

## 🚀 Future Improvements

- Add stronger input validation
- Use prepared statements for all database queries
- Add pagination for large tables
- Add user permissions for each role
- Add book cover image upload
- Add due date notifications
- Add export reports to PDF or Excel

---

## 👩‍💻 Author

Developed by:

**Roz Warasna,**
**Rama Sayyad,**
**Aliaa Yaghi**

---

## 📌 Project Status

This project is completed as a university web application project for managing a library database system.

---

## 📄 License

This project is for educational purposes.
