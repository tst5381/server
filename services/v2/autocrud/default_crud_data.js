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
    "create":"games.createGameJSON",
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
    "getData":"",
    "updateData":"",
    "deleteData":""
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
    "getData":"",
    "updateData":"",
    "deleteData":""
}
];
