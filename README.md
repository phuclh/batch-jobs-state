# Manage state of Laravel batch jobs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/phuclh/batch-jobs-state.svg?style=flat-square)](https://packagist.org/packages/phuclh/batch-jobs-state)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/phuclh/batch-jobs-state/run-tests?label=tests)](https://github.com/phuclh/batch-jobs-state/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/phuclh/batch-jobs-state/Check%20&%20fix%20styling?label=code%20style)](https://github.com/phuclh/batch-jobs-state/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/phuclh/batch-jobs-state.svg?style=flat-square)](https://packagist.org/packages/phuclh/batch-jobs-state)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/batch-jobs-state.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/batch-jobs-state)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require phuclh/batch-jobs-state
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="batch-jobs-state-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="batch-jobs-state-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="batch-jobs-state-views"
```

Progress bar component

Config Tailwind CSS for the progress bar component in `tailwind.config.js`:

```bash
module.exports = {
    content: [
        ...
        './vendor/phuclh/batch-jobs-state/resources/views/**/*.blade.php',
    ]
};
```

## Usage

```php
$batchJobsState = new Phuclh\BatchJobsState();
echo $batchJobsState->echoPhrase('Hello, Phuclh!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [phucle](https://github.com/phuclh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
