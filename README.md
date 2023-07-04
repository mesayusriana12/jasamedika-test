## Requirements
 - PHP 8.1 or higher
 - Node JS 14 or higher

---
## Installation

  This might take awhile but I'm pretty sure this is worth it. So let's get started.

  1. Clone the repository

     ```git clone https://github.com/mesayusriana12/jasamedika-test.git```

  2. Install dependencies

      ```
      cd jasamedika-test
      composer install
      npm install
      ```
  
  3. Copy .env.example to .env

      ``` cp .env.example .env ```
  
  4. Generate key application

      ``` php artisan key:generate ```
  
  5. Configure your database in .env
      ```
      DB_CONNECTION=mysql (can be changed to other database)
      DB_HOST=your_database_host
      DB_PORT=your_database_port
      DB_DATABASE=your_database_name
      DB_USERNAME=your_database_user
      DB_PASSWORD=your_database_password
      ```
  6. Run migrations and initial seeder

      ``` php artisan migrate:fresh --seed ```
  
  7. Run web server 
  
      ``` php artisan serve ```

  8. Run vite server 
  
      ``` npm run dev ```
    
  9. Open your browser and go to [localhost](http://localhost:8000) and now you ready to build your apps!
