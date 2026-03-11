    <script>
        // Magnetic button effects (Senior Refactor: Simplified & Global Selectors)
        document.querySelectorAll('.action-btn-primary, .action-btn-outline, .view-btn').forEach(btn => {
            btn.addEventListener('mousemove', function(e) {
                const rect = this.getBoundingClientRect();
                const x = (e.clientX - rect.left) - (rect.width / 2);
                const y = (e.clientY - rect.top) - (rect.height / 2);
                this.style.transform = `translate(${x * 0.1}px, ${y * 0.1}px)`;
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translate(0px, 0px)';
            });
        });
    </script>
</body>
</html>
