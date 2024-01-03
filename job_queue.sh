log_file="/home/u631657739/public_html/contas/storage/logs/jobs.log"
max_size_kb=1024

file_size_kb=$(du -k "$log_file" | cut -f1)

if [ "$file_size_kb" -gt "$max_size_kb" ]; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] job_queue.sh: Limpando o arquivo de log (tamanho excedeu 1 MB)..." > "$log_file"
fi

if pgrep -f "php /home/u631657739/public_html/contas/artisan queue:work --daemon" >/dev/null; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] job_queue.sh: Worker ok" >> "$log_file" 2>&1
else
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] job_queue.sh: Reiniciando o worker.." >> "$log_file" 2>&1
    /usr/bin/php /home/u631657739/public_html/contas/artisan cache:clear >> "$log_file" 2>&1
    /usr/bin/php /home/u631657739/public_html/contas/artisan queue:restart >> "$log_file" 2>&1
    nohup /usr/bin/php /home/u631657739/public_html/contas/artisan queue:work --daemon >> "$log_file" 2>&1 &
fi