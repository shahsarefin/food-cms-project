# Food CMS - SHAH

## Introduction

Food CMS by Shah Sultanul Arefin is a custom-built Content Management System for Food Manitoba designed to streamline online food menu management. Developed for my backend web development course, this CMS offers a user-friendly web interface for both customers and staff, enhancing the overall food ordering experience.

## Features

### 1- Content Management System CRUD

- **Create New Pages**: Admins can create new pages for the CMS, with at least 10 real-data pages including posts or products.
- **Edit and Delete Pages**: Admins have the authority to edit or delete existing pages through secured forms.
- **View and Sort Page List**: Admins can view a sortable list of existing pages, sorted at the database level for efficiency.
- **Category Management**: Admins can create, update, and assign categories to pages using a separate categories table.

### 2- User Navigation

- **Page Navigation**: Users can navigate through the pages created by admins, with a menu linking to all content.
- **Category-Based Navigation**: Users can browse pages by their associated categories through a dedicated menu system.

### 3- Content Search

- **Keyword Search**: Users can search for pages using keywords, with results linked directly to the CMS pages.

### 4- Validation and Security

- **Data Validation**: Validation rules are in place to ensure the integrity of the data when creating and updating pages.
- **Sanitization**: Numerical IDs and strings from GET or POST parameters are sanitized to prevent SQL injection and HTML injection attacks.

### 5- Image Uploads and Processing

- **Image Handling**: Admins can upload images to pages, with tests for 'image-ness' and proper handling of uploads.

### 6- Administrative Logins

- **Admin-Only Tasks**: Only admins can perform create, update, and delete (CUD) tasks, with additional measures for user management.
- **Password Security**: Passwords are hashed and salted using PHP functions for secure storage and verification.

### 7- Advanced Web Technologies

- **AJAX**: AJAX is used to load foods based on category, enhancing user interaction without page reloads.

### 8- Deployment and Version Control

- **Git**: The project’s source code is managed with Git, ensuring version control and tracking of commits.

## User Roles

- **Admin Users**: Can manage food categories, food items, including adding, updating, and removing content as needed and add/delete another admins.

## Database Structure

- **Admin Table**: Stores admin credentials and roles.
- **Category Table**: Categorizes the menu for easy browsing.
- **Food Table**: Details of all food items including title, price, image, and description.

## Technology Stack

- **Back-End**: PHP and MySQL with PDO for database interactions.
- **Front-End**: HTML, CSS, and JavaScript as needed.

## Getting Started

To begin using the Food Ordering CMS, follow these steps:

1. Clone the repository from the provided Github link.
2. Set up your local environment with a server capable of running PHP (e.g., XAMPP, MAMP).
3. Import the database schema provided in the repository to your MySQL database.
4. Configure the connection settings in the `db_connect.php` file.
5. Access the admin dashboard using the credentials provided to start managing your content.

## Support

For any queries or technical support, please contact [email support](mailto:shahsarefin@gmail.com).
