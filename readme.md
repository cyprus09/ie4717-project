# Footscape - E-commerce Platform for Sneaker Enthusiasts

Footscape is a dynamic e-commerce platform offering a curated selection of the latest and most popular sneakers. With a streamlined design and intuitive interface, it provides an enjoyable shopping experience from browsing products to completing a purchase.

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [API Endpoints](#api-endpoints)
- [Contributing](#contributing)
- [Contact](#contact)

---

## Features

- **Home Page**: Introduces the brand, featuring a search bar, new arrivals, and featured shoes. The homepage highlights trending products and collections.
- **Catalog Page**: Displays the entire collection of products with filtering options (category, gender, brand, and price range) and sorting by popularity or price.
- **Product Description Page**: Contains detailed information about each shoe, including brand, sizing options, high-quality images, and customer reviews. Users can add items to their cart directly from this page.
- **Cart and Checkout Pages**: Provides a secure, session-based cart system allowing users to add, remove, and modify items. Users proceed through a seamless checkout process to finalize their purchases.
- **User Authentication**: Users can create accounts, log in, and manage their profiles securely.
- **Feedback Page**: Users can submit feedback on the products, website.
- **Order Confirmation Page**: Page seen post submission of order to confirm order details.
- **Responsive Design**: Optimized for both desktop and mobile devices to ensure a consistent user experience.

## Tech Stack

- **Frontend**: HTML, CSS, JavaScript (with AJAX for real-time updates)
- **Backend**: PHP (session management, form handling)
- **Database**: MySQL (data persistence for user accounts, products, orders)
- **Server**: XAMPP on macOS for local development

## Project Structure

```plaintext
ie4717-project/
├── assets/              # Images and logos used on the site
├── components/          # Reusable components (carousels, footers, etc.)
│   ├── card-carousel.php
│   ├── category-card.php
│   ├── footer.php
│   ├── main-carousel.php
│   ├── navbar.php
│   └── product-card.php
├── pages/
│   ├── auth.php          # Authentication page
│   ├── catalog.php       # Product catalog page
│   ├── feedback.php      # Feedback page
│   ├── home.php          # Home page
│   ├── prod-desc.php     # Product description page
│   ├── shopping-cart.php # Cart and checkout page
│   └── thank-you.php     # Order confirmation page
├── scripts/              # JavaScript files
│   ├── components/
│   │   ├── card-carousel.js
│   │   ├── main-carousel.js
│   │   └── navbar.js
│   ├── pages/
│   │   ├── auth.js
│   │   ├── cart.js
│   │   ├── catalog.js
│   │   ├── home.js
│   │   └── prod-desc.js
├── css/                 # Stylesheets for different pages and components
│   ├── components/
│   │   ├── card-carousel.css
│   │   ├── footer.css
│   │   ├── main-carousel.css
│   │   ├── navbar.css
│   │   └── product-card.css
│   ├── pages/
│   │   ├── auth.css
│   │   ├── cart.css
│   │   ├── catalog.css
│   │   ├── feedback.css
│   │   ├── home.css
│   │   ├── prod-desc.css
│   │   └── thank-you.css
│   └── main.css         # Global styles
└── utils/               # Utility functions for backend operations
   ├── auth/
   │   ├── db-connect.php
   │   ├── logout.php
   │   └── session.php
   └── cart/
       ├── add-to-cart.php
       ├── cart-functions.php
       ├── process-order.php
       ├── remove-item.php
       └── update-quantity.php
```

---

## Installation

To set up Footscape locally:

1. **Clone/Fork the Repository**: Clone the repository to your local machine.

2. **Start XAMPP**: Launch XAMPP on macOS and start Apache and MySQL services.

3. **Database Setup**:

   - Open `phpMyAdmin` and create a new database (e.g., `footscape_db`).
   - Import the SQL files:
     - `sql/table_structure.sql`: Sets up the database schema.
     - `sql/product_data.sql`: Populates the database with sample product data.

4. **Configure Database Connection**: Update `utils/auth/db-connect.php` with your database credentials (e.g., `DBSERVER`, `DBUSERNAME`, `DBPASSWORD`, and `DBNAME`).

5. **Access the Application**: Open your browser and navigate to `http://localhost:8000/path/to/local/repo`.

---

## Database Schema

The database schema is structured to support an efficient and scalable e-commerce application:

- **Users**: Stores user account information, including encrypted passwords and profile details.
- **Products**: Contains product details such as name, description, brand, size options, and price.
- **Cart**: Tracks items added to a user’s cart, including quantity and product ID.
- **Orders**: Stores information about completed orders, including user ID and total amount.
- **Order Items**: Records each product in an order, with details like product ID, order ID, quantity, and price at the time of purchase.

---

## API Endpoints

Footscape offers the following API endpoints:

- **User Authentication**:

  - `POST /auth/login`: Authenticates a user and starts a session.
  - `POST /auth/register`: Registers a new user.

- **Cart Management**:

  - `POST /cart/add`: Adds a product to the cart.
  - `POST /cart/update`: Updates quantity of a product in the cart.
  - `DELETE /cart/remove`: Removes a product from the cart.

- **Order Management**:
  - `POST /checkout`: Finalizes the order and stores order details.
  - `GET /orders`: Retrieves a user’s past orders.

---

## Contributing

This project was developed as part of the IE4727 Web Application Design course at NTU (2024-25). Contributions are welcome! To contribute:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes and commit them (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Open a Pull Request.

---

## Contact

- **Developers**:
  - Mayank Pallai ([mayankpallai@gmail.com](mailto:mayankpallai@gmail.com))
  - Kevin Lee Gunawan ([kevin.leegunawan8@gmail.com](mailto:kevin.leegunawan8@gmail.com))
