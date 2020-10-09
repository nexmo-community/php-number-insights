# Use Vonage Number Insights with PHP

This is a very simple demo app showing the [Number Insights API](https://developer.nexmo.com/number-insight/overview) in action.

## Getting Started

First: install dependencies `composer install`

Then: in your Terminal run: `ngrok http 8000`

Then: copy `config.php.sample` to `config.php`. Edit the variables to be your Vonage API key, Vonage API secret, and your Ngrok forwarding URL found in the output form the above command. Your Vonage API key and secret is available from the [dashboard](http://dashboard.nexmo.com), where you can also sign up there too if you don't have an account).

Finally: change into the `public/` directory and start the webserver with `php -S localhost:8080`
