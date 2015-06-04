
export function getScrollTop() {
    if (typeof window.pageYOffset != 'undefined') {
        return pageYOffset;
    } else {
        var b = document.body; //IE 'quirks'
        var d = document.documentElement; //IE with doctype
        d = (d.clientHeight)? d : b;
        return d.scrollTop;
    }
};

export function getInnerHeight() {
    return window.innerHeight
        || document.documentElement.clientHeight
        || document.body.clientHeight
};

