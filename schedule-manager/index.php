<?php
    $GLOBALS["page-title"] = "Schedule Manager";
    $GLOBALS["page-language"] = "en";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_config.php";

    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_header.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/schedule-manager/_schedule-manager_header.php";
?>

<main>
    <p class="h1 fw-bold mb-3">Schedule Manager</p>
    <div class="alert alert-primary" role="alert">
        Select the menu.
    </div>
</main>

<?php
    include_once $_SERVER["DOCUMENT_ROOT"]."/schedule-manager/_schedule-manager_footer.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_includes/_footer.php";
?>