<div id="date" class="date"></div>
        <div id="time" class="time"></div>

        <script>
            function updateClock() {
                const now = new Date();

                // 日付
                const year = now.getFullYear();
                const month = now.getMonth() + 1;
                const day = now.getDate();

                // 曜日
                const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
                const weekday = weekdays[now.getDay()];

                // 時刻
                const hour = String(now.getHours()).padStart(2, '0');
                const minute = String(now.getMinutes()).padStart(2, '0');

                // 表示
                document.getElementById('date').textContent =
                    `${year}年${month}月${day}日（${weekday}）`;

                document.getElementById('time').textContent =
                    `${hour}:${minute}`;
            }

            setInterval(updateClock, 1000);
            updateClock();
        </script>