
# Laravel Product Management with Cart System

This Laravel application allows you to manage products with multiple images and includes a shopping cart system. The user can view products, add them to the cart, and view/update the cart in a modal popup. The system is built using Laravel, Bootstrap 5, and jQuery — designed for responsiveness and a user-friendly experience.

---

## Features

- Add, edit, and delete products
- Upload multiple images for each product
- Add products to cart (hardcoded user ID = 1)
- Update quantity in cart if the same product is added again
- View cart in a popup modal
- See all images of each product in the cart (displayed in a row)
- Responsive and clean UI with Bootstrap

---

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/sushantpalvi/inovantsolution.git
   cd inovantsolution
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   - Create a MySQL database.
   - Update `.env` with your database credentials.
   - Run migrations:
     ```bash
     php artisan migrate
     ```

5. **Storage linking**
   ```bash
   php artisan storage:link
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

---

## License

This project is open-sourced under the MIT License. Feel free to use and modify.

---

## Author

Sushant Palavi.
sushantpalvi.40@gmail.com
