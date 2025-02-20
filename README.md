# Online Library Platform

## Overview
The **Online Library Platform** is a web application built with **Laravel** that connects **authors** and **readers**. Authors can upload their books, while readers can access content. Premium books are available through a subscription model. The platform is integrated with a **Laravel REST API**, ensuring seamless communication with mobile and web applications.

## Features
- **Author Dashboard**: Manage books, track views, and interact with readers.
- **Reader Dashboard**: Browse, search, and read books.
- **Premium Subscription**: Access exclusive content through a subscription model.
- **REST API Integration**: Smooth app connectivity for web and mobile applications.
- **User Authentication**: Secure login and registration system.
- **Book Management**: Upload, edit, and categorize books.
- **Responsive Design**: Fully optimized for different devices.
- **Search and Filtering**: Find books by title, author, or genre.

## Technologies Used
- **Laravel** (Back-end framework)
- **MySQL** (Database)
- **REST API** (Integration with mobile apps)
- **JavaScript, jQuery, AJAX** (Interactive front-end)
- **Bootstrap, CSS** (Responsive UI)
- **Git** (Version control)

## Installation
### Prerequisites
Ensure you have the following installed:
- PHP 8+
- Composer
- MySQL
- Laravel 10+
- Node.js & npm (optional for front-end assets)

### Steps to Install
1. Clone the repository:
   ```bash
   git clone https://github.com/mokaddes/OnlineLibraryPlatform.git
   cd online-library
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Set up the `.env` file:
   ```bash
   cp .env.example .env
   ```
   - Update **database credentials**
   - Set **APP_URL** and other configurations
4. Generate the application key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations:
   ```bash
   php artisan migrate --seed
   ```
6. Serve the application:
   ```bash
   php artisan serve
   ```
7. (Optional) Install front-end dependencies:
   ```bash
   npm install && npm run dev
   ```

## API Endpoints
The platform provides a **REST API** for integration with mobile and other applications.
- `POST /api/auth/login` - Authenticate user
- `GET /api/books` - Fetch all books
- `GET /api/books/{id}` - Fetch a single book
- `POST /api/books` - Upload a new book (Authors only)
- `POST /api/subscribe` - Subscribe to premium content

## Contribution
Feel free to contribute by submitting **pull requests**. Please follow the coding standards and ensure compatibility before submitting changes.

## License
This project is licensed under the **MIT License**.

## Contact
For any inquiries or contributions:
- **Developer**: Mokaddes Hosain
- **Portfolio**: [mokaddes.com](https://mokaddes.com)
- **GitHub**: [github.com/mokaddes](https://github.com/mokaddes)
- **Email**: mr.mokaddes@gmail.com

