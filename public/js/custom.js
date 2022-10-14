// encode special characters
function htmlEncode(string) {
    return string
        .replace(/&/g, "&amp;")
        .replace(/>/g, "&gt;")
        .replace(/</g, "&lt;")
        .replace(/"/g, "&quot;");
}

// prop disabled selector by status
function propDisabled(selector, status) {
    selector.prop('disabled', status);
}

// prop disabled selector's children option by status
function propDisabledChildrenOption(selector, status) {
    selector.find('option').prop('disabled', status);
}

// add css selector's children option by option
function addDisplayChildrenOption(selector, option) {
    selector.find('option').css('display', option);
}

// add css display select
function addDisplay(selector, option) {
    selector.css('display', option);
}

// render html selector
function renderHTML(selector, value) {
    selector.html(value)
}

// render value selector
function renderValue(selector, value) {
    selector.val(value)
}

// prop checked selector by status
function propChecked(selector, status) {
    selector.prop('checked', status);
}

// prop disabled selector's children by status
function propDisabledChildren(selector, status) {
    selector.find('input,button,textarea,select,a').prop('disabled', status);
}


// render text display of option in select --}}
function renderNoneOption(selector, status) {
    if(true === status) {
        let previousText = selector.find(':selected').text();
        let previousDataText = selector.find(':selected').data('text');
        selector
            .find(':selected')
            .attr('data-text', previousText ? previousText : previousDataText)
            .text('')
    } else {
        let dataText = selector.find(':selected').data('text');
        selector
            .find(':selected')
            .text(dataText);
    }
}

// check object is empty
function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}
