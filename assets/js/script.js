'use strict';

/*
var expandControls = document.querySelectorAll('.expand-control');

var hidePopups = function () {
  [].forEach.call(document.querySelectorAll('.expand-list'), function (item) {
    item.classList.add('hidden');
  });
};

document.body.addEventListener('click', hidePopups, true);

[].forEach.call(expandControls, function (item) {
  item.addEventListener('click', function () {
    item.nextElementSibling.classList.toggle('hidden');
  });
});

document.body.addEventListener('click', function (event) {
  var target = event.target;
  var modal = null;

  if (target.classList.contains('open-modal')) {
    var modal_id = target.getAttribute('target');
    modal = document.getElementById(modal_id);

    if (modal) {
      document.body.classList.add('overlay');
      modal.removeAttribute('hidden');
    }
  }

  if (target.classList.contains('modal__close')) {
    modal = target.parentNode;
    modal.setAttribute('hidden', 'hidden');
    document.body.classList.remove('overlay');
  }
});
*/

(function() {
  var $checkbox = document.getElementsByClassName('show_completed')[0];
  if (!$checkbox) return;

  $checkbox.addEventListener('change', function (event) {
    var is_checked = +event.target.checked;

    var searchParams = new URLSearchParams(window.location.search.slice(1));
    searchParams.set('show_completed', is_checked);

    window.location = '/index.php?' + searchParams;
  });
})();

(function() {
  var $taskCheckboxes = document.getElementsByClassName('tasks')[0];
  if (!$taskCheckboxes) return;

  $taskCheckboxes.addEventListener('change', function (event) {
    if (event.target.classList.contains('task__checkbox')) {
      var el = event.target;

      var is_checked = +el.checked;
      var task_id = el.getAttribute('value');

      var searchParams = new URLSearchParams(window.location.search.slice(1));
      searchParams.set('task_id', task_id);
      searchParams.set('check', is_checked);

      window.location = '/index.php?' + searchParams;
    }
  });
})();

(function() {
  var date_input = document.getElementById('date');
  if (!date_input) return;

  flatpickr(date_input, {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    time_24hr: true,
    locale: "ru"
  });
})();

(function(m, e, t, r, i, k, a) {
  m[i] = m[i] || function() {
    (m[i].a = m[i].a || []).push(arguments)
  };
  m[i].l = 1 * new Date();
  k = e.createElement(t),
  a = e.getElementsByTagName(t)[0],
  k.async = 1,
  k.src = r,
  a.parentNode.insertBefore(k,a)
})(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

ym(51712244, "init", {
  id:51712244,
  clickmap:true,
  trackLinks:true,
  accurateTrackBounce:true,
  webvisor:true
});
