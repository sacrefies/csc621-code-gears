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


/**
 * Check if the element has the given class
 */
var hasClass = function (id, className) {
    var el = element(id);
    return el.classList.contains(className);
}


/**
 * Add a class to the specified element
 */
var addClass = function (id, className) {
    var el = element(id);
    el.classList.add(className)
}


/**
 * Remove a class from the specified element
 */
var removeClass = function (id, className) {
    var el = element(id);
    el.classList.remove(className)
}
