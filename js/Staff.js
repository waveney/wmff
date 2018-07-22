
$.typeahead({
    input: '.findaside',
    minLength: 0,
    hint: true,
    dynamic: true,
    source: {
        "Side": {
            ajax: {
                url: "http://wimbornefolk.co.uk/int/partfind.php",
                data: { S: "{{query}}" }
            }
        }
    },
    callback: {
        onClick: function (node, a, item, event) {
        }
    },
    debug: true
});


