## Loan EMI Processing Web Application Documentation

### Introduction
This web application is designed to manage and process loan details and corresponding EMI calculations using Laravel and MySQL.

### Dependencies
- **Laravel Framework**: Version 10.10
- **PHP**: Version 8.1
- **NODE**: Version 16.8.1


### Installation Instructions

**Clone the repository**
    git clone https://github.com/siddhantmore21/emi-processing.git
    cd emi-processing


**Install Composer dependencies**    
    composer install

**Copy .env.example to .env and configure**    
    cp .env.example .env
    Configure your database connection details (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

**Generate application key**    
    php artisan key:generate

**Run database migrations and seeders**    
    php artisan migrate --seed
    
**Install Node dependencies**    
    npm install

**Start the development server**    
    php artisan serve
    npm run dev


    Functionality
    1. Loan Details
        View Loan Details: Lists all loan details stored in the database.
    2. EMI Processing
        Process EMI Data:
        Calculates monthly EMI amounts based on loan_amount and num_of_payment.
        Inserts calculated EMI amounts into the emi_details table dynamically.
        Ensures that the total of all EMI payments matches the total loan amount specified in loan_details.
    3. View EMI Details
        Display EMI Details: Shows a table with EMI details for each client, organized by month.
        Dynamic Columns: Columns are dynamically created based on the first_payment_date and last_payment_date to accommodate varying loan durations.
    4. Authentication
        Login: Secure login functionality for admin access.


    Access the Application

    Open your web browser and navigate to http://localhost:8000 (or the URL provided by php artisan serve).
    Navigate the Application

    Use the navigation menu to access different sections like Loan Details, EMI Details, and Processing EMI.
    Processing EMI

    Click on the "Process Data" button to initiate the EMI processing. This will calculate and insert EMI details into the emi_details table.
    Viewing EMI Details

    Navigate to the EMI Details section to view the dynamically generated table showing EMI details for each client.
   
   
    Troubleshooting
    Database Connection: Double-check database credentials in .env.
    Dependencies: Ensure PHP, Composer, and Laravel dependencies are correctly installed.
    Additional Notes
    This documentation assumes basic familiarity with Laravel, PHP, and MySQL.
    Adjust the functionality and implementation as per specific project requirements.