<div class="wrap">

    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form action="options.php" method="post">

        <?php settings_fields('lsx_cf_zoho'); ?>

        <?php do_settings_sections('lsx_cf_zoho'); ?>

        <?php submit_button('Save Settings'); ?>

    </form>
</div>
