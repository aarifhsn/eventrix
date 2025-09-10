<?php
// messages.php - Bootstrap 4 Compatible Version

function displayMessages()
{
    $output = '';

    // Success messages
    if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
        $output .= '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        $output .= '<strong>Success!</strong> ' . htmlspecialchars($_SESSION['success_message']);
        $output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $output .= '<span aria-hidden="true">&times;</span>';
        $output .= '</button>';
        $output .= '</div>';
        unset($_SESSION['success_message']);
    }

    // Error messages
    if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
        $output .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        $output .= '<strong>Error!</strong> ' . htmlspecialchars($_SESSION['error_message']);
        $output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $output .= '<span aria-hidden="true">&times;</span>';
        $output .= '</button>';
        $output .= '</div>';
        unset($_SESSION['error_message']);
    }

    // Info messages
    if (isset($_SESSION['info_message']) && !empty($_SESSION['info_message'])) {
        $output .= '<div class="alert alert-info alert-dismissible fade show" role="alert">';
        $output .= '<strong>Info!</strong> ' . htmlspecialchars($_SESSION['info_message']);
        $output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $output .= '<span aria-hidden="true">&times;</span>';
        $output .= '</button>';
        $output .= '</div>';
        unset($_SESSION['info_message']);
    }

    // Warning messages
    if (isset($_SESSION['warning_message']) && !empty($_SESSION['warning_message'])) {
        $output .= '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
        $output .= '<strong>Warning!</strong> ' . htmlspecialchars($_SESSION['warning_message']);
        $output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $output .= '<span aria-hidden="true">&times;</span>';
        $output .= '</button>';
        $output .= '</div>';
        unset($_SESSION['warning_message']);
    }

    return $output;
}

function getMessageScript()
{
    return '
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll(".alert:not(.alert-permanent)");
            alerts.forEach(function(alert) {
                if (typeof $ !== "undefined" && $.fn.alert) {
                    // jQuery Bootstrap method
                    $(alert).alert("close");
                } else {
                    // Vanilla JavaScript fallback
                    alert.style.transition = "opacity 0.5s";
                    alert.style.opacity = "0";
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500);
                }
            });
        }, 5000);
    });
    </script>
    ';
}
?>