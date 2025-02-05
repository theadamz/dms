import {
    axiosCustom,
    formatBytes,
    loadingProcess,
    MsgBox,
    refactorErrorMessages
} from "./general.js";

export async function clearCache() {
    const confirmation = await MsgBox.Confirm('clear cache?').catch(err => {
        if (err) console.log(err)
    });
    if (!confirmation) return;
    loadingProcess(true, 'Please wait', 'Clearing Cache...');

    // get data
    const response = await axiosCustom(`${_baseURL}/cache-clear`, 'GET', null);

    // if status not OK
    if (![200].includes(response.status)) {
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`);
        return;
    }

    Toast.fire({
        icon: 'success',
        title: response.statusText,
        text: response.data.message
    });
}

export async function getOptionsAjax(url, notExpectedStatusCodes = [200]) {
    // get data
    const response = await axiosCustom(url, 'GET', null);

    // if status not expected
    if (!notExpectedStatusCodes.includes(response.status)) {
        return null;
    }

    return response.data;
}

export async function fillOptionsFromAjax(elementId, url, selectedId = null, selectedIndex = null, valueArr = ['id', 'name'], message = 'Retriving data...') {
    // disable element
    $(elementId).prop('disabled', true);

    // check if element has class form-select2
    if ($(elementId).hasClass('form-select2')) {
        $(elementId).next().after(`<small class="text-muted text-status">${message}</small>`);
    } else {
        // append element for indicator
        $(elementId).after(`<small class="text-muted text-status">${message}</small>`);
    }

    // clear options
    $(elementId).val(null).empty().trigger('change');

    // get options
    const response = await getOptionsAjax(url);

    // if null
    if (!response) {
        $(elementId).val(null).empty().trigger('change');
        $(elementId).prop('disabled', false);
        $(elementId).siblings('.text-status').remove();
        return;
    }

    $(elementId).append(new Option("", "", true, true));
    for (const opt of response.data) {
        $(elementId).append(new Option(opt[valueArr[1]], opt[valueArr[0]], false, selectedId == opt[valueArr[0]]));
    }
    $(elementId).prop('disabled', false);
    $(elementId).siblings('.text-status').remove();
    $(elementId).trigger('change');

    if (selectedIndex) {
        $(elementId).prop('selectedIndex', selectedIndex).trigger('change');
    }
}

export const select2TemplateOptions = {
    defaultResult: (item) => {
        if (item.id && item.text) {
            return item.text;
        }

        if (!item.id) {
            return item.text;
        }

        return `${item.name}`;
    },
    defaultSelection: (item) => {
        if (item.id && item.text) {
            return item.text;
        }

        if (!item.id) {
            return item.text;
        }

        return `${item.name}`;
    },
    userResult: (item) => {
        // still loading
        if (!item.id) {
            return item.text;
        }

        return `<div class="d-flex flex-column">
                    <span class="font-weight-bold">${item.name}</span>
                    <span class="font-weight-light">${item.email}</span>
                    <span class="font-weight-light">${item.department.name}</span>
                </div>`
    },
    userSelection: (item) => {
        if (item.id && item.text) {
            return item.text;
        }

        if (!item.id) {
            return item.text;
        }

        return `${item.name}`;
    },
    userSelectionNameEmail: (item) => {
        if (item.id && item.text) {
            return item.text;
        }

        if (!item.id) {
            return item.text;
        }

        return `<div class="d-flex">
                    <span class="font-weight-bold mr-1${item.email == item.username ? ' d-none' : ''}">${item.username} | </span>
                    <span class="font-weight-bold mr-1">${item.name}</span>
                    <span class="font-weight-light">(${item.email})</span>
                </div>`;
    },
}

export const components = {
    cardDocumentFile: (item) => {
        return `<div class="d-flex flex-grow-1 justify-content-between align-items-start">
                    <div>
                        <span class="text-muted font-weight-normal">${item.file_name}</span>
                        <div class="d-flex text-muted mt-1">
                            <span class="badge badge-primary mr-2">${item.file_type}</span>
                            <span class="badge badge-info">${formatBytes(item.file_size, 1)}</span>
                        </div>
                    </div>
                    <button type="button" class="btn p-0 document-file-remove" title="Remove ${item.file_name}" data-id="${item.id ?? ''}" data-name="${item.file_name}">
                        <i class="fas fa-times text-danger"></i>
                    </button>
                </div>`;
    }
};
