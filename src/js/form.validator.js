// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

// Global variables

// class names for a form group container
var classHasError = 'has-error';
var classHasSuccess = 'has-success';
// custom error messages
var errorEmail = 'Email address is not valid';
var errorCard = 'Credit card number is not valid';
var errorPassword = '1+ uppercase letter expected;\n1+ digit expected;\n1+ one of the following, \'%$#@*&)(!\' expected';

/**
 * Return the form validation final state.
 */
var setFinalState = function (passed) {
    if (passed) {
        // set error state off
        element('lblError').style.display = 'none';
        // set success state on
        element('lblSuccess').style.display = '';
    } else {
        // set success state off
        element('lblSuccess').style.display = 'none';
        // set error state on
        element('lblError').style.display = '';
    }
}


/**
 * Validate a credit card number with a required length.
 */
var validateCardNumber = function (length, cardNumber) {
    if (length == 0) return false;

    cardNumber = cardNumber.trim();
    var pat = '^\\d{' + length.toString() + '}$';
    var reg = new RegExp(pat);

    return reg.test(cardNumber);
}


/**
 * Validate an email address.
 * The pattern it uses is from http://jsfiddle.net/ghvj4gy9/embedded/result,js/
 */
var validateEmailAddress = function (email) {
    // see http://jsfiddle.net/ghvj4gy9/embedded/result,js/
    var pat = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return pat.test(email);
}


/**
 * Validate a password
 */
var validatePassword = function (password) {
    var pat = /(?=.*\d)(?=.*[A-Z])(?=.*\W)^[\S\W\d\w]{6,}/;
    return pat.test(password);
}


/**
 * Get one input element's parent container <div> element id by given the input
 * element's id
 */
var getContainerId = function (inputId) {
    return inputId.trim().replace(/input/, 'div');
}


/**
 * Highlight the specified element as invalid state
 */
var setInvalidElementState = function (inputId) {
    var containerId = getContainerId(inputId);
    clearHighlightedElementState(containerId);
    addClass(containerId, classHasError);
}


/**
 * Clear any highlighted state of the specified element
 */
var clearHighlightedElementState = function (inputId) {
    var containerId = getContainerId(inputId);
    if (hasClass(containerId, classHasError))
        removeClass(containerId, classHasError);
    if (hasClass(containerId, classHasSuccess))
        removeClass(containerId, classHasSuccess);
}


/**
 * Highlight the specified element as valid state
 */
var setValidElementState = function (inputId) {
    var containerId = getContainerId(inputId);
    clearHighlightedElementState(inputId);
    addClass(containerId, classHasSuccess);
}


/**
 * Validate an input element of a form and set its validity state
 */
var checkFormInput = function (id) {
    var el = element(id);
    var key = id.trim().substr(5).toLowerCase();

    clearHighlightedElementState(id);
    switch (key) {
    case 'creditcard':
        var types = element('selectCardTypes');
        var fix = parseInt(types.options[types.selectedIndex].value);
        if (!validateCardNumber(fix, el.value)) {
            setInvalidElementState(id);
            el.setCustomValidity(errorCard);
            return false;
        }
        break;
    case 'email':
        if (!validateEmailAddress(el.value)) {
            setInvalidElementState(id);
            el.setCustomValidity(errorEmail);
            return false;
        }
        break;
    case 'password':
        if (!validatePassword(el.value)) {
            setInvalidElementState(id);
            el.setCustomValidity(errorPassword);
            return false;
        }
        break;
    default:
        return false;
    }

    setValidElementState(id);
    return true;
}


/**
 * Clear all custom validation messages
 */
var clearAllInputsCustomValidity = function () {
    var form = element('simpleForm');
    var inputs = form.getElementsByTagName('input');
    for (index = 0; index < inputs.length; ++index)
        inputs[index].setCustomValidity('');
}


/**
 * Exec form submission event handler
 */
var submit_click = function (e) {
    e.preventDefault();

    var form = element('simpleForm');

    // var inputs = form.getElementsByTagName('input');
    // for (index = 0; index < inputs.length; ++index) {
    //     if (inputs[index].type != 'submit') {
    //         success = success && checkFormInput(inputs[index].id);
    //     }
    // }

    var em = !element('inputEmail').validity.customError;
    var cc = !element('inputCreditCard').validity.customError;
    var pw = !element('inputPassword').validity.customError;
    var success = em && cc && pw;

    setFinalState(success);
    // clearAllInputsCustomValidity();
    if (success) form.submit();
}


var submit_button_click = function (e) {
    clearAllInputsCustomValidity();
    var form = element('simpleForm');

    // var inputs = form.getElementsByTagName('input');
    // for (index = 0; index < inputs.length; ++index) {
    //     if (inputs[index].type != 'submit') {
    //         success = success && checkFormInput(inputs[index].id);
    //     }
    // }

    var em = checkFormInput('inputEmail');
    var cc = checkFormInput('inputCreditCard');
    var pw = checkFormInput('inputPassword');
    var success = em && cc && pw;

    setFinalState(success);
    // clearAllInputsCustomValidity();
    // if (success) form.submit();
}
