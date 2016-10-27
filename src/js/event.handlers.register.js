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


// -----------------------------------------------------------------------------
// register all event handlers right after the DOM content is fully loaded
// -----------------------------------------------------------------------------

// register form onsubmit event handler
document.addEventListener('DOMContentLoaded', function (e) {
    var form = element('simpleForm');
    // alert(form.id);
    form.addEventListener('submit', submit_click, false);
});

// register form submit button click event handler
document.addEventListener('DOMContentLoaded', function (e) {
    var submitButton = element('inputSubmit');
    // alert(form.id);
    submitButton.addEventListener('click', submit_button_click, false);
});


// register other event handlers

// --------------------------- DOMContentLoaded --------------------------------
