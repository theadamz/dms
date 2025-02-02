import {
    clearCache
} from './application';
import './bootstrap';
import {
    initSelect2,
    initThemeMode,
    isFloat,
    isInt,
    unFormatNumber
} from './general';

// regex
window._regExpForCode = /^[A-Za-z0-9-_+=^]*$/;
window._regExpForUsername = /^[A-Za-z0-9-+=^@.]*$/;
window._regExpForTransType = /^[A-Za-z_{}#+=/-]*$/;

// Public variabels
window._baseURL = document.querySelector('meta[name="_baseURL"]').getAttribute('content');
window._csrf = document.querySelector('meta[name="_csrf-token"]').getAttribute('content');
window._id = document.querySelector('meta[name="_id"]').getAttribute('content');
window._locale_short = document.querySelector('meta[name="_locale_short"]').getAttribute('content');
window._locale = document.querySelector('meta[name="_locale"]').getAttribute('content');
window._action = document.querySelector('meta[name="_action"]') ? document.querySelector('meta[name="_action"]').getAttribute('content') : "create";
window._permissions = typeof __permissions === "undefined" || !__permissions ? null : (typeof __permissions === "object" ? __permissions : JSON.parse(__permissions));

window._formValidation = null;
window._data2Send = null;
window._limit = typeof __limit === "undefined" ? null : __limit;
window._thousandSeparator = typeof __thousandSeparator === "undefined" ? null : __thousandSeparator;
window._decimalSeparator = typeof __decimalSeparator === "undefined" ? null : __decimalSeparator;
window._decimalPoint = typeof __decimalPoint === "undefined" ? null : __decimalPoint;
window._jsDateFormat = typeof __jsDateFormat === "undefined" ? null : __jsDateFormat;
window._jsTimeFormat = typeof __jsDateFormat === "undefined" ? null : __jsTimeFormat;
window._jsDateTimeFormat = typeof __jsDateTimeFormat === "undefined" ? null : __jsDateTimeFormat;
window._jsDateFormatMask = typeof __jsDateFormatMask === "undefined" ? null : __jsDateFormatMask;
window._jsTimeFormatMask = typeof __jsTimeFormatMask === "undefined" ? null : __jsTimeFormatMask;
window._jsDateTimeFormatMask = typeof __jsDateTimeFormatMask === "undefined" ? null : __jsDateTimeFormatMask;
window._timezone = typeof __timezone === "undefined" ? 'UTC' : __timezone;

// Datatables
window._dataTable = null;
window._dataTableResetFilter = false;
window._dataTableSelectColumn = 'id';
window._dataTableSelectedValues = [];
window._dataTableSelectedValuesCountPerPage = [];
window._dataTablePageArr = [];
window._dataTableSelectedValueRow = [];
window._dataTableScrollY = screen.availHeight >= 1040 ? "62vh" : "54vh";
window._dataTableUseState = true;
window._dataTableStateDuration = (60 * 60) * 0.5; // 30 menit

window._globaVar = [];
window.Toast = [];
window._isMobileBrowser = typeof navigator.userAgentData === "undefined" ? false : navigator.userAgentData.mobile;

// Set idler
const urlLogin = [`${_baseURL}/`, `${_baseURL}/sign-in`, `${_baseURL}/login`, `${_baseURL}/sign-out`, `${_baseURL}/logout`];
if (urlLogin.indexOf(window.location.href) < 0 && typeof $(document).idleTimer !== 'undefined') {
    $(document).on("idle.idleTimer", function (event, elem, obj) {
        location.replace(_baseURL + '/sign-out');
    });

    $(document).idleTimer({
        timeout: (60000 * 60) * 2 // 2 hours
    });
}

// Others
if (typeof moment !== 'undefined') {
    moment.locale(_locale_short);
    if (moment.tz) {
        moment.tz.setDefault(_timezone);
    }
    window._jsDateStart = moment().subtract(29, 'days');
    window._jsDateEnd = moment();
}

// validation
// override plugin defaults
if (typeof $.validator !== 'undefined') {
    $.validator.setDefaults({
        ignore: ".ignore",
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.fv-row').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.fv-row').find('.form-control').removeClass(validClass).addClass(errorClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).closest('.fv-row').find('.form-control').removeClass(errorClass).addClass(validClass);
        },
    });

    $.validator.addMethod("regex", function (value, element, regexp) {
        const re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    }, "This field is invalid.");

    $.validator.addMethod("dateValid", function (value, element, param) {
        return moment(value, _jsDateFormat, true).isValid();
    }, "This field is invalid.");

    $.validator.addMethod("dateTimeValid", function (value, element, param) {
        return moment(value, __jsDateTimeFormat, true).isValid();
    }, "This field is invalid.");

    $.validator.addMethod("timeValid", function (value, element, param) {
        return moment(value, _jsTimeFormat, true).isValid();
    }, "This field is invalid.");

    $.validator.addMethod("filesize", function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param * 1000)
    }, "File size must be less than {0} KB.");

    $.validator.addMethod("formatNumber", function (value, element, param) {
        const check = value.split('').every((char) => {
            return (char >= '0' && char <= '9') || char == '.' || char == ',';
        });

        if (!check) return false;
        return isFloat(unFormatNumber(value)) || isInt(unFormatNumber(value));
    }, "Please enter a valid number.");

}

// bootstrap file input
if (typeof bsCustomFileInput !== 'undefined') {
    bsCustomFileInput.init();
}

// check if element has form-select2 class
if (document.getElementsByClassName('form-select2').length > 0) {
    const elements = document.getElementsByClassName('form-select2');
    for (const item of elements) {
        initSelect2(`[name="${item.getAttribute('name')}"]`);
    }
}

// theme mode
initThemeMode();

// toast
if (typeof Swal !== 'undefined') {
    Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        showCloseButton: true,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
}

// clear cache
if (document.getElementById('clearCache')) {
    document.getElementById('clearCache').addEventListener('click', clearCache)
}

// tooltip
$('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover',
});

// accounting
if (typeof accounting !== "undefined") {
    accounting.settings = {
        currency: {
            format: "%s %v", // controls output: %s = symbol, %v = value/number (can be object: see below)
            thousand: _thousandSeparator, // thousands separator
            decimal: _decimalSeparator, // decimal point separator
            precision: _decimalPoint // decimal places
        },
        number: {
            thousand: _thousandSeparator,
            decimal: _decimalSeparator,
            precision: _decimalPoint // default precision on numbers is 0
        }
    }
}
