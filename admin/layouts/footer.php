</div>
</div>

<script src="<?php echo ADMIN_URL; ?>/dist/js/scripts.js"></script>
<script src="<?php echo ADMIN_URL; ?>/dist/js/custom.js"></script>
<script>
    $(document).ready(function () {
        // Force reinitialize dropdown
        $('.dropdown-toggle').off('click.bs.dropdown');
        $('.dropdown-toggle').dropdown();

        // Manual click handler as backup
        $('.dropdown-toggle').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $menu = $(this).next('.dropdown-menu');
            var isOpen = $menu.hasClass('show');

            // Close all dropdowns first
            $('.dropdown-menu').removeClass('show');

            // Toggle current dropdown
            if (!isOpen) {
                $menu.addClass('show');
            }
        });

        // Close dropdown when clicking outside
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').removeClass('show');
            }
        });
    });
</script>

</body>

</html>