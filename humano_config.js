var Config = {
    url: $.parseJSON($.ajax({
        type: "GET",
        url: "http://www.humanobs5.com/humano_config.json",
        dataType: "json",
        global: false,
        async: false,
        success: function (data) {
            return data;
        }
    }).responseText).url
}
