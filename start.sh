#!/bin/sh
echo "account:" >> /bot/config.yml \
&& echo "  uin: ${UIN}" > /bot/config.yml \
&& echo "  password: '${PASSWD}'" >> /bot/config.yml \
&& echo "  encrypt: false" >> /bot/config.yml \
&& echo "  status: 18" >> /bot/config.yml \
&& echo "  relogin:" >> /bot/config.yml \
&& echo "    delay: 0" >> /bot/config.yml \
&& echo "    interval: 0" >> /bot/config.yml \
&& echo "    max-times: 0" >> /bot/config.yml \
&& echo "  use-sso-address: false" >> /bot/config.yml \
&& echo "heartbeat:" >> /bot/config.yml \
&& echo "  interval: 5" >> /bot/config.yml \
&& echo "message:" >> /bot/config.yml \
&& echo "  post-format: string" >> /bot/config.yml \
&& echo "  ignore-invalid-cqcode: false" >> /bot/config.yml \
&& echo "  force-fragment: false" >> /bot/config.yml \
&& echo "  fix-url: false" >> /bot/config.yml \
&& echo "  proxy-rewrite: ''" >> /bot/config.yml \
&& echo "  report-self-message: false" >> /bot/config.yml \
&& echo "  remove-reply-at: false" >> /bot/config.yml \
&& echo "  extra-reply-data: false" >> /bot/config.yml \
&& echo "  skip-mime-scan: false" >> /bot/config.yml \
&& echo "output:" >> /bot/config.yml \
&& echo "  log-level: warn" >> /bot/config.yml \
&& echo "  log-aging: 1" >> /bot/config.yml \
&& echo "  log-force-new: true" >> /bot/config.yml \
&& echo "  log-colorful: true" >> /bot/config.yml \
&& echo "  debug: false" >> /bot/config.yml \
&& echo "default-middlewares: &default" >> /bot/config.yml \
&& echo "  access-token: ''" >> /bot/config.yml \
&& echo "  filter: '/bot/filter.json'" >> /bot/config.yml \
&& echo "  rate-limit:" >> /bot/config.yml \
&& echo "    enabled: false" >> /bot/config.yml \
&& echo "    frequency: 1" >> /bot/config.yml \
&& echo "    bucket: 1" >> /bot/config.yml \
&& echo "database:" >> /bot/config.yml \
&& echo "  leveldb:" >> /bot/config.yml \
&& echo "    enable: true" >> /bot/config.yml \
&& echo "  cache:" >> /bot/config.yml \
&& echo "    image: data/image.db" >> /bot/config.yml \
&& echo "    video: data/video.db" >> /bot/config.yml \
&& echo "servers:" >> /bot/config.yml \
&& echo "  - http:" >> /bot/config.yml \
&& echo "      host: 127.0.0.1" >> /bot/config.yml \
&& echo "      port: 5700" >> /bot/config.yml \
&& echo "      timeout: 5" >> /bot/config.yml \
&& echo "      long-polling:" >> /bot/config.yml \
&& echo "        enabled: false" >> /bot/config.yml \
&& echo "        max-queue-size: 2000" >> /bot/config.yml \
&& echo "      middlewares:" >> /bot/config.yml \
&& echo "        <<: *default" >> /bot/config.yml \
&& echo "      post:" >> /bot/config.yml \
&& echo "      - url: 'http://localhost:5580'" >> /bot/config.yml \
&& echo "      secret: ''" >> /bot/config.yml
./go-cqhttp &
php -S localhost:5580
