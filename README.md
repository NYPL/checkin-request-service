# NYPL Checkin Request Service

[![Build Status](https://travis-ci.org/NYPL/checkin-request-service.svg?branch=master)](https://travis-ci.org/NYPL/checkin-request-service)
[![Coverage Status](https://coveralls.io/repos/github/NYPL/checkin-request-service/badge.svg?branch=master)](https://coveralls.io/github/NYPL/checkin-request-service?branch=master)

The Checkin Request Service receives a request from an API service or consumer
and processes the implied action for a given hold request. 

Once the service validates the request and saves it to its database instance,
it sends a new request via NCIP to the Sierra ILS, processes the response
from Sierra ILS and returns a successful response or an error response.

After these responsibilities are met, the contract ends and another
service or consumer takes over or the request terminates in a response from this
service.

This service works in tandem with the [NYPL Checkout Request Service](https://github.com/NYPL/checkout-request-service). The two services now are consumed by [Cancel Request Consumer](https://github.com/NYPL/cancel-request-consumer) for canceling a hold on a ReCap item in Sierra. The way to perform the cancelling is to check out and check in the item right away.

This package is intended to be used as a Lambda-based Node.js/PHP Patron Service using the 
[NYPL PHP Microservice Starter](https://github.com/NYPL/php-microservice-starter).

This package adheres to [PSR-1](http://www.php-fig.org/psr/psr-1/), 
[PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/) 
(using the [Composer](https://getcomposer.org/) autoloader).

## Requirements

* Node.js >=6.0
* PHP >=7.0 
  * [pdo_pdgsql](http://php.net/manual/en/ref.pdo-pgsql.php)

Homebrew is highly recommended for PHP:
  * `brew install php71`
  

## Installation

1. Clone the repo.
2. Install required dependencies.
   * Run `npm install` to install Node.js packages.
   * Run `composer install` to install PHP packages.
   * If you have not already installed `node-lambda` as a global package, run `npm install -g node-lambda`.
3. Setup [configuration files](#configuration).
   * Copy the `.env.sample` file to `.env`.
   * Copy `config/var_env.sample` to `config/var_development.env`.
4. Replace sample values in `.env` and `config/var_development.env`.

## Configuration

Various files are used to configure and deploy the Lambda.

### .env

`.env` is used *locally* for two purposes:

1. By `node-lambda` for deploying to and configuring Lambda in *all* environments. 
   * You should use this file to configure the common settings for the Lambda 
   (e.g. timeout, Node version). 
2. To set local environment variables so the Lambda can be run and tested in a local environment.
   These parameters are ultimately set by the [var environment files](#var_environment) when the Lambda is deployed.

### package.json

Configures `npm run` commands for each environment for deployment and testing. Deployment commands may also set
the proper AWS Lambda VPC, security group, and role.
 
~~~~
"scripts": {
    "deploy-qa": "node-lambda deploy -e qa -f config/var_qa.env -S config/event_sources_qa.json -b {subnets} -g {security-groups}",
    "deploy-production": "node-lambda deploy -e production -f config/var_production.env -S config/event_sources_production.json -b {subnets} -g {security-groups}",
},
~~~~

### config/var_app

Configures environment variables common to *all* environments. This is also the place that holds the environment variables you need for running the service locally.

### config/var_*environment*.env

Configures environment variables specific to each environment.

### config/event_sources_*environment*

Configures Lambda event sources (triggers) specific to each environment.

## Usage

### Process a Lambda Event

To use `node-lambda` to process the sample API Gateway event in `event.json`, run:

~~~~
npm run test-checkin
~~~~

### Run as a Web Server

To use the PHP internal web server, run:

~~~~
php -S localhost:8888 -t . index.php
~~~~

You can then make a request to the Lambda: `http://localhost:8888/api/v0.1/patrons`.

Notice that you will need a running database assigned to the service. Please set the correct configurations for the database in config/var_app.

### Swagger Documentation Generator

Create a Swagger route to generate Swagger specification documentation:

~~~~
$service->get("/docs", SwaggerGenerator::class);
~~~~

### Response

A successful response for checking in will return a status code of `202`. However, if you continue to check in the same item — even it has been checked in — the response will still be succesful but only with a status code of `208`.

## Deployment

Before deploying, ensure [configuration files](#configuration) have been properly set up:

1. Copy `config/var_env.sample` to `config/dev.env`, `config/var_qa.env`, and `config/var_production.env`.
   *  Verify environment variables are correct.
2. Verify `.env` has correct settings for deployment.
3. Verify `package.json` has correct command-line options for security group, VPC, and role (if applicable).
4. Verify `config/event_sources_dev.json`, `config/event_sources_qa.json`, `config/event_sources_production.json` have proper event sources.

To deploy to an environment, run the corresponding command. For example:

~~~~
npm run deploy-development
~~~~
