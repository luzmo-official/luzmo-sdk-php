# Luzmo API

You can use this PHP project to interact with the [Luzmo](https://luzmo.com) API in order to create, modify or delete datasets, dashboards or push new data into the platform in a programmatic way.

## Installation
Install the latest version with
`composer require luzmo/luzmo-sdk-php`

## Usage
Include the `luzmo` php package in your project by including the composer `vendor/autoload.php` file.
See the `example.php` file for examples of how to create datasets or push data into the platform (triggering real-time dashboard updates).
See the `example-embedding.php` file to see an example of how to use the API to securely embed dashboards in a web page (with serverside pre-filtering of the data that the end-user can query).

## Documentation

The API documentation (available services and methods) can be found [here](https://developer.luzmo.com/?php#introduction).
