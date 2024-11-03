# Hotel Catalog

A web-based catalog application designed for Brickspoint's luxury hotels and aparthotels, allowing potential guests to view, explore, and choose from available rooms with ease. The Hotel Catalog provides a visually engaging gallery with images, room details, and a smooth user experience on both desktop and mobile devices.

## Features

- **Room Listings**: Browse and view detailed listings of hotel rooms.
- **Interactive Gallery**: Zoomable and scrollable image gallery with slideshows for each room.
- **Responsive Design**: Fully responsive interface for mobile and desktop views.
- **Pagination Support**: Room listing pagination to ensure smooth browsing.
- **Dynamic Data**: Fetch and display room information dynamically from a backend database.
- **User-Friendly Filtering and Sorting**: Easily locate rooms based on categories or user preferences (e.g., room size, price).
- **Authentication**: Restricted admin access for room and catalog management.
- **Drag-and-Drop Image Upload**: Upload room images directly via drag-and-drop for quick content updates.

## Technologies Used

- **Backend**: Laravel 11
- **Frontend**: Blade, JavaScript, HTML5, CSS3
- **Database**: MySQL
- **Image Handling**: Intervention Image for resizing and optimizing images
- **Authentication**: Laravel Sanctum
- **Deployment**: Docker (for local development), GitHub Actions for CI/CD (optional)

## Installation

### Prerequisites

- PHP 8.3+
- Composer
- Node.js & npm
- MySQL or another compatible SQL database
- Docker (optional, for containerized setup)

### Steps

1. **Clone the Repository**:
    ```bash
    git clone https://github.com/yourusername/hotel-catalog.git
    cd hotel-catalog
    ```

2. **Install Dependencies**:
    ```bash
    composer install
    npm install
    npm run build
    ```

3. **Environment Setup**:
    - Copy the `.env.example` file to create a new `.env` file:
        ```bash
        cp .env.example .env
        ```
    - Configure database settings and other environment variables in `.env`.

4. **Generate Application Key**:
    ```bash
    php artisan key:generate
    ```

5. **Run Migrations**:
    ```bash
    php artisan migrate
    ```

6. **Start the Development Server**:
    ```bash
    php artisan serve
    ```

    Your project should now be accessible at `http://127.0.0.1:8000`.

## Usage

1. **Viewing Rooms**: Users can navigate to the main page to view available rooms in a paginated gallery.
2. **Admin Access**: Admins can log in to add, edit, or delete room listings, update images, and manage room availability.
3. **Image Upload**: Drag-and-drop functionality for easy room image uploads, with image storage managed by Laravel’s built-in file storage and ID-based retrieval.

## Project Structure

```bash
hotel-catalog/
├── app/
│   ├── Http/
│   └── Models/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
├── routes/
│   └── web.php
├── database/
│   └── migrations/
├── public/
└── .env.example
```

## Contributing

1. Fork the repository.
2. Create a feature branch (`git checkout -b feature-name`).
3. Commit your changes (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature-name`).
5. Create a new Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any inquiries or suggestions, please contact **Oluwasheyi** at [jaywoncoded@gmail.com](mailto:jaywoncoded@gmail.com).
