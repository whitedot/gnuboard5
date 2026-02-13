<?php include 'layout/header.php'; ?>
<?php include 'layout/sidebar.php'; ?>

<div class="card p-6">
    <div id="readme-content" class="prose prose-indigo max-w-none">
        Loading...
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    fetch('../README.md')
        .then(response => response.text())
        .then(text => {
            document.getElementById('readme-content').innerHTML = marked.parse(text);
        })
        .catch(err => {
            document.getElementById('readme-content').innerHTML = '<p class="text-red-500">README.md 파일을 불러올 수 없습니다.</p>';
        });
</script>

<?php include 'layout/footer.php'; ?>
