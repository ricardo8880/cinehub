/* CineHub mobile ads: position original mobile units between completed content
 * sections. Desktop units are distinct and never reparented after loading. */
(() => {
    const source = document.querySelector('[data-mobile-ad-flow]');
    if (!source || !window.matchMedia('(max-width: 1199.98px)').matches) return;

    const google = [...source.querySelectorAll('.sidebar-ad-unit--google')];
    const own = [...source.querySelectorAll('[data-sidebar-rotation]')];
    const kind = source.dataset.mobileAdFlow;
    let placed = 0;
    const placeAfter = (ad, anchor, grid = false) => {
        if (!ad || !anchor || !anchor.parentNode) return;
        const wrapper = document.createElement('div');
        wrapper.className = grid ? 'col-12 mobile-inline-ad mobile-inline-ad--grid' : 'mobile-inline-ad';
        wrapper.appendChild(ad);
        anchor.insertAdjacentElement('afterend', wrapper);
        placed++;
    };
    const related = [...document.querySelectorAll('section.mt-5')]
        .find(section => section.querySelector('.row.g-3'));

    if (kind === 'blog-post') {
        const text = document.querySelector('article.public-article .public-article__text');
        const paragraphs = text ? [...text.querySelectorAll(':scope > p')] : [];
        const headings = text ? [...text.querySelectorAll(':scope > h2')] : [];
        const boundaries = headings.slice(1)
            .map(heading => heading.previousElementSibling)
            .filter(element => element && element.tagName !== 'H2');

        if (paragraphs.length >= 8) {
            const anchors = boundaries.length >= 4
                ? [boundaries[0], boundaries[2], boundaries[3]]
                : [paragraphs[5], paragraphs[Math.floor(paragraphs.length * 0.48)], paragraphs[Math.floor(paragraphs.length * 0.76)]];
            const used = new Set();
            google.forEach((unit, index) => {
                if (index > 0 && paragraphs.length < 18) return;
                const anchor = anchors[index];
                if (anchor && !used.has(anchor)) {
                    placeAfter(unit, anchor);
                    used.add(anchor);
                }
            });
        }
        const bodyCard = text?.closest('.public-article__section');
        if (paragraphs.length >= 14) placeAfter(own[0], bodyCard);
        if (paragraphs.length >= 25) placeAfter(own[1], related || document.getElementById('comments'));
    } else if (kind === 'post') {
        const article = document.querySelector('article.public-article');
        const reading = [...(article?.querySelectorAll('.public-article__section') || [])]
            .filter(section => section.id !== 'interactions' && section.querySelector('.public-article__text'));
        if (reading[0]) placeAfter(google[0], reading[0]);
        if (reading.length >= 2) placeAfter(google[1], reading[1]);
        if (reading.length >= 2 && related) placeAfter(own[0], related);
        if (reading.length >= 3) placeAfter(google[2], document.getElementById('comments'));
        if (reading.length >= 3) placeAfter(own[1], document.querySelector('#interactions'));
    } else if (kind === 'home') {
        placeAfter(google[0], document.getElementById('latest'));
        placeAfter(google[1], document.getElementById('popular'));
        placeAfter(own[0], document.getElementById('categories'));
    } else if (kind === 'blog-index' || kind === 'category') {
        const grid = document.querySelector('.public-column-layout__content .row.g-3');
        if (grid) {
            const cards = [...grid.children].filter(item => item.classList.contains('col-12'));
            if (cards.length >= 4) placeAfter(google[0], cards[2], true);
            if (cards.length >= 10) placeAfter(google[1], cards[8], true);
            if (cards.length >= 12) placeAfter(own[0], cards[cards.length - 1], true);
        }
    }

    // Discard unused mobile placeholders before Google's asynchronous loader runs.
    // The desktop slots stay in their original location.
    source.remove();
    if (placed) document.documentElement.classList.add('has-mobile-inline-ads');
})();
