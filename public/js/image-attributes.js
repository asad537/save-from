(function () {
    function humanizeSource(image) {
        var source = image.getAttribute('src') || '';
        var filename = source.split('/').pop().split('?')[0].replace(/\.[^.]+$/, '');
        return filename.replace(/[-_]+/g, ' ').trim() || 'Save-Froms media image';
    }

    function completeImageAttributes(root) {
        (root || document).querySelectorAll('img').forEach(function (image) {
            var alt = (image.getAttribute('alt') || '').trim();
            var title = (image.getAttribute('title') || '').trim();
            var fallback = alt || title || humanizeSource(image);

            if (!alt) image.setAttribute('alt', fallback);
            if (!title) image.setAttribute('title', fallback);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        completeImageAttributes(document);

        new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                mutation.addedNodes.forEach(function (node) {
                    if (node.nodeType !== 1) return;
                    if (node.matches && node.matches('img')) completeImageAttributes(node.parentNode);
                    else completeImageAttributes(node);
                });
            });
        }).observe(document.body, { childList: true, subtree: true });
    });
})();
