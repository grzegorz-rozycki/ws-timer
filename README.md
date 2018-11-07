# WS Timer

The projects purpose is to play around with build tools
and deployment methods available for PHP projects.

The service exposed is very simple and servers just as a demonstration.
It creates a WebSocket server and sends a timestamp every second to connected clients.

## Building the project

A Phar build is supported via [box project](https://github.com/box-project/box2).
It's included as a composer dev dependency. There is also a Dockerfile provided.
It's setup to create a simple image with the Phar embedded.

### Building the Phar

To build the projects Phar file just open a terminal in the projects main directory,
then run:
```bash
composer install
php -dphar.readonly=0 vendor/bin/box build
```

The Phar should be located in the `build` directory. You can alter the servers settings
by setting the following environment variables:
- `APP_DEBUG` changes the logging output level. Example values `0`, `1`. Defaults to `0`.
- `APP_HOST` changes the listening host / IP. Defaults to `0.0.0.0`.
- `APP_PORT` changes the listening port. Defaults to `8080`

### Building the docker image

After creating the Phar you can embed it into a docker image. To do so run:
```bash
docker build -t wc-timer:latest ./
```

Then you can run the image by typing:
```bash
docker run --rm -p8080:8080 -ti wc-timer:latest
```

If you'd like to change the logging verbosity set the debug environment variable to true:
```bash
docker run --rm -p8080:8080 -eAPP_DEBUG=1 -ti wc-timer:latest
```

### TODO
Automated `box` + `docker` build.
