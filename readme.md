# Wordpress Database Direct Query

Run SQL queries directly on your WordPress database with ease!


By exposing REST API endpoints, the WordPress Database Direct Query plugin empowers you to execute custom SQL queries directly on your WordPress database. Take control of your WordPress database like never before, ensuring efficiency and flexibility in managing your site's data.

## Features

- Execute custom SQL queries on your WordPress database.
- Secure APIs using Wordpress builting authentication mechanisms
- Retrieve data and perform advanced operations.
- Enhance your control over the WordPress database.

## Installation

1. Download the plugin zip file.
2. Upload and activate the plugin through the WordPress admin panel.
3. Navigate to the "DB Direct Query" page in the admin menu to start executing custom queries.

<!-- ## Usage

1. After installation, go to your WordPress admin panel.
2. Find and click on the "DB Direct Query" menu.
3. Enter your custom SQL queries and execute them directly on your database. -->

## Authentication

The WordPress Database Direct Query plugin utilizes secure authentication mechanisms to interact with the WordPress REST API. Authentication is crucial to ensure that only authorized users can execute custom SQL queries directly on the database.

### Application Passwords

As of WordPress 5.6, the plugin supports authentication via Application Passwords. Application Passwords provide a secure way to authenticate REST API requests without exposing the user's actual login credentials.

To authenticate API calls using Application Passwords:

1. Navigate to your WordPress admin panel.
2. Go to Users -> All Users.
3. Click on the user you want to use for API authentication.
4. Scroll down to the "Application Passwords" section.
5. Generate a new Application Password, providing a name for reference.
6. Copy the generated password.


When making API requests, such as executing custom queries or retrieving data, use the generated Application Password as the username and password in the Basic Authentication setup of your API client (e.g., Postman).

1. Open Postman.
2. Create a new request or open an existing one.
3. Go to the "Authorization" tab.
4. Choose "Basic Auth" from the "Type" dropdown.
5. Enter your WordPress username as the "Username" and the Application Password as the "Password."

Ensure that your WordPress site is using HTTPS to secure the communication.

For more information on Application Passwords, refer to the [WordPress Documentation](https://wordpress.org/support/article/application-passwords/).

Feel free to reach out if you encounter any authentication-related issues or need further assistance.


## REST API Endpoints

The plugin provides REST API endpoints for programmatically interacting with the database.

- **Get Tables:**
```
/wp-json/wp-db-direct-query/v1/get-tables
```

- **Get Table Columns:**
```
/wp-json/wp-db-direct-query/v1/get-table-columns/{table_name}
```

- **Execute Custom Query:**
```
/wp-json/wp-db-direct-query/v1/run
```
  > Payload
  >```json
  >{
  >   "query": "SELECT * FROM wp_posts"
  >}
  >```

## Frequently Asked Questions

1. **Is it safe to execute custom queries?**
 - This plugin is intended for advanced users who are familiar with SQL queries. Ensure you have proper backups before making any changes to your database.

## Support and Issues

For support or to report issues, please [open a new issue](https://github.com/teners-net).

## Contributing

Contributions are welcome! Feel free to fork the repository, make improvements, and submit pull requests.

## License

This plugin is licensed under the GPLv2 or later. See the [LICENSE](LICENSE) file for details.

---

Enjoy using the WordPress Database Direct Query plugin! If you have any feedback or suggestions, we'd love to hear from you.
