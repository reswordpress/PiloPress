<?php
/**
 * @var $success_icon
 * @var $error_icon
 * @var $configurations
 * @var $layouts
 * @var $add_new_link
 */
?>

<div class="wrap">
    <div class="wp-heading-inline">
        <img class="pilopress-logo"
             src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0iI2EwYTVhYSI+PHBhdGggZD0iTTEwIC4yQzQuNi4yLjMgNC42LjMgMTBzNC40IDkuOCA5LjcgOS44YzIuNiAwIDUuMS0xIDYuOS0yLjggMS44LTEuOCAyLjgtNC4zIDIuOC02LjkgMC01LjUtNC4zLTkuOS05LjctOS45em02LjQgMTYuM2MtMS43IDEuNy00IDIuNi02LjQgMi42LTUgMC05LTQuMS05LTkuMVM1IC45IDEwIC45IDE5IDUgMTkgMTBjMCAyLjUtLjkgNC43LTIuNiA2LjV6Ii8+PHBhdGggZD0iTTEwIDUuM2MtMi41IDAtNC42IDIuMS00LjYgNC43di41Yy4yIDEuOCAxLjQgMy4zIDMgMy45LjUuMiAxIC4zIDEuNS4zLjQgMCAuOS0uMSAxLjMtLjIuMSAwIC4xIDAgLjItLjEuMy0uMS41LS4yLjgtLjMgMCAwIC4xIDAgLjEtLjEgMCAwIC4xIDAgLjEtLjFoLjFzLjEgMCAuMS0uMWMwIDAgLjEgMCAuMS0uMS4yLS4yLjUtLjQuNy0uNmwuMy0uM2MuNi0uOCAxLTEuOSAxLTIuOSAwLTIuNS0yLjEtNC42LTQuNy00LjZ6bTMuMSA3LjNjMC0uMSAwLS4xIDAgMC0uNi0uNC0uNy0uOS0uNy0xLjR2LS40LS4xLS4zYzAtLjctLjItMS41LTEuNS0xLjYtLjUgMC0xLjMuMS0yLjMuNC0uMi0uMS0uNCAwLS42LjEtLjYuMi0xLjIuNC0yIC43IDAtMi4yIDEuOC00IDMuOS00IDEuNSAwIDIuOC44IDMuNSAyLjEuNC42LjYgMS4yLjYgMS45IDAgLjktLjMgMS44LS45IDIuNnoiLz48L3N2Zz4="
             alt="logo">
        <h1>Pilo'Press : Dashboard</h1>
    </div>

    <?php // Widgets area ?>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder">

            <?php // Column 1 ?>
            <div id="postbox-container-1" class="postbox-container">
                <div id="normal-sortables" class="meta-box-sortables ui-sortable">

                    <?php // Meta-boxes ?>
                    <div id="pilopress_configuration" class="postbox">
                        <div class="inside">
                            <h4><strong><?php _e( 'Configuration status', 'pilopress' ) ?></strong></h4>
                            <div class="main config-status">
                                <ul>
                                    <?php foreach ( $configurations as $configuration ) : ?>
                                        <li>
                                            <?php echo $configuration['status'] ? $success_icon : $error_icon ?>
                                            <?php echo $configuration['label'] ?>
                                            <?php if ( isset( $configuration['status_label'] ) ): ?>
                                                <?php echo $configuration['status_label'] ?>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php // Column 2 ?>
            <div id="postbox-container-2" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable">

                    <?php // Meta-boxes ?>
                    <div id="pilopress_layouts_actions" class="postbox">
                        <div class="inside">
                            <h4>
                                <strong><?php _e( 'Layouts', 'pilopress' ) ?></strong>
                                <span id="pilopress_layouts_count"><?php echo count( $layouts ) ?></span>
                            </h4>
                            <a href="<?php echo $add_new_link ?>" class="button button-secondary">
                                <?php _e( 'Add new layout', 'pilopress' ) ?>
                            </a>
                        </div>
                    </div>

                    <div id="pilopress_layouts" class="postbox pilopress-layouts-table">
                        <table class="widefat">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><strong><?php _e( 'Layout name', 'pilopress' ) ?></strong></th>
                                <th><strong><?php _e( 'Locations', 'pilopress' ) ?></strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if ( $layouts ): ?>
                                <?php foreach ( $layouts as $key => $layout ) : ?>
                                    <tr class="<?php echo $key % 2 ? 'alternate' : ''; ?>">
                                        <td><?php echo $key + 1 ?></td>
                                        <td>
                                            <a href="<?php echo $layout['edit_link'] ?>">
                                                <?php echo $layout['title'] ?>
                                            </a>
                                        </td>
                                        <td><?php echo $layout['location'] ?></td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <?php // Column 3 ?>
            <div id="postbox-container-3" class="postbox-container">
                <div id="column3-sortables" class="meta-box-sortables ui-sortable">

                    <?php // Meta-boxes ?>
                    <div id="pilopress_quick_links" class="postbox">
                        <div class="inside">
                            <h3><strong><?php _e( 'Documentations', 'pilopress' ) ?></strong></h3>
                            <div class="main">
                                <ul>
                                    <li>
                                        <span class="dashicons dashicons-external"></span>
                                        <a href="https://github.com/Pilot-in/PiloPress" target="_blank">
                                            GitHub Pilo'Press
                                        </a>
                                    </li>
                                    <li>
                                        <span class="dashicons dashicons-external"></span>
                                        <a href="https://www.advancedcustomfields.com/resources/" target="_blank">
                                            Advanced Custom Fields
                                        </a>
                                    </li>
                                    <li>
                                        <span class="dashicons dashicons-external"></span>
                                        <a href="https://wordpress.org/plugins/acf-extended/" target="_blank">
                                            Advanced Custom Fields: Extended
                                        </a>
                                    </li>
                                    <li>
                                        <span class="dashicons dashicons-external"></span>
                                        <a href="https://tailwindcss.com/docs/installation" target="_blank">
                                            Tailwind CSS
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="pilopress_pilotin" class="postbox">
                        <div class="inside">
                            Made with &#x2764; by
                            <a href="https://www.pilot-in.com" target="_blank">Pilot’in</a>
                        </div>
                    </div>

                </div>
            </div>

            <?php // Column 4 ?>
            <div id="postbox-container-4" class="postbox-container">
                <div id="column4-sortables" class="meta-box-sortables ui-sortable">

                    <?php // Meta-boxes ?>

                </div>
            </div>

        </div>
    </div>

</div>
