# UCLan-Shop - Web Application (CO1418 Assignment 2)

## Project Information

**Project title:** UCLan-Shop

**Student name:** Vladislav Vasilev

**Student Number:** G21303193

**Course:** BSc Computing Y2

**Module:** CO1418 – Web Technologies

**Homepage URL:** http://vesta.uclan.ac.uk/~vvasilev1/UCLan-Shop-Extended/php/index.php

**GitHub:** https://github.com/MockingbirdCopycatovich/UCLan-Shop-Extended

This project is a responsive front-end web application developed as part of **Assignment 2** for the module *Web Technologies (CO1418)*. The application represents an online **Student Union Shop** for the University of Central Lancashire, designed to sell discounted legacy merchandise.

---


## Test Account
Use the following credentials to test the system:

- **Email:** test@test.com
- **Password:** 123E1a

---

## Project Overview
This project is a full-stack web application developed for the **CO1418 Web Technologies module**.  
It extends Assignment 1 by implementing **server-side functionality using PHP and MySQL**.

The system represents an online student shop with features such as:
- User authentication
- Product browsing
- Shopping cart
- Order processing
- Reviews system

---

## Technologies Used
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL (via phpMyAdmin)
- **Server:** UCLan Vesta Server
- **Security:** bcrypt password hashing, prepared statements, htmlspecialchars()

---

## Features Implemented

### Core Features (Should Requirements)
- Database connection using `mysqli`
- Dynamic homepage displaying offers (`tbl_offers`)
- Product listing from database (`tbl_products`)
- User login system
- Personalized welcome message using PHP sessions

### Extended Features (Could Requirements)
- User registration with validation
- Secure password hashing (bcrypt)
- Product reviews system
- Average rating calculation
- Shopping cart using cookies
- Checkout system (order stored in `tbl_orders`)
- Custom error pages via `.htaccess`

---

## Pages Description

### index.php
- Displays offers dynamically from database
- Embedded video and introduction content
- Responsive navigation with burger menu

### products.php
- Lists all products from database
- Filtering system (stock levels)
- Add to cart functionality (for logged-in users)

### item.php
- Displays individual product details
- Shows average rating
- Allows logged-in users to:
  - Add to cart
  - Leave reviews

### cart.php
- Cart stored using cookies
- Add/remove/update items
- Displays total price
- Checkout functionality (creates order in DB)

### login.php
- User authentication
- Session handling
- Error feedback

### register.php
- User registration form
- Client-side + server-side validation
- Password hashing with bcrypt

### customErrorPage.php
- Handles HTTP errors (400, 401, 403, 404, 500)
- Friendly UI for errors

---

## Database Structure
The application uses the following tables:

- `tbl_users`
- `tbl_products`
- `tbl_offers`
- `tbl_reviews`
- `tbl_orders`

---

## Security Features
- Password hashing using `password_hash()` (bcrypt)
- Password verification using `password_verify()`
- Prepared statements (SQL injection protection)
- Output escaping using `htmlspecialchars()`
- Session-based authentication
- Input validation (client & server side)

---

## Project Structure

```
/php
  index.php
  products.php
  item.php
  cart.php
  login.php
  register.php
  conn.php
  logout.php
  customErrorPage.php

/js
  index.js
  products.js
  item.js
  cart.js
  register.js

/css
  style.css

/media
  images/
  video/

.htaccess
assignment2.sql
README.md
```

---

## Development Notes
- Sessions are used to manage user state across pages
- Cookies store cart data for persistence
- JavaScript enhances UI (filters, menu, cart actions)
- Code is structured for readability and maintainability

---

## Demo
- A short demo video is included separately as required
