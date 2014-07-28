var CRUD_DEFAULTS = [
{
    "service":"",
    "create":"",
    "get":"",
    "update":"",
    "delete":"",
    "createData":"",
    "getData":"",
    "updateData":"",
    "deleteData":""
},
{
    "service":"games",
    "create":"games.createGame",
    "get":"games.getGame",
    "update":"games.updateGame",
    "delete":"games.deleteGame",
    "createData":
        '\
        {\n\
          "name":"gameName",\n\
          "description":"gameDescription",\n\
          "icon_media_id":1,\n\
          "media_id":2,\n\
          "map_type":"huh",\n\
          "latitude":1.234,\n\
          "longitude":2.468,\n\
          "zoom_level":2,\n\
          "show_player_location":true\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "game_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"gameName",\n\
          "description":"gameDescription",\n\
          "icon_media_id":1,\n\
          "media_id":2,\n\
          "map_type":"huh",\n\
          "latitude":1.234,\n\
          "longitude":2.468,\n\
          "zoom_level":2,\n\
          "show_player_location":true\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "game_id":123\n\
        }\n\
        '
},
{
    "service":"requirements",
    "create":"requirements.createRequirementPackage",
    "get":"requirements.getRequirementPackage",
    "update":"requirements.updateRequirementPackage",
    "delete":"requirements.deleteRequirementPackage",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"requirementPackageName",\n\
          "and_packages": [\n\
            {\n\
              "name":"andPackageName",\n\
              "atoms": [\n\
                {\n\
                  "bool_operator":0,\n\
                  "requirement":"PLAYER_HAS_ITEM",\n\
                  "content_id":1,\n\
                  "qty":4,\n\
                  "latitude":86.75309,\n\
                  "longitude":3.141592\n\
                },\n\
                {\n\
                  "bool_operator":1,\n\
                  "requirement":"PLAYER_HAS_ITEM",\n\
                  "content_id":2,\n\
                  "qty":3,\n\
                  "latitude":86.75309,\n\
                  "longitude":3.141592\n\
                }\n\
              ]\n\
            },\n\
            {\n\
              "name":"andPackageName2",\n\
              "atoms": [\n\
                {\n\
                  "bool_operator":0,\n\
                  "requirement":"PLAYER_HAS_ITEM",\n\
                  "content_id":1,\n\
                  "qty":4,\n\
                  "latitude":86.75309,\n\
                  "longitude":3.141592\n\
                },\n\
                {\n\
                  "bool_operator":1,\n\
                  "requirement":"PLAYER_HAS_ITEM",\n\
                  "content_id":2,\n\
                  "qty":3,\n\
                  "latitude":86.75309,\n\
                  "longitude":3.141592\n\
                }\n\
              ]\n\
            }\n\
          ]\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "requirement_root_package_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "requirement_root_package_id":123,\n\
          "name":"requirementPackageName",\n\
          "and_packages": [\n\
            {\n\
              "requirement_and_package_id":234,\n\
              "name":"andPackageName",\n\
              "atoms": [\n\
                {\n\
                  "requirement_atom_id":234,\n\
                  "bool_operator":0,\n\
                  "requirement":"PLAYER_HAS_ITEM",\n\
                  "content_id":1,\n\
                  "qty":4,\n\
                  "latitude":86.75309,\n\
                  "longitude":3.141592\n\
                },\n\
                {\n\
                  "requirement_atom_id":235,\n\
                  "bool_operator":1,\n\
                  "requirement":"PLAYER_HAS_ITEM",\n\
                  "content_id":2,\n\
                  "qty":3,\n\
                  "latitude":86.75309,\n\
                  "longitude":3.141592\n\
                }\n\
              ]\n\
            },\n\
            {\n\
              "requirement_and_package_id":235,\n\
              "name":"andPackageName2",\n\
              "atoms": [\n\
                {\n\
                  "requirement_atom_id":236,\n\
                  "bool_operator":0,\n\
                  "requirement":"PLAYER_HAS_ITEM",\n\
                  "content_id":1,\n\
                  "qty":4,\n\
                  "latitude":86.75309,\n\
                  "longitude":3.141592\n\
                },\n\
                {\n\
                  "requirement_atom_id":237,\n\
                  "bool_operator":1,\n\
                  "requirement":"PLAYER_HAS_ITEM",\n\
                  "content_id":2,\n\
                  "qty":3,\n\
                  "latitude":86.75309,\n\
                  "longitude":3.141592\n\
                }\n\
              ]\n\
            }\n\
          ]\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "requirement_root_package_id":123\n\
        }\n\
        '
},
{
    "service":"scenes",
    "create":"scenes.createScene",
    "get":"scenes.getScene",
    "update":"scenes.updateScene",
    "delete":"scenes.deleteScene",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"sceneName"\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "scene_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "scene_id":234,\n\
          "name":"sceneName"\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "scene_id":123\n\
        }\n\
        '
},
{
    "service":"triggers",
    "create":"triggers.createTrigger",
    "get":"triggers.getTrigger",
    "update":"triggers.updateTrigger",
    "delete":"triggers.deleteTrigger",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "instance_id":123,\n\
          "scene_id":123,\n\
          "requirement_root_package_id":132,\n\
          "type":"LOCATION",\n\
          "name":"triggerName",\n\
          "title":"triggerTitle",\n\
          "icon_media_id":123,\n\
          "latitude":1.234,\n\
          "longitude":2.468,\n\
          "distance":5,\n\
          "wiggle":1,\n\
          "show_title":1,\n\
          "hidden":0,\n\
          "trigger_on_enter":0,\n\
          "qr_code":"abc123"\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "trigger_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "trigger_id":123,\n\
          "instance_id":123,\n\
          "scene_id":123,\n\
          "requirement_root_package_id":132,\n\
          "type":"LOCATION",\n\
          "name":"triggerName",\n\
          "title":"triggerTitle",\n\
          "icon_media_id":123,\n\
          "latitude":1.234,\n\
          "longitude":2.468,\n\
          "distance":5,\n\
          "wiggle":1,\n\
          "show_title":1,\n\
          "hidden":0,\n\
          "trigger_on_enter":0,\n\
          "code":"abc123"\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "trigger_id":123\n\
        }\n\
        '
},
{
    "service":"instances",
    "create":"instances.createInstance",
    "get":"instances.getInstance",
    "update":"instances.updateInstance",
    "delete":"instances.deleteInstance",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "object_id":123,\n\
          "object_type":"PLAQUE",\n\
          "factory_id":123\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "instance_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "instance_id":123,\n\
          "object_id":123,\n\
          "object_type":"PLAQUE",\n\
          "factory_id":123\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "instance_id":123\n\
        }\n\
        '
},
{
    "service":"items",
    "create":"items.createItem",
    "get":"items.getItem",
    "update":"items.updateItem",
    "delete":"items.deleteItem",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"itemName",\n\
          "description":"itemDescription",\n\
          "icon_media_id":123,\n\
          "media_id":123,\n\
          "droppable":1,\n\
          "destroyable":1,\n\
          "max_qty_in_inventory":500,\n\
          "weight":0,\n\
          "url":"http://www.arisgames.org",\n\
          "type":"NORMAL"\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "item_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "item_id":123,\n\
          "name":"itemName",\n\
          "description":"itemDescription",\n\
          "icon_media_id":123,\n\
          "media_id":123,\n\
          "droppable":1,\n\
          "destroyable":1,\n\
          "max_qty_in_inventory":500,\n\
          "weight":0,\n\
          "url":"http://www.arisgames.org",\n\
          "type":"NORMAL"\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "item_id":123\n\
        }\n\
        '
},
{
    "service":"plaques",
    "create":"plaques.createPlaque",
    "get":"plaques.getPlaque",
    "update":"plaques.updatePlaque",
    "delete":"plaques.deletePlaque",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"plaqueName",\n\
          "description":"plaqueDescription",\n\
          "icon_media_id":123,\n\
          "media_id":123\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "plaque_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "plaque_id":123,\n\
          "name":"plaqueName",\n\
          "description":"plaqueDescription",\n\
          "icon_media_id":123,\n\
          "media_id":123\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "plaque_id":123\n\
        }\n\
        '
},
{
    "service":"web_pages",
    "create":"web_pages.createWebPage",
    "get":"web_pages.getWebPage",
    "update":"web_pages.updateWebPage",
    "delete":"web_pages.deleteWebPage",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"webPageName",\n\
          "url":"http://www.google.com",\n\
          "icon_media_id":123\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "web_page_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "web_page_id":123,\n\
          "name":"webPageName",\n\
          "url":"http://www.google.com",\n\
          "icon_media_id":123\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "web_page_id":123\n\
        }\n\
        '
},
{
    "service":"tags",
    "create":"tags.createTag",
    "get":"tags.getTag",
    "update":"tags.updateTag",
    "delete":"tags.deleteTag",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "tag":"myTag",\n\
          "media_id":123,\n\
          "player_created":0,\n\
          "visible":1,\n\
          "sort_index":0\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "tag_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "tag_id":123,\n\
          "tag":"myTag",\n\
          "media_id":123,\n\
          "player_created":0\n\
          "visible":1\n\
          "sort_index":0\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "tag_id":123\n\
        }\n\
        '
},
{
    "service":"dialogs",
    "create":"dialogs.createDialog",
    "get":"dialogs.getDialog",
    "update":"dialogs.updateDialog",
    "delete":"dialogs.deleteDialog",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"dialogName",\n\
          "description":"dialogDescription",\n\
          "icon_media_id":123,\n\
          "intro_dialog_script_id":123\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "dialog_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "dialog_id":123,\n\
          "name":"dialogName",\n\
          "description":"dialogDescription",\n\
          "icon_media_id":123,\n\
          "intro_dialog_script_id":123\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "dialog_id":123\n\
        }\n\
        '
},
{
    "service":"dialog_characters",
    "create":"dialogs.createDialogCharacter",
    "get":"dialogs.getDialogCharacter",
    "update":"dialogs.updateDialogCharacter",
    "delete":"dialogs.deleteDialogCharacter",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"dialogCharacterName",\n\
          "title":"dialog character title",\n\
          "media_id":123\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "dialog_character_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "dialog_character_id":123,\n\
          "name":"dialogCharacterName",\n\
          "title":"dialog character title",\n\
          "media_id":123\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "dialog_character_id":123\n\
        }\n\
        '
},
{
    "service":"dialog_scripts",
    "create":"dialogs.createDialogScript",
    "get":"dialogs.getDialogScript",
    "update":"dialogs.updateDialogScript",
    "delete":"dialogs.deleteDialogScript",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "dialog_id":123,\n\
          "dialog_character_id":123,\n\
          "text":"dialogScriptText",\n\
          "event_package_id":123\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "dialog_script_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "dialog_script_id":123,\n\
          "dialog_character_id":123,\n\
          "text":"dialogScriptText",\n\
          "event_package_id":123\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "dialog_script_id":123\n\
        }\n\
        '
},
{
    "service":"dialog_options",
    "create":"dialogs.createDialogOption",
    "get":"dialogs.getDialogOption",
    "update":"dialogs.updateDialogOption",
    "delete":"dialogs.deleteDialogOption",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "dialog_id":123,\n\
          "parent_dialog_script_id":123,\n\
          "prompt":"dialogOptionPrompt",\n\
          "link_type":"DIALOG_SCRIPT",\n\
          "link_id":123,\n\
          "requirement_root_package_id":123,\n\
          "sort_index":0\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "dialog_option_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "dialog_option_id":123,\n\
          "parent_dialog_script_id":123,\n\
          "prompt":"dialogOptionPrompt",\n\
          "link_type":"DIALOG_SCRIPT",\n\
          "link_id":123,\n\
          "requirement_root_package_id":123,\n\
          "sort_index":0\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "dialog_option_id":123\n\
        }\n\
        '
},
{
    "service":"events",
    "create":"events.createEvent",
    "get":"events.getEvent",
    "update":"events.updateEvent",
    "delete":"events.deleteEvent",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "event":"GIVE_ITEM",\n\
          "qty":10,\n\
          "content_id":123\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "event_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "event_id":123,\n\
          "event":"GIVE_ITEM",\n\
          "qty":10,\n\
          "content_id":123\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "event_id":123\n\
        }\n\
        '
},
{
    "service":"media",
    "create":"media.createMedia",
    "get":"media.getMedia",
    "update":"media.updateMedia",
    "delete":"media.deleteMedia",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"smiley",\n\
          "file_name":"smiley.png",\n\
          "data":"iVBORw0KGgoAAAANSUhEUgAAABQAAAAUBAMAAAB/pwA+AAAAA3NCSVQICAjb4U/gAAAAMFBMVEX/////7AD/4gD/2QD/zAD/xQD/vAD/sgD/qQA8/wCZmZnAlACceAA/Pz9AMQAAAADauoGxAAAAEHRSTlP///////////8A////////Xxf4pAAAAAlwSFlzAAAK8AAACvABQqw0mAAAAAh0RVh0Q29tbWVudAD2zJa/AAAAIXRFWHRTb2Z0d2FyZQBNYWNyb21lZGlhIEZpcmV3b3JrcyAzLjDvaTHwAAAAnElEQVR4nGOYCQcMQDz///+fEOb87+Xl9T/BzP9laWlp/0HM+eX/Q0P/p/8EMn+U7f7ivzusHsj8nv3f2Pj/1vyZDPPb/zsrKSkDVTDM7091VhRUdv0OZLaf/w8Ef0Ci7fkMQHAVnZm9gIGBayuQCTT3FwPDerC5P8rz3/9/mg5izm8HuaEcZPHMH+3l5eX9EEf+6Ojo/4nudBgAAKTFdNGCgV+fAAAAAElFTkSuQmCC"\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "media_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "media_id":123,\n\
          "name":"smiley"\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "media_id":123\n\
        }\n\
        '
},
{
    "service":"quests",
    "create":"quests.createQuest",
    "get":"quests.getQuest",
    "update":"quests.updateQuest",
    "delete":"quests.deleteQuest",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"questName",\n\
          "description":"questDescription",\n\
          "active_icon_media_id":123,\n\
          "active_media_id":123,\n\
          "active_description":"activeDescription",\n\
          "active_notification_type":"NONE",\n\
          "active_function":"NONE",\n\
          "active_event_package_id":1,\n\
          "active_requirement_root_package_id":1,\n\
          "complete_icon_media_id":123,\n\
          "complete_media_id":123,\n\
          "complete_description":"completeDescription",\n\
          "complete_notification_type":"NONE",\n\
          "complete_function":"NONE",\n\
          "complete_event_package_id":1\n\
          "complete_requirement_root_package_id":1\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "quest_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "quest_id":123,\n\
          "name":"questName",\n\
          "description":"questDescription",\n\
          "active_icon_media_id":123,\n\
          "active_media_id":123,\n\
          "active_description":"activeDescription",\n\
          "active_notification_type":"NONE",\n\
          "active_function":"NONE",\n\
          "active_event_package_id":1,\n\
          "active_requirement_root_package_id":1,\n\
          "complete_icon_media_id":123,\n\
          "complete_media_id":123,\n\
          "complete_description":"completeDescription",\n\
          "complete_notification_type":"NONE",\n\
          "complete_function":"NONE",\n\
          "complete_event_package_id":1\n\
          "complete_requirement_root_package_id":1\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "quest_id":123\n\
        }\n\
        '
},
{
    "service":"tabs",
    "create":"tabs.createTab",
    "get":"tabs.getTab",
    "update":"tabs.updateTab",
    "delete":"tabs.deleteTab",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "type":"MAP",\n\
          "name":"Map",\n\
          "icon_media_id":123,\n\
          "tab_detail_1":0,\n\
          "requirement_root_package_id":0,\n\
          "sort_index":0\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "tab_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "tab_id":123,\n\
          "type":"MAP",\n\
          "name":"Map",\n\
          "icon_media_id":123,\n\
          "tab_detail_1":0,\n\
          "requirement_root_package_id":0,\n\
          "sort_index":0\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "tab_id":123\n\
        }\n\
        '
},
{
    "service":"factories",
    "create":"factories.createFactory",
    "get":"factories.getFactory",
    "update":"factories.updateFactory",
    "delete":"factories.deleteFactory",
    "createData":
        '\
        {\n\
          "game_id":123,\n\
          "name":"MyFactory",\n\
          "description":"This is my factory",\n\
          "object_type":"ITEM",\n\
          "object_id":123,\n\
          "seconds_per_production":10,\n\
          "production_probability":0.5,\n\
          "max_production":100,\n\
          "produce_expiration_time":20,\n\
          "produce_expire_on_view":1,\n\
          "production_bound_type":"PER_PLAYER",\n\
          "location_bound_type":"PLAYER",\n\
          "min_production_distance":5,\n\
          "max_production_distance":10,\n\
          "requirement_root_package_id":123,\n\
          "trigger_latitude":0.0,\n\
          "trigger_longitude":0.0,\n\
          "trigger_distance":3,\n\
          "trigger_on_enter":0,\n\
          "trigger_hidden":0,\n\
          "trigger_wiggle":1,\n\
          "trigger_title":"My Produced Trigger",\n\
          "trigger_icon_media_id":123,\n\
          "trigger_show_title":1,\n\
          "trigger_requirement_root_package_id":123\n\
        }\n\
        ',
    "getData":
        '\
        {\n\
          "factory_id":123\n\
        }\n\
        ',
    "updateData":
        '\
        {\n\
          "factory_id":123,\n\
          "name":"MyFactory",\n\
          "description":"This is my factory",\n\
          "object_type":"ITEM",\n\
          "object_id":123,\n\
          "seconds_per_production":10,\n\
          "production_probability":0.5,\n\
          "max_production":100,\n\
          "produce_expiration_time":20,\n\
          "produce_expire_on_view":1,\n\
          "production_bound_type":"PER_PLAYER",\n\
          "location_bound_type":"PLAYER",\n\
          "min_production_distance":5,\n\
          "max_production_distance":10,\n\
          "requirement_root_package_id":123,\n\
          "trigger_latitude":0.0,\n\
          "trigger_longitude":0.0,\n\
          "trigger_distance":3,\n\
          "trigger_on_enter":0,\n\
          "trigger_hidden":0,\n\
          "trigger_wiggle":1,\n\
          "trigger_title":"My Produced Trigger",\n\
          "trigger_icon_media_id":123,\n\
          "trigger_show_title":1,\n\
          "trigger_requirement_root_package_id":123\n\
        }\n\
        ',
    "deleteData":
        '\
        {\n\
          "factory_id":123\n\
        }\n\
        '
}
];
