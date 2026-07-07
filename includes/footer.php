<?php
// includes/footer.php
?>
            </div> <!-- end content -->
        </div> <!-- end main-content -->

        <footer class="footer">
            <span>SIAKAD SMA PGRI 4 Jakarta</span>
            <span id="footerClock"></span>
        </footer>
    </div>

    <script src="../assets/js/script.js"></script>
    <script>
        // Clock
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const clock = document.getElementById('clock');
            if (clock) clock.textContent = time;
            const footerClock = document.getElementById('footerClock');
            if (footerClock) footerClock.textContent = now.toLocaleDateString('id-ID') + ' ' + time;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const titleGroup = document.querySelector('.navbar-title-group');

        if (sidebarToggle && sidebar) {
            // Pulihkan status terakhir dari localStorage
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('collapsed');
                if (titleGroup) titleGroup.classList.add('collapsed');
            }

            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                if (titleGroup) {
                    titleGroup.classList.toggle('collapsed');
                }
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
        }
    </script>
</body>
</html>