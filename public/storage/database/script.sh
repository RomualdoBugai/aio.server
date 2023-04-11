#!/bin/bash
pg_dump -d teste -h 127.0.0.1 -p 5432 -U postgres -w -f /var/www/html/aio.git/public/storage/database/201703271544.backup