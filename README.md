# Laravel-Zoom

**⚠️ This package is currently in beta. Breaking changes may still occur. Please use with caution in production environments.**

Laravel-Zoom is a lightweight and extensible package for integrating Zoom API functionality into Laravel applications. It provides seamless authorization using OAuth (account-level apps) and a clean interface for sending authenticated requests to the Zoom API.

## Installation

Install the package using Composer:

```bash
composer require aboutnima/laravel-zoom:^0.1.1@beta
```


## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=laravel-zoom-config
```

Next, add your Zoom API credentials to your `.env` file:

```env
ZOOM_ACCOUNT_ID=your_zoom_account_id
ZOOM_CLIENT_ID=your_zoom_client_id
ZOOM_CLIENT_SECRET=your_zoom_client_secret
```

The configuration file (`config/zoom.php`) will contain:

```php
<?php

return [
    'account_id' => env('ZOOM_ACCOUNT_ID', ''),
    'client_id' => env('ZOOM_CLIENT_ID', ''),
    'client_secret' => env('ZOOM_CLIENT_SECRET', ''),
];
```


## Usage Example

The following example demonstrates how to use the package to retrieve a list of meetings for the authenticated user:

```php
use AboutNima\LaravelZoom\Zoom;

$zoom = app(Zoom::class);

$response = $zoom->sendRequest(
    method: 'get',
    endpoint: 'users/me/meetings',
    query: ['page_size' => 10],
    success: function ($status, $data) {
        // Handle successful response
    },
    error: function ($status, $message, $data) {
        // Handle error response
    }
);
```

## Documentation (References)

- [Zoom API Documentation](https://marketplace.zoom.us/docs/api-reference/zoom-api/)
- [Creating a Zoom OAuth App](https://marketplace.zoom.us/docs/guides/build/oauth-app/)


## Planned Features

The following enhancements are planned for future versions of this package:

- **Automatic Token Refresh:**  
  The package will automatically refresh the access token when it expires to ensure continuous integration with the Zoom API.

- **User Model Traits:**  
  Developers will be able to use a trait on their User model which provides helper methods for easier integration with the Zoom API without creating request via package.  
  For example:
  ```php
  $user->zoomUpcomingMeetings();
  ```
  This allows each user to manage their Zoom-related data directly through the model.

- **Other traits will be planned as features very soon**

- **Queued Requests:**  
  Request dispatching to the Zoom API will support Laravel’s queue system, enabling better performance and retry logic.

- **Events and Listeners:**  
  The package will dispatch events for key actions (e.g., Zoom meeting created, request failed), allowing developers to hook into these events and add custom logic through listeners.


## Contributing

Contributions are welcome and appreciated!  
If you are a developer and interested in improving this package, feel free to fork the repository and submit a pull request.  
Bug reports, feature suggestions, and improvements are all encouraged.

Please refer to [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.


## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
