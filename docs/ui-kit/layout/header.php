<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UI Guide - G5AIF</title>
    <script>
        (function () {
            var storageKey = 'g5aif-ui-theme';
            var savedTheme = null;

            try {
                savedTheme = localStorage.getItem(storageKey);
            } catch (error) {
                savedTheme = null;
            }

            var isDark =
                savedTheme === 'dark' ||
                (!savedTheme &&
                    window.matchMedia &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches);

            document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
        })();
    </script>
    <!-- Tailwind CSS (compiled or CDN depending on setup) -->
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/ui-kit.css">
    <!-- UI Guide Custom CSS -->
    <link rel="stylesheet" href="css/ui-guide.css">
    <!-- Iconify -->
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
</head>
<body class="bg-gray-50">
    <div class="ui-layout-wrapper">
