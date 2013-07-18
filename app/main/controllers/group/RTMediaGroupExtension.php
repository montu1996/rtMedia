<?php
/**
 * Description of BPMediaGroupLoader
 *
 * @author faishal
 */
if (class_exists('BP_Group_Extension')) :// Recommended, to prevent problems during upgrade or when Groups are disabled

    class RTMediaGroupExtension extends BP_Group_Extension {
        function __construct() {
            $this->name = RTMEDIA_MEDIA_LABEL;
            $this->slug = RTMEDIA_MEDIA_SLUG;
            $this->create_step_position = 21;
            $this->nav_item_position = 31;
        }

        function create_screen() {
            
            if (!bp_is_group_creation_step($this->slug))
                return false;
            ?>
            <h4><?php _e("Album Creation Control", 'rtmedia'); ?></h4>
            <p><?php _e("Who can create Albums in this group?", 'rtmedia'); ?></p>
            <div class="radio">
                <label>
                    <input name="rt_album_creation_control" type="radio" id="rt_media_group_level_all" checked="checked" value="all">
                    <strong><?php _e("All Group Members", 'rtmedia'); ?></strong>
                </label>
                <label>
                    <input name="rt_album_creation_control" type="radio" id="rt_media_group_level_moderators" value="moderators">
                    <strong><?php _e("Group Admins and Mods only", 'rtmedia'); ?></strong>
                </label>
                <label>
                    <input name="rt_album_creation_control" type="radio" id="rt_media_group_level_admin" value="admin">
                    <strong><?php _e("Group Admin only", 'rtmedia'); ?></strong>
                </label>
            </div>
            <h4><?php _e("Media Upload Control", 'rtmedia'); ?></h4>
            <p><?php _e("Who can Upload media in this group?", 'rtmedia'); ?></p>
            <div class="radio">
                <label>
                    <input name="rt_upload_media_control" type="radio" id="rt_media_upload_level_all" checked="checked" value="all">
                    <strong><?php _e("All Group Members", 'rtmedia'); ?></strong>
                </label>
                <label>
                    <input name="rt_upload_media_control" type="radio" id="rt_media_upload_level_moderators" value="moderators">
                    <strong><?php _e("Group Admins and Mods only", 'rtmedia'); ?></strong>
                </label>
                <label>
                    <input name="rt_upload_media_control" type="radio" id="rt_media_upload_level_admin" value="admin">
                    <strong><?php _e("Group Admin only", 'rtmedia'); ?></strong>
                </label>
            </div>

            <?php
            wp_nonce_field('groups_create_save_' . $this->slug);
        }

        /**
         *
         * @global type $bp
         */
        function create_screen_save() {
            global $bp;

            check_admin_referer('groups_create_save_' . $this->slug);

            /* Save any details submitted here */
            if (isset($_POST['rt_album_creation_control']) && $_POST['rt_album_creation_control'] != '')
                groups_update_groupmeta($bp->groups->new_group_id, 'rt_media_group_control_level', $_POST['rt_album_creation_control']);
                groups_update_groupmeta($bp->groups->new_group_id, 'rt_upload_media_control_level', $_POST['rt_upload_media_control']);
        }

        /**
         *
         * @global type $bp_media
         * @return boolean
         */
        function edit_screen() {
            if (!bp_is_group_admin_screen($this->slug))
                return false;
            $current_level = groups_get_groupmeta(bp_get_current_group_id(), 'rt_media_group_control_level');
            $current_level = groups_get_groupmeta(bp_get_current_group_id(), 'rt_upload_media_control_level');
            ?>

            <h4><?php _e("Album Creation Control", 'rtmedia'); ?></h4>
            <p><?php _e("Who can Upload media in this group?", 'rtmedia'); ?></p>
            <div class="radio">
                <label>
                    <input name="rt_album_creation_control" type="radio" id="rt_media_group_level_moderators"  value="all"<?php checked($current_level, 'all', true) ?>>
                    <strong><?php _e("All Group Members", 'rtmedia'); ?></strong>
                </label>
                <label>
                    <input name="rt_album_creation_control" type="radio" id="rt_media_group_level_moderators" value="moderators" <?php checked($current_level, 'moderators', true) ?>>
                    <strong><?php _e("Group Admins and Mods only", 'rtmedia'); ?></strong>
                </label>
                <label>
                    <input name="rt_album_creation_control" type="radio" id="rt_media_group_level_admin" value="admin" <?php checked($current_level, 'admin', true) ?>>
                    <strong><?php _e("Group Admin only", 'rtmedia'); ?></strong>
                </label>
            </div>
            <hr>
            <h4><?php _e("Media Upload Control", 'rtmedia'); ?></h4>
            <p><?php _e("Who can create Albums in this group?", 'rtmedia'); ?></p>
            <div class="radio">
                <label>
                    <input name="rt_upload_media_control" type="radio" id="rt_media_upload_level_all"  value="all"<?php checked($current_level, 'all', true) ?>>
                    <strong><?php _e("All Group Members", 'rtmedia'); ?></strong>
                </label>
                <label>
                    <input name="rt_upload_media_control" type="radio" id="rt_media_upload_level_moderator" value="moderators" <?php checked($current_level, 'moderators', true) ?>>
                    <strong><?php _e("Group Admins and Mods only", 'rtmedia'); ?></strong>
                </label>
                <label>
                    <input name="rt_upload_media_control" type="radio" id="rt_media_upload_level_admin" value="admin" <?php checked($current_level, 'admin', true) ?>>
                    <strong><?php _e("Group Admin only", 'rtmedia'); ?></strong>
                </label>
            </div>
            <hr>
            <input type="submit" name="save" value="<?php _e("Save Changes", 'rtmedia'); ?>" />
            <?php
            wp_nonce_field('groups_edit_save_' . $this->slug);
        }

        /**
         *
         * @global type $bp
         * @global type $bp_media
         * @return boolean
         */
        function edit_screen_save() {
            global $bp;

            if (!isset($_POST['save']))
                return false;

            check_admin_referer('groups_edit_save_' . $this->slug);

            if (isset($_POST['rt_album_creation_control']) && $_POST['rt_album_creation_control'] != ''){
                $success = groups_update_groupmeta(bp_get_current_group_id(), 'rt_media_group_control_level', $_POST['rt_album_creation_control']);
                $success = groups_update_groupmeta(bp_get_current_group_id(), 'rt_upload_media_control_level', $_POST['rt_upload_media_control']);
            }
            else
                $success = false;

            /* To post an error/success message to the screen, use the following */
            if (!$success)
                bp_core_add_message(__('There was an error saving, please try again', 'rtmedia'), 'error');
            else
                bp_core_add_message(__('Settings saved successfully', 'rtmedia'));

            bp_core_redirect(bp_get_group_permalink($bp->groups->current_group) . '/admin/' . $this->slug);
        }

        /**
         * The display method for the extension
         *
         * @since BuddyPress Media 2.3
         */

        /**
         *
         * @global type $bp_media
         */
        function widget_display() {
            ?>
                   <div class="info-group" >
                   <h4><?php echo esc_attr($this->name) ?></h4>
            <p>
                <?php _e("You could display a small snippet of information from your group extension here. It will show on the group
	                home screen.", 'rtmedia'); ?>
            </p>
            </div>
            <?php
        }

    }






endif; // class_exists( 'BP_Group_Extension' )