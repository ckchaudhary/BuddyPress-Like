<?php
// Defining BuddyPress Like's version in the site database
// TODO May be better to check if they're not difined?
define('BP_LIKE_VERSION', '0.0.9');
define('BP_LIKE_DB_VERSION', '10');

if (!defined('BPLIKE_PATH'))
    define('BPLIKE_PATH', plugin_dir_path(dirname(__FILE__)));


// TODO fix bug when below line is uncommeted.
// WP Admin wont load..
//include 'install.php';
// may add in supported version of php, wp &  bp
//make a .po file!!
load_plugin_textdomain('bplike', false, BPLIKE_PATH . '/languages/');


/* The notice we show when the plugin is installed. */

function bp_like_install_buddypress_notice() {

    echo '<div id="message" class="error fade"><p style="line-height: 150%">';
    _e('<strong>BuddyPress Like</strong></a> requires the BuddyPress plugin to work. Please <a href="http://buddypress.org">install BuddyPress</a> first, or <a href="plugins.php">deactivate BuddyPress Like</a>.', 'bp-like');
    echo '</p></div>';
}

/* The notice we show if the plugin is updated. */

function bp_like_updated_notice() {

    if (!is_super_admin())
        return false;

    echo '<div id="message" class="updated fade"><p style="line-height: 150%">';
    printf(__('<strong>BuddyPress Like</strong> has been successfully updated to version %s.', 'buddypress-like'), BP_LIKE_VERSION);
    echo '</p></div>';
}

/**
 * bp_like_install()
 *
 * Installs or upgrades the database content
 *
 */
function bp_like_install() {



// build a way to easily change this to other predefined words, eg love, thumbs up
    $default_text_strings = array(
        'like' => array(
            'default' => __('Like', 'buddypress-like'),
            'custom' => __('Like', 'buddypress-like')
        ),
        'unlike' => array(
            'default' => __('Unlike', 'buddypress-like'),
            'custom' => __('Unlike', 'buddypress-like')
        ),
        'like_this_item' => array(
            'default' => __('Like this item', 'buddypress-like'),
            'custom' => __('Like this item', 'buddypress-like')
        ),
        'unlike_this_item' => array(
            'default' => __('Unlike this item', 'buddypress-like'),
            'custom' => __('Unlike this item', 'buddypress-like')
        ),
        'view_likes' => array(
            'default' => __('View likes', 'buddypress-like'),
            'custom' => __('View likes', 'buddypress-like')
        ),
        'hide_likes' => array(
            'default' => __('Hide likes', 'buddypress-like'),
            'custom' => __('Hide likes', 'buddypress-like')
        ),
        'show_activity_likes' => array(
            'default' => __('Show Activity Likes', 'buddypress-like'),
            'custom' => __('Show Activity Likes', 'buddypress-like')
        ),
        'show_blogpost_likes' => array(
            'default' => __('Show Blog Post Likes', 'buddypress-like'),
            'custom' => __('Show Blog Post Likes', 'buddypress-like')
        ),
        'must_be_logged_in' => array(
            'default' => __('Sorry, you must be logged in to like that.', 'buddypress-like'),
            'custom' => __('Sorry, you must be logged in to like that.', 'buddypress-like')),
        'record_activity_likes_own' => array(
            'default' => __('%user% liked their own <a href="%permalink%">activity</a>', 'buddypress-like'),
            'custom' => __('%user% liked their own <a href="%permalink%">activity</a>', 'buddypress-like')
        ),
        'record_activity_likes_an' => array(
            'default' => __('%user% liked an <a href="%permalink%">activity</a>', 'buddypress-like'),
            'custom' => __('%user% liked an <a href="%permalink%">activity</a>', 'buddypress-like')
        ),
        'record_activity_likes_users' => array(
            'default' => __('%user% liked %author%\'s <a href="%permalink%">activity</a>', 'buddypress-like'),
            'custom' => __('%user% liked %author%\'s <a href="%permalink%">activity</a>', 'buddypress-like')
        ),
        'record_activity_likes_own_blogpost' => array(
            'default' => __('%user% liked their own blog post, <a href="%permalink%">%title%</a>', 'buddypress-like'),
            'custom' => __('%user% liked their own blog post, <a href="%permalink%">%title%</a>', 'buddypress-like')
        ),
        'record_activity_likes_a_blogpost' => array(
            'default' => __('%user% liked a blog post, <a href="%permalink%">%title%</a>', 'buddypress-like'),
            'custom' => __('%user% liked an blog post, <a href="%permalink%">%title%</a>', 'buddypress-like')
        ),
        'record_activity_likes_users_blogpost' => array(
            'default' => __('%user% liked %author%\'s blog post, <a href="%permalink%">%title%</a>', 'buddypress-like'),
            'custom' => __('%user% liked %author%\'s blog post, <a href="%permalink%">%title%</a>', 'buddypress-like')
        ),
        'get_likes_no_likes' => array(
            'default' => __('Nobody likes this yet.', 'buddypress-like'),
            'custom' => __('Nobody likes this yet.', 'buddypress-like')
        ),
        'get_likes_only_liker' => array(
            'default' => __('You are the only person who likes this so far.', 'buddypress-like'),
            'custom' => __('You are the only person who likes this so far.', 'buddypress-like')
        ),
        'get_likes_you_and_singular' => array(
            'default' => __('You and %count% other person like this.', 'buddypress-like'),
            'custom' => __('You and %count% other person like this.', 'buddypress-like')
        ),
        'get_likes_you_and_plural' => array(
            'default' => __('You and %count% other people like this', 'buddypress-like'),
            'custom' => __('You and %count% other people like this', 'buddypress-like')
        ),
        'get_likes_count_people_singular' => array(
            'default' => __('%count% person likes this.', 'buddypress-like'),
            'custom' => __('%count% person likes this.', 'buddypress-like')
        ),
        'get_likes_count_people_plural' => array(
            'default' => __('%count% people like this.', 'buddypress-like'),
            'custom' => __('%count% people like this.', 'buddypress-like')
        ),
        'get_likes_and_people_singular' => array(
            'default' => __('and %count% other person like this.', 'buddypress-like'),
            'custom' => __('and %count% other person like this.', 'buddypress-like')
        ),
        'get_likes_and_people_plural' => array(
            'default' => __('and %count% other people like this.', 'buddypress-like'),
            'custom' => __('and %count% other people like this.', 'buddypress-like')
        ),
        'get_likes_likes_this' => array(
            'default' => __('liked this.', 'buddypress-like'),
            'custom' => __('liked this.', 'buddypress-like')
        ),
        'get_likes_like_this' => array(
            'default' => __('like this.', 'buddypress-like'),
            'custom' => __('like this.', 'buddypress-like')
        ),
        'get_likes_no_friends_you_and_singular' => array(
            'default' => __('None of your friends like this yet, but you and %count% other person does.', 'buddypress-like'),
            'custom' => __('None of your friends like this yet, but you and %count% other person does.', 'buddypress-like')
        ),
        'get_likes_no_friends_you_and_plural' => array(
            'default' => __('None of your friends like this yet, but you and %count% other people do.', 'buddypress-like'),
            'custom' => __('None of your friends like this yet, but you and %count% other people do.', 'buddypress-like')
        ),
        'get_likes_no_friends_singular' => array(
            'default' => __('None of your friends like this yet, but %count% other person does.', 'buddypress-like'),
            'custom' => __('None of your friends like this yet, but %count% other person does.', 'buddypress-like')
        ),
        'get_likes_no_friends_plural' => array(
            'default' => __('None of your friends like this yet, but %count% other people do.', 'buddypress-like'),
            'custom' => __('None of your friends like this yet, but %count% other people do.', 'buddypress-like')
        )
    );

    $current_settings = get_site_option('bp_like_settings');

    if ($current_settings['post_to_activity_stream'])
        $post_to_activity_stream = $current_settings['post_to_activity_stream'];
    else
        $post_to_activity_stream = 1;

    if ($current_settings['show_excerpt'])
        $show_excerpt = $current_settings['show_excerpt'];
    else
        $show_excerpt = 0;

    if ($current_settings['excerpt_length'])
        $excerpt_length = $current_settings['excerpt_length'];
    else
        $excerpt_length = 140;

    if ($current_settings['likers_visibility'])
        $likers_visibility = $current_settings['likers_visibility'];
    else
        $likers_visibility = 'show_all';

    if ($current_settings['name_or_avatar'])
        $name_or_avatar = $current_settings['name_or_avatar'];
    else
        $name_or_avatar = 'name';

    if ($current_settings['text_strings']) :

        $current_text_strings = $current_settings['text_strings'];

        /* Go through each string and update the default to the current default, keep the custom settings */
        foreach ($default_text_strings as $string_name => $string_contents) :

            $default = $default_text_strings[$string_name]['default'];
            $custom = $current_settings['text_strings'][$string_name]['custom'];

            if (empty($custom))
                $custom = $default;

            $text_strings[$string_name] = array('default' => $default, 'custom' => $custom);

        endforeach;

    else :
        $text_strings = $default_text_strings;
    endif;

    $settings = array(
        'likers_visibility' => $likers_visibility,
        'post_to_activity_stream' => $post_to_activity_stream,
        'show_excerpt' => $show_excerpt,
        'excerpt_length' => $excerpt_length,
        'text_strings' => $text_strings,
        'name_or_avatar' => $name_or_avatar
    );

    update_site_option('bp_like_db_version', BP_LIKE_DB_VERSION);
    update_site_option('bp_like_settings', $settings);

    add_action('admin_notices', 'bp_like_updated_notice');
}

/**
 * bp_like_check_installed()
 *
 * Checks to see if the DB tables exist or if you are running an old version
 * of the component. If it matches, it will run the installation function.
 * This means we don't have to deactivate and then reactivate.
 *
 */
function bp_like_check_installed() {
    global $wpdb;

    if (!is_super_admin())
        return false;

    if (!get_site_option('bp_like_settings') || get_site_option('bp-like-db-version'))
        bp_like_install();

    if (get_site_option('bp_like_db_version') < BP_LIKE_DB_VERSION)
        bp_like_install();
}

add_action('admin_menu', 'bp_like_check_installed');

/**
 * bp_like_get_settings()
 *
 * Returns settings from the database
 *
 */
function bp_like_get_settings($option = false) {

    $settings = get_site_option('bp_like_settings');

    if (!$option)
        return $settings;
    else
        return $settings[$option];
}

/**
 * bp_like_get_text()
 *
 * Returns a custom text string from the database
 *
 */
function bp_like_get_text($text = false, $type = 'custom') {

    $settings = get_site_option('bp_like_settings');
    $text_strings = $settings['text_strings'];
    $string = $text_strings[$text];

    return $string[$type];
}

/**
 * bp_like_process_ajax()
 *
 * Runs the relevant function depending on what Ajax call has been made.
 *
 */
function bp_like_process_ajax() {
    global $bp;

    $id = preg_replace("/\D/", "", $_POST['id']);

    if ($_POST['type'] == 'button like')
        bp_like_add_user_like($id, 'activity');

    if ($_POST['type'] == 'button unlike')
        bp_like_remove_user_like($id, 'activity');

    if ($_POST['type'] == 'acomment-reply bp-primary-action like')
        bp_like_add_user_like($id, 'activity');

    if ($_POST['type'] == 'acomment-reply bp-primary-action unlike')
        bp_like_remove_user_like($id, 'activity');

    if ($_POST['type'] == 'button view-likes')
        bp_like_get_likes($id, 'activity');

    if ($_POST['type'] == 'button like_blogpost')
        bp_like_add_user_like($id, 'blogpost');

    if ($_POST['type'] == 'button unlike_blogpost')
        bp_like_remove_user_like($id, 'blogpost');

    die();
}

add_action('wp_ajax_activity_like', 'bp_like_process_ajax');

/**
 * bp_like_is_liked()
 *
 * Checks to see whether the user has liked a given item.
 *
 */
function bp_like_is_liked($item_id = '', $type = '', $user_id = '') {
    global $bp;

    if (!$type)
        return false;

    if (!$item_id)
        return false;

    if (isset($user_id)) {
        if (!$user_id)
            $user_id = $bp->loggedin_user->id;
    }

    if ($type == 'activity')
        $user_likes = get_user_meta($user_id, 'bp_liked_activities', true);

    if ($type == 'blogpost')
        $user_likes = get_user_meta($user_id, 'bp_liked_blogposts', true);

    if (!$user_likes) {
        return false;
    } elseif (!array_key_exists($item_id, $user_likes)) {
        return false;
    } else {
        return true;
    };
}

/**
 * bp_like_add_user_like()
 *
 * Registers that the user likes a given item.
 *
 */
function bp_like_add_user_like($item_id = '', $type = 'activity') {
    global $bp;

    if (!$item_id)
        return false;

    if (!isset($user_id))
        $user_id = $bp->loggedin_user->id;

    if ($user_id == 0) {
        echo bp_like_get_text('must_be_logged_in');
        return false;
    }

    if ($type == 'activity') :

        /* Add to the users liked activities. */
        $user_likes = get_user_meta($user_id, 'bp_liked_activities', true);
        $user_likes[$item_id] = 'activity_liked';
        update_user_meta($user_id, 'bp_liked_activities', $user_likes);

        /* Add to the total likes for this activity. */
        $users_who_like = bp_activity_get_meta($item_id, 'liked_count', true);
        $users_who_like[$user_id] = 'user_likes';
        bp_activity_update_meta($item_id, 'liked_count', $users_who_like);

        $liked_count = count($users_who_like);

        /* Publish to the activity stream if we're allowed to. */
        if (bp_like_get_settings('post_to_activity_stream') == 1) {

            $activity = bp_activity_get_specific(array('activity_ids' => $item_id, 'component' => 'bp-like'));
            $author_id = @$activity['activities'][0]->user_id;

            if ($user_id == $author_id)
                $action = bp_like_get_text('record_activity_likes_own');
            elseif ($user_id == 0)
                $action = bp_like_get_text('record_activity_likes_an');
            else
                $action = bp_like_get_text('record_activity_likes_users');

            $liker = bp_core_get_userlink($user_id);
            $author = bp_core_get_userlink($author_id);
            $activity_url = bp_activity_get_permalink($item_id);
            $content = ''; //content must be defined...

            /* Grab the content and make it into an excerpt of 140 chars if we're allowed */
            if (bp_like_get_settings('show_excerpt') == 1) {
                $content = $activity['activities'][0]->content;
                if (strlen($content) > bp_like_get_settings('excerpt_length')) {
                    $content = substr($content, 0, bp_like_get_settings('excerpt_length'));
                    $content = $content . '...';
                }
            };

            /* Filter out the placeholders */
            $action = str_replace('%user%', $liker, $action);
            $action = str_replace('%permalink%', $activity_url, $action);
            $action = str_replace('%author%', $author, $action);

            bp_activity_add(
                    array(
                        'action' => $action,
                        'content' => $content,
                        'primary_link' => $activity_url,
                        'component' => 'bp-like',
                        'type' => 'activity_liked',
                        'user_id' => $user_id,
                        'item_id' => $item_id
                    )
            );
        };
    elseif ($type == 'blogpost') :

        /* Add to the users liked blog posts. */
        $user_likes = get_user_meta($user_id, 'bp_liked_blogposts', true);
        $user_likes[$item_id] = 'blogpost_liked';
        update_user_meta($user_id, 'bp_liked_blogposts', $user_likes);

        /* Add to the total likes for this blog post. */
        $users_who_like = get_post_meta($item_id, 'liked_count', true);
        $users_who_like[$user_id] = 'user_likes';
        update_post_meta($item_id, 'liked_count', $users_who_like);

        $liked_count = count($users_who_like);

        if (bp_like_get_settings('post_to_activity_stream') == 1) {
            $post = get_post($item_id);
            $author_id = $post->post_author;

            $liker = bp_core_get_userlink($user_id);
            $permalink = get_permalink($item_id);
            $title = $post->post_title;
            $author = bp_core_get_userlink($post->post_author);

            if ($user_id == $author_id)
                $action = bp_like_get_text('record_activity_likes_own_blogpost');
            elseif ($user_id == 0)
                $action = bp_like_get_text('record_activity_likes_a_blogpost');
            else
                $action = bp_like_get_text('record_activity_likes_users_blogpost');

            /* Filter out the placeholders */
            $action = str_replace('%user%', $liker, $action);
            $action = str_replace('%permalink%', $permalink, $action);
            $action = str_replace('%title%', $title, $action);
            $action = str_replace('%author%', $author, $action);

            /* Grab the content and make it into an excerpt of 140 chars if we're allowed */
            if (bp_like_get_settings('show_excerpt') == 1) {
                $content = $post->post_content;
                if (strlen($content) > bp_like_get_settings('excerpt_length')) {
                    $content = substr($content, 0, bp_like_get_settings('excerpt_length'));
                    $content = $content . '...';
                }
            };

            bp_activity_add(
                    array(
                        'action' => $action,
                        'content' => $content,
                        'component' => 'bp-like',
                        'type' => 'blogpost_liked',
                        'user_id' => $user_id,
                        'item_id' => $item_id,
                        'primary_link' => $permalink
                    )
            );
        };

    endif;

    echo bp_like_get_text('unlike');
    if ($liked_count)
        echo ' (' . $liked_count . ')';
}

/**
 * bp_like_remove_user_like()
 *
 * Registers that the user has unliked a given item.
 *
 */
function bp_like_remove_user_like($item_id = '', $type = 'activity') {
    global $bp;

    if (!$item_id)
        return false;

    if (!isset($user_id)) {
        $user_id = $bp->loggedin_user->id;
    }

    if ($user_id == 0) {
        echo bp_like_get_text('must_be_logged_in');
        return false;
    }

    if ($type == 'activity') :

        /* Remove this from the users liked activities. */
        $user_likes = get_user_meta($user_id, 'bp_liked_activities', true);
        unset($user_likes[$item_id]);
        update_user_meta($user_id, 'bp_liked_activities', $user_likes);

        /* Update the total number of users who have liked this activity. */
        $users_who_like = bp_activity_get_meta($item_id, 'liked_count', true);
        unset($users_who_like[$user_id]);

        /* If nobody likes the activity, delete the meta for it to save space, otherwise, update the meta */
        if (empty($users_who_like))
            bp_activity_delete_meta($item_id, 'liked_count');
        else
            bp_activity_update_meta($item_id, 'liked_count', $users_who_like);

        $liked_count = count($users_who_like);

        /* Remove the update on the users profile from when they liked the activity. */
        $update_id = bp_activity_get_activity_id(
                array(
                    'item_id' => $item_id,
                    'component' => 'bp-like',
                    'type' => 'activity_liked',
                    'user_id' => $user_id
                )
        );

        bp_activity_delete(
                array(
                    'id' => $update_id,
                    'item_id' => $item_id,
                    'component' => 'bp-like',
                    'type' => 'activity_liked',
                    'user_id' => $user_id
                )
        );

    elseif ($type == 'blogpost') :

        /* Remove this from the users liked activities. */
        $user_likes = get_user_meta($user_id, 'bp_liked_blogposts', true);
        unset($user_likes[$item_id]);
        update_user_meta($user_id, 'bp_liked_blogposts', $user_likes);

        /* Update the total number of users who have liked this blog post. */
        $users_who_like = get_post_meta($item_id, 'liked_count', true);
        unset($users_who_like[$user_id]);

        /* If nobody likes the blog post, delete the meta for it to save space, otherwise, update the meta */
        if (empty($users_who_like))
            delete_post_meta($item_id, 'liked_count');
        else
            update_post_meta($item_id, 'liked_count', $users_who_like);

        $liked_count = count($users_who_like);

        /* Remove the update on the users profile from when they liked the activity. */
        $update_id = bp_activity_get_activity_id(
                array(
                    'item_id' => $item_id,
                    'component' => 'bp-like',
                    'type' => 'blogpost_liked',
                    'user_id' => $user_id
                )
        );

        bp_activity_delete(
                array(
                    'id' => $update_id,
                    'item_id' => $item_id,
                    'component' => 'bp-like',
                    'type' => 'blogpost_liked',
                    'user_id' => $user_id
                )
        );

    endif;

    echo bp_like_get_text('like');
    if ($liked_count)
        echo ' (' . $liked_count . ')';
}

/**
 * bp_like_get_likes()
 *
 * Outputs a list of users who have liked a given item.
 *
 */
function bp_like_get_likes($item_id = '', $type = '', $user_id = '') {
    global $bp;

    if (!$type || !$item_id)
        return false;

    if (!$user_id)
        $user_id = $bp->loggedin_user->id;

    if ($type == 'activity') :

        /* Grab some core data we will need later on, specific to activities */
        $users_who_like = array_keys(bp_activity_get_meta($item_id, 'liked_count'));
        $liked_count = count(bp_activity_get_meta($item_id, 'liked_count'));
        $output = '';
        /* Intercept any messages if nobody likes it, just incase the button was clicked when it shouldn't be */
        if ($liked_count == 0) :

            $output .= bp_like_get_text('get_likes_no_likes');

        /* We should show information about all likers */
        elseif (bp_like_get_settings('likers_visibility') == 'show_all') :

            /* Settings say we should show their name. */
            if (bp_like_get_settings('name_or_avatar') == 'name') :

                /* Current user likes it too, remove them from the liked count and output appropriate message */
                if (bp_like_is_liked($item_id, 'activity', $user_id)) :

                    $liked_count = $liked_count - 1;

                    if ($liked_count == 1)
                        $output .= bp_like_get_text('get_likes_you_and_singular');

                    elseif ($liked_count == 0)
                        $output .= bp_like_get_text('get_likes_only_liker');
                    else
                        $output .= bp_like_get_text('get_likes_you_and_plural');

                else :

                    if ($liked_count == 1)
                        $output .= bp_like_get_text('get_likes_count_people_singular');
                    else
                        $output .= bp_like_get_text('get_likes_count_people_plural');

                endif;

                /* Now output the name of each person who has liked it (except the current user obviously) */
                foreach ($users_who_like as $id) :

                    if ($id != $user_id)
                        $output .= ' &middot <a href="' . bp_core_get_user_domain($id) . '" title="' . bp_core_get_user_displayname($id) . '">' . bp_core_get_user_displayname($id) . '</a>';

                endforeach;

            /* Settings say we should show their avatar. */
            elseif (bp_like_get_settings('name_or_avatar') == 'avatar') :

                /* Output the avatar of each person who has liked it. */
                foreach ($users_who_like as $id) :

                    $output .= '<a href="' . bp_core_get_user_domain($id) . '" title="' . bp_core_get_user_displayname($id) . '">' . bp_core_fetch_avatar(array('item_id' => $id, 'object' => 'user', 'type' => 'thumb', 'width' => 30, 'height' => 30)) . '</a> ';

                endforeach;

            endif;

        /* We should show the information of friends, but only the number of non-friends. */
        elseif (bp_like_get_settings('likers_visibility') == 'friends_names_others_numbers' && bp_is_active('friends')) :

            /* Grab some information about their friends. */
            $users_friends = friends_get_friend_user_ids($user_id);
            if (!empty($users_friends))
                $friends_who_like = array_intersect($users_who_like, $users_friends);

            /* Current user likes it, so reduce the liked count by 1, to get the number of other people who like it. */
            if (bp_like_is_liked($item_id, 'activity', $user_id))
                $liked_count = $liked_count - 1;

            /* Settings say we should show their names. */
            if (bp_like_get_settings('name_or_avatar') == 'name') :

                /* Current user likes it too, tell them. */
                if (bp_like_is_liked($item_id, 'activity', $user_id))
                    $output .= 'You ';

                /* Output the name of each friend who has liked it. */
                foreach ($users_who_like as $id) :

                    if (in_array($id, $friends_who_like)) {
                        $output .= ' &middot <a href="' . bp_core_get_user_domain($id) . '" title="' . bp_core_get_user_displayname($id) . '">' . bp_core_get_user_displayname($id) . '</a> ';

                        $liked_count = $liked_count - 1;
                    }

                endforeach;

                /* If non-friends like it, say so. */
                if ($liked_count == 1)
                    $output .= bp_like_get_text('get_likes_and_people_singular');

                elseif ($liked_count > 1)
                    $output .= bp_like_get_text('get_likes_and_people_plural');
                else
                    $output .= bp_like_get_text('get_likes_like_this');

            /* Settings say we should show their avatar. */
            elseif (bp_like_get_settings('name_or_avatar') == 'avatar') :

                /* Output the avatar of each friend who has liked it, as well as the current users' if they have. */
                if (!empty($friends_who_like)) :

                    foreach ($users_who_like as $id) :

                        if ($id == $user_id || in_array($id, $friends_who_like)) {
                            $user_info = get_userdata($id);
                            $output .= '<a href="' . bp_core_get_user_domain($id) . '" title="' . bp_core_get_user_displayname($id) . '">' . get_avatar($user_info->user_email, 30) . '</a> ';
                        }

                    endforeach;

                endif;

            endif;
        elseif (bp_like_get_settings('likers_visibility') == 'friends_names_others_numbers' && !bp_is_active('friends') || bp_like_get_settings('likers_visibility') == 'just_numbers') :

            /* Current user likes it too, remove them from the liked count and output appropriate message */
            if (bp_like_is_liked($item_id, 'activity', $user_id)) :

                $liked_count = $liked_count - 1;

                if ($liked_count == 1)
                    $output .= bp_like_get_text('get_likes_you_and_singular');

                elseif ($liked_count == 0)
                    $output .= bp_like_get_text('get_likes_only_liker');
                else
                    $output .= bp_like_get_text('get_likes_you_and_plural');

            else :

                if ($liked_count == 1)
                    $output .= bp_like_get_text('get_likes_count_people_singular');
                else
                    $output .= bp_like_get_text('get_likes_count_people_plural');

            endif;

        endif;

    endif;

    /* Filter out the placeholder. */
    $output = str_replace('%count%', $liked_count, $output);

    echo $output;
}

/**
 * 
 * bp_like_users_who_like()
 * 
 * Gets the usernames of users who liked post id
 * 
 */
function bp_like_users_who_like($bp_like_id = '') {
    $bp_like_id = bp_get_activity_id(); 
    $users_who_like = array_keys(bp_activity_get_meta($bp_like_id, 'liked_count', true));
    if (count($users_who_like) == 1) { ?>
<p><?php echo bp_core_get_userlink($users_who_like[0], $no_anchor = false, $just_link = false); ?><?php _e(' liked this.', 'bp-like'); ?> </p>
   <?php } else {
       $others = count($users_who_like)-1;
       echo bp_core_get_userlink($users_who_like[$others], $no_anchor = false, $just_link = false);
       _e(' and ', 'bp-like'); 
       echo bp_core_get_userlink($users_who_like[$others - 1], $no_anchor = false, $just_link = false);
       _e(' and ', 'bp-like'); echo $others . _e('liked this', 'bp-like');
        
    }
}

/**
 * bp_like_button()
 *
 * Outputs the 'Like/Unlike' and 'View likes/Hide likes' buttons.
 *
 */
function bp_like_button($id = '', $type = '') {

    $users_who_like = 0;
    $liked_count = 0;

    /* Set the type if not already set, and check whether we are outputting the button on a blogpost or not. */
    if (!$type && !is_single())
        $type = 'activity';
    elseif (!$type && is_single())
        $type = 'blogpost';

    if ($type == 'activity') :

        $bp_like_comment_id = bp_get_activity_comment_id();

        if (empty($bp_like_comment_id)) {

            $bp_like_id = bp_get_activity_id();
            $bp_like_view = 'button view-likes';

            if (bp_like_is_liked($bp_like_id, 'activity')) {
                $bp_like_css = 'button unlike';
            } else {
                $bp_like_css = 'button like';
            }
        } else {

            $bp_like_id = bp_get_activity_comment_id();
            $bp_like_view = 'acomment-reply bp-primary-action view-likes';

            if (bp_like_is_liked($bp_like_id, 'activity')) {
                $bp_like_css = 'acomment-reply bp-primary-action unlike';
            } else {
                $bp_like_css = 'acomment-reply bp-primary-action like';
            }
        }

        $activity = bp_activity_get_specific(array('activity_ids' => $bp_like_id));
        $activity_type = @$activity['activities'][0]->type;
        //print_r($activity);

        if ($activity_type === null)
            $activity_type = 'activity_update';
        //need to test this more, seems to work though...

        if (is_user_logged_in() && $activity_type !== 'activity_liked') :

            if (bp_activity_get_meta($bp_like_id, 'liked_count', true)) {
                $users_who_like = array_keys(bp_activity_get_meta($bp_like_id, 'liked_count', true));
                $liked_count = count($users_who_like);
            }

            if (!bp_like_is_liked($bp_like_id, 'activity')) :
                ?>
                <a href="#" class="<?php echo $bp_like_css; ?>" id="like-activity-<?php echo $bp_like_id; ?>" title="<?php echo bp_like_get_text('like_this_item'); ?>"><?php
                    echo bp_like_get_text('like');
                    if ($liked_count)
                        echo ' (' . $liked_count . ')';
                    ?></a>
                <?php else : ?>
                <a href="#" class="<?php echo $bp_like_css; ?>" id="unlike-activity-<?php echo $bp_like_id; ?>" title="<?php echo bp_like_get_text('unlike_this_item'); ?>"><?php
                    echo bp_like_get_text('unlike');
                    if ($liked_count)
                        echo ' (' . $liked_count . ')';
                    ?></a>
            <?php
            endif;

            if ($users_who_like):
                bp_like_users_who_like($bp_like_id);

            endif;
        endif;

    elseif ($type == 'blogpost') :
        global $post;

        if (!$id && is_single())
            $id = $post->ID;

        if (is_user_logged_in() && get_post_meta($id, 'liked_count', true)) {
            $liked_count = count(get_post_meta($id, 'liked_count', true));
        }

        if (!bp_like_is_liked($id, 'blogpost')) :
            ?>

            <div class="like-box"><a href="#" class="like_blogpost" id="like-blogpost-<?php echo $id; ?>" title="<?php echo bp_like_get_text('like_this_item'); ?>"><?php
                    echo bp_like_get_text('like');
                    if ($liked_count)
                        echo ' (' . $liked_count . ')';
                    ?></a></div>

        <?php else : ?>

            <div class="like-box"><a href="#" class="unlike_blogpost" id="unlike-blogpost-<?php echo $id; ?>" title="<?php echo bp_like_get_text('unlike_this_item'); ?>"><?php
                    echo bp_like_get_text('unlike');
                    if ($liked_count)
                        echo ' (' . $liked_count . ')';
                    ?></a></div>
        <?php
        endif;

    endif;
}

;
add_filter('bp_activity_entry_meta', 'bp_like_button');
add_action('bp_before_blog_single_post', 'bp_like_button');
add_filter('bp_activity_comment_options', 'bp_like_button');

/**
 * bp_like_activity_filter()
 *
 * Adds 'Show Activity Likes' to activity stream filters.
 *
 */
function bp_like_activity_filter() {
    echo '<option value="activity_liked">' . bp_like_get_text('show_activity_likes') . '</option>';
    echo '<option value="blogpost_liked">Show Blog Post Likes</option>';
}

add_action('bp_activity_filter_options', 'bp_like_activity_filter');
add_action('bp_member_activity_filter_options', 'bp_like_activity_filter');
add_action('bp_group_activity_filter_options', 'bp_like_activity_filter');

/**
 * bp_like_list_scripts()
 *
 * Includes the Javascript required for ajax etc.
 *
 */
function bp_like_list_scripts() {
    wp_enqueue_script(
            "bp-like", plugins_url('/js/bp-like.min.js', __FILE__), array('jquery')
    );
}

add_action('wp_print_scripts', 'bp_like_list_scripts');

/**
 * bp_like_add_admin_page_menu()
 *
 * Adds "BuddyPress Like" to the main BuddyPress admin menu.
 *
 */
function bp_like_add_admin_page_menu() {
    add_submenu_page(
            'bp-general-settings', 'BuddyPress Like', 'BuddyPress Like', 'manage_options', 'bp-like-settings', 'bp_like_admin_page'
    );
}

add_action('admin_menu', 'bp_like_add_admin_page_menu');

/**
 * bp_like_admin_page_verify_nonce()
 *
 * When the settings form is submitted, verifies the nonce to ensure security.
 *
 */
function bp_like_admin_page_verify_nonce() {
    if (isset($_POST['_wpnonce']) && isset($_POST['bp_like_updated'])) {
        $nonce = $_REQUEST['_wpnonce'];
        if (!wp_verify_nonce($nonce, 'bp-like-admin'))
            wp_die(__('You do not have permission to do that.'));
    }
}

add_action('init', 'bp_like_admin_page_verify_nonce');

/**
 * bp_like_admin_page()
 *
 * Outputs the admin settings page.
 *
 */
function bp_like_admin_page() {
    global $current_user;

    wp_get_current_user();

    /* Update our options if the form has been submitted */
    if (isset($_POST['_wpnonce']) && isset($_POST['bp_like_updated'])) {

        /* Add each text string to the $strings_to_save array */
        foreach ($_POST as $key => $value) {
            if (preg_match("/text_string_/i", $key)) {
                $default = bplike_get_text(str_replace('bp_like_admin_text_string_', '', $key), 'default');
                $strings_to_save[str_replace('bp_like_admin_text_string_', '', $key)] = array('default' => $default, 'custom' => stripslashes($value));
            }
        }


        /* Now actually save the data to the options table */
        update_site_option(
                'bp_like_settings', array(
            'likers_visibility' => $_POST['bp_like_admin_likers_visibility'],
            'post_to_activity_stream' => $_POST['bp_like_admin_post_to_activity_stream'],
            'show_excerpt' => $_POST['bp_like_admin_show_excerpt'],
            'excerpt_length' => $_POST['bp_like_admin_excerpt_length'],
            'text_strings' => $strings_to_save,
            'translate_nag' => bp_like_get_settings('translate_nag'),
            'name_or_avatar' => $_POST['name_or_avatar']
                )
        );

        /* Let the user know everything's cool */
        echo '<div class="updated"><p><strong>';
        _e('Settings saved.', 'wordpress');
        echo '</strong></p></div>';
    }

    $text_strings = bp_like_get_settings('text_strings');
    $title = __('BuddyPress Like');
    ?>
    <style type="text/css">
        table input { width: 100%; }
        table label { display: block; }
    </style>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('select.name-or-avatar').change(function() {
                var value = jQuery(this).val();
                jQuery('select.name-or-avatar').val(value);
            });
        });
    </script>

    <div class="wrap">
        <h2><?php echo esc_html($title); ?></h2>
        <form action="" method="post">
            <input type="hidden" name="bp_like_updated" value="updated">

            <table class="form-table" id="bp-like-admin" style="max-width:650px;float:left;">
                <tr valign="top">
                    <th scope="row"><?php _e('General settings', 'bp-like'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('General settings', 'bp-like'); ?></span>
                            </legend>
                            <input type="checkbox" id="bp_like_admin_post_to_activity_stream" name="bp_like_admin_post_to_activity_stream" value="1"<?php if (bp_like_get_settings('post_to_activity_stream') == 1) echo ' checked="checked"' ?>>
                            <label for="bp_like_admin_post_activity_updates">
    <?php _e("Post an activity update when something is liked", 'bp-like'); ?>
                            </label>
                            <p class="description"><?php echo __('e.g. ') . $current_user->display_name . __(" liked Bob's activity. "); ?></p>
                            <br />

                            <input type="checkbox" id="bp_like_admin_show_excerpt" name="bp_like_admin_show_excerpt" value="1"<?php if (bp_like_get_settings('show_excerpt') == 1) echo ' checked="checked"' ?>>
                            <label for="bp_like_admin_show_excerpt"><?php _e("Show a short excerpt of the activity that has been liked.", 'bp-like'); ?></label>
                            <p>Limit to <input type="text" maxlength="3" style="width: 40px" value="<?php echo bp_like_get_settings('excerpt_length'); ?>" name="bp_like_admin_excerpt_length" /> characters.</p>

                        </fieldset>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><?php _e('Privacy Settings'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e("User Privacy", "bp-like"); ?></span>
                            </legend>
                            <p><?php _e("Choose how much information about users who 'liked' a particular item is shown.", "bp-like"); ?></p>

                            <p style="line-height: 200%;">
                                <input type="radio" name="bp_like_admin_likers_visibility" value="show_all"<?php
                                if (bp_like_get_settings('likers_visibility') == 'show_all') {
                                    echo ' checked="checked""';
                                };
                                ?> /> Show <select name="name_or_avatar" class="name-or-avatar"><option value="name"<?php
                                       if (bp_like_get_settings('name_or_avatar') == 'name') {
                                           echo ' selected="selected""';
                                       };
                                       ?>>names</option><option value="avatar"<?php
                                                                                                     if (bp_like_get_settings('name_or_avatar') == 'avatar') {
                                                                                                         echo ' selected="selected""';
                                                                                                     };
                                                                                                     ?>>avatars</option></select> of all likers<br />
                                <?php if (bp_is_active('friends')) { ?>
                                    <input type="radio" name="bp_like_admin_likers_visibility" value="friends_names_others_numbers"<?php
                                    if (bp_like_get_settings('likers_visibility') == 'friends_names_others_numbers') {
                                        echo ' checked="checked""';
                                    };
                                    ?> /> Show <select name="name_or_avatar" class="name-or-avatar"><option value="name"<?php
                                           if (bp_like_get_settings('name_or_avatar') == 'name') {
                                               echo ' selected="selected""';
                                           };
                                           ?>>names</option><option value="avatar"<?php
                                                                                                         if (bp_like_get_settings('name_or_avatar') == 'avatar') {
                                                                                                             echo ' selected="selected""';
                                                                                                         };
                                                                                                         ?>>avatars</option></select> of friends, and the number of non-friends<br />
                                <?php }; ?>
                                <input type="radio" name="bp_like_admin_likers_visibility" value="just_numbers"<?php
                                if (bp_like_get_settings('likers_visibility') == 'just_numbers') {
                                    echo ' checked="checked""';
                                };
                                ?> /> <?php _e('Show only the number of likers', 'bp-like'); ?>
                            </p>

            </table> 

            <div id="bplike-about" style="float:right; background:#fff;max-width:300px;padding:20px;margin-bottom:30px;">
                <h3 class="hndle"><span>About BuddyPress Like</span></h3>
                <p><strong>Version: 0.1.0</strong></p>
                <div class="inside">

                    Gives users the ability to 'like' content across your BuddyPress enabled site.

                    <p>Available for free on <a href="http://wordpress.org/plugins/buddypress-like/">WordPress.org</a>.</p>

                    <strong>Want to help?</strong>
                    <ul>
                        <li><a href="http://wordpress.org/support/view/plugin-reviews/buddypress-like">Give 5 stars on WordPress.org</a></li>
                        <li>Development takes place publicly on <a href="https://github.com/Darrenmeehan/BuddyPress-Like">Github</a>. Is there any issues or bugs you have?</li>
                        <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZAJLLEJDBHAWL"><strong>Donate</strong></a></li>
                    </ul>

                    <strong>Need help?</strong>
                    <ul><li>Ask on the <a href="http://wordpress.org/support/plugin/buddypress-like">WordPress.org forum</a></li></ul>

                </div>          
            </div>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Custom Messages', 'bp-like'); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php _e('Custom Messages', 'bp-like'); ?></span>
                            </legend>
                            <label for="bp_like_admin_post_activity_updates">
    <?php _e("Change what messages are shown to users. For example, they can 'love' or 'dig' items instead of liking them.", "bp-like"); ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>   
            </table>
            <table class="widefat fixed" cellspacing="0">


                <thead>
                    <tr>
                        <th scope="col" id="default" class="column-name" style="width: 43%;"><?php _e('Default', 'buddypress-like'); ?></th>
                        <th scope="col" id="custom" class="column-name" style=""><?php _e('Custom', 'bp-like'); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th colspan="2" id="default" class="column-name"></th>
                    </tr>
                </tfoot>

    <?php foreach ($text_strings as $key => $string) : ?>
                    <tr valign="top">
                        <th scope="row" style="width:400px;"><label for="bp_like_admin_text_string_<?php echo $key; ?>"><?php echo htmlspecialchars($string['default']); ?></label></th>
                        <td><input name="bp_like_admin_text_string_<?php echo $key; ?>" id="bp_like_admin_text_string_<?php echo $key; ?>" value="<?php echo htmlspecialchars($string['custom']); ?>" class="regular-text" type="text"></td>
                    </tr>
    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="submit">
                <input class="button-primary" type="submit" name="bp-like-admin-submit" id="bp-like-admin-submit" value="<?php _e('Save Changes', 'wordpress'); ?>"/>
            </p>
    <?php wp_nonce_field('bp-like-admin') ?>
        </form>
    </div>
    <?php
}
