# Event Management System

The **Event Management System** is a web application designed to streamline event management and participation for both organizers and students. This system offers tools to create, manage, and participate in events, with additional features like notifications, attendance tracking, and feedback submission.

## Features

### 1. For Students
- Discover upcoming and ongoing events.
- Register for events with limited occupancy.
- Receive notifications about events and opportunities.
- Submit feedback for attended events.
- View personal attendance and event history.

### 2. For Organizers
- Create and manage events with participant limits.
- Track event participation and volunteer attendance.
- Mark attendance for participants and volunteers.
- Monitor event engagement through detailed records.

### 3. For Admin
- Edit user details.
- Manage volunteer and participation events' details.
- Manage "Contact Us" messages.

### 4. Shared Features
- Role-based redirection for organizers, students, and admins.
- Search and filter events with pagination for easy browsing.

## Technology Stack
- **Backend**: PHP, MySQL (via XAMPP)
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MySQL with tables like `events`, `participants`, `volunteers`, and more.
- **Image Management**: Stored in the database using `LONG BLOB` format.

## Prerequisites
To set up and run this project, you need:
- XAMPP installed on your local machine.
- A web browser to access the web application.
- A text editor like Visual Studio Code for code modifications.

## Installation Guide

### Step 1: Configure the Database
1. Start XAMPP and ensure the Apache and MySQL services are running.
2. Import the provided `eventmanagementsystem.sql` file into your database via phpMyAdmin.
3. Ensure the database connection file `config/connection.php` contains the following:


   <?php
   // Connect to localhost phpmyadmin
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "eventmanagementsystem";

   $conn = new mysqli($servername, $username, $password, $dbname);

   if ($conn->connect_error) {
       die("Connection Failed: " . $conn->connect_error);
   }
   ?>


### Step 2: Run the Application
1. Place the project folder inside the `htdocs` directory in XAMPP.
2. Access the application at `http://localhost/EventManagementSystem`.

## Usage

### For Students
1. Register or log in to access events.
2. Join events and check attendance status.
3. Submit feedback for attended events.

### For Organizers
1. Log in to create and manage events.
2. Mark attendance and monitor engagement.
3. Ensure smooth participation through proper event management tools.

### For Admin
1. Log in to edit user details.
2. Manage volunteer and participation events' details.
3. Manage messages from the "Contact Us" page.

---
### Email & Passwords
- admin@admin.com - aDmin@123
- student@student.com - sTudent@123
- organizer@organizer.com - oRganizer@123
- alex@student.com - aLex@123
- vanessa@student.com - vAnessa@123
- michael@student.com - mIchael@123
- lim@organizer.com - lIm@12345
- daniel@organizer.com - dAniel@123
- lacy@organizer.com - lAcy@123
- simon@student.com , sImon@123
- siti@student.com, sIti@123
- sally@student.com, sAlly@123
- alice@student.com, aLice@123
- emma@student.com, eMma@123


## Developed By
- Lim Yee Jie
- Goh Hooi Yu  
- Pritika A/P Mariappen
- Darneshtas A/L P.Mokanatas
- Muhammad Tamyuzuddin bin Muhamad

---