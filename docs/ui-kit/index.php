<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="card p-6">
    <div class="mb-4 overflow-hidden rounded-lg border border-default-200">
        <img
            src="images/WChwGxuZl7SE6RiHti5x.jpg"
            alt="README 소개 이미지"
            class="block w-full"
            style="height: 160px; max-height: 160px; object-fit: cover; object-position: center;"
        />
    </div>

    <div id="readme-content" class="prose prose-indigo max-w-none">
        Loading...
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    const readmeUrl = `../../README.md?v=${Date.now()}`;
    const rootBaseUrl = new URL('../../', window.location.href);

    function normalizeReadmeLinks(container) {
        const protocolPattern = /^[a-zA-Z][a-zA-Z0-9+.-]*:/;

        container.querySelectorAll('a[href]').forEach(anchor => {
            const href = anchor.getAttribute('href');

            if (!href || href.startsWith('#') || href.startsWith('//') || protocolPattern.test(href)) {
                return;
            }

            try {
                anchor.setAttribute('href', new URL(href, rootBaseUrl).toString());
            } catch (error) {}
        });
    }

    fetch(readmeUrl, { cache: 'no-store' })
        .then(response => {
            if (!response.ok) {
                throw new Error('README_NOT_FOUND');
            }
            return response.text();
        })
        .then(text => {
            const readmeContainer = document.getElementById('readme-content');
            readmeContainer.innerHTML = marked.parse(text);
            normalizeReadmeLinks(readmeContainer);
        })
        .catch(() => {
            document.getElementById('readme-content').innerHTML = '<p class="text-red-500">README.md 파일을 불러올 수 없습니다.</p>';
        });
</script>

<?php include 'layout/footer.php'; ?>
