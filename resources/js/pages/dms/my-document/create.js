import {
    components,
    fillOptionsFromAjax,
    getOptionsAjax,
    select2TemplateOptions
} from "../../../application";
import {
    axiosCustom,
    cfTnyMCE,
    dateFormat,
    formValidationSetErrorMessages,
    initselect2AjaxCustomOption,
    initTempusDateTimePicker,
    MsgBox,
    refactorErrorMessages,
    setContentTinyeMCE,
    setTempusDateTimePicker,
    showBlockUIElement,
    showProgressButton
} from '../../../general';

let _formValidationUser = null;
let _formValidationApprovalSet = null;
let documentFiles = [];
let approvalUsers = [];

function initFormValidation() {
    _formValidation = $(document.querySelector('#formInput')).validate({
        rules: {
            due_date: {
                required: $('#use_due_date').is(':checked'),
            },
            approval_workflow_type: {
                required: true,
            },
            category: {
                required: true,
            },
            category_sub: {
                required: true,
            },
            req_review: {
                required: true,
            },
            req_acknowledgement: {
                required: true,
            },
            is_locked: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) saveData();
        }
    });

    _formValidationUser = $(document.querySelector('#formUser')).validate({
        rules: {
            users: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) approvalWorkflowAdd($('#users').select2('data'));
        }
    });

    _formValidationApprovalSet = $(document.querySelector('#formApprovalSet')).validate({
        rules: {
            approval_set: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) approvalWorkflowUsersSet($('#approval_set').select2('data')[0]);
        }
    });
}

function initOtherElements() {
    // formInput
    initTempusDateTimePicker('#due_date', 'date');
    cfTnyMCE('#notes', 'notes', 200);

    // general information -- events
    $('#use_due_date').on('change', function () {
        if ($(this).is(':checked')) {
            $("[name='due_date']").prop('disabled', false)
        } else {
            $("[name='due_date']").prop('disabled', true)
        }
    });
    $("#category").on("change", function () {
        if ($(this).val()) {
            $("#category_sub").val(null).empty().trigger("change");
            fillOptionsFromAjax("#category_sub", `${_baseURL}/options/basics/category-subs?category=${$(this).val()}`);
        }
    });
    $('#category_sub').on('select2:clear', function (e) {
        $('#category_sub option[value=""]').prop('selected', true).change();
        $("#category_sub").val(null).empty().trigger("change");
    });

    // document files -- events
    document.getElementById('addFiles').addEventListener('click', function () {
        $('#files').click();
    });
    document.getElementById('files').addEventListener('change', function (e) {
        documentFilesNew(this.files);
    });
    document.getElementById('clear').addEventListener('click', formInputClear);

    // approval workflow -- events
    $('#modalFormUser').on('hidden.bs.modal', formUserClear);
    initselect2AjaxCustomOption("#users", "Select", `${_baseURL}/options/configs/users`, 2, select2TemplateOptions.userResult, select2TemplateOptions.userSelection, false, true, true, _limit, false);
    $('#modalFormApprovalSet').on('hidden.bs.modal', formApprovalSetClear);
    initselect2AjaxCustomOption("#approval_set", "Select", `${_baseURL}/options/basics/approval-sets`, 2, select2TemplateOptions.defaultResult, select2TemplateOptions.defaultSelection);
    $('#approvalWorkflowContainer').sortable({
        handle: '.approval-workflow-user-handle-order',
        invertSwap: true,
        group: 'list',
        animation: 200,
        ghostClass: 'ghost',
        onSort: approvalWorkflowRender,
    });
}

function initActions() {
    documentFilesRender();
    approvalWorkflowRender();
}

async function saveData() {
    // check files
    if (documentFiles.length <= 0) {
        MsgBox.HtmlNotification('Files empty');
        return;
    }

    // check approval
    if (approvalUsers.length <= 0) {
        MsgBox.HtmlNotification('Approval users empty');
        return;
    }

    const confirmation = await MsgBox.Confirm('Are you sure?').catch(err => {
        if (err) console.log(err)
    });
    if (!confirmation) return;

    // show progress
    showProgressButton(true, '#save');
    showBlockUIElement('#formInput');

    // prepare data
    _data2Send = new FormData($('#formInput')[0]);
    _data2Send.append("due_date", dateFormat($('#due_date').datetimepicker('viewDate'), 'YYYY-MM-DD'));
    _data2Send.append('approval_users', JSON.stringify(approvalUsers));
    documentFiles.forEach(file => {
        _data2Send.append('files[]', file.file_uri);
    });

    // send request
    const response = await axiosCustom($('#formInput').attr('action'), "POST", _data2Send, null);

    // if response status not 201 or 200
    if (![201, 200].includes(response.status)) {
        // show error
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)
        formValidationSetErrorMessages(response.data.errors);

        // hide progress
        showProgressButton(false, '#save');
        showBlockUIElement('#formInput', false);
        return;
    }

    const confirmationCreateNew = await MsgBox.ConfirmHtml(`${response.data.message}<br/><br/>Create new?`).catch(err => {
        if (err) console.log(err)
    });
    if (confirmationCreateNew) {
        $('#clear').click();
    } else {
        window.location.href = cancelURL;
    }

    // reset form
    formInputClear();

    // hide progress
    showProgressButton(false, '#save');
    showBlockUIElement('#formInput', false);
}

// document files ===============

function documentFilesNew(files) {
    if (files.length <= 0) return;

    for (const file of files) {
        // check for duplicate
        if (documentFiles.find(d => d.file_name === file.name)) return;

        documentFiles.push({
            file_name: file.name,
            file_size: file.size,
            file_type: file.type,
            file_uri: file
        });
    }

    // clear file
    $('#files').val('');

    documentFilesRender();
}

function documentFilesRender() {
    // clear events
    $('.document-file-remove').off('click');

    if (documentFiles.length <= 0) {
        $('#documentFilesContainer').html(`<div class="d-flex flex-grow-1 justify-content-center align-items-center">No document file found</div>`);
        return;
    }

    let html = '';
    documentFiles.forEach((file) => {
        html += components.cardDocumentFile(file);
        html += `<div class="mb-3"></div>`
    });

    $('#documentFilesContainer').html(html);

    // init events
    $('.document-file-remove').on('click', documentFilesRemove);
}

function documentFilesRemove() {
    const fileName = $(this).data('name');

    const data = documentFiles.find(item => item.file_name == fileName);
    if (!data) return;

    // filter data to remove
    documentFiles = documentFiles.filter(item => item.file_name != fileName);

    documentFilesRender();
}

// ./ document files ===============

// approval workflow ===============

function approvalWorkflowAdd(selectedUsers) {
    if (!selectedUsers) return;

    showProgressButton(true, '#addUser');
    showBlockUIElement('#formUser');

    selectedUsers.forEach(user => {
        // check if user already exist
        if (approvalUsers.find(u => u.id === user.id)) return;

        // add user
        approvalUsers.push({
            id: user.id,
            name: user.name,
            email: user.email,
            order: approvalUsers.length + 1
        })
    });

    showProgressButton(false, '#addUser');
    showBlockUIElement('#formUser', false);

    $('#modalFormUser').modal('hide');
    approvalWorkflowRender();
}

function approvalWorkflowRender() {
    // clear events
    $('.approval-workflow-user-remove').off('click');

    if (approvalUsers.length <= 0) {
        $('#approvalWorkflowContainer').html(`<div class="d-flex flex-grow-1 justify-content-center align-items-center">No workflow found</div>`);
        return;
    }

    // reordering
    approvalWorkflowReorder();

    let html = '';
    approvalUsers.forEach((user, index) => {
        html += `<div class="list-group-item list-group-item-action approval-workflow-user-handle-order" data-id="${user.id}">
                    <div class="d-flex align-items-center">
                        <h4 class="mr-3">#${user.order}</h4>
                        <div class="d-flex flex-grow-1 justify-content-between">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold mb-1">${user.name}</span>
                                <span class="mb-1">${user.email}</span>
                            </div>
                            <button type="button" class="btn btn-sm text-danger approval-workflow-user-remove" data-id="${user.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
    });

    $('#approvalWorkflowContainer').html(html);

    // init events
    $('.approval-workflow-user-remove').on('click', approvalWorkflowRemove);
}

function approvalWorkflowRemove() {
    const id = $(this).data('id');
    const data = approvalUsers.find(item => item.id == id);
    if (!data) return;

    // filter data to remove
    approvalUsers = approvalUsers.filter(item => item.id != id);

    approvalWorkflowRender();
}

function approvalWorkflowReorder() {
    // get orders from tr data-id
    const orderIds = $('#approvalWorkflowContainer').sortable('toArray');

    let order = 1;
    orderIds.forEach((id, index) => {
        // get which index is data stored
        const dataIndex = approvalUsers.findIndex(item => item.id == id);

        // if not found
        if (dataIndex < 0) return;

        // reassign order
        approvalUsers[dataIndex].order = order++;
    });

    // sort
    approvalUsers.sort((a, b) => a.order - b.order);
}

async function approvalWorkflowUsersSet(approvalSet) {
    if (!approvalSet) return;

    if (approvalUsers.length > 0) {
        const confirmation = await MsgBox.ConfirmTripleButtons('What do you want to do?', 'Workflow not empty', 'question', ['Replace', 'Append', 'Cancel']).catch(err => {
            if (err) console.log(err)
        });
        if (confirmation.isDismissed) return;
        if (confirmation.isConfirmed) {
            approvalUsers = [];
        }
    }

    showProgressButton(true, '#addApprovalSetUsers');
    showBlockUIElement('#formApprovalSet');

    // get data
    const response = await getOptionsAjax(`${_baseURL}/options/basics/approval-sets/${approvalSet.id}`);

    showProgressButton(false, '#addApprovalSetUsers');
    showBlockUIElement('#formApprovalSet', false);
    $('#modalFormApprovalSet').modal('hide');

    // if response null
    if (!response) return;

    // get data options
    const options = response.data;

    approvalWorkflowAdd(options);
}

// ./ approval workflow ===============

function formInputClear() {
    _data2Send = null;
    _formValidation.resetForm();
    $('#formInput')[0].reset();
    $('#date').val(dateFormat(moment()));
    $('#use_due_date').prop('checked', false);
    $("[name='due_date']").prop('disabled', true)
    setTempusDateTimePicker('#due_date', moment());
    $('#approval_workflow_type').prop('selectedIndex', 0).trigger('change');
    $('#category').prop('selectedIndex', 0).trigger('change');
    $('#category_sub').val(null).empty().trigger('change');
    $('#req_review').prop('selectedIndex', 0).trigger('change');
    $('#req_acknowledgement').prop('selectedIndex', 0).trigger('change');
    setContentTinyeMCE('notes', '<p></p>');
    $('#is_locked').prop('selectedIndex', 0).trigger('change');
    documentFiles = [];
    documentFilesRender();
    approvalUsers = [];
    approvalWorkflowRender();
}

function formUserClear() {
    _formValidationUser.resetForm();
    $('#formUser')[0].reset();
    $('#users').val(null).empty().trigger('change');
}

function formApprovalSetClear() {
    _formValidationApprovalSet.resetForm();
    $('#formApprovalSet')[0].reset();
    $('#approval_set').val(null).empty().trigger('change');
}

document.addEventListener("DOMContentLoaded", function () {
    initFormValidation();
    initOtherElements();
    initActions();
});
