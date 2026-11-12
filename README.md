# LifeLink – Blood Donor Finder 🩸

## Project Overview
LifeLink is a web-based blood donor finder application designed to connect people who need blood with available blood donors. It helps users search for donors based on blood group and city and allows donors to register their details.

## Features

- Donor registration
- Search donors by blood group and city
- Display donor contact details
- Emergency blood request submission
- Admin login and dashboard
- Manage donor information
- View and manage emergency requests
- Update donor availability status

## Technologies Used

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Local Server:** XAMPP

## Project Structure

```text
LifeLink/
│
├── admin/
├── css/
├── js/
├── php/
│
├── index.php
├── about.html
├── contact.html
├── register.html
├── search.html
└── emergency-request.html
```

## How to Run the Project Locally

1. Install XAMPP.
2. Copy the LifeLink project folder into the `htdocs` directory.
3. Start Apache and MySQL from XAMPP Control Panel.
4. Create the MySQL database named `lifelink`.
5. Configure the local database connection in `php/db.php`.
6. Create the required database tables.
7. Open your browser and visit:

`http://localhost/LifeLink/`

**Note:** The database connection file and database records are not included in this public repository.

## Future Enhancements

- Improve mobile responsiveness.
- Add secure user authentication.
- Add location-based donor search.
- Improve emergency request notifications.

## Project Purpose

LifeLink aims to make blood donor discovery easier and help connect blood donors with people in need during emergencies.
