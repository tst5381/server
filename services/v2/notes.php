<?php
require_once("dbconnection.php");
require_once("users.php");
require_once("editors.php");
require_once("games.php");
require_once("return_package.php");

require_once("media.php");
require_once("note_comments.php");
require_once("instances.php");
require_once("triggers.php");
require_once("tags.php");

require_once("client.php");

class notes extends dbconnection
{
    public static function createNote($pack)
    {
        $pack->auth->permission = "read_write";
        if(!users::authenticateUser($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        if($pack->media)
        {
            $pack->media->auth = $pack->auth;
            $pack->media->game_id = $pack->game_id;
            $pack->media_id = media::createMedia($pack->media)->data->media_id;
        }

        $game = games::getGame($pack);

        $pack->note_id = dbconnection::queryInsert(
            "INSERT INTO notes (".
            "game_id,".
            "user_id,".
            (isset($pack->name)        ? "name,"        : "").
            (isset($pack->description) ? "description," : "").
            (isset($pack->media_id)    ? "media_id,"    : "").
            "published,".
            "created".
            ") VALUES (".
            "'".$pack->game_id."',".
            "'".$pack->auth->user_id."',".
            (isset($pack->name)        ? "'".addslashes($pack->name)."',"        : "").
            (isset($pack->description) ? "'".addslashes($pack->description)."'," : "").
            (isset($pack->media_id)    ? "'".addslashes($pack->media_id)."',"    : "").
            ($game->data->moderated ? "'PENDING'" : "'AUTO'").",".
            "CURRENT_TIMESTAMP".
            ")"
        );

        if($pack->trigger)
        {
            $scene_id    = dbconnection::queryObject("SELECT * FROM user_game_scenes WHERE user_id = '{$pack->auth->user_id}' AND game_id = '{$pack->game_id}'")->scene_id;
            if (!$scene_id) {
                $scene_id = $game->data->intro_scene_id;
            }
            $instance_id = dbconnection::queryInsert("INSERT INTO instances (game_id, object_id, object_type, created) VALUES ('{$pack->game_id}', '{$pack->note_id}', 'NOTE', CURRENT_TIMESTAMP)");
            $trigger_id  = dbconnection::queryInsert("INSERT INTO triggers (game_id, instance_id, scene_id, type, latitude, longitude, infinite_distance, created) VALUES ( '{$pack->game_id}', '{$instance_id}', '{$scene_id}', 'LOCATION', '{$pack->trigger->latitude}', '{$pack->trigger->longitude}', '1', CURRENT_TIMESTAMP);");
        }

        //allow for 'tag_id' in API, but really just use object_tags
        if($pack->tag_id) {
            dbconnection::queryInsert("INSERT INTO object_tags (game_id, object_type, object_id, tag_id, created) VALUES ('{$pack->game_id}', 'NOTE', '{$pack->note_id}', '{$pack->tag_id}', CURRENT_TIMESTAMP)");
        }

        client::logPlayerCreatedNote($pack);
        games::bumpGameVersion($pack);
        return notes::getNote($pack);
    }

    public static function updateNote($pack)
    {
        $pack->auth->permission = "read_write";
        if(
          $pack->auth->user_id != dbconnection::queryObject("SELECT * FROM notes WHERE note_id = '{$pack->note_id}'")->user_id ||
          !users::authenticateUser($pack->auth)
        ) return new return_package(6, NULL, "Failed Authentication");

        if($pack->trigger)
        {
            $instance = dbconnection::queryObject("SELECT * FROM instances WHERE game_id = '{$pack->game_id}' AND object_type = 'NOTE' AND object_id = '{$pack->note_id}'");
            if(!$instance)
            {
                dbconnection::queryInsert("INSERT INTO instances (game_id, object_id, object_type, created) VALUES ('{$pack->game_id}', '{$pack->note_id}', 'NOTE', CURRENT_TIMESTAMP)");
                $instance = dbconnection::queryObject("SELECT * FROM instances WHERE game_id = '{$pack->game_id}' AND object_type = 'NOTE' AND object_id = '{$pack->note_id}'");
            }

            // Find existing
            $trigger = dbconnection::queryObject("SELECT * FROM triggers WHERE game_id = '{$pack->game_id}' AND instance_id = '{$instance->instance_id}'");
            if($trigger)
            {
                dbconnection::query("UPDATE triggers SET latitude = '{$pack->trigger->latitude}', longitude = '{$pack->trigger->longitude}' WHERE trigger_id = '{$trigger->trigger_id}'");
            }
            else
            {
                $scene_id = dbconnection::queryObject("SELECT * FROM user_game_scenes WHERE user_id = '{$pack->auth->user_id}' AND game_id = '{$pack->game_id}'")->scene_id;
                dbconnection::queryInsert("INSERT INTO triggers (game_id, instance_id, scene_id, type, latitude, longitude, infinite_distance, created) VALUES ( '{$pack->game_id}', '{$instance->instance_id}', '{$scene_id}', 'LOCATION', '{$pack->name}', '{$pack->name}', '{$pack->trigger->latitude}', '{$pack->trigger->longitude}', '1', CURRENT_TIMESTAMP);");
            }
        }

        dbconnection::query(
            "UPDATE notes SET ".
            (isset($pack->name)        ? "name        = '".addslashes($pack->name)."', "        : "").
            (isset($pack->description) ? "description = '".addslashes($pack->description)."', " : "").
            "last_active = CURRENT_TIMESTAMP ".
            "WHERE note_id = '{$pack->note_id}'"
        );

        //allow for 'tag_id' in API, but really just use object_tags
        if(isset($pack->tag_id))
        {
            dbconnection::query("DELETE FROM object_tags WHERE game_id = '{$pack->game_id}' AND object_type = 'NOTE' AND object_id = '{$pack->note_id}'");
            if($pack->tag_id != 0)
            {
                dbconnection::queryInsert("INSERT INTO object_tags (game_id, object_type, object_id, tag_id, created) VALUES ('{$pack->game_id}', 'NOTE', '{$pack->note_id}', '{$pack->tag_id}', CURRENT_TIMESTAMP)");
            }
        }

        games::bumpGameVersion($pack);
        return notes::getNote($pack);
    }

    private static function noteObjectFromSQL($sql_note)
    {
        if(!$sql_note) return $sql_note;
        $note = new stdClass();
        $note->note_id     = $sql_note->note_id;
        $note->game_id     = $sql_note->game_id;
        $note->user_id     = $sql_note->user_id;
        $note->name        = $sql_note->name;
        $note->description = $sql_note->description;
        $note->media_id    = $sql_note->media_id;
        $note->created     = $sql_note->created;
        $note->published   = $sql_note->published;
        $note->user               = new stdClass();
        $note->user->user_id      = $note->user_id;
        $note->user->user_name    = $sql_note->user_name;
        $note->user->display_name = $sql_note->display_name;

        return $note;
    }

    public static function getNote($pack)
    {
        $sql_note = dbconnection::queryObject("SELECT notes.*, users.user_name, users.display_name FROM notes LEFT JOIN users ON notes.user_id = users.user_id WHERE note_id = '{$pack->note_id}' LIMIT 1");
        $note = notes::noteObjectFromSQL($sql_note);
        if($note)
        {
            //allow for 'tag_id' in API, but really just use object_tags
            if($tag = dbconnection::queryObject("SELECT * FROM object_tags WHERE game_id = '{$note->game_id}' AND object_type = 'NOTE' AND object_id = '{$note->note_id}'"))
            {
                $note->tag_id = $tag->tag_id;
                $note->object_tag_id = $tag->object_tag_id;
            }
            else
            {
                $note->tag_id = "0";
                $note->object_tag_id = "0";
            }
        }
        return new return_package(0,$note);
    }

    public static function getNotesForGame($pack)
    {
        $notes = array();
        //two separate impls depending on presense of search. search makes query MUCH slower.
        if(isset($pack->search))
        {
            /*
            //Search in PHP
            $notes_arr       = dbconnection::queryArray("SELECT * FROM notes       WHERE game_id = '{$pack->game_id}'");
            $tags_arr        = dbconnection::queryArray("SELECT * FROM tags        WHERE game_id = '{$pack->game_id}' AND object_type = 'NOTE'");
            $object_tags_arr = dbconnection::queryArray("SELECT * FROM object_tags WHERE game_id = '{$pack->game_id}'");

            $tags        = array(); for($i = 0; $i < count($tags_arr);        $i++)        $tags[       $tags_arr[$i]->tag_id] =        $tags_arr[$i];
            $object_tags = array(); for($i = 0; $i < count($object_tags_arr); $i++) $object_tags[$object_tags_arr[$i]->tag_id] = $object_tags_arr[$i];
            $users       = array(); //will be derived on the spot

            $returned
            for($i = 0; $i < count($notes_arr); $i++)
            {
                $note = $notes_arr[$i];
                if(!$users[$note->user_id]) $users[$note->user_id] = dbconnection::queryObject("SELECT * FROM users WHERE user_id = '{$note->user_id}'");
                $user = $users[$note->user_id];
                $tag = $tags[$object_tags_arr[$note->note_id]->tag_id]

                if(
                    preg_match("@".$pack->search."@is",$note->name) ||
                    preg_match("@".$pack->search."@is",$note->description) ||
                    preg_match("@".$pack->search."@is",$user->user_name) ||
                    preg_match("@".$pack->search."@is",$user->display_name) ||
                    preg_match("@".$pack->search."@is",$tag->tag)
                )
                {
                    $note->user_name    = $users->user_name;
                    $note->display_name = $users->display_name;
                    $n = notes::noteObjectFromSQL($note);
                    $n-tag_id = $tag->tag_id;
                    $notes[] = $n;
                }
            }
            */

            //Search w/ SQL
            $sql_notes = dbconnection::queryArray("SELECT notes.*, users.user_name, users.display_name FROM notes LEFT JOIN users ON notes.user_id = users.user_id WHERE game_id = '{$pack->game_id}' AND (name LIKE '%{$pack->search}%' OR description LIKE '%{$pack->search}%' OR user_name LIKE '%{$pack->search}%' OR display_name LIKE '%{$pack->search}%')");
            for($i = 0; $i < count($sql_notes); $i++)
            {
                if(!($ob = notes::noteObjectFromSQL($sql_notes[$i]))) continue;

                //allow for 'tag_id' in API, but really just use object_tags
                if($tag = dbconnection::queryObject("SELECT * FROM object_tags WHERE game_id = '{$ob->game_id}' AND object_type = 'NOTE' AND object_id = '{$ob->note_id}'"))
                    $ob->tag_id = $tag->tag_id;
                else
                    $ob->tag_id = "0";

                $notes[] = $ob;
            }

            //search comments to find matching comments, then RETURN PARENT NOTE- NOT THE COMMENTS THEMSELVES
            $sql_note_comments = dbconnection::queryArray("SELECT * FROM note_comments LEFT JOIN users ON note_comments.user_id = users.user_id WHERE game_id = '{$pack->game_id}' AND (name LIKE '%{$pack->search}%' OR description LIKE '%{$pack->search}%' OR user_name LIKE '%{$pack->search}%' OR display_name LIKE '%{$pack->search}%')");
            for($i = 0; $i < count($sql_note_comments); $i++)
            {
                $sql_note = dbconnection::queryObject("SELECT * FROM notes LEFT JOIN users ON notes.user_id = users.user_id WHERE note_id = '{$sql_note_comments[$i]->note_id}'");
                if(!($ob = notes::noteObjectFromSQL($sql_note))) continue;

                //allow for 'tag_id' in API, but really just use object_tags
                if($tag = dbconnection::queryObject("SELECT * FROM object_tags WHERE game_id = '{$ob->game_id}' AND object_type = 'NOTE' AND object_id = '{$ob->note_id}'"))
                    $ob->tag_id = $tag->tag_id;
                else
                    $ob->tag_id = "0";

                $notes[] = $ob;
            }
        }
        else
        {
            $sql_notes = dbconnection::queryArray("SELECT notes.*, users.user_name, users.display_name FROM notes LEFT JOIN users ON notes.user_id = users.user_id WHERE game_id = '{$pack->game_id}'");
            for($i = 0; $i < count($sql_notes); $i++)
            {
                if(!($ob = notes::noteObjectFromSQL($sql_notes[$i]))) continue;

                //allow for 'tag_id' in API, but really just use object_tags
                if($tag = dbconnection::queryObject("SELECT * FROM object_tags WHERE game_id = '{$ob->game_id}' AND object_type = 'NOTE' AND object_id = '{$ob->note_id}'"))
                    $ob->tag_id = $tag->tag_id;
                else
                    $ob->tag_id = "0";

                $notes[] = $ob;
            }
        }

        return new return_package(0,$notes);
    }

    // The following functions performing clustering come from
    // http://www.appelsiini.net/2008/introduction-to-marker-clustering-with-google-maps

    // define('OFFSET', 268435456);
    // define('RADIUS', 85445659.4471); /* $offset / pi() */

    private static function lonToX($lon) {
        return round(268435456 + 85445659.4471 * $lon * pi() / 180);
    }

    private static function latToY($lat) {
        return round(268435456 - 85445659.4471 *
                    log((1 + sin($lat * pi() / 180)) /
                    (1 - sin($lat * pi() / 180))) / 2);
    }

    private static function pixelDistance($lat1, $lon1, $lat2, $lon2, $zoom) {
        // MT TODO: handle international date line properly

        $x1 = notes::lonToX($lon1);
        $y1 = notes::latToY($lat1);

        $x2 = notes::lonToX($lon2);
        $y2 = notes::latToY($lat2);

        return sqrt(pow(($x1-$x2),2) + pow(($y1-$y2),2)) >> (21 - $zoom);
    }

    private static function cluster($markers, $distance, $zoom) {
        $clustered = array();
        /* Loop until all markers have been compared. */
        while (count($markers)) {
            $marker  = array_pop($markers);
            $cluster = array();
            /* Compare against all markers which are left. */
            foreach ($markers as $key => $target) {
                $pixels = notes::pixelDistance($marker->latitude, $marker->longitude,
                                        $target->latitude, $target->longitude,
                                        $zoom);
                /* If two markers are closer than given distance remove */
                /* target marker from array and add it to cluster.      */
                if ($distance > $pixels) {
                    // printf("Distance between %s,%s and %s,%s is %d pixels.\n",
                    //     $marker->latitude, $marker->longitude,
                    //     $target->latitude, $target->longitude,
                    //     $pixels);
                    unset($markers[$key]);
                    $cluster[] = $target;
                }
            }

            /* If a marker has been added to cluster, add also the one  */
            /* we were comparing to and remove the original from array. */
            if (count($cluster) > 0) {
                $cluster[] = $marker;
                $clustered[] = $cluster;
            } else {
                $clustered[] = $marker;
            }
        }
        return $clustered;
    }

    public static function siftrSearch($pack)
    {
        if (isset($pack->auth)) {
            $pack->auth->game_id    = $pack->game_id;
            $pack->auth->permission = "read_write"  ;
            $auth_user   =   users::authenticateUser      ($pack->auth);
            $auth_editor = editors::authenticateGameEditor($pack->auth);
        }
        else {
            $auth_user   = false;
            $auth_editor = false;
        }

        $game_id       = isset($pack->game_id)                                ? intval($pack->game_id)              : 0;
        $search        = isset($pack->search) && is_string($pack->search)     ? $pack->search                       : '';
        $offset        = isset($pack->offset)                                 ? intval($pack->offset)               : null;
        $limit         = isset($pack->limit)                                  ? intval($pack->limit)                : null;
        $user_id       = $auth_user                                           ? intval($pack->auth->user_id)        : null;
        $order         = isset($pack->order) && is_string($pack->order)       ? $pack->order                        : '';
        $filter        = isset($pack->filter) && is_string($pack->filter)     ? $pack->filter                       : '';
        $tag_ids       = isset($pack->tag_ids) && is_array($pack->tag_ids)    ? array_map('intval', $pack->tag_ids) : array();
        $min_latitude  = isset($pack->min_latitude)                           ? floatval($pack->min_latitude)       : null;
        $max_latitude  = isset($pack->max_latitude)                           ? floatval($pack->max_latitude)       : null;
        $min_longitude = isset($pack->min_longitude)                          ? floatval($pack->min_longitude)      : null;
        $max_longitude = isset($pack->max_longitude)                          ? floatval($pack->max_longitude)      : null;
        $zoom          = isset($pack->zoom)                                   ? intval($pack->zoom)                 : 1;
        $min_time      = isset($pack->min_time) && is_string($pack->min_time) ? $pack->min_time                     : null;
        $max_time      = isset($pack->max_time) && is_string($pack->max_time) ? $pack->max_time                     : null;
        $map_data      = isset($pack->map_data)                               ? !!($pack->map_data)                 : true;

        $q = "SELECT notes.*
        , users.user_name
        , users.display_name
        , object_tags.tag_id
        , triggers.latitude
        , triggers.longitude
        , media.file_name
        , media.file_folder
        FROM notes
        LEFT JOIN users ON users.user_id = notes.user_id
        LEFT JOIN instances ON instances.object_type = 'NOTE' AND notes.note_id = instances.object_id
        LEFT JOIN triggers ON triggers.instance_id = instances.instance_id AND triggers.type = 'LOCATION'
        LEFT JOIN media ON media.media_id = notes.media_id
        LEFT JOIN object_tags ON object_tags.game_id = notes.game_id AND object_tags.object_type = 'NOTE' AND notes.note_id = object_tags.object_id
        LEFT JOIN note_likes ON notes.note_id = note_likes.note_id
        LEFT JOIN note_comments ON notes.note_id = note_comments.note_id
        WHERE notes.game_id = {$game_id}";

        // Search text
        foreach (preg_split("/\\s+/", $search) as $term) {
            if (strlen($term) === 0) continue;
            $pat = '"%' . addslashes($term) . '%"';
            $q .= " AND (notes.description LIKE {$pat} OR users.user_name LIKE {$pat} OR users.display_name LIKE {$pat} OR note_comments.description LIKE {$pat})";
        }

        // Tag search
        if (count($tag_ids)) {
            $q .= ' AND object_tags.tag_id IN (' . implode($tag_ids, ',') . ')';
        }

        // Map boundaries
        if (!is_null($min_latitude) && !is_null($max_latitude)) {
            $q .= " AND {$min_latitude} <= triggers.latitude AND triggers.latitude <= {$max_latitude}";
        }
        if (!is_null($min_longitude) && !is_null($max_longitude)) {
            if ($min_longitude <= $max_longitude) {
                $q .= " AND {$min_longitude} <= triggers.longitude AND triggers.longitude <= {$max_longitude}";
            } else {
                // the international date line is inside the 2 longitudes
                $q .= " AND ({$min_longitude} <= triggers.longitude OR triggers.longitude <= {$max_longitude})";
            }
        }

        // Filter by date
        if (!is_null($min_time)) {
            $str = '"' . addslashes($min_time) . '"';
            $q .= " AND {$str} <= notes.created";
        }
        if (!is_null($max_time)) {
            $str = '"' . addslashes($max_time) . '"';
            $q .= " AND notes.created <= {$str}";
        }

        // Only the user's notes
        if ($filter === 'mine') {
            $q .= " AND notes.user_id = {$user_id}";
        }

        // Authentication
        if (!$auth_editor) {
            if ($auth_user) {
                $q .= " AND (notes.published != 'PENDING' OR notes.user_id = '{$user_id}')";
            }
            else {
                $q .= " AND notes.published != 'PENDING'";
            }
        }

        // Order
        $q .= " GROUP BY notes.note_id";
        if ($order === 'recent') {
            $q .= " ORDER BY notes.note_id DESC";
        } else if ($order === 'popular') {
            $q .= " ORDER BY (COUNT(note_likes.note_id) + COUNT(note_comments.note_id)) DESC";
        }

        // Limit/offset if we're not compiling map data
        if (!$map_data) {
            if ($limit) {
                $q .= " LIMIT {$limit}";
                if ($offset) {
                    $q .= " OFFSET {$offset}";
                }
            }
        }

        $notes = dbconnection::queryArray($q);
        if ($notes === false) {
            $notes = array(); // ugh
        }
        if (!is_array($notes)) {
            return new return_package(1, NULL, "Error when running SQL query");
        }

        $map_notes = array();
        $map_clusters = array();
        if ($map_data) {
            foreach (notes::cluster($notes, 50, $zoom) as $map_object) {
                if (is_array($map_object)) {
                    $low_latitude = $high_latitude = $low_longitude = $high_longitude = null;
                    $tags = new stdClass();
                    foreach ($map_object as $clustered_note) {
                        $lat = floatval($clustered_note->latitude);
                        $lon = floatval($clustered_note->longitude);
                        if (is_null($low_latitude  ) || $lat < $low_latitude  ) $low_latitude   = $lat;
                        if (is_null($high_latitude ) || $lat > $high_latitude ) $high_latitude  = $lat;
                        if (is_null($low_longitude ) || $lon < $low_longitude ) $low_longitude  = $lon;
                        if (is_null($high_longitude) || $lon > $high_longitude) $high_longitude = $lon;
                        $tag_id = $clustered_note->tag_id;
                        if (isset($tags->$tag_id)) {
                            $tags->$tag_id++;
                        } else {
                            $tags->$tag_id = 1;
                        }
                    }
                    $cluster = new stdClass();
                    $cluster->min_latitude = $low_latitude;
                    $cluster->max_latitude = $high_latitude;
                    $cluster->min_longitude = $low_longitude;
                    $cluster->max_longitude = $high_longitude;
                    $cluster->note_count = count($map_object);
                    $cluster->tags = $tags;
                    $map_clusters[] = $cluster;
                } else {
                    $map_notes[] = $map_object;
                }
            }
        }

        $ret_obj = new stdClass();
        // Limit/offset if we *did* compile map data
        if ($map_data) {
            $ret_obj->notes = array_slice($notes, $offset, $limit);
            // this works fine even if $offset and/or $limit are null
        } else {
            $ret_obj->notes = $notes;
        }
        foreach ($ret_obj->notes as $note) {
            $note->media = media::mediaObjectFromSQL($note);
            unset($note->file_name);
            unset($note->file_folder);
            unset($note->media->file_name);
            unset($note->media->name);
            unset($note->media->media_id);
            unset($note->media->game_id);
        }
        $ret_obj->map_notes = $map_notes;
        foreach ($ret_obj->map_notes as $note) {
            if (!isset($note->media)) {
                $note->media = media::mediaObjectFromSQL($note);
                unset($note->file_name);
                unset($note->file_folder);
                unset($note->media->file_name);
                unset($note->media->name);
                unset($note->media->media_id);
                unset($note->media->game_id);
            }
        }
        $ret_obj->map_clusters = $map_clusters;
        return new return_package(0, $ret_obj);
    }

    public static function searchNotes($pack)
    {
        if (isset($pack->auth)) {
            $pack->auth->game_id    = $pack->game_id;
            $pack->auth->permission = "read_write"  ;
            $auth_user   =   users::authenticateUser      ($pack->auth);
            $auth_editor = editors::authenticateGameEditor($pack->auth);
        }
        else {
            $auth_user   = false;
            $auth_editor = false;
        }

        $game_id = intval($pack->game_id);
        $search_terms = isset($pack->search_terms) ? $pack->search_terms : array();
        $note_count = intval($pack->note_count);
        $user_id = $auth_user ? intval($pack->auth->user_id) : 0;
        $order_by = $pack->order_by;
        $filter_by = $pack->filter_by;
        $tag_ids = isset($pack->tag_ids) ? array_map('intval', $pack->tag_ids) : array();
        $note_id = intval($pack->note_id);

        $lines = array();

        $selects = array(
            "notes.*",
            "users.user_name",
            "users.display_name",
            "object_tags.tag_id",
            "tags.tag",
            "COUNT(all_likes.note_like_id) AS note_likes",
            "COUNT(my_likes.note_like_id) > 0 AS player_liked",
            "triggers.latitude",
            "triggers.longitude",
            "media.name AS media_name",
            "media.file_name AS media_file_name",
            "media.file_folder AS media_file_folder",
        );
        $lines[] = "SELECT " . implode(", ", $selects);

        $lines[] = "FROM notes";
        $lines[] = "JOIN users ON notes.user_id = users.user_id";
        if ($order_by === 'popular' || !empty($search_terms)) {
            $lines[] = "LEFT JOIN note_comments ON notes.note_id = note_comments.note_id";
        }
        $lines[] = "LEFT JOIN object_tags ON object_tags.game_id = notes.game_id AND object_tags.object_type = 'NOTE' AND notes.note_id = object_tags.object_id";
        $lines[] = "LEFT JOIN tags ON object_tags.tag_id = tags.tag_id";
        $lines[] = "LEFT JOIN note_likes AS all_likes ON notes.note_id = all_likes.note_id";
        $lines[] = "LEFT JOIN note_likes AS my_likes ON notes.note_id = my_likes.note_id AND my_likes.user_id = '{$user_id}'";
        $lines[] = "LEFT JOIN instances ON instances.object_type = 'NOTE' AND notes.note_id = instances.object_id";
        $lines[] = "LEFT JOIN triggers ON triggers.instance_id = instances.instance_id AND triggers.type = 'LOCATION'";
        $lines[] = "LEFT JOIN media ON media.media_id = notes.media_id";

        $lines[] = "WHERE 1=1";
        $lines[] = "AND notes.game_id = '{$game_id}'";
        $searchables = array('notes.name', 'notes.description', 'users.user_name', 'users.display_name', 'note_comments.description');
        foreach ($search_terms as $term) {
            $matches = array();
            $term = addslashes($term);
            foreach ($searchables as $key) {
                $matches[] = "({$key} LIKE '%{$term}%')";
            }
            $lines[] = 'AND (' . implode(' OR ', $matches) . ')';
        }
        if ($user_id && $filter_by === 'mine') {
            $lines[] = "AND notes.user_id = '{$user_id}'";
        }
        if (!empty($tag_ids)) {
            $tag_list = implode(',', $tag_ids);
            $lines[] = "AND object_tags.tag_id IN ({$tag_list})";
        }
        if ($note_id) {
            $lines[] = "AND notes.note_id = '{$note_id}'";
        }
        if (!$auth_editor) {
            if ($auth_user) {
                $lines[] = "AND (notes.published != 'PENDING' OR notes.user_id = '{$user_id}')";
            }
            else {
                $lines[] = "AND notes.published != 'PENDING'";
            }
        }

        $lines[] = "GROUP BY notes.note_id";
        if ($order_by === 'popular') {
            $lines[] = "ORDER BY (COUNT(all_likes.note_id) + COUNT(note_comments.note_id)) DESC";
        }
        else if ($order_by === 'recent') {
            $lines[] = "ORDER BY notes.created DESC";
        }

        if ($note_count) {
            $lines[] = "LIMIT {$note_count}";
        }

        $query = implode(' ', $lines);
        $sql_notes = dbconnection::queryArray($query);
        $notes = array();
        for ($i = 0; $i < count($sql_notes); $i++) {
            $ob = notes::noteObjectFromSQL($sql_notes[$i]);
            if (!$ob) continue;
            foreach (array('tag_id', 'note_likes', 'tag', 'latitude', 'longitude', 'user_name', 'display_name', 'player_liked') as $field) {
                $ob->$field = $sql_notes[$i]->$field;
            }

            $sql_media = $sql_notes[$i];
            $sql_media->name        = $sql_media->media_name;
            $sql_media->file_name   = $sql_media->media_file_name;
            $sql_media->file_folder = $sql_media->media_file_folder;
            $ob->media = new return_package(0, media::mediaObjectFromSQL($sql_media));

            $ob->comments = note_comments::getNoteCommentsForNote((object) array('game_id' => $ob->game_id, 'note_id' => $ob->note_id));
            $notes[] = $ob;
        }
        return new return_package(0, $notes);
    }

    public static function flagNote($pack)
    {
        $sql_note = dbconnection::queryObject("SELECT * FROM notes WHERE note_id = '{$pack->note_id}'");
        $note = notes::noteObjectFromSQL($sql_note);
        // No authentication; anyone can flag a note that is not in APPROVED state
        if ($note->published == 'APPROVED') {
            return new return_package(1, NULL, "Cannot flag note because it has already been approved by the moderator");
        }
        dbconnection::query(
            "UPDATE notes SET published = 'PENDING' WHERE note_id = '{$pack->note_id}'"
        );
        return new return_package(0);
    }

    public static function approveNote($pack)
    {
        $note = dbconnection::queryObject("SELECT * FROM notes WHERE note_id = '{$pack->note_id}'");
        $pack->auth->game_id = $note->game_id;
        $pack->auth->permission = "read_write";
        // You must be a game owner to approve notes
        if (!editors::authenticateGameEditor($pack->auth)) {
            return new return_package(6, NULL, "Failed Authentication");
        }
        dbconnection::query(
            "UPDATE notes SET published = 'APPROVED' WHERE note_id = '{$pack->note_id}'"
        );
        return new return_package(0);
    }

    public static function deleteNote($pack)
    {
        $note = dbconnection::queryObject("SELECT * FROM notes WHERE note_id = '{$pack->note_id}'");
        $pack->auth->game_id = $note->game_id;
        $pack->auth->permission = "read_write";
        if(
          ($pack->auth->user_id != $note->user_id || !users::authenticateUser($pack->auth)) &&
          !editors::authenticateGameEditor($pack->auth)
        ) return new return_package(6, NULL, "Failed Authentication");

        // Cleanup related items.
        $noteComments = dbconnection::queryArray("SELECT * FROM note_comments WHERE note_id = '{$pack->note_id}'");
        for($i = 0; $i < count($note_comments); $i++)
        {
            $pack->note_comment_id = $noteComments[$i]->note_comment_id;
            note_comments::deleteNoteComment($pack);
        }

        // NOTE duplicated from tags.php/instances.php/triggers.php due to amf framework public methods being accessible via url.
        $tags = dbconnection::queryArray("SELECT * FROM object_tags WHERE object_type = 'NOTE' AND object_id = '{$pack->note_id}'");
        for($i = 0; $i < count($tags); $i++)
        {
            $pack->object_tag_id = $tags[$i]->object_tag_id;
            dbconnection::query("DELETE FROM object_tags WHERE object_tag_id = '{$pack->object_tag_id}' LIMIT 1");
        }

        $instances = dbconnection::queryArray("SELECT * FROM instances WHERE object_type = 'NOTE' AND object_id = '{$pack->note_id}'");
        for($i = 0; $i < count($instances); $i++)
        {
            $pack->instance_id = $instances[$i]->instance_id;
            dbconnection::query("DELETE FROM instances WHERE instance_id = '{$pack->instance_id}' LIMIT 1");

            $triggers = dbconnection::queryArray("SELECT * FROM triggers WHERE instance_id = '{$pack->instance_id}'");
            for($i = 0; $i < count($triggers); $i++)
            {
                $pack->trigger_id = $triggers[$i]->trigger_id;
                dbconnection::query("DELETE FROM triggers WHERE trigger_id = '{$pack->trigger_id}' LIMIT 1");
                // TODO fix and clean the rest of the hierarchy (requirement package)
            }
        }

        // After everything is cleaned up.
        dbconnection::query("DELETE FROM notes WHERE note_id = '{$pack->note_id}' LIMIT 1");

        games::bumpGameVersion($pack);
        return new return_package(0);
    }

    public static function likeNote($pack)
    {
        $pack->auth->permission = "read_write";
        if(!users::authenticateUser($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        $existing = dbconnection::queryObject(
            "SELECT * FROM note_likes"
            . " WHERE game_id = '" . intval($pack->game_id) . "'"
            . " AND note_id = '" . intval($pack->note_id) . "'"
            . " AND user_id = '" . intval($pack->auth->user_id) . "'"
        );
        if($existing)
        {
            return new return_package(0);
        }

        dbconnection::queryInsert(
            "INSERT INTO note_likes"
            . " (game_id, note_id, user_id, created)"
            . " VALUES"
            . " ( '" . intval($pack->game_id)       . "'"
            . " , '" . intval($pack->note_id)       . "'"
            . " , '" . intval($pack->auth->user_id) . "'"
            . " , CURRENT_TIMESTAMP"
            . " )"
        );

        return new return_package(0);
    }

    public static function unlikeNote($pack)
    {
        $pack->auth->permission = "read_write";
        if(!users::authenticateUser($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

        dbconnection::query(
            "DELETE FROM note_likes"
            . " WHERE game_id = '" . intval($pack->game_id) . "'"
            . " AND note_id = '" . intval($pack->note_id) . "'"
            . " AND user_id = '" . intval($pack->auth->user_id) . "'"
            . " LIMIT 1"
        );

        return new return_package(0);
    }

    public static function exportNotes($pack)
    {
      $pack->auth->game_id = $pack->game_id;
      $pack->auth->permission = "read_write";
      if(!editors::authenticateGameEditor($pack->auth)) return new return_package(6, NULL, "Failed Authentication");

      $export = notes::getNotesForGame($pack);

      $tmp_export_folder = $pack->game_id."_notebook_export_".date("mdY_Gis");
      $fs_tmp_export_folder = Config::v2_gamedata_folder."/".$tmp_export_folder;
      if(file_exists($fs_tmp_export_folder)) util::rdel($fs_tmp_export_folder);
      mkdir($fs_tmp_export_folder,0777);
      $jsonfile = fopen($fs_tmp_export_folder."/export.json","w");
      fwrite($jsonfile,json_encode($export));
      fclose($jsonfile);

      util::rcopy(Config::v2_gamedata_folder."/".$pack->game_id,$fs_tmp_export_folder."/gamedata");
      util::rzip($fs_tmp_export_folder,$fs_tmp_export_folder.".zip");
      util::rdel($fs_tmp_export_folder);

      return new return_package(0, Config::v2_gamedata_www_path."/".$tmp_export_folder.".zip");
    }

}
?>
