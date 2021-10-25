# integration-commons
Library with common classes used for constructing MyParcel.com eCommerce Integrations

## PHP 8
The minimum PHP version is `8.0`. To update dependencies on a system without PHP 8 use:
```shell
docker run --rm --mount type=bind,source="$(pwd)",target=/app composer:2.0 composer update
```

## Run tests
You can run the test through docker:
```shell
docker run -v $(pwd):/app --rm -w /app php:8.0-cli vendor/bin/phpunit
```
