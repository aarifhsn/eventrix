## 🚀 Installation

### Prerequisites

- **PHP 8.3+**
- **MySQL/MariaDB**
- **Web Server** (Apache/Nginx with mod_rewrite)
- **Composer** (for dependencies)

### Required PHP Extensions

- cURL (for payment processing)
- GD (for image handling)
- JSON
- PDO MySQL

### Installation Steps

#### Option 1: Installation Wizard (Recommended)

1. **Download/Clone the Project**

   ```bash
   git clone https://github.com/aarifhsn/eventrix.git
   cd eventrix
   ```

2. **Set Directory Permissions**

   ```bash
   chmod 755 uploads/
   chmod 755 config/
   ```

3. **Run Installation Wizard**

   - Open your browser and navigate to: `http://yourdomain.com/install.php`
   - Follow the step-by-step installation wizard:
     - **Step 1:** Database configuration
     - **Step 2:** Import database structure
     - **Step 3:** Create admin account
     - **Step 4:** Complete setup

4. **Security**
   - Delete or rename `install.php` after installation
   - Access admin panel: `http://yourdomain.com/admin`

#### Option 2: Manual Installation

1. **Database Setup**

   - Create a new MySQL database
   - Import the database structure:

   ```bash
   mysql -u your_username -p your_database_name < eventrix.sql
   ```

2. **Configuration**

   - Copy configuration templates:

   ```bash
   cp config/config.example.php config/config.php
   cp config/config-payment.example.php config/config-payment.php
   ```

   - Update `config/config.php` with your database credentials
   - Update `config/config-payment.php` with your payment credentials

3. **Create Admin Account**
   - Navigate to: `http://yourdomain.com/admin/setup.php`
   - Fill in the admin registration form
   - Login with your new credentials

### First Time Setup

After installation:

1. **Login to Admin Panel**

   - Visit: `http://yourdomain.com/admin/login.php`
   - Use the credentials you created during setup

2. **Configure Payment Settings**

   - Update Stripe/PayPal credentials in admin panel
   - Test payment integration

3. **Customize Your Site**
   - Upload logo and branding
   - Configure site settings
   - Create your first event
