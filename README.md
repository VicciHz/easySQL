# EasySQL

EasySQL is a lightweight and user-friendly PHP database wrapper that simplifies MySQL database operations. It provides an intuitive interface for common database tasks like inserting, fetching, updating, and deleting records with prepared statements for security.

## Features

- **Simple API**: Easy-to-use methods for common database operations
- **Secure**: Uses prepared statements to prevent SQL injection
- **Lightweight**: Minimal codebase with no external dependencies
- **Beginner-friendly**: Straightforward implementation for developers of all skill levels

## Installation

1. Clone the repository or download the `easySQL.php` file
2. Include the file in your PHP project:

```php
require_once 'path/to/easySQL.php';
```

## Usage

### Basic Connection

```php
<?php
require_once 'easySQL.php';

use App\Config\EasySQL;

// Create a new database connection
// EasySQL automaticly uses 3306 if no port is specifyed 
$db = new EasySQL('localhost', 'username', 'password', 'database_name', 3307); // You can leave port empty
?>
```

### Insert Data

```php
<?php
// Data to insert
$data = [
    'username' => 'fake_user',
    'email' => 'fake@example.com',
    'password' => 'secure_password'
];

// Insert into 'users' table
$db->db_In('users', $data);
?>
```

### Fetch Data

```php
<?php
// Fetch all usernames from 'users' table
$results = $db->db_Out('users', 'username');

// Fetch specific columns with conditions
$results = $db->db_Out('users', 'username, email', 'id = ?', [1], 'username ASC');
?>
```

### Update Records

```php
<?php
// Data to update
$updateData = [
    'email' => 'newemail@example.com'
];

// Update record where id = 1
$db->db_Set('users', $updateData, 'id = ?', [1]);
?>
```

### Delete Records

```php
<?php
// Delete records where status is inactive
$db->db_Del('users', 'status = ?', ['inactive']);
?>
```

### Close Connection

```php
<?php
// Close the database connection when done
$db->closeConnection();
?>
```

## Available Methods

- `db_In($table, $data)`: Insert data into a table
- `db_Out($table, $columns, $where = null, $params = [], $orderBy = '')`: Fetch data from a table
- `db_Set($table, $data, $where, $params = [])`: Update records in a table
- `db_Del($table, $where, $params = [])`: Delete records from a table
- `closeConnection()`: Close the database connection

## Contributing

We welcome contributions from the open-source community! Here's how you can help:

1. Fork this repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write clear, descriptive commit messages
- Add or update documentation as needed
- Ensure your code passes all tests (if any exist)
- Test your changes thoroughly

### Reporting Issues

If you find bugs or have feature requests, please open an issue in the repository. Include:

- A clear title and description
- Steps to reproduce (for bugs)
- Expected vs actual behavior
- Your environment (PHP version, MySQL version)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

If you have questions or need help:

- Check the documentation above
- Open an issue in the repository
- Look for examples in the example.php file

## Security

We take security seriously. If you discover any security vulnerabilities, please let us know immediately by opening an issue with the security details so we can address them promptly.

## Acknowledgments

- Special thanks to all contributors who help maintain and improve EasySQL
- Built with PHP and MySQL
