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
    </script>
</body>
</html>