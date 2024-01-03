/usr/bin/php /home/u631657739/public_html/contas/artisan cache:clear >> /home/u631657739/public_html/contas/storage/logs/jobs.log 2>&1
/usr/bin/php /home/u631657739/public_html/contas/artisan config:clear >> /home/u631657739/public_html/contas/storage/logs/jobs.log 2>&1
/usr/bin/php /home/u631657739/public_html/contas/artisan view:clear >> /home/u631657739/public_html/contas/storage/logs/jobs.log 2>&1
/usr/bin/php /home/u631657739/public_html/contas/artisan optimize:clear >> /home/u631657739/public_html/contas/storage/logs/jobs.log 2>&1
/usr/bin/php /home/u631657739/public_html/contas/artisan queue:restart >> /home/u631657739/public_html/contas/storage/logs/jobs.log 2>&1
nohup /usr/bin/php /home/u631657739/public_html/contas/artisan queue:work --daemon >> /home/u631657739/public_html/contas/storage/logs/jobs.log 2>&1 &