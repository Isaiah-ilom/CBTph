# E-Exam CBT System

An online Computer Based Test (CBT) examination system built with PHP and SQLite.

## Overview

This is a CBT software for managing online examinations including:
- Student registration and login
- Admin panel for managing exams, students, and questions
- Practice tests and mock exams
- Score tracking and reporting

## Project Structure

- `/` - Main application files (login.php, register.php, etc.)
- `/admin/` - Admin panel pages
- `/inc/` - Include files with core functions
- `/inc1/` - Additional include files for public pages
- `/sql/` - Database schema files
- `/css/`, `/js/` - Frontend assets
- `/images/` - Image assets

## Database

The application uses SQLite instead of the original MySQL.
- Database file: `database.sqlite`
- Schema: `sql/schema.sql`

## Key Pages

- `login.php` - Student login with PIN
- `nopin_login.php` - Student login without PIN
- `admin_login.php` - Admin login (access code: 12345)
- `register.php` - Student registration
- `index.php` - Student dashboard (after login)

## Default Credentials

- **Admin Access Code**: 12345
- **Test Student**: Reg No: TEST001

## Configuration

- Database connection is handled in `inc/functions.php`
- Environment settings are in `database.php`
- ROOT_URL is dynamically configured based on server environment

## Recent Changes (Dec 2025)

- Converted MySQL to SQLite for Replit compatibility
- Removed license and date expiration checks
- Updated database wrapper for PDO/SQLite compatibility
- Fixed Windows line endings in PHP files
- Configured for Replit deployment

## Running the Application

The PHP development server runs on port 5000.
