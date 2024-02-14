    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        const BASE_URL = "<?=BASE_URL?>";
        const USER_PERMISSION = "<?=isset($_SESSION["user"]) ? $_SESSION["user"]["permission"] : "none"?>";
    </script>

    <?php foreach($js_scripts as $script_name): ?>
    <script src="<?=BASE_URL?>public/js/<?=$script_name?>.js?v=<?=time()?>"></script>
    <?php endforeach; ?>
</body>
</html>