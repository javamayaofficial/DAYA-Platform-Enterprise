document.addEventListener('DOMContentLoaded', function () {
  const alerts = document.querySelectorAll('.alert[data-autohide="true"]');
  alerts.forEach(function (alert) {
    window.setTimeout(function () {
      alert.remove();
    }, 4000);
  });
});
