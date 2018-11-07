FROM php:7.2

LABEL maintainer="grozycki@op.pl"

ENV APP_DEBUG=${DEBUG:-0}
ENV APP_HOST=${HOST:-0.0.0.0}
ENV APP_PORT=${PORT:-8080}

COPY build/ws-timer.phar /usr/local/bin/ws-timer

EXPOSE "${APP_PORT}/tcp"

CMD ["ws-timer", "", ""]
