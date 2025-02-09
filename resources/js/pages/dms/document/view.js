import {
    elementIsInPage
} from '../../../general';


function initOtherElements() {
    //
}

function initActions() {
    if (elementIsInPage(document.querySelector('#edit'))) {
        document.querySelector('#edit').addEventListener('click', () => {
            const data = _dataTable.row({
                selected: true
            }).data();
            if (typeof data === "undefined") return;
            editData(_dataTable.row({
                selected: true
            }).data().id.trim());
        });
    }
}

document.addEventListener("DOMContentLoaded", function () {
    initOtherElements();
    initActions();
});
