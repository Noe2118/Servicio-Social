</main><!-- /.main-content -->

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mobile sidebar toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Close sidebar when clicking on main content (mobile)
    document.getElementById('mainContent')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.remove('show');
    });
</script>
</body>
</html>
