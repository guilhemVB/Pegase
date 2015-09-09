$().ready(function () {

    //doc sortable :  https://github.com/RubaXa/Sortable/wiki/Sortable-v1.0-%E2%80%94-New-capabilities

    $(document).ready(function () {
        Sortable.create(listDestinations, {
            group: {
                name: 'shared',
                animation: 200,
                pull: 'clone',
                put: false
            }
        });
    });
});
