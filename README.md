## Support us

At Opscale, we’re passionate about contributing to the open-source community by providing solutions that help businesses scale efficiently. If you’ve found our tools helpful, here are a few ways you can show your support:

⭐ **Star this repository** to help others discover our work and be part of our growing community. Every star makes a difference!

💬 **Share your experience** by leaving a review on [Trustpilot](https://www.trustpilot.com/review/opscale.co) or sharing your thoughts on social media. Your feedback helps us improve and grow!

📧 **Send us feedback** on what we can improve at [feedback@opscale.co](mailto:feedback@opscale.co). We value your input to make our tools even better for everyone.

🙏 **Get involved** by actively contributing to our open-source repositories. Your participation benefits the entire community and helps push the boundaries of what’s possible.

💼 **Hire us** if you need custom dashboards, admin panels, internal tools or MVPs tailored to your business. With our expertise, we can help you systematize operations or enhance your existing product. Contact us at hire@opscale.co to discuss your project needs.

Thanks for helping Opscale continue to scale! 🚀

## Description

This package extends [Spatie's Laravel Package Tools](https://github.com/spatie/laravel-package-tools) to provide Nova-specific functionality for package development. It inherits all the features from the base package while adding support for Nova resources.

## Installation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/opscale-co/nova-catalogs.svg?style=flat-square)](https://packagist.org/packages/opscale-co/nova-catalogs)

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash

composer require opscale-co/nova-package-tools

```

## Usage

### Getting Started

In your Nova package, you should let your service provider extend `Opscale\NovaPackageTools\NovaPackageServiceProvider`:

```php
use Opscale\NovaPackageTools\NovaPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class YourNovaPackageServiceProvider extends NovaPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('your-nova-package')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_your_package_tables')
            ->hasCommand(YourPackageCommand::class)
            // Nova-specific features are now directly available
            ->hasResource(YourResource::class)
            ->hasResources([
                UserResource::class,
                PostResource::class,
            ]);
    }
}
```

### Nova Resources

The main addition this package provides is the ability to register Nova resources with your package.

#### Registering a Single Resource

You can register a single Nova resource using the `hasResource` method:

```php
use App\Nova\User;

$package
    ->name('your-nova-package')
    ->hasResource(User::class);
```

#### Registering Multiple Resources

If your package provides multiple Nova resources, you can register them all at once using `hasResources`:

```php
use App\Nova\User;
use App\Nova\Post;
use App\Nova\Comment;

$package
    ->name('your-nova-package')
    ->hasResources([
        User::class,
        Post::class,
        Comment::class,
    ]);
```

You can also pass multiple resources as separate arguments:

```php
$package
    ->name('your-nova-package')
    ->hasResources(User::class, Post::class, Comment::class);
```

## Testing

``` bash
npm run test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/opscale-co/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email development@opscale.co instead of using the issue tracker.

## Credits

- [Opscale](https://github.com/opscale-co)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.