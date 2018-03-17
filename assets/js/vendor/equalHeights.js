$(document).ready(function () {

  $.fn.equalHeights = function () {
    var children = $(this);

    if (!children || children.length == 0) return;

    var elemHeights = Array.from(children).map(function (elem) {
      return elem.offsetHeight;
    });

    var maxHeight = elemHeights.reduce(function (prev, curr) {
      return curr > prev ? curr : prev;
    }, 0);

    Array.from(children).forEach(function (elem) {
      elem.style.height = maxHeight + 'px';
    });
  };

});
