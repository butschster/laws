require('./unify/hs.core');
require('./unify/components/hs.header');
require('./unify/components/hs.dropdown');
require('./unify/helpers/hs.hamburgers');

$(document).on('ready', function () {
    // initialization of HSDropdown component
    $.HSCore.components.HSDropdown.init($('[data-dropdown-target]'), {
        afterOpen: function () {
            $(this).find('input[type="search"]').focus();
        }
    });
});

$(window).on('load', function () {
    // initialization of header
    $.HSCore.components.HSHeader.init($('#js-header'));
    $.HSCore.helpers.HSHamburgers.init('.hamburger');
});