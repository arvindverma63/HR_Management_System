# HR Management System

An HR management system built with Laravel, Tailwind CSS, and MySQL for efficient employee management, attendance tracking, and reporting.

## 📋 Features

* Employee Management (Add, Edit, Delete)
* Attendance Tracking
* PDF and CSV Report Generation
* User Authentication and Roles
* Responsive UI with Tailwind CSS

## 🚀 Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/arvindverma63/HR_Management_System.git
   cd hr-management-system
   ```
2. Install dependencies:

   ```bash
   composer install
   npm install
   npm run build
   ```
3. Set up environment variables:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configure the database in the `.env` file:

   ```plaintext
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
5. Run database migrations:

   ```bash
   php artisan migrate
   ```
6. Start the development server:

   ```bash
   php artisan serve
   ```

## 📄 Usage

* Access the application at `http://localhost:8000`
* Log in or register to start managing employees and attendance.

## 📊 Reports

* Generate attendance reports in PDF and CSV formats.

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## 📜 License

This project is licensed under the MIT License.

## 📞 Contact

For support, reach out to [arvindverma63](https://github.com/arvindverma63).

---

Made with ❤️ using Laravel, Tailwind CSS, and MySQL.
