// ========================== regex

export const regExpForCode = /^[A-Za-z0-9-+=^]*$/;
export const regExpForUsername = /^[A-Za-z0-9-+=^@.]*$/;
export const regExpForTransType = /^[A-Za-z_{}#+=/-]*$/;
export const regExpForSlug = /^[A-Za-z0-9-]*$/;

// ========================== ./ regex

// ========================== Theme mode

export function initThemeMode() {
    if (!localStorage.getItem('theme-mode')) {
        localStorage.setItem('theme-mode', 'dark-light-mode');
    }

    if (elementIsInPage(document.querySelector('#changeTheme'))) {
        document.querySelector('#changeTheme').addEventListener('click', function () {
            switch (localStorage.getItem('theme-mode')) {
                case "light-mode":
                    localStorage.setItem('theme-mode', 'dark-light-mode');
                    break;

                case "dark-mode":
                    localStorage.setItem('theme-mode', 'light-mode');
                    break;

                default:
                    localStorage.setItem('theme-mode', 'dark-mode');
                    break;
            }

            setThemeMode(localStorage.getItem('theme-mode'));
        });
    }

    setThemeMode(localStorage.getItem('theme-mode'));
}

export function setThemeMode(mode = 'light-mode') {
    if (mode == 'dark-mode') {
        $('body').removeClass('light-mode').addClass('dark-mode');
        $('#changeTheme i').removeClass().addClass('fas fa-sun');
        $('.main-sidebar').removeClass('sidebar-light-primary').addClass('sidebar-dark-primary');
        $('.main-header').removeClass('navbar-white navbar-light').addClass('navbar-dark');
        $('.content-header-fixed nav').removeClass('navbar-white').addClass('navbar-dark');
        $('.user-panel .info').children().removeClass('text-dark').addClass('text-light');
    } else if (mode == 'light-mode') {
        $('body').removeClass('dark-mode').addClass('light-mode');
        $('#changeTheme i').removeClass().addClass('fas fa-adjust');
        $('.main-sidebar').removeClass('sidebar-dark-primary').addClass('sidebar-light-primary');
        $('.main-header').removeClass('navbar-dark').addClass('navbar-white navbar-light');
        $('.content-header-fixed nav').removeClass('navbar-dark').addClass('navbar-white');
        $('.user-panel .info').children().removeClass('text-light').addClass('text-dark');
    } else {
        $('body').removeClass('light-mode').removeClass('dark-mode');
        $('#changeTheme i').removeClass().addClass('fas fa-moon');
        $('.main-sidebar').removeClass('sidebar-light-primary').addClass('sidebar-dark-primary');
        $('.main-header').removeClass('navbar-dark').addClass('navbar-white navbar-light');
        $('.content-header-fixed nav').removeClass('navbar-dark').addClass('navbar-white');
        $('.user-panel .info').children().removeClass('text-dark').addClass('text-light');
    }
}

// ========================== ./ Theme mode

// ========================== Datatables

export const MsgBox = {
    Notification: function (notifMsg = '', notifTitle = "Warning", notifType = 'warning') {
        Swal.fire({
            title: notifTitle,
            text: notifMsg,
            icon: notifType,
            // buttonsStyling: false,
            confirmButtonText: "Close",
            confirmButtonColor: "#3085d6",
            customClass: {
                confirmButton: "font-weight-bold px-6 py-2",
            },
            didOpen: () => {
                // add class
                document.querySelector('.swal2-title').classList.add('h4');
                document.querySelector('.swal2-html-container').classList.add('text-md');
                document.querySelector('.swal2-actions').classList.add('text-md');

                setTimeout(() => {
                    $('.swal2-confirm').focus();
                }, 300);
            },
        });
    },
    HtmlNotification: function (notifMsg = '', notifTitle = "Warning", notifType = 'warning') {
        Swal.fire({
            title: notifTitle,
            html: notifMsg,
            icon: notifType,
            // buttonsStyling: false,
            confirmButtonText: "Close",
            confirmButtonColor: "#3085d6",
            customClass: {
                confirmButton: "font-weight-bold px-6 py-2",
            },
            didOpen: () => {
                // add class
                document.querySelector('.swal2-title').classList.add('h4');
                document.querySelector('.swal2-html-container').classList.add('text-md');
                document.querySelector('.swal2-actions').classList.add('text-md');

                setTimeout(() => {
                    $('.swal2-confirm').focus();
                }, 300);
            },
        });
    },
    Confirm: function (MsgConfirm = '', confirmTitle = 'Confirmation', confirmType = 'question', useCancelButton = true) {
        return new Promise(function (resolve, reject) {
            Swal.fire({
                title: !confirmTitle ? 'Confirmation' : confirmTitle,
                text: MsgConfirm,
                icon: confirmType,
                showCancelButton: useCancelButton,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                customClass: {
                    confirmButton: "font-weight-bold px-6 py-2",
                    cancelButton: "font-weight-bold px-6 py-2"
                },
                didOpen: () => {
                    // add class
                    document.querySelector('.swal2-title').classList.add('h4');
                    document.querySelector('.swal2-html-container').classList.add('text-md');
                    document.querySelector('.swal2-actions').classList.add('text-md');

                    setTimeout(() => {
                        $('.swal2-confirm').focus();
                    }, 300);
                },
            }).then(function (t) {
                if (t.value) {
                    resolve(true);
                } else {
                    reject(false);
                }
            });
        });
    },
    ConfirmHtml: function (MsgConfirm = '', confirmTitle = 'Confirmation', confirmType = 'question', useCancelButton = true) {
        return new Promise(function (resolve, reject) {
            Swal.fire({
                title: !confirmTitle ? 'Confirmation' : confirmTitle,
                html: MsgConfirm,
                icon: confirmType,
                showCancelButton: useCancelButton,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                customClass: {
                    confirmButton: "font-weight-bold px-6 py-2",
                    cancelButton: "font-weight-bold px-6 py-2"
                },
                didOpen: () => {
                    // add class
                    document.querySelector('.swal2-title').classList.add('h4');
                    document.querySelector('.swal2-html-container').classList.add('text-md');
                    document.querySelector('.swal2-actions').classList.add('text-md');
                },
            }).then(function (t) {
                if (t.value) {
                    resolve(true);
                } else {
                    reject(false);
                }
            });
        });
    },
    ConfirmTripleButtons: function (MsgConfirm = '', confirmTitle = 'Confirmation', confirmType = 'question', buttonCaptions = ['Yes', 'No', 'Cancel']) {
        return Swal.fire({
            title: !confirmTitle ? 'Confirmation' : confirmTitle,
            html: MsgConfirm,
            icon: confirmType,
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: buttonCaptions[0],
            denyButtonText: buttonCaptions[1],
            cancelButtonText: buttonCaptions[2],
            confirmButtonColor: "#3085d6",
            denyButtonColor: "#ffc107",
            cancelButtonColor: "#d33",
            customClass: {
                confirmButton: "font-weight-bold px-6 py-2",
                cancelButton: "font-weight-bold px-6 py-2"
            },
            didOpen: () => {
                // add class
                document.querySelector('.swal2-title').classList.add('h4');
                document.querySelector('.swal2-html-container').classList.add('text-md');
                document.querySelector('.swal2-actions').classList.add('text-md');
            },
        }).then(function (t) {
            // return object t = { "isConfirmed": false, "isDenied": false, "isDismissed": true, "dismiss": "cancel" }
            return t;
        });
    },
};

export function initDataTableSearch(dataTable = null, searchElement = null) {
    if (!searchElement) return;
    $(searchElement).off('keyup.DT input.DT');

    let searchDelay = null;
    let prevSearch = null;
    let newSearch = null;

    $(searchElement).on('keyup search', function (e) {
        // Block search if it's not appropiate keyword
        /* if (((e.keyCode >= 0 && e.keyCode <= 47) ||
            (e.keyCode >= 91 && e.keyCode <= 95) ||
            (e.keyCode >= 112 && e.keyCode <= 151) ||
            (e.keyCode >= 166 && e.keyCode <= 169) ||
            (e.keyCode >= 172 && e.keyCode <= 183) ||
            (e.keyCode >= 224 && e.keyCode <= 255)) &&
            (e.keyCode != 8 || e.keyCode != 46)
           )
        {
           return;
        } */

        newSearch = $(this).val();

        clearTimeout(searchDelay);

        searchDelay = setTimeout(function () {
            if (newSearch != null && prevSearch != newSearch) {
                prevSearch = newSearch;
                dataTable.search(newSearch).draw();
            }
        }, 500);
    });
}

export function initDataTableSelectedRows(dataTable = null, multipleSelect = true, selectedCol = 'id', bgColor = 'bg-primary') {
    // dataTable.tables().nodes().to$().attr('id')
    const elementId = '#' + dataTable.table().node().id;
    _dataTableSelectedValueRow[elementId] = [];

    $(elementId + ' tbody').on('click', 'tr', function () {
        // Check if for multipleSelect
        if (multipleSelect) {
            $(this).toggleClass(bgColor);
        } else {
            if ($(this).hasClass(bgColor)) {
                $(this).removeClass(bgColor);
            } else {
                dataTable.$('tr.' + bgColor).removeClass(bgColor);
                $(this).addClass(bgColor);
            }
        }

        if (_dataTableSelectedValueRow[elementId].indexOf(dataTable.row(this).data()[selectedCol]) >= 0 && multipleSelect) {
            _dataTableSelectedValueRow[elementId].splice(_dataTableSelectedValueRow[elementId].indexOf(dataTable.row(this).data()[selectedCol]), 1);
        } else {
            if (multipleSelect) {
                _dataTableSelectedValueRow[elementId].push(dataTable.row(this).data()[selectedCol]);
            } else {
                _dataTableSelectedValueRow[elementId] = [];
                _dataTableSelectedValueRow[elementId].push(dataTable.row(this).data()[selectedCol]);
            }
        }
    });
}

export function saveRowDataTable(dtElement = null, data2Save = null, dtAction = 'create', rowIndex = null) {
    if (dtAction == 'edit') {
        dtElement.row(rowIndex).data(data2Save).draw();
        dtElement.rows().invalidate().draw();
    } else {
        dtElement.row.add(data2Save).draw();
    }
}

export function deleteRowDataTable(dtElement = null, rowIndex = null) {
    dtElement.row(rowIndex).remove().draw();
    dtElement.rows().invalidate().draw();
}

export function adjustColumnDataTables(dtElements = []) {
    dtElements.map(element => {
        setTimeout(() => {
            element.columns.adjust();
        }, 300);
    })
}

export function checkDuplicateDataTables(dtElement = null, arrayKeys = [], value2Check = [], dtAction = 'create', rowIndex = null) {
    let result = null;
    let i = 0;
    if (dtAction == 'create') {
        for (let values of dtElement.rows().data().toArray()) {
            i = 0;
            for (let key of arrayKeys) {
                if (values[key] == value2Check[i]) {
                    result = true;
                    break;
                } else {
                    result = false;
                }
                i++;
            } // End 2nd loop
            // Break if result from 2nd loop is true
            if (result) break;
        } // End 1st loop
    } else {
        let data2compare = dtElement.row(rowIndex).data();
        for (let values of dtElement.rows().data().toArray()) {
            i = 0;
            for (let key of arrayKeys) {
                if (values[key] == value2Check[i] && data2compare[key] != value2Check[i]) {
                    result = true;
                    break;
                } else {
                    result = false;
                }
                i++;
            } // End 2nd loop
            // Break if result from 2nd loop is true
            if (result) break;
        } // End 1st loop
    }

    return result;
}

export function initDataTablesCheckBoxes(dtElement) {
    // Off event
    $('.datatable-group-checkable').off('change');
    $('.datatable-checkable').off('change');

    // Check all
    $('.datatable-group-checkable').on('change', function (e) {
        const set = $('.datatable-checkable');
        const checked = $(this).is(':checked');

        // Check all checkbox
        $(set).each(function (index, element) {
            const indexChekBox = $(element).data('index');
            const index3 = _dataTableSelectedValues.indexOf(element.value);
            if (checked) {
                $(this).prop('checked', true);
                $(this).closest('tr').addClass('bg-light');
                if (index3 <= -1) {
                    _dataTableSelectedValues.push(element.value);
                }
            } else {
                $(this).prop('checked', false);
                $(this).closest('tr').removeClass('bg-light');
                if (index3 > -1) {
                    _dataTableSelectedValues.splice(index3, 1);
                }
            }

            // Cek jika sudah ada array key dengan page_x, jika ada maka kurangi denga nilai 1
            if (!_dataTableSelectedValuesCountPerPage.hasOwnProperty(`page_${dtElement.page.info().page}`)) {
                _dataTableSelectedValuesCountPerPage[`page_${dtElement.page.info().page}`] = dtElement.data().count();
            } else {
                _dataTableSelectedValuesCountPerPage[`page_${dtElement.page.info().page}`] = checked ? dtElement.data().count() : 0;
            }
        });
    });

    // Check single
    $('.datatable-checkable').on('change', function (element) {
        $(this).parents('tr').toggleClass('bg-light');
        const indexChekBox = $(this).data('index');

        // Cek jika value ada pada array _dataTableSelectedValues
        const index = _dataTableSelectedValues.indexOf(this.value);
        if (index > -1) {
            // Hapus data
            _dataTableSelectedValues.splice(index, 1);

            // Cek jika sudah ada array key dengan page_x, jika ada maka kurangi denga nilai 1
            if (_dataTableSelectedValuesCountPerPage.indexOf(`page_${dtElement.page.info().page}`) < 0) {
                _dataTableSelectedValuesCountPerPage[`page_${dtElement.page.info().page}`] -= 1;
            }
        } else {
            // Push ke dalam list
            _dataTableSelectedValues.push(this.value);

            // Cek jika sudah ada array key dengan page_x, jika belum maka buat dengan nilai 1. Jika sudah ada tambahkan 1
            if (!_dataTableSelectedValuesCountPerPage.hasOwnProperty(`page_${dtElement.page.info().page}`)) {
                _dataTableSelectedValuesCountPerPage[`page_${dtElement.page.info().page}`] = 1;
            } else {
                _dataTableSelectedValuesCountPerPage[`page_${dtElement.page.info().page}`] += 1;
            }
        }

        // Jika jumlah yang dipilih dengan data datatable maka checklist di atas terchecklist
        if (_dataTableSelectedValues.length > 0 && _dataTableSelectedValuesCountPerPage.hasOwnProperty(`page_${dtElement.page.info().page}`) && _dataTableSelectedValuesCountPerPage[`page_${dtElement.page.info().page}`] === dtElement.data().count() && dtElement.data().count() > 0) {
            $('.datatable-group-checkable').prop('checked', true);
        } else {
            $('.datatable-group-checkable').prop('checked', false);
        }
    });

    if ($('input.datatable-checkable:checked').length === dtElement.data().count() && dtElement.data().count() > 0) {
        $('.datatable-group-checkable').prop('checked', true);
    }
}

export function _datatableClearSelectedValues() {
    _dataTableSelectColumn = 'id';
    _dataTableSelectedValues = [];
    _dataTableSelectedValuesCountPerPage = [];
    _dataTablePageArr = [];
    _dataTableSelectedValueRow = [];
    $('.datatable-checkable').prop('checked', false);
    $('.datatable-group-checkable').prop('checked', false);
}

// ========================== ./ Datatables

export function convertSerializeArrayToJSON(arrData) {
    let result = {};
    arrData.forEach((item, index) => {
        result[item.name] = item.value;
    });

    return result;
}

export function convertApplicationXWwwFormUrlencodedToJson(value, stringify = true) {
    const data = Object.fromEntries(
        value
        .trim()
        .split("&")
        .map((s) => s.split("="))
        .map((pair) => pair.map(decodeURIComponent))
    );

    if (stringify) {
        return JSON.stringify(data, undefined, 2);
    }

    return data;
}

export function isObjectEmpty(obj) {
    for (const prop in obj) {
        if (obj.hasOwnProperty(prop)) {
            return false;
        }
    }

    return JSON.stringify(obj) === JSON.stringify({});
}

export function setCSRF(json, fromDataTable = true, fromDataTableIgnoreDraw = false) {
    if (!json.csrf) return;

    if (fromDataTable) {
        if (!fromDataTableIgnoreDraw) {
            if (json.draw > 1) {
                $('#' + json.csrf.name).val(json.csrf.hash);
            }
        } else {
            $('#' + json.csrf.name).val(json.csrf.hash);
        }
        _csrf = json.csrf.hash;
        document.querySelector(`meta[name="${json.csrf.name}"]`).setAttribute('content', json.csrf.hash);
    } else {
        $('#' + json.name).val(json.hash);
        _csrf = json.hash;
        document.querySelector(`meta[name="${json.name}"]`).setAttribute('content', json.hash);
    }
}

export function showProgressButton(startProcess = true, elementId = null, disabled = true) {
    const btn = document.querySelector(elementId);
    if (startProcess) {
        btn.querySelector('.indicator-label').classList.add('d-none');
        btn.querySelector('.indicator-progress').classList.remove('d-none');
        btn.disabled = disabled;
    } else {
        btn.querySelector('.indicator-label').classList.remove('d-none');
        btn.querySelector('.indicator-progress').classList.add('d-none');
        btn.disabled = false;
    }
}

export function showBlockUIElement(elementTarget = null, show = true, elementOverlay = "loading-overlay", message = 'Please wait...') {
    const element = document.querySelector(elementTarget);
    const html = document.createElement('div');
    html.id = elementOverlay;
    html.style = "z-index: 2; position: absolute; top: 0; bottom: 0; left: 0; right: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; background-color: rgba(0, 0, 0, 0.05);";
    html.innerHTML = `<i class="fas fa fa-sync-alt fa-spin"></i><div class="text-md pt-2">${message}</div>`;

    // if show = true
    if (show) {
        element.appendChild(html);
    } else {
        if (!elementOverlay || elementOverlay == 'loading-overlay') {
            element.querySelector(`#${elementOverlay}`).remove();
        } else {
            element.querySelector(elementOverlay).remove();
        }
    }
}

export function getConfigBlockUIElement(target = null, message = 'Please wait...', overlayColor = null, state = 'primary') {

    return new KTBlockUI(document.querySelector(target), {
        overlayClass: overlayColor,
        message: `<div class="blockui-message">
                  <span class="spinner-border text-${state}"></span>
                  ${message}
               </div>`,
    });
}

export function initMaxLength(element = null, appendToParent = false) {
    $(element).maxlength({
        warningClass: "badge badge-success badge-rounded badge-inline",
        limitReachedClass: "badge badge-danger badge-rounded badge-inline",
        appendToParent: appendToParent
    });
}

export function loadingProcess(showProcess = true, titleMessage = "Please wait", messageHtml = "Processing...", messageTimer = null) {
    if (showProcess) {
        Swal.fire({
            title: titleMessage,
            html: messageHtml,
            timer: messageTimer,
            confirmButtonColor: "#7a6fbe",
            allowOutsideClick: false,
            didOpen: function () {
                // add class
                document.querySelector('.swal2-title').classList.add('h4');
                document.querySelector('.swal2-html-container').classList.add('text-md');
                document.querySelector('.swal2-actions').classList.add('text-md');

                Swal.showLoading();
            },
        });
    } else {
        Swal.close();
    }
}

export function loadingProcessWithTimer(showProcess = true, titleMessage = "Please wait", messageTimer = 2000) {
    if (showProcess) {
        Swal.fire({
            title: titleMessage,
            html: 'Time elapsed: <strong></strong>',
            timer: messageTimer,
            allowOutsideClick: false,
            didOpen: () => {
                // add class
                document.querySelector('.swal2-title').classList.add('h4');
                document.querySelector('.swal2-html-container').classList.add('text-md');
                document.querySelector('.swal2-actions').classList.add('text-md');

                Swal.showLoading();

                timerInterval = setInterval(() => {
                    Swal.increaseTimer(2000);
                    Swal.getHtmlContainer().querySelector('strong').textContent = secondsToPrettyTime((Swal.getTimerLeft() / 1000).toFixed(0));
                }, 1000)
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        });
    } else {
        Swal.close();
    }
}

export function secondsToPrettyTime(seconds = 0) {
    let date = new Date(0);
    date.setSeconds(seconds); // specify value for SECONDS here
    let timeString = date.toISOString().substr(11, 8);
    return timeString;
}

export function axiosCustom(URLAddress = null, method = 'GET', data2Send = null, contentType = 'application/x-www-form-urlencoded', responseType = 'json', timeout = 30000) {

    return new Promise((resolve, reject) => {
        let headers = {};

        if (contentType != null) {
            headers['Content-Type'] = contentType; // application/x-www-form-urlencoded
        }

        axios({
            url: URLAddress,
            method: method,
            headers: headers,
            responseType: responseType,
            timeout: timeout,
            validateStatus: function (status) {
                // return status < 500; // Resolve semua status di bawah 500
                return status; // Resolve semua status di bawah 500
            },
            data: data2Send,
        }).then(response => {
            resolve(response);
        }).catch(error => {
            reject(error);
        });
    });
}

export function initSelect2(element, placeholder = "Select", minimumInputLength = null, allowClear = true, multiple = false, closeOnSelect = true, theme = 'bootstrap4') {
    const dataHideSearch = document.querySelector(element).getAttribute('data-hide-search');
    const dataAllowClear = document.querySelector(element).getAttribute('data-allow-clear');
    const dataDropDownParent = document.querySelector(element).getAttribute('data-dropdown-parent');
    const dataMultiple = document.querySelector(element).getAttribute('multiple');

    $(element).select2({
        dropdownParent: dataDropDownParent ? $(dataDropDownParent) : null,
        theme: theme,
        minimumResultsForSearch: dataHideSearch ? (dataHideSearch == 'true' ? -1 : 0) : 0,
        placeholder: placeholder,
        minimumInputLength: minimumInputLength,
        multiple: dataMultiple ? true : multiple,
        allowClear: dataAllowClear ? (dataAllowClear == 'true' ? true : false) : allowClear,
        closeOnSelect: closeOnSelect,
    });
}

export function initSelect2Ajax(element, placeholder = "", url, valueArr = ['id', 'text'], minimumInputLength = 3, allowClear = true, multiple = false, useCache = true, closeOnSelect = true, theme = 'bootstrap4') {
    const dataDropDownParent = document.querySelector(element).getAttribute('data-dropdown-parent');

    $(element).select2({
        dropdownParent: dataDropDownParent ? $(dataDropDownParent) : null,
        theme: theme,
        placeholder: placeholder,
        minimumInputLength: minimumInputLength,
        multiple: multiple,
        allowClear: allowClear,
        closeOnSelect: closeOnSelect,
        ajax: {
            url: url,
            type: "GET",
            dataType: "json",
            delay: 400,
            data: function (params) {
                return {
                    keyword: params.term
                }
            },
            processResults: function (data) { // parse the results into the format expected by Select2.
                // Render select2
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            id: item[valueArr[0]],
                            text: item[valueArr[1]]
                        }
                    })
                };
            },
            cache: useCache
        }
    });
}

export function initselect2AjaxPaging(element, placeholder = "", url, valueArr = ['id', 'text'], minimumInputLength = 3, allowClear = true, multiple = false, useCache = true, limit = _limit, closeOnSelect = true, theme = 'bootstrap4') {
    const dataDropDownParent = document.querySelector(element).getAttribute('data-dropdown-parent');

    $(element).select2({
        dropdownParent: dataDropDownParent ? $(dataDropDownParent) : null,
        theme: theme,
        placeholder: placeholder,
        minimumInputLength: minimumInputLength,
        multiple: multiple,
        allowClear: allowClear,
        closeOnSelect: closeOnSelect,
        ajax: {
            url: url,
            type: "GET",
            dataType: "json",
            delay: 400,
            data: function (params) {
                return {
                    keyword: params.term,
                    page: !params.page ? 1 : params.page,
                    perPage: limit,
                    pageType: 'offset'
                }
            },
            processResults: function (response, params) {
                params.page = !params.page ? 1 : params.page + 1;
                // Render select2
                return {
                    results: $.map(response.data, function (item) {
                        return {
                            id: item[valueArr[0]],
                            text: item[valueArr[1]]
                        }
                    }),
                    pagination: {
                        more: response.data.length > 0
                    }
                };
            },
            cache: useCache,
        }
    });
}

export function initselect2AjaxPagingByLastId(element, placeholder = "", url, columnId = 'id', valueArr = ['id', 'text'], minimumInputLength = 3, allowClear = true, multiple = false, useCache = true, closeOnSelect = true, theme = 'bootstrap4') {
    const dataDropDownParent = document.querySelector(element).getAttribute('data-dropdown-parent');

    // init _globaVar
    _globaVar[element] = {
        last_id: "",
        keyword: ""
    };

    $(element).select2({
        dropdownParent: dataDropDownParent ? $(dataDropDownParent) : null,
        theme: theme,
        placeholder: placeholder,
        minimumInputLength: minimumInputLength,
        multiple: multiple,
        allowClear: allowClear,
        closeOnSelect: closeOnSelect,
        ajax: {
            url: url,
            type: "GET",
            dataType: "json",
            delay: 500,
            data: function (params) {
                // reset last_id if keyword different
                _globaVar[element]['last_id'] = _globaVar[element]['keyword'] != params.term ? "" : _globaVar[element]['last_id'];
                _globaVar[element]['keyword'] = params.term;

                return {
                    keyword: params.term ? params.term : '',
                    lastId: _globaVar[element]['last_id'],
                    pageType: 'id'
                }
            },
            processResults: function (response, params) {
                // Set _globaVar[element]['last_id']
                if (response.data.length > 0) _globaVar[element]['last_id'] = response.data[response.data.length - 1][columnId];
                // Render select2
                return {
                    results: $.map(response.data, function (item, index) {
                        return {
                            id: item[valueArr[0]],
                            text: item[valueArr[1]]
                        }
                    }),
                    pagination: {
                        more: response.data.length > 0 ? true : false
                    }
                };
            },
            cache: useCache,
        }
    });
    // event
    $(element).on("select2:close", function (e) {
        _globaVar[element]['last_id'] = 0;
        _globaVar[element]['keyword'] = '';
    });
}

export function initselect2AjaxCustomOption(element, placeholder = "", url, minimumInputLength = 3, templateResult = null, templateSelection = null, allowClear = true, multiple = false, useCache = true, limit = _limit, closeOnSelect = true, theme = 'bootstrap4') {
    const dataDropDownParent = document.querySelector(element).getAttribute('data-dropdown-parent');

    $(element).select2({
        dropdownParent: dataDropDownParent ? $(dataDropDownParent) : null,
        theme: theme,
        placeholder: placeholder,
        minimumInputLength: minimumInputLength,
        multiple: multiple,
        allowClear: allowClear,
        closeOnSelect: closeOnSelect,
        ajax: {
            url: url,
            type: "GET",
            dataType: "json",
            delay: 400,
            data: function (params) {
                return {
                    keyword: params.term,
                    page: !params.page ? 1 : params.page,
                    perPage: limit,
                    pageType: 'offset'
                }
            },
            processResults: function (response, params) {
                params.page = !params.page ? 1 : params.page + 1;

                return {
                    results: response.data,
                    pagination: {
                        more: response.data.length > 0
                    }
                };
            },
            cache: useCache,
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        templateSelection: templateSelection ? templateSelection : templateResult,
        templateResult: templateResult
    });
}

export function initDateRangePicker(elementId, type = 'daterange', showDropdowns = true, minDate = null, drops = "auto", _startDate = _jsDateStart, _endDate = _jsDateEnd, autoApply = true, parentElement = 'body') {
    // _jsDateFormat = MMM D, YYYY
    switch (type) {
        case "singledatepicker":
            $(elementId).daterangepicker({
                // autoUpdateInput: false,
                minDate: minDate,
                parentEl: parentElement,
                singleDatePicker: true,
                locale: _locale[0].split('-')[0],
                startDate: moment(),
                showDropdowns: showDropdowns,
                locale: {
                    format: _jsDateFormat
                },
                "autoApply": autoApply,
                "drops": drops
            });
            break;
        case "singledatetimepicker":
            $(elementId).daterangepicker({
                parentEl: parentElement,
                singleDatePicker: true,
                locale: _locale[0].split('-')[0],
                minDate: minDate,
                startDate: moment(),
                showDropdowns: showDropdowns,
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: _jsDateFormat + ' HH:mm'
                }
            });
            break;
        case "datetime":
            $(elementId).daterangepicker({
                locale: _locale[0].split('-')[0],
                parentEl: parentElement,
                minDate: minDate,
                startDate: _startDate,
                endDate: _endDate,
                showDropdowns: showDropdowns,
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: _jsDateFormat + ' HH:mm'
                }
            });
            break;
        case "timeonly":
            $(elementId).daterangepicker({
                parentEl: parentElement,
                singleDatePicker: true,
                locale: _locale[0].split('-')[0],
                // minDate: minDate,
                timePicker: true,
                timePicker24Hour: true,
                locale: {
                    format: 'HH:mm'
                }
            }).on('show.daterangepicker', function (ev, picker) {
                picker.container.find(".calendar-table").hide();
            });
            break;
        default:
            $(elementId).daterangepicker({
                locale: _locale[0].split('-')[0],
                parentEl: parentElement,
                minDate: minDate,
                startDate: _startDate,
                endDate: _endDate,
                showDropdowns: showDropdowns,
                locale: {
                    format: _jsDateFormat
                }
            });
            break;
    }
}

export function setDateRangePickerValue(elementId, startDate = moment(), endDate = moment()) {
    $(elementId).data('daterangepicker').setStartDate(startDate);
    $(elementId).data('daterangepicker').setEndDate(endDate);
}

export function initTempusDateTimePicker(element, type = 'datetime', showTodaybutton = true, showClear = true, viewMode = 'days', startDate = moment(), endDate = moment()) {
    const format = {
        datetime: _jsDateTimeFormat,
        date: _jsDateFormat,
        time: _jsTimeFormat
    }

    $(element).datetimepicker({
        locale: moment.locale(_locale_short),
        viewMode: viewMode,
        format: format[type],
        showTodayButton: showTodaybutton,
        showClear: showClear,
        icons: {
            previous: 'fas fa-arrow-left',
            next: 'fas fa-arrow-right',
            today: type === 'time' ? 'fas fa-clock' : 'fas fa-calendar-day',
            clear: showClear ? 'fas fa-trash' : null,
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
        }
    });
}

export function setTempusDateTimePicker(element, value) {
    $(element).datetimepicker('date', value);
}

export function ucwords(str = '') {
    return str.toLowerCase().replace(/\b[a-z]/g, function (letter) {
        return letter.toUpperCase();
    });
}

export function elementIsInPage(node) {
    return (node === document.body) ? false : document.body.contains(node);
}

export function setElementReadOnly(elementControl = null, readonly = true) {
    if (readonly) {
        $(elementControl).prop('readonly', true);
        $(elementControl).addClass("form-control-solid");
    } else {
        $(elementControl).prop('readonly', false);
        $(elementControl).removeClass("form-control-solid");
    }
}

export function setElementDisabled(elementControl = null, disabled = true) {
    document.querySelector(elementControl).disabled = disabled;
}

export function goToNextElement(fromElement = null, toElement = null, elementType = 'text', onEvent = 'keypress', keyCodes = [13]) {
    if (elementType == 'select2') {
        $(fromElement).on("select2:Select, select2:close", function (e) {
            setTimeout(() => {
                document.querySelector(toElement).focus();
            }, 100);
        });
    } else {
        document.querySelector(fromElement).addEventListener(onEvent, (e) => {
            e.preventDefault();
            if (keyCodes.indexOf(e.keyCode) < 0) return;
            setTimeout(() => {
                document.querySelector(toElement).focus();
            }, 100);
        });
    }
}

export function setFocus(destionationElement = null, timeOut = 300) {
    setTimeout(() => {
        document.querySelector(destionationElement).focus();
    }, timeOut);
}

export function checkEmptyOrNull(arrListElement = null) {
    let result = null;
    for (let elementControl of arrListElement) {
        if (!$(elementControl).val() || $(elementControl).val().trim() == "" || document.querySelector(elementControl).value == null) {
            result = true;
            break;
        } else {
            result = false;
        }
    }

    return result;
}

export function getDaysNameFromObjectDaysNumbers(daysArr) {
    let html = '';
    let days = JSON.parse(daysArr);
    days.map((item, index) => {
        html += index == 0 ? moment(item, "d").format("ddd") : ', ' + moment(item, "d").format("ddd");
    });
    return html;
}

export function nFormatter(num, digits) {
    const lookup = [{
            value: 1,
            symbol: ""
        },
        {
            value: 1e3,
            symbol: "k"
        },
        {
            value: 1e6,
            symbol: "M"
        },
        {
            value: 1e9,
            symbol: "G"
        },
        {
            value: 1e12,
            symbol: "T"
        },
        {
            value: 1e15,
            symbol: "P"
        },
        {
            value: 1e18,
            symbol: "E"
        }
    ];
    const rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
    let item = lookup.slice().reverse().find(function (item) {
        return num >= item.value;
    });
    return item ? (num / item.value).toFixed(digits).replace(rx, "$1") + item.symbol : "0";
}

export function initDragableModal(element) {
    const elmnt = document.querySelector(element);

    var pos1 = 0,
        pos2 = 0,
        pos3 = 0,
        pos4 = 0;
    if (elmnt.querySelector('.modal-header')) {
        // if present, the header is where you move the DIV from:
        elmnt.querySelector('.modal-header').onmousedown = dragMouseDown;
    } else {
        // otherwise, move the DIV from anywhere inside the DIV:
        elmnt.onmousedown = dragMouseDown;
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
    }
}

export function refactorErrorMessages(data) {
    if (typeof data === "undefined") return null;
    if (data === null) return null;
    if (data.length <= 0) return null;

    let messageError = data.message + '<br/>';

    if (typeof data.errors === "undefined" || data.errors === null || data.errors.length <= 0) return messageError;

    for (const [field, messages] of Object.entries(data.errors)) {
        messageError += '<br/>' + messages.join('<br/>');
    }

    return messageError;
}

export function formValidationSetErrorMessages(errors, fv = null) {
    if (typeof errors === "undefined") return;
    if (errors === null) return;
    if (errors.length <= 0) return;

    // jika fv kosong, maka gunakan _formValidation
    if (!fv) {
        fv = _formValidation;
    }

    // loop error
    for (const [field, message] of Object.entries(errors)) {
        // jika element
        if (document.getElementById(field) != null) {
            // show error
            fv.showErrors({
                [field]: message.join('<br/>')
            });
        }

        // hapus object
        delete errors[field];
    }
}

export function initFileUpload(element, targetCaption = '.custom-file-label', labelCaption = 'Select file') {
    $(element).on('change', function () {
        if ($(this).val().trim() != '') {
            const fileName = $(this).val().split('\\').pop();
            // replace the "Choose a file" label
            $(this).next(targetCaption).html(fileName);
        } else {
            $(this).next(targetCaption).html(labelCaption);
        }
    })
}

export function cfTnyMCE(element, idElement, height, elementTinyText, elementTinyVisual) {
    let _height = height;
    let _element = element;

    $(elementTinyText).click(function (e) {
        e.stopPropagation();
        tinymce.EditorManager.execCommand('mceRemoveEditor', true, idElement);
    });

    $(elementTinyVisual).click(function (e) {
        e.stopPropagation();
        tinymce.EditorManager.execCommand('mceAddEditor', true, idElement);
    });

    tinymce.init({
        selector: _element,
        editor_deselector: 'mceNoEditor',
        // skin: 'lightgray',
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor",
            "code fullscreen youtube autoresize codemirror codesample pagebreak"
        ],
        toolbar1: 'undo redo | bold italic underline forecolor backcolor | alignjustify alignleft aligncenter alignright | outdent indent bullist numlist table | pagebreak',
        toolbar2: 'removeformat styleselect | fontsizeselect | image media youtube | hr charmap link unlink  codesample code | visualblocks preview fullscreen',
        branding: false,
        menubar: false,
        relative_urls: false,
        remove_script_host: false,
        image_caption: true,
        image_advtab: true,
        resize: true,
        fontsize_formats: '8px 10px 12px 14px 18px 24px 36px',
        autoresize_min_height: _height,
        autoresize_top_margin: 5,
        autoresize_bottom_margin: 2,
        visualblocks_default_state: true,
        content_css: _baseURL + '/assets/vendor/plugins/tinymce/plugins/bootstrap/css/bootstrap.min.css,' + _baseURL + '/assets/vendor/plugins/fontawesome-free/css/all.min.css',
        codemirror: {
            indentOnInit: true,
            path: _baseURL + '/assets/vendor/plugins/codemirror'
        },
    });
}

export function setContentTinyeMCE(element, content) {
    document.getElementById(element).innerHTML = content;
    tinymce.get(element).setContent(content);
}

export function summernoteInit(element, placeholder = '', height = 150) {
    $(element).summernote({
        toolbar: [
            ['insert', ['picture', 'link', 'table', 'hr']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['misc', ['undo', 'redo', 'fullscreen']]
        ],
        placeholder: placeholder,
        height: height
    });
}

export function summernoteSetCode(element, values) {
    $(element).summernote('code', values);
}

export function initTagInput(element, tagName, displayKey = 'id', valueKey = 'name', source) {
    $(element).tagsinput({
        typeaheadjs: {
            name: tagName, // tagName
            displayKey: displayKey,
            valueKey: valueKey,
            source: source.ttAdapter()
        }
    });
}

export function boolValue(value) {
    if (typeof value === "boolean") {
        return value;
    } else {
        return Boolean(Number(value));
    }
}

export function formatBytes(bytes, decimals) {
    if (bytes == 0) return '0 Bytes';
    var k = 1024,
        dm = decimals || 2,
        sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

export function downloadFile(filePath) {
    const link = document.createElement('a');
    link.href = filePath;
    link.download = filePath.substr(filePath.lastIndexOf('/') + 1);
    link.click();
}

export function dateFormat(date, format = null) {
    return moment(date).format(format ? format : _jsDateFormat);
}

export function timeFormat(date, format = null) {
    return moment(date).format(format ? format : _jsTimeFormat);
}

export function dateTimeFormat(date, format = null) {
    return moment(date).format(format ? format : _jsDateTimeFormat);
}

export function initInputNumber(element, type = 'number') {
    // need to load js assets/js/lib/auto-numeric/auto-numeric.min.js

    switch (type) {
        case "coordinate":
            $(element).autoNumeric('init', {
                aSep: ",",
                aDec: ".",
                lZero: false,
                aPad: false,
                mDec: '15',
                vMin: '-999999999999999',
                vMax: '999999999999999'
            });
            break;

        case "float":
        case "decimal":
            $(element).autoNumeric('init', {
                aSep: _thousandSeparator,
                aDec: _decimalSeparator,
                lZero: false,
                aPad: true,
                mDec: _decimalPoint,
                vMin: '0',
                vMax: '999999999999999'
            });
            break;

        case "integer":
        case "int":
        default:
            $(element).autoNumeric('init', {
                aSep: _thousandSeparator,
                aDec: _decimalSeparator,
                lZero: false,
                aPad: false,
                mDec: '0',
                vMin: '0',
                vMax: '999999999999999'
            });
            break;
    }
}

export function formatNumber(value) {
    return accounting.formatNumber(value);
}

export function unFormatNumber(value) {
    return accounting.unformat(value, _decimalSeparator);
}

export function isInt(n) {
    return Number(n) === n && n % 1 === 0;
}

export function isFloat(n) {
    return Number(n) === n && n % 1 !== 0;
}
