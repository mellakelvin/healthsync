$(document).ready(function () {
  const loading = $('.loading-content');
  loading.removeClass('loading-strip');

  $('.custom-nav-link').on('click', function () {
    const target = $(this).data('target');
    const path = `./users/dentist/${target}.php`;
    loading.addClass('loading-strip');

    $('#main-container').load(path, function (response, status, xhr) {
      if (status === 'error') {
        $('#main-container').html(
          `<div class="p-4 text-danger">Error loading content: ${xhr.status} ${xhr.statusText}</div>`
        );
      }
      loading.removeClass('loading-strip');
    });

    $('.custom-nav-link').removeClass('active custom-active');
    $(this).addClass('active custom-active');
  });

  $('.custom-nav-link[data-target="dashboard"]').trigger('click');
});
