# Omada Voucher Timer

## Description

Omada Voucher Timer is a simple tool written in PHP for managing and monitoring voucher durations in Omada networks. It leverages the Tick Counter API for timer functionality and allows you to retrieve voucher duration information from the Omada Cloud API. This project currently lacks a polished user interface and requires manual input for retrieving client data.

## Installation

To run Omada Voucher Timer locally, follow these steps:

1. Clone the repository into your local web server directory:

   - For WampServer (e.g., `www` folder):
     ```bash
     git clone https://github.com/tantanquiaeo/omada-timer-php-test.git
     ```

   - For XAMPP (e.g., `htdocs` folder):
     ```bash
     git clone https://github.com/tantanquiaeo/omada-timer-php-test.git
     ```

2. Navigate to the project directory:

   ```bash
   cd omada-timer-php-test
Start your local web server (e.g., WampServer or XAMPP).

Access the application in your web browser at http://localhost/omada-timer-php-test.

Usage
Ensure that PHP is installed and running on your local web server.

Manually populate the client data:

RUn the clients.php

http://localhost/omada-timer-php-test/clients.php

Monitor the timer and manage vouchers as needed.

Timer Works now if your IP address is not in the json file the time is set to 0 else it will get the end time

Contributing
Contributions to Omada Voucher Timer are welcome! If you'd like to contribute, please follow these steps:

Fork the repository.
Create a new branch (git checkout -b feature/your-feature-name).
Make your changes.
Commit your changes (git commit -am 'Add some feature').
Push to the branch (git push origin feature/your-feature-name).
Create a new Pull Request.
License
This project is licensed under the MIT License - see the LICENSE file for details.

Contact
For inquiries or support, please contact cyrustristanquiaeo@gmail.com.
