/**
 * @copyright https://remysharp.com/2010/07/21/throttling-function-calls
 *
 * @param fn
 * @param delay
 * @returns {Function}
 */
export function debounce(fn, delay) {
    var timer = null;
    return function () {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            fn.apply(context, args);
        }, delay);
    };
}