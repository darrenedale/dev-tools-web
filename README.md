# dev-tools-web
Some useful tools for developers, webified

For local development, fire up the docker containers:

````bash
docker compose up -d
````

For the app to run successfully, you'll need to build the assets. To build the typescript project:

````bash
cd public/scripts
tsc
````

To build the CSS:

````bash
cd public/styles
sass --source-map dev-tools.scss:dev-tools.css
````

Visit http://localhost:8099/ to see the app running inside Docker.

The following directories must be readable and writable from the fpm container:

- data/session
- logs

xdebug is installed, configured and enabled in the fpm container. You should be able to debug without
any further server-side modifications.
