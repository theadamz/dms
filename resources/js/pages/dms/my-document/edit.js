import {
    components,
    fillOptionsFromAjax,
    getOptionsAjax,
    select2TemplateOptions
} from "../../../application";
import {
    axiosCustom,
    dateFormat,
    formValidationSetErrorMessages,
    initMaxLength,
    initselect2AjaxCustomOption,
    initTempusDateTimePicker,
    loadingProcess,
    MsgBox,
    refactorErrorMessages,
    showBlockUIElement,
    showProgressButton
} from '../../../general';

let _formValidationUser = null;
let _formValidationApprovalSet = null;

// for inputs
let workFlowSectionSelected = 'approval';

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
            review_workflow_type: {
                required: isReviewRequired,
            },
            acknowledgement_workflow_type: {
                required: isAcknowledgementRequired,
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

    _formValidationUser = $(document.querySelector('#formWorkflowUser')).validate({
        rules: {
            workflowUsers: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) workflowAddUsers($('#workflowUsers').select2('data'));
        }
    });

    _formValidationApprovalSet = $(document.querySelector('#formWorkflowUserSet')).validate({
        rules: {
            workflowUsersSet: {
                required: true,
            },
        },
        submitHandler: function (form, e) {
            e.preventDefault();

            if ($(form).valid()) workflowAddUsersSet($('#workflowUsersSet').select2('data')[0]);
        }
    });
}

function initOtherElements() {
    // toolbar
    document.getElementById('reload').addEventListener('click', function () {
        loadingProcess();
        window.location.reload();
    });

    // formInput
    initTempusDateTimePicker('#due_date', 'date');

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
        $('#attachments').click();
    });
    document.getElementById('attachments').addEventListener('change', function (e) {
        documentFilesNew(this.files);
    });

    // users and approval set -- events
    $('#modalFormWorkflowUser').on('hidden.bs.modal', formWorkflowUserClear);
    initselect2AjaxCustomOption("#workflowUsers", "Select", `${_baseURL}/options/configs/users`, 2, select2TemplateOptions.userResult, select2TemplateOptions.userSelection, false, true, true, _limit, false);
    $('#modalFormWorkflowUserSet').on('hidden.bs.modal', formWorkflowUserSetClear);
    initselect2AjaxCustomOption("#workflowUsersSet", "Select", `${_baseURL}/options/basics/approval-sets`, 2, select2TemplateOptions.defaultResult, select2TemplateOptions.defaultSelection);

    // approval workflow -- events
    document.getElementById('addApprovalUsers').addEventListener('click', function () {
        workFlowSectionSelected = 'approval'; // set section selected
        $('#modalFormWorkflowUser').modal('show'); // show modal
    });
    document.getElementById('addApprovalUsersSet').addEventListener('click', function () {
        workFlowSectionSelected = 'approval'; // set section selected
        $('#modalFormWorkflowUserSet').modal('show'); // show modal
    });
    $('#approvalWorkflowContainer').sortable({
        handle: '.approval-workflow-user-handle-order',
        invertSwap: true,
        animation: 200,
        ghostClass: 'ghost',
        onSort: approvalWorkflowRender,
    });

    // approval workflow -- events
    document.getElementById('addReviewWorkFlow').addEventListener('click', function () {
        isReviewRequired = true;
        $('#reviewWorkflowCard').removeClass('d-none');
        $('#addReviewWorkFlow').addClass('d-none');
    });
    document.getElementById('removeReviewWorkFlow').addEventListener('click', async function () {
        const confirmation = await MsgBox.Confirm('Are you sure?').catch(err => {
            if (err) console.log(err)
        });
        if (!confirmation) return;

        isReviewRequired = false;
        $('#reviewWorkflowCard').addClass('d-none');
        $('#addReviewWorkFlow').removeClass('d-none');
        reviewUsers = [];
        reviewWorkflowRender();
    });
    document.getElementById('addReviewUsers').addEventListener('click', function () {
        workFlowSectionSelected = 'review'; // set section selected
        $('#modalFormWorkflowUser').modal('show'); // show modal
    });
    document.getElementById('addReviewApprovalSet').addEventListener('click', function () {
        workFlowSectionSelected = 'review'; // set section selected
        $('#modalFormWorkflowUserSet').modal('show'); // show modal
    });
    $('#reviewWorkflowContainer').sortable({
        handle: '.review-workflow-user-handle-order',
        invertSwap: true,
        animation: 200,
        ghostClass: 'ghost',
        onSort: reviewWorkflowRender,
    });

    // acknowledgement workflow -- events
    document.getElementById('addAcknowledgementWorkFlow').addEventListener('click', function () {
        isAcknowledgementRequired = true;
        $('#acknowledgementWorkflowCard').removeClass('d-none');
        $('#addAcknowledgementWorkFlow').addClass('d-none');
    });
    document.getElementById('removeAcknowledgementWorkFlow').addEventListener('click', async function () {
        const confirmation = await MsgBox.Confirm('Are you sure?').catch(err => {
            if (err) console.log(err)
        });
        if (!confirmation) return;

        isAcknowledgementRequired = false;
        $('#acknowledgementWorkflowCard').addClass('d-none');
        $('#addAcknowledgementWorkFlow').removeClass('d-none');
        acknowledgementUsers = [];
        acknowledgementWorkflowRender();
    });
    document.getElementById('addAcknowledgementUsers').addEventListener('click', function () {
        workFlowSectionSelected = 'acknowledgement'; // set section selected
        $('#modalFormWorkflowUser').modal('show'); // show modal
    });
    document.getElementById('addAcknowledgementApprovalSet').addEventListener('click', function () {
        workFlowSectionSelected = 'acknowledgement'; // set section selected
        $('#modalFormWorkflowUserSet').modal('show'); // show modal
    });
    $('#acknowledgementWorkflowContainer').sortable({
        handle: '.acknowledgement-workflow-user-handle-order',
        invertSwap: true,
        animation: 200,
        ghostClass: 'ghost',
        onSort: acknowledgementWorkflowRender,
    });

    // informed users -- events
    document.getElementById('addInformedUsers').addEventListener('click', function () {
        workFlowSectionSelected = 'informed'; // set section selected
        $('#modalFormWorkflowUser').modal('show'); // show modal
    });
}

function initMaxLengthForm() {
    initMaxLength('#notes');
}

function initActions() {
    documentFilesRender();
    approvalWorkflowRender();
    informedUsersRender();
    reviewWorkflowRender();
    acknowledgementWorkflowRender();

    // get initial option
    fillOptionsFromAjax("#category_sub", `${_baseURL}/options/basics/category-subs?category=${$('#category').val()}`, categorySubId);
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

    // check review
    if (isReviewRequired && reviewUsers.length <= 0) {
        MsgBox.HtmlNotification('Review users empty');
        return;
    }

    // check acknowledgement
    if (isAcknowledgementRequired && acknowledgementUsers.length <= 0) {
        MsgBox.HtmlNotification('Acknowledgement users empty');
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
    _data2Send.append('ref_doc_id', refDocId);
    _data2Send.append("due_date", dateFormat($('#due_date').datetimepicker('viewDate'), 'YYYY-MM-DD'));
    _data2Send.append('approval_users', JSON.stringify(approvalUsers));
    _data2Send.append('informed_users', JSON.stringify(informedUsers));
    _data2Send.append('is_review_required', isReviewRequired);
    _data2Send.append('review_users', JSON.stringify(reviewUsers));
    _data2Send.append('is_acknowledgement_required', isAcknowledgementRequired);
    _data2Send.append('acknowledgement_users', JSON.stringify(acknowledgementUsers));
    documentFiles.forEach(file => {
        if (file.file_uri) {
            _data2Send.append('files[]', file.file_uri);
        }
    });
    _data2Send.append('document_files', JSON.stringify(documentFiles.map((item) => {
        const {
            file_uri,
            ...rest
        } = item;
        return rest;
    })));

    // send request
    const response = await axiosCustom($('#formInput').attr('action'), $('#formInput').attr('method'), _data2Send, null);

    // if response status not 201 or 200
    if (![200].includes(response.status)) {
        // show error
        MsgBox.HtmlNotification(refactorErrorMessages(response.data), `${response.status} - ${response.statusText}`)
        formValidationSetErrorMessages(response.data.errors);

        // hide progress
        showProgressButton(false, '#save');
        showBlockUIElement('#formInput', false);
        return;
    }

    // reload page
    window.location.reload();
}

// document files ===============

function documentFilesNew(files) {
    if (files.length <= 0) return;

    for (const file of files) {
        // check for duplicate
        if (documentFiles.find(d => d.file_origin_name === file.name)) return;

        documentFiles.push({
            file_origin_name: file.name,
            file_size: file.size,
            file_type: file.type,
            file_uri: file
        });
    }

    // clear file
    $('#attachments').val('');

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

    const data = documentFiles.find(item => item.file_origin_name == fileName);
    if (!data) return;

    // filter data to remove
    documentFiles = documentFiles.filter(item => item.file_origin_name != fileName);

    documentFilesRender();
}

// ./ document files ===============

// workflow users / approval set ===============

function workflowAddUsers(selectedUsers) {
    switch (workFlowSectionSelected) {
        case "approval":
            approvalWorkflowAdd(selectedUsers);
            break;

        case "review":
            reviewWorkflowAdd(selectedUsers);
            break;

        case "acknowledgement":
            acknowledgementWorkflowAdd(selectedUsers);
            break;

        case "informed":
            informedUsersAdd(selectedUsers);
            break;

    }
}

function workflowAddUsersSet(approvalSet) {
    switch (workFlowSectionSelected) {
        case "approval":
            approvalWorkflowUsersSet(approvalSet);
            break;

        case "review":
            reviewWorkflowUsersSet(approvalSet);
            break;

        case "acknowledgement":
            acknowledgementWorkflowUsersSet(approvalSet);
            break;

    }
}

// ./ workflow users / approval set ===============

// informed users ===============

function informedUsersAdd(selectedUsers) {
    if (!selectedUsers) return;

    showProgressButton(true, '#workflowUsersAdd');
    showBlockUIElement('#formWorkflowUser');

    selectedUsers.forEach(user => {
        // check if user already exist
        if (informedUsers.find(u => u.id === user.id)) return;

        // add user
        informedUsers.push({
            id: user.id,
            name: user.name,
            email: user.email,
        })
    });

    showProgressButton(false, '#workflowUsersAdd');
    showBlockUIElement('#formWorkflowUser', false);

    $('#modalFormWorkflowUser').modal('hide');
    informedUsersRender();
}

function informedUsersRender() {
    // clear events
    $('.informed-users-remove').off('click');

    if (informedUsers.length <= 0) {
        $('#informedUsersContainer').html(`<div class="d-flex flex-grow-1 justify-content-center align-items-center">No workflow found</div>`);
        return;
    }

    let html = '';
    informedUsers.forEach((user, index) => {
        html += `<div class="list-group-item list-group-item-action" data-id="${user.id}">
                    <div class="d-flex align-items-center">
                        <div class="d-flex flex-grow-1 justify-content-between">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold mb-1">${user.name}</span>
                                <span class="mb-1">${user.email}</span>
                            </div>
                            <button type="button" class="btn btn-sm text-danger informed-users-remove" data-id="${user.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
    });

    $('#informedUsersContainer').html(html);

    // init events
    $('.informed-users-remove').on('click', informedUsersRemove);
}

function informedUsersRemove() {
    const id = $(this).data('id');
    const data = informedUsers.find(item => item.id == id);
    if (!data) return;

    // filter data to remove
    informedUsers = informedUsers.filter(item => item.id != id);

    informedUsersRender();
}

// ./ informed users ===============

// acknowledgement workflow ===============

function acknowledgementWorkflowAdd(selectedUsers) {
    if (!selectedUsers) return;

    showProgressButton(true, '#workflowUsersAdd');
    showBlockUIElement('#formWorkflowUser');

    selectedUsers.forEach(user => {
        // check if user already exist
        if (acknowledgementUsers.find(u => u.id === user.id)) return;

        // add user
        acknowledgementUsers.push({
            id: user.id,
            name: user.name,
            email: user.email,
            order: acknowledgementUsers.length + 1
        })
    });

    showProgressButton(false, '#workflowUsersAdd');
    showBlockUIElement('#formWorkflowUser', false);

    $('#modalFormWorkflowUser').modal('hide');
    acknowledgementWorkflowRender();
}

function acknowledgementWorkflowRender() {
    // clear events
    $('.acknowledgement-workflow-user-remove').off('click');

    if (acknowledgementUsers.length <= 0) {
        $('#acknowledgementWorkflowContainer').html(`<div class="d-flex flex-grow-1 justify-content-center align-items-center">No workflow found</div>`);
        return;
    }

    // reordering
    acknowledgementWorkflowReorder();

    let html = '';
    acknowledgementUsers.forEach((user, index) => {
        html += `<div class="list-group-item list-group-item-action acknowledgement-workflow-user-handle-order" data-id="${user.id}">
                    <div class="d-flex align-items-center">
                        <h4 class="mr-3">#${user.order}</h4>
                        <div class="d-flex flex-grow-1 justify-content-between">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold mb-1">${user.name}</span>
                                <span class="mb-1">${user.email}</span>
                            </div>
                            <button type="button" class="btn btn-sm text-danger acknowledgement-workflow-user-remove" data-id="${user.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
    });

    $('#acknowledgementWorkflowContainer').html(html);

    // init events
    $('.acknowledgement-workflow-user-remove').on('click', acknowledgementWorkflowRemove);
}

function acknowledgementWorkflowRemove() {
    const id = $(this).data('id');
    const data = acknowledgementUsers.find(item => item.id == id);
    if (!data) return;

    // filter data to remove
    acknowledgementUsers = acknowledgementUsers.filter(item => item.id != id);

    acknowledgementWorkflowRender();
}

function acknowledgementWorkflowReorder() {
    // get orders from tr data-id
    const orderIds = $('#acknowledgementWorkflowContainer').sortable('toArray');

    let order = 1;
    orderIds.forEach((id, index) => {
        // get which index is data stored
        const dataIndex = acknowledgementUsers.findIndex(item => item.id == id);

        // if not found
        if (dataIndex < 0) return;

        // reassign order
        acknowledgementUsers[dataIndex].order = order++;
    });

    // sort
    acknowledgementUsers.sort((a, b) => a.order - b.order);
}

async function acknowledgementWorkflowUsersSet(approvalSet) {
    if (!approvalSet) return;

    if (acknowledgementUsers.length > 0) {
        const confirmation = await MsgBox.ConfirmTripleButtons('What do you want to do?', 'Workflow not empty', 'question', ['Replace', 'Append', 'Cancel']).catch(err => {
            if (err) console.log(err)
        });
        if (confirmation.isDismissed) return;
        if (confirmation.isConfirmed) {
            acknowledgementUsers = [];
        }
    }

    showProgressButton(true, '#workflowUsersSetAdd');
    showBlockUIElement('#formWorkflowUserSet');

    // get data
    const response = await getOptionsAjax(`${_baseURL}/options/basics/approval-sets/${approvalSet.id}`);

    showProgressButton(false, '#workflowUsersSetAdd');
    showBlockUIElement('#formWorkflowUserSet', false);
    $('#modalFormWorkflowUserSet').modal('hide');

    // if response null
    if (!response) return;

    // get data options
    const options = response.data;

    acknowledgementWorkflowAdd(options);
}

// ./ acknowledgement workflow ===============

// review workflow ===============

function reviewWorkflowAdd(selectedUsers) {
    if (!selectedUsers) return;

    showProgressButton(true, '#workflowUsersAdd');
    showBlockUIElement('#formWorkflowUser');

    selectedUsers.forEach(user => {
        // check if user already exist
        if (reviewUsers.find(u => u.id === user.id)) return;

        // add user
        reviewUsers.push({
            id: user.id,
            name: user.name,
            email: user.email,
            order: reviewUsers.length + 1
        })
    });

    showProgressButton(false, '#workflowUsersAdd');
    showBlockUIElement('#formWorkflowUser', false);

    $('#modalFormWorkflowUser').modal('hide');
    reviewWorkflowRender();
}

function reviewWorkflowRender() {
    // clear events
    $('.review-workflow-user-remove').off('click');

    if (reviewUsers.length <= 0) {
        $('#reviewWorkflowContainer').html(`<div class="d-flex flex-grow-1 justify-content-center align-items-center">No workflow found</div>`);
        return;
    }

    // reordering
    reviewWorkflowReorder();

    let html = '';
    reviewUsers.forEach((user, index) => {
        html += `<div class="list-group-item list-group-item-action review-workflow-user-handle-order" data-id="${user.id}">
                    <div class="d-flex align-items-center">
                        <h4 class="mr-3">#${user.order}</h4>
                        <div class="d-flex flex-grow-1 justify-content-between">
                            <div class="d-flex flex-column">
                                <span class="font-weight-bold mb-1">${user.name}</span>
                                <span class="mb-1">${user.email}</span>
                            </div>
                            <button type="button" class="btn btn-sm text-danger review-workflow-user-remove" data-id="${user.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>`;
    });

    $('#reviewWorkflowContainer').html(html);

    // init events
    $('.review-workflow-user-remove').on('click', reviewWorkflowRemove);
}

function reviewWorkflowRemove() {
    const id = $(this).data('id');
    const data = reviewUsers.find(item => item.id == id);
    if (!data) return;

    // filter data to remove
    reviewUsers = reviewUsers.filter(item => item.id != id);

    reviewWorkflowRender();
}

function reviewWorkflowReorder() {
    // get orders from tr data-id
    const orderIds = $('#reviewWorkflowContainer').sortable('toArray');

    let order = 1;
    orderIds.forEach((id, index) => {
        // get which index is data stored
        const dataIndex = reviewUsers.findIndex(item => item.id == id);

        // if not found
        if (dataIndex < 0) return;

        // reassign order
        reviewUsers[dataIndex].order = order++;
    });

    // sort
    reviewUsers.sort((a, b) => a.order - b.order);
}

async function reviewWorkflowUsersSet(approvalSet) {
    if (!approvalSet) return;

    if (reviewUsers.length > 0) {
        const confirmation = await MsgBox.ConfirmTripleButtons('What do you want to do?', 'Workflow not empty', 'question', ['Replace', 'Append', 'Cancel']).catch(err => {
            if (err) console.log(err)
        });
        if (confirmation.isDismissed) return;
        if (confirmation.isConfirmed) {
            reviewUsers = [];
        }
    }

    showProgressButton(true, '#workflowUsersSetAdd');
    showBlockUIElement('#formWorkflowUserSet');

    // get data
    const response = await getOptionsAjax(`${_baseURL}/options/basics/approval-sets/${approvalSet.id}`);

    showProgressButton(false, '#workflowUsersSetAdd');
    showBlockUIElement('#formWorkflowUserSet', false);
    $('#modalFormWorkflowUserSet').modal('hide');

    // if response null
    if (!response) return;

    // get data options
    const options = response.data;

    reviewWorkflowAdd(options);
}

// ./ review workflow ===============

// approval workflow ===============

function approvalWorkflowAdd(selectedUsers) {
    if (!selectedUsers) return;

    showProgressButton(true, '#workflowUsersAdd');
    showBlockUIElement('#formWorkflowUser');

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

    showProgressButton(false, '#workflowUsersAdd');
    showBlockUIElement('#formWorkflowUser', false);

    $('#modalFormWorkflowUser').modal('hide');
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

    showProgressButton(true, '#workflowUsersSetAdd');
    showBlockUIElement('#formWorkflowUserSet');

    // get data
    const response = await getOptionsAjax(`${_baseURL}/options/basics/approval-sets/${approvalSet.id}`);

    showProgressButton(false, '#workflowUsersSetAdd');
    showBlockUIElement('#formWorkflowUserSet', false);
    $('#modalFormWorkflowUserSet').modal('hide');

    // if response null
    if (!response) return;

    // get data options
    const options = response.data;

    approvalWorkflowAdd(options);
}

// ./ approval workflow ===============

function formWorkflowUserClear() {
    _formValidationUser.resetForm();
    $('#formWorkflowUser')[0].reset();
    $('#workflowUsers').val(null).empty().trigger('change');
}

function formWorkflowUserSetClear() {
    _formValidationApprovalSet.resetForm();
    $('#formWorkflowUserSet')[0].reset();
    $('#workflowUsersSet').val(null).empty().trigger('change');
}

document.addEventListener("DOMContentLoaded", function () {
    initFormValidation();
    initOtherElements();
    initActions();
    initMaxLengthForm();
});
