# **TMS-php — Textile Management System**

---

## **📘 Description**

### **What the project does**

**TMS-php** digitizes the complete workflow of a textile business. It provides a centralized management system to handle:

- **Inventory Management:** Products, categories, real-time stock updates.
- **Sales & Billing:** Create invoices, manage payments, track dues.
- **Customer CRM:** Maintain customer history and inquiries.
- **Staff Operations:** Monitor staff activity with role-based control.
- **Notifications:** Automated alerts sent via Telegram.

The application includes:

- **Admin Panel** — full control over system operations.
- **Staff Panel** — restricted access for daily tasks.

---

### **Why this project was built**

Many textile businesses rely on notebooks, Excel files, or memory-based workflows.
This system replaces those manual processes and was also developed as a **major academic college project** to demonstrate professional proficiency in **Native PHP and MySQL**.

---

### **Problems it solves**

- **Removes Manual Errors:** Automated billing, stock counting, and calculations.
- **Instant Overview:** Dashboard with live sales and inventory analytics.
- **Secure Access Control:** Admin vs. Staff permissions.

---

## **✨ Features**

### **Core Modules**

#### 🔐 **Role-Based Authentication**

- Secure login for Admin & Staff
- **Google OAuth Login** support

#### 📊 **Dashboard**

- Sales overview
- Stock status
- Inquiries & orders summary
- Graphs via ApexCharts

#### 📦 **Inventory Management**

- Manage categories & products
- Live stock tracking
- Stock update history

#### 🧾 **Invoicing System**

- Generate invoices dynamically
- Auto-calculation of totals
- Print-ready view
- Invoice history

#### 👥 **Customer Management**

- Customer details
- Inquiry tracking
- Transaction logs

#### 💰 **Financial Module**

- Record partial/full payments
- Outstanding balance tracking
- Payment history

#### 🤖 **Smart Notifications**

- Integrated **Telegram Bot API**
- Auto alerts for sales, low stock, inquiries, etc.

---

## **🛠 Tech Stack**

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap
- **Backend:** Native PHP
- **Database:** MySQL
- **Server:** Apache (XAMPP)
- **Libraries & APIs:**

  - Telegram Bot API
  - Google OAuth
  - SweetAlert2
  - ApexCharts

---

## **📂 Project Structure**

```text
tms-php/
├── public/             # Public files and project docs file -> public/tms_documentation.docx
├── admin/              # Admin panel (dashboard, users, products, etc.)
├── staff/              # Staff panel (limited features)
├── db_dump/            # SQL dump files
├── include/            # Common components (header, footer, auth, DB connection)
├── public/             # Images & static resources
├── src/                # CSS, JS, plugins, fonts
├── config.php          # Main configuration file
├── Database.php        # Database class
├── setup.php           # Auto database setup
├── send_tg_msg.php     # Telegram notification helper
├── index.php           # Landing to login
└── login.php           # Login screen
```

---

## **📌 Why PHP, MySQL & Apache?**

This project aligns with academic guidelines requiring PHP & MySQL.
Additional reasons:

- **Perfect for rapid development**
- **Runs flawlessly on XAMPP**
- **Zero build steps — simple deployment**
- **Beginner-friendly yet powerful**

---

## **🚀 Installation Guide**

### **Prerequisites**

- XAMPP / WAMP / MAMP
- Web browser

---

### **Step 1: Clone or Download**

```bash
git clone https://github.com/thedhruvish/TMS-php.git
```

Move the folder to:

- **Windows:** `C:\xampp\htdocs\tms-php`
- **Linux/Mac:** `/opt/lampp/htdocs/tms-php`

---

### **Step 2: Configuration**

1. Go to the root directory
2. Rename `sample_config.php` → `config.php`
3. Update DB credentials:

```php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'tms-php');
```

---

### **Step 3: Database Setup**

#### **Option A — Automatic (Recommended)**

Start Apache & MySQL → visit:

```
http://localhost/tms-php/setup.php
```

#### **Option B — Manual Import**

1. Open phpMyAdmin
2. Create database `tms-php`
3. Import `db_dump/DATA-TMS.sql`

---

### **Step 4: Run the App**

Visit:

```
http://localhost/tms-php/
```

Login using credentials in documentation or register (if enabled).

---

## **🖼 Screenshots**

![image1](public/img/image1.png)
![image2](public/img/image2.png)
![image3](public/img/image3.png)
![image4](public/img/image4.png)
![image5](public/img/image5.png)
![image6](public/img/image6.png)
![image7](public/img/image7.png)
![image8](public/img/image8.png)
![image9](public/img/image9.png)
![image10](public/img/image10.png)
![image11](public/img/image11.png)
![image12](public/img/image12.png)
![image13](public/img/image13.png)
![image14](public/img/image14.png)
![image15](public/img/image15.png)
![image16](public/img/image16.png)
![image17](public/img/image17.png)
![image18](public/img/image18.png)
![image19](public/img/image19.png)
![image20](public/img/image20.png)
![image21](public/img/image21.png)
![image22](public/img/image22.png)
![image23](public/img/image23.png)
![image24](public/img/image24.png)
![image25](public/img/image25.png)
![image26](public/img/image26.png)
![image27](public/img/image27.png)
![image28](public/img/image28.png)
![image29](public/img/image29.png)
![image30](public/img/image30.png)
![image31](public/img/image31.png)
![image32](public/img/image32.png)
![image33](public/img/image33.png)
![image34](public/img/image34.png)
![image35](public/img/image35.png)

---

## **👨‍💻 Developers**

---

### **Dhruvish Lathiya**

[![Website](https://img.shields.io/badge/-Website-black?style=flat-square&logo=website)](https://dhruvish.in)
[![GitHub](https://img.shields.io/badge/-GitHub-black?style=flat-square&logo=github)](https://github.com/thedhruvish)
[![X](https://img.shields.io/badge/-X-black?style=flat-square&logo=x)](https://x.com/dhruvishlathiya)

---

### **Maunish Prajapati**

[![GitHub](https://img.shields.io/badge/-GitHub-black?style=flat-square&logo=github)](https://github.com/MaunishPrajapati)

---

### **Sahil Vaghasiya**
[![Website](https://img.shields.io/badge/-Website-black?style=flat-square&logo=website)](https://sahilvaghasiya.netlify.app/)
[![GitHub](https://img.shields.io/badge/-GitHub-black?style=flat-square&logo=github)](https://github.com/vaghasiyasahil)

---

## **📄 License**

Distributed under the **MIT License** — free to use, modify, and distribute.
