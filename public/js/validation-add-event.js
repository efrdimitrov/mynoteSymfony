// SELECTING ALL TEXT ELEMENTS
let eventName = document.forms['add-event']['event[name]'];
// SELECTING ALL ERROR DISPLAY ELEMENTS
let name_error = document.getElementById('event-name');
// SETTING ALL EVENT LISTENERS
eventName.addEventListener('blur', nameVerify, true);

// validation function
/**
 * @return {boolean}
 */
function Validate() {

    if(eventName.value.length < 3) {
        eventName.style.border = "2px solid red";
        document.getElementsByName('event[name]')[0].placeholder='Полето е празно или е по-малко от 3 символа';
        eventName.focus();
        return false;
    }

}