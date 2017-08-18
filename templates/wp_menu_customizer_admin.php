<div id="wp_menu_customizer" class="wrap">
    <h1>Wordpress menu customizer</h1>

    <form method="post" action="options.php">
    <ul id="wp_menu_customizer_menu_list">
        <?php settings_fields('WPMenuCustomizer-menu-order-settings-group'); ?>
        <?php do_settings_sections('WPMenuCustomizer-menu-order-settings-group'); ?>
        <?php foreach ($WPMenuCustomizer->getMenuItems() as $menuItem) { ?>
            <li class="ui-state-default">
                <h2><?= (empty($menuItem[0])) ? $menuItem[2] : $menuItem[0]; ?></h2>
                <span class="dashicons dashicons-sort"></span>
                <input type="hidden" name="WPMenuCustomizer_menu_order[]" value="<?= $menuItem[2]; ?>">
            </li>
        <?php } ?>
    </ul>
    <?php submit_button(); ?>
    </form>
</div>