var Config = {
    url: $.parseJSON($.ajax({
        type: "GET",
        url: "https://edjohnpaul.github.io/humano_config.json",
        dataType: "json",
        global: false,
        async: false,
        success: function (data) {
            return data;
        }
    }).responseText).url
}
